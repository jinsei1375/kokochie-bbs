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
    
    public function getChildRelatedCommentsToArray($commentId)
    {
        $childComments = $this->getChildComments($commentId);
        $childCommentsArray = $this->getChildCommentsToArray($commentId);
        $relatedCommentsArray = [];
        
        if (!empty($childCommentsArray)) {
            
            $relatedCommentsArray = $childCommentsArray;
            do {
                $keyComments = Comment::getOnlyChildComments(($childComments));
                $keyCommentsArray = Comment::getOnlyChildCommentsArray($childComments);
                if(!empty($keyCommentsArray)) {
                    array_push($relatedCommentsArray, $keyCommentsArray);
                }
                $childComments = $keyComments;
            } while (!$keyComments->isEmpty());
        }
        return $relatedCommentsArray;
    }

    // 直下の子コメントを取得
    public static function getChildComments($id)
    {
        $childComments = Comment::where('parent_comment_id', '=', $id)->get();
        return $childComments;
    }
    // 直下の子コメントを取得して配列に
    public static function getChildCommentsToArray($id)
    {
        $childCommentsArray = Comment::getChildComments($id)->toArray();
        return $childCommentsArray;
    }
    
    private static function getOnlyChildCommentsArray($comments)
    { 
        $onlyChildCommentsArray = [];
        foreach($comments as $comment) {
            if(!empty(Comment::getChildCommentsToArray($comment->id))){
                $childCommentsArray = Comment::getChildCommentsToArray($comment->id);
                foreach($childCommentsArray as $childCommentArray) {
                    $onlyChildCommentsArray[] = $childCommentArray;
                }
                \Log::debug($onlyChildCommentsArray);
            }
        }
        return $onlyChildCommentsArray;
    }

    private static function getOnlyChildComments($comments)
    { 
        $onlyChildComments = collect();
        foreach($comments as $comment) {
            $onlyChildComments->merge(Comment::getChildComments($comment->id));
        }
        return $onlyChildComments;
    }

    // // コメントに対するコメント取得（子コメント全て取得）
    // public function getChildRelatedCommentsToArray($id)
    // {
    //     $sortArry = [];
    //     // 直下の子供のコメントを取得
    //     $childComments = $this->getChildComments($id); 
        
    //     // 子供のコメント配列を取得
    //     if(!$childComments->isEmpty()) {
    //         $childCommentsArry = $this->getChildCommentsToArray($id); 
    //         if(!empty($childCommentsArry)){
    //             $sortArry = $childCommentsArry;
    //         }

    //         $sortArry = $this->addChildCommentsArray($sortArry, $childComments);
    //     }
    //     \Log::debug(print_r($sortArry, true));
    //     return $sortArry;
    // }
    // // 子供のコメント配列を親コメントの後ろに追加
    // public function addChildCommentsArray($targetArray, $parentComments)
    // {
    //     foreach($parentComments as $comment) {
    //         $commentArray = $comment->getChildCommentsToArray($comment->id);
    //         $arryNum = array_search($commentArray, $targetArray) + 1;
    //         $childComments = $comment->getChildComments($comment->id);
    //         $childCommentsArry = $comment->getChildCommentsToArray($comment->id);
    
    //         if(!empty($childCommentsArry)) {
    //             array_splice($targetArray, $arryNum, 0, [$childCommentsArry]);
    //             // 再帰呼び出し
    //             $comment->addChildCommentsArray($childCommentsArry, $childComments);
    //         }
    //     }
    //     return $targetArray;
    // }
}
