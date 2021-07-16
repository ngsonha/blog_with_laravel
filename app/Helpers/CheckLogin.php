<?php

function checkuser($post){
    if (!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin())){
         echo '<button class="btn" style="float: right"><a href="{{ url("edit/"' .$post->slug .')}}">Edit Post</a></button>';
    }
}
function checkuserdraf($post){
    if (!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->is_admin())){
        if($post->active == '1'){
            echo '<button class="btn" style="float: right"><a href="{{ url("edit/"' .$post->slug .')}}">Edit Post</a></button>';
        }else{
            echo '<button class="btn" style="float: right"><a href="{{ url("edit/"' .$post->slug .')}}">Edit Draf</a></button>';
        }
   
    }
}
?>