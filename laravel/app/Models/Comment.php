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
    // 直下の子コメントを取得
    public function getChildComments($id)
    {
        $childComments = Comment::where('parent_comment_id', '=', $id)->get();
        return $childComments;
    }
    // 直下の子コメントを取得して配列に
    public function getChildCommentsToArray($id)
    {
        $childCommentsArray = $this->getChildComments($id)->toArray();
        return $childCommentsArray;
    }
    // コメントに対するコメント取得（子コメント全て取得）
    public function getChildRelatedCommentsToArray($id)
    {
        $sortArry = [];
        // 直下の子供のコメントを取得
        $childComments = $this->getChildComments($id); 
        
        // 子供のコメント配列を取得
        if(!$childComments->isEmpty()) {
            $childCommentsArry = $this->getChildCommentsToArray($id); 
            if(!empty($childCommentsArry)){
                $sortArry = $childCommentsArry;
            }

            $sortArry = $this->addChildCommentsArray($sortArry, $childComments);
        }
        \Log::debug(print_r($sortArry, true));
        return $sortArry;
    }
    // 子供のコメント配列を親コメントの後ろに追加
    public function addChildCommentsArray($targetArray, $parentComments)
    {
        foreach($parentComments as $comment) {
            $commentArray = $comment->getChildCommentsToArray($comment->id);
            $arryNum = array_search($commentArray, $targetArray) + 1;
            $childComments = $comment->getChildComments($comment->id);
            $childCommentsArry = $comment->getChildCommentsToArray($comment->id);
    
            if(!empty($childCommentsArry)) {
                array_splice($targetArray, $arryNum, 0, [$childCommentsArry]);
                // 再帰呼び出し
                $comment->addChildCommentsArray($childCommentsArry, $childComments);
            }
        }
        return $targetArray;
    }
}
