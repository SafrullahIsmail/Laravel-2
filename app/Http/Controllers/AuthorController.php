<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreatePost;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:author');
    }

    public function dashboard()
    {
        $posts = Post::where('user_id', Auth::id())->pluck('id')->toArray();
        $comments = Comment::whereIn('post_id', $posts)->get();

        return view('author.dashboard', ['allComments' => $comments]);
    }
    
    public function posts()
    {
        return view('author.posts');
    }

    public function comments()
    {
        return view('author.comments');
    }

    public function newPost()
    {
        return view('author.newPost');
    }

    public function create(CreatePost $request)
    {
        $post = new Post();
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->user_id = Auth::id();
        $post->save();

        return back()->with('success', 'Post is successfully created.');
    }
    
    public function postEdit($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        return view('author.editFormPost', ['post' => $post]);
    }

    public function edit(CreatePost $request, $id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->save();

        return back()->with('success', 'Post updated successfully');
    }

    public function delete($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();
        $post->delete();

        return back();
    }
}
