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
    // public function getRelatedComments($id)
    // {
    //     $relatedCommentsArry = Comment::where('base_comment_id', '=', $id)->get()->toArray();

    //     // 新しい配列作る
    //     $sortArry = [];
        
    //     if(!empty($relatedCommentsArry)){
    //         foreach ($relatedCommentsArry as $relatedComment) {

    //             // 親コメント取得
    //             $parentComment = Comment::where('id', '=', $relatedComment['parent_comment_id'])->get()->toArray();
                
    //             // 重複チェックと親コメントが存在しているかチェック
    //             if(!in_array($relatedComment, $sortArry) && !empty($parentComment)){
    //                 array_push($sortArry, $relatedComment);
    //             }
                
    //             // 配列に入れる順番を取得（親のコメントの次に入れるため）
    //             $arryNum = array_search($relatedComment, $sortArry) + 1;
                
    //             // 子コメントを配列に置き換え
    //             $childCommentsArry = Comment::where('parent_comment_id', '=', $relatedComment['id'])->get()->toArray();

    //             // 直下の子コメントを配列に$sortArryに追加（重複チェック）
    //             foreach ($childCommentsArry as $childComment) {
    //                 if(!in_array($childComment, $sortArry)){
    //                     array_splice($sortArry, $arryNum, 0, [$childComment]);
    //                 }
    //             }

    //         }
    //         return $sortArry;
    //     }
    // }
    
    // コメントに対するコメント取得（子コメント全て取得）
    public function getChildRelatedCommentsToArray($id)
    {
        $sortArry = [];
        // 子供のコメントを取得
        $childComments = $this->getChildComments($id); 
        
        // 子供のコメント配列を取得
        if($childComments !== null) {
            $childCommentsArry = $this->getChildCommentsToArray($id); 
            if(!empty($childCommentsArry)){
                $sortArry = $childCommentsArry;
            }
            $sortArry = $this->addChildCommentsArray($sortArry, $childComments);
        }
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
                \Log::debug(print_r($childCommentsArry, true));
                $comment->addChildCommentsArray($childCommentsArry, $childComments);
            }
        }
        return $targetArray;
    }
    
    // コメントを取得
    public function getComments($id)
    {
        $Comments = Comment::where('parent_comment_id', '=', $id)->get();
        
        return $Comments;
    }
    // コメントを取得して配列に
    public function getCommentsToArray($id)
    {
        $Comments = Comment::where('parent_comment_id', '=', $id)->get()->toArray();
        
        return $Comments;
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
        $childCommentsArray = Comment::where('parent_comment_id', '=', $id)->get()->toArray();
        
        return $childCommentsArray;
    }
    
    
    // コメントに関連するコメントを全て取得する
    public function getRelatedAllCommentsToArray($id)
    {
        $relatedAllRelatedCommentsToArray = Comment::where('base_comment_id', '=', $id)->get()->toArray();

        return $relatedAllRelatedCommentsToArray;
    }

}
