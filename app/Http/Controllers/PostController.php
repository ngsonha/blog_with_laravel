<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Posts;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use App\Enums\PostRole;

class PostController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $posts = Posts::GetAllPost()->orderBy('created_at', 'desc')->paginate(5);
    $title = 'Latest Posts';
    return view('home')->withPosts($posts)->withTitle($title);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    // 
    if ($request->user()->can_post()) {
      return view('posts.create');
    } else {
      return redirect('/')->withErrors(__('post.create_post'));
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(PostFormRequest $request)
  {
    $post = new Posts();
    $post->title = $request->get('title');
    $post->body = $request->get('body');
    $post->slug = Str::slug($post->title);

    $duplicate = Posts::GetPost($post->slug)->first();
    if ($duplicate) {
      return redirect('new-post')->withErrors(__('post.title'))->withInput();
    }

    $post->author_id = $request->user()->id;
    if ($request->has('save')) {
      $post->active = 0;
      $message = __('post.saved');
    } else {
      $post->active = 1;
      $message = __('post.published');
    }
    $post->save();
    return redirect('edit/' . $post->slug)->withMessage($message);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($slug)
  {
    $post = Posts::GetPost($slug)->first();


    if ($post) {
      if ($post->active == false)
        return redirect('/')->withErrors('requested page not found');
      $comments = $post->comments;
    } else {
      return redirect('/')->withErrors('requested page not found');
    }
    return view('posts.show')->withPost($post)->withComments($comments);

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit(Request $request, $slug)
  {
    $post = Posts::GetPost($slug)->first();
    if ($post && ($request->user()->id == $post->author_id || $request->user()->is_admin()))
      return view('posts.edit')->with('post', $post);
    else {
      return redirect('/')->withErrors(__('post.per'));
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request)
  {
    //
    $post_id = $request->input('post_id');
    $post = Posts::find($post_id);
    if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
      $title = $request->input('title');
      $slug = Str::slug($title);
      $duplicate = Posts::GetPost($slug)->first();
      if ($duplicate) {
        if ($duplicate->id != $post_id) {
          return redirect('edit/' . $post->slug)->withErrors(__('post.title'))->withInput();
        } else {
          $post->slug = $slug;
        }
      }

      $post->title = $title;
      $post->body = $request->input('body');

      if ($request->has('save')) {
        $post->active0 ;
        $message = __('post.update');
        $landing = 'edit/' . $post->slug;
      } else {
        $post->active1;
        $message = __('post.sucess');
        $landing = $post->slug;
      }
      $post->save();
      return redirect($landing)->withMessage($message);
    } else {
      return redirect('/')->withErrors(__('post.per'));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request, $id)
  {
    //
    $post = Posts::find($id);
    if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
      $post->delete();
      $data['message'] = __('post.deleted');
    } else {
      $data['errors'] = __('post.per');
    }

    return redirect('/')->with($data);
  }
}