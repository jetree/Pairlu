<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Post;
use App\User;
use App\Friend;
use Log;

class PostsController extends Controller
{
    public function index(){
      $Auth = Auth::user();
      $id = Auth::id();
      $posts = Post::where('user_id',[$id])->get();
      $users = User::whereNotIn('id',[$id])->get();
      // dd($users);
      if (Auth::check()){
        // 全てのフォローしたユーザー
        $all_follow_friends = Auth::user()->follow_friends()->get();
        // 全てのフォローを受けたユーザー
        $all_follower_friends = Auth::user()->follower_friends()->get();
        // 相互フォロー状態を抽出
        $friends = $all_follower_friends->intersect($all_follow_friends);
        // 全てのフォローしたユーザー + 全てのフォローを受けたユーザー
        $merge_friends = $all_follow_friends->merge($all_follower_friends);
        // 相互フォローから全てのフォローを受けたユーザーを除外
        $follow_friends = $merge_friends->diff($all_follower_friends);
        // 相互フォローから全てのフォローしたユーザーを除外
        $follower_friends = $merge_friends->diff($all_follow_friends);
        // postに共有先が自分になっているpostを追加
        $posts = $posts->merge(Post::where('friend_id',[$id])->get());
      }else {
        $posts = Post::whereNull('user_id')->get();
        $follow_friends = [];
        $follower_friends = [];
        $friends = [];
      }
        return view('posts.index')
        ->with([
          'posts' => $posts,
          'Auth' => $Auth,
          'users' => $users,
          'follow_friends' => $follow_friends,
          'follower_friends' => $follower_friends,
          'friends' => $friends,
        ]);
    }

    public function store(Request $request){
      $this->validate($request,[
        'todo' => 'required',
      ]);
      $post = new Post();
      $post->todo = $request->todo;
      $post->user_id = $request->user_id;
      $post->status = 0;
      $post->friend_id = $request->friend_id;
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

    public function done(Request $request){
      Log::debug($request);
      $post = Post::where('id',$request->post_id)->first();
      Log::debug($post->status);
      if($post->status == 0){
        $post->status = 1;
        $post->save();
        Log::debug($post->status);
        return response()->json(
            '完了しました'
        );
      }else{
        Log::debug($post);
        $post->status = 0;
        $post->save();
        Log::debug($post);
        return response()->json(
          '取り消しました'
        );
      }

    }


}
