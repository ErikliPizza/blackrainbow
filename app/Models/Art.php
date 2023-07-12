<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Art extends Model
{
    use HasFactory;

    protected $table = 'arts';
    protected $fillable = [
        'title',
        'description',
        'hours_spent',
        'user_id',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'art_tag', 'art_id', 'tag_id');
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class, 'art_style', 'art_id', 'style_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'art_id', 'user_id')->select('users.id', 'users.name', 'users.role', 'users.email');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class); // 'foreign_key', 'local_key' : 'art_id', 'id'
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function scopeSearchWithFiltersAndOrder($query, $request)
    {
        $order = $request->input('order') ?? 1;

        return $query->with('tags', 'styles')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when(!empty($request->input('tag')) || !empty($request->input('style')), function ($query) use ($request) {
                $tags = $request->input('tag');
                $styles = $request->input('style');
                $query->where(function ($subQuery) use ($tags, $styles) {
                    if (!empty($tags)) {
                        $subQuery->whereHas('tags', function ($subSubQuery) use ($tags) {
                            $subSubQuery->whereIn('name', $tags);
                        }, '=', count($tags)); // Add this line to ensure all tags match
                    }
                    if (!empty($styles)) {
                        $subQuery->whereHas('styles', function ($subSubQuery) use ($styles) {
                            $subSubQuery->whereIn('name', $styles);
                        }, '=', count($styles)); // Add this line to ensure all styles match
                    }
                });
            })
            ->when(!empty($request->input('author')), function ($query) use ($request) {
                $authorId = $request->input('author');
                $query->where('user_id', $authorId);
            })
            ->when($order == 1, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($order == 2, function ($query) {
                $query->withCount('likes')->orderBy('likes_count', 'desc');
            })
            ->when($order == 3, function ($query) {
                $query->orderBy('views', 'desc');
            })
            ->when($order == 4, function ($query) {
                $query->withCount('comments')->orderBy('comments_count', 'desc');
            })
            ->paginate(6)
            ->withQueryString();

    }

}
