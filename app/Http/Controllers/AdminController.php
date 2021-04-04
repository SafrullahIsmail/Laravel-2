<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Http\Requests\CreatePost;
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
        $posts = Post::all();
        return view('admin.posts', ['posts' => $posts]);
    }

    public function postEdit($id)
    {
        $post = Post::where('id', $id)->first();
        return view('admin.editFormPost', ['post' => $post]);
    }

    public function edit(CreatePost $request, $id)
    {
        $post = Post::where('id', $id)->first();
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->save();

        return back()->with('success', 'Post updated successfully');
    }

    public function delete($id)
    {
        $post = Post::where('id', $id)->first();
        $post->delete();

        return back();
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
