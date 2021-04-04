<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    
    public function posts()
    {
        return view('admin.posts');
    }

    public function comments()
    {
        $comments = Comment::all();
        return view('admin.comments', ['comments' => $comments]);
    }

    public function deleteComment($id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->delete();

        return back();
    }

    public function users()
    {
        return view('admin.users');
    }
}
