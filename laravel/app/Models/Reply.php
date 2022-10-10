<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reply extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }
    public function post(){
        return $this->belongsTo('App\Models\Post');
    }
    public function comment(){
        return $this->belongsTo('App\Models\Comment');
    }
    public function getReply($replyId)
    {
        $replyReplies = DB::table('replies')->where('comment_id', $replyId);

        return $replyReplies;
    }
}
