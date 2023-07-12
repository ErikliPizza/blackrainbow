<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|max:255',
            'art_id' => 'required|exists:arts,id'
        ]);
        $validatedData['user_id'] = Auth::id();

        // Save the comment to the database or perform other actions as needed
        Comment::create($validatedData);
        return redirect()->back()->with('success', 'comment posted');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect()->back()->with('success', 'comment deleted');
    }
}
