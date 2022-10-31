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


    // 投稿に対するコメント取得（子コメント全て取得）
    public function getRelatedComments($id)
    {
        $relatedCommentsArry = Comment::where('parent_comment_id', '=', $id)->get()->toArray();

        if(!empty($relatedCommentsArry)){
            foreach ($relatedCommentsArry as $relatedComment) {

                // 配列に入れる順番を取得（親のコメントの次に入れたい）
                $arryNum = array_search($relatedComment, $relatedCommentsArry) + 1;

                // 配列に入れる
                array_splice($relatedCommentsArry, $arryNum, 0, Comment::where('parent_comment_id', '=', $relatedComment['id'])->get()->toArray());
            }
            return $relatedCommentsArry;
        }
    }


}
