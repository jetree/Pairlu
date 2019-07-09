<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    public function index(){
      $posts = Post::all();
      // dd($posts->toArray());
      return view('posts.index')
      ->with('posts',$posts);
    }

    public function store(Request $request){
      $this->validate($request,[
        'todo' => 'required'
      ]);
      $post = new Post();
      $post->todo = $request->todo;
      $post->save();
      return redirect('/');
    }

    public function destroy(Post $post){
      $post->delete();
      return redirect('/');
    }

    public function update(Request $request, Post $post){
      $this->validate($request,[
        'todo' => 'required'
      ]);
      $post->todo = $request->todo;
      $post->save();
      return redirect('/');
    }


}
