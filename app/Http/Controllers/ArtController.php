<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Resources\ArtResource;
use App\Http\Resources\CommentResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Art;
use App\Models\Tag;
use App\Models\Style;
use App\Models\Image;
use App\Http\Requests\CreateArtRequest;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ArtController extends Controller
{
    public function index(Request $request)
    {
        $arts = Auth::user()->arts()->searchWithFiltersAndOrder($request);

        if ($request->wantsJson()) {
            return ArtResource::collection($arts);
        }

        return Inertia::render('Artboard/Index', [
            'arts' => ArtResource::collection($arts),
            'count' => Auth::user()->arts()->count()
        ]);
    }

    public function likedIndex(Request $request)
    {
        $userId = Auth::id();
        $arts = Art::whereHas('likes', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->searchWithFiltersAndOrder($request);

        if ($request->wantsJson()) {
            return ArtResource::collection($arts);
        }

        return Inertia::render('Artboard/Index', [
            'arts' => ArtResource::collection($arts),
            'count' => Auth::user()->likedArts()->count()
        ]);
    }

    public function show(Request $request, $id)
    {
        $authenticatedUserId = Auth::id();
        $art = Art::findOrFail($id);
        $art->views += 1;
        $art->save();
        return Inertia::render('Artboard/Show', [
            'art' => new ArtResource($art),
            'comments' => CommentResource::collection($art->comments()
                ->with('user')
                ->when($authenticatedUserId !== null, function ($query) use ($authenticatedUserId) {
                    return $query->orderByRaw("user_id = {$authenticatedUserId} desc");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(4, ['*'], 'page', $request->input('commentPage'))),
        ]);
    }

    public function create()
    {
        return Inertia::render('Artboard/Create', [
            'tags' => Tag::latest()
                ->take(12)
                ->get(['id', 'name'])
                ->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
            'styles' => Style::all()
                ->map(fn ($style) => [
                    'id' => $style->id,
                    'name' => $style->name,
                ]),
        ]);
    }

    public function store(CreateArtRequest $request)
    {
        $data = $request->validated();
        $tags = [];
        foreach ($data['tags'] as $tagData) {
            $tag = Tag::firstOrCreate(['name' => $tagData['name']], $tagData);
            $tags[] = $tag->id;
        }

        $data['user_id'] = Auth::id();
        $art = Art::create($data);

        $manager = new ImageManager();

        foreach ($data['images'] as $image) {
            $base64Data = Str::after($image['src'], 'base64,');
            // Decode the base64 image data
            $decodedImage = base64_decode($base64Data);
            // Create an Intervention Image instance
            $img = $manager->make($decodedImage);

            // Validate the image aspect ratio (2/1.2 = 1.6667)
            $desiredRatio = 1.6667;
            $actualRatio = $img->width() / $img->height();
            if (abs($actualRatio - $desiredRatio) > 0.01) {
                // The image ratio is not valid, handle the validation error
                // For example, you can return an error response
                return redirect()->back()->withErrors('Images must be in 2/1.2 aspect ratio.');
            }

            // Validate the image format (jpeg or png)
            $allowedFormats = ['jpeg', 'png', 'jpg'];
            $mime = strtolower($img->mime());
            $extension = str_replace('image/', '', $mime);

            if (!in_array($extension, $allowedFormats)) {
                // The image format is not valid, handle the validation error
                // For example, you can return an error response
                return redirect()->back()->withErrors('Images must be in JPEG or PNG format.');
            }


            // Optimize the image for better quality and file size
            $img->encode($extension, 80); // Adjust the quality (0-100) as needed

            // Generate a unique filename
            $filename = uniqid() . '.' . $extension;

            // Store the image in the desired folder
            $storagePath = 'public/article_images/' . $filename;
            Storage::put($storagePath, $img->stream());

            // Save the image to the database and associate it with the art
            $imageModel = new Image(['filename' => $filename]);
            $imageModel->art_id = $art->id; // Set the art_id
            $imageModel->save();

        }
        $art->tags()->sync($tags);
        $art->styles()->attach($data['styles']);

        return redirect()->back()->with([
            'msg' => 'Your article was created successfully',
            'url' => '/arts/'.$art->id
        ]);
    }

    public function destroy(Art $art)
    {
        $this->authorize('delete', $art);

        // Delete all image records associated with the art
        $art->images()->each(function ($image) {
            // Delete the image file from storage
            $storagePath = 'public/article_images/' . $image->filename;
            Storage::delete($storagePath);

            // Delete the image record from the database
            $image->delete();
        });

        // Delete the art
        $art->delete();

        return redirect()->back()->with('success', 'Your article has been deleted successfully');
    }
}
