<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Posts;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function logout()
    {
      Auth::logout();
      return redirect('/');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $posts = Posts::UserAllPost($user->id)->orderBy('created_at', 'desc')->paginate(5);
        $title = $user->name;
        return view('home')->withPosts($posts)->withTitle($title);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $posts = Posts::UserPost0($user->id)->orderBy('created_at', 'desc')->paginate(5);
        $title = $user->name;
        return view('home')->withPosts($posts)->withTitle($title);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Posts::UserPost1($id)->orderBy('created_at', 'desc')->paginate(5);
        $title = User::find($id)->name;
        return view('home')->withPosts($posts)->withTitle($title);
    }
    
    public function profile(Request $request, $id)
    {
      $data['user'] = User::find($id);
      if (!$data['user'])
        return redirect('/');
  
      if ($request->user() && $data['user']->id == $request->user()->id) {
        $data['author'] = true;
      } else {
        $data['author'] = null;
      }
      
      $data['posts_count'] = $data['user']->posts->count();
      $data['posts_active_count'] = $data['user']->posts->where('active', 1)->count();
      $data['posts_draft_count'] = $data['posts_count'] - $data['posts_active_count'];
      $data['latest_posts'] = $data['user']->posts->where('active', 1)->take(5);
      return view('admin.profile', $data);
    }
    
}