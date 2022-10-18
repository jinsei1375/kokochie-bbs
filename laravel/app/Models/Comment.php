<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function order_desc() {
        return $this->orderBy('created_at', 'desc')->get();
    }
    public function replies() {
        return $this->hasMany('App\Models\Reply');
    }

    // public function getRelatedComment($id)
    // {
    //     $relatedComments = Comment::where('parent_comment_id', '=', $id)->get();
    //     if(!$relatedComments){
    //         $list = '<ul>'
    //         foreach($relatedComments as $relatedComment) {
    //             $list .= '<li>' $relatedComment->user->name . '<br>' . $relatedComment->cotent . '</li>';
    //             $relatedComment->getRelatedComment($relatedComment->id);
    //         }
    //         return $list;
    //     }
    //     return false;
    // }
}
