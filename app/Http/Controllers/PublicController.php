<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(2);

        return view('welcome', ['posts' => $posts]);
    }

    public function singlePost($id)
    {
        $post = Post::find($id);
        return view('singlePost', ['post' => $post]);
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactPost()
    {
                
    }

}
