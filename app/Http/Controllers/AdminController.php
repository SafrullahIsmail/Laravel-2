<?php

namespace App\Http\Controllers;

use App\Charts\DashboardChart;
use App\Comment;
use App\Post;
use App\Product;
use App\Http\Requests\CreatePost;
use App\Http\Requests\UserUpdate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkRole:admin');
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $chart = new DashboardChart;

        $days = $this->generateDateRange(Carbon::now()->subDays(30), Carbon::now());

        $posts = [];

        foreach($days as $day){
            $posts[] = Post::whereDate('created_at', $day)->count();
        }

        $chart->dataset('Posts', 'line', $posts);
        $chart->labels($days);


        return view('admin.dashboard', ['chart' => $chart]);
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        
        for($date = $start_date; $date->lte($end_date); $date->addDay()){
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
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

    public function editPost(CreatePost $request, $id)
    {
        $post = Post::where('id', $id)->first();
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->save();

        return back()->with('success', 'Post updated successfully');
    }

    public function deletePost($id)
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
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    public function userEdit($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.editFormUser', ['user' => $user]);
    }

    public function editUser(UserUpdate $request, $id)
    {
        $user = User::where('id', $id)->first();
        $user->name = $request['name'];
        $user->email = $request['email'];
        if($request['author'] == 1){
            $user->author = true;
        }
        if($request['admin'] == 1){
            $user->admin = true;
        }
        $user->save();

        return back()->with('success', 'User update successfully');
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();

        return back();
    }

    public function products()
    {
        $products = Product::all();
        return view('admin.products', ['products' => $products]);
    }

    public function newProduct()
    {
        return view('admin.newProductForm');
    }

    public function newProductPost(Request $request)
    {
        $this->validate($request, [
            'thumbnail' => 'required|file',
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        $product = new Product;
        $product->title = $request['title'];
        $product->description = $request['description'];
        $product->price = $request['price'];

        $thumbnail = $request->file('thumbnail');

        $fileName = $thumbnail->getClientOriginalName();
        $fileExtension = $thumbnail->getClientOriginalExtension();

        $thumbnail->move('product-images', $fileName);
        $product->thumbnail = 'product-images/' . $fileName;

        $product->save();

        return back();
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.editProductForm', ['product' => $product]);
    }

    public function editProductPost(Request $request, $id)
    {
        $this->validate($request, [
            'thumbnail' => 'file',
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/'
        ]);

        $product = Product::findOrFail($id);

        $product->title = $request['title'];
        $product->description = $request['description'];
        $product->price = $request['price'];

        if($request->hasFile('thumbnail')){
            $thumbnail = $request->file('thumbnail');
            $fileName = $thumbnail->getClientOriginalName();        
            $thumbnail->move('product-images', $fileName);
            $product->thumbnail = 'product-images/' . $fileName;
        }
        $product->save();

        return back();
    }
}
