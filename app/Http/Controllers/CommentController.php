<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CommentController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    //on_post, from_user, body
    $input['from_user'] = $request->user()->id;
    $input['on_post'] = $request->input('on_post');
    $input['body'] = $request->input('body');
    $slug = $request->input('slug');
    Comments::create($input);

    return redirect($slug)->with('message', __('comment.published'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request,$id)
  {
    $comment = Comments::find($id);
    if ($comment && ($comment->author_id == $request->user()->id || $request->user()->is_admin())) {
      $comment->delete();
    } else {
      $data['errors'] = __('comment.per');
    }

    return redirect('');
  }
}