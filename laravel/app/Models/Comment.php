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

                // 配列に入れる順番を取得（親のコメントの次に入れるため）
                $arryNum = array_search($relatedComment, $relatedCommentsArry) + 1;

                // 子コメントを配列に
                $childComments = Comment::where('parent_comment_id', '=', $relatedComment['id'])->get()->toArray();

                // 配列に入れる
                array_splice($relatedCommentsArry, $arryNum, 0, $childComments);
            }
            return $relatedCommentsArry;
        }
    }

    // 投稿に対するコメント取得（子コメント全て取得）
    // public function getRelatedComments($id)
    // {
    //     $relatedCommentsArry = [];

    //     $baseArray = Comment::where('parent_comment_id', '=', $id)->get()->toArray();

    //     foreach($baseArray as $array){
    //         if(!empty(Comment::where('parent_comment_id', '=', $array['id'])->get()->toArray())){
    //             // 配列に入れる順番を取得（親のコメントの次に入れたい）
    //             $arryNum = array_search($array, $baseArray) + 1;

    //             $currentComment = Comment::where('id', '=', $array['id'])->first();

    //             $targetArray = Comment::where('parent_comment_id', '=', $array['id'])->get()->toArray();
    //             foreach($targetArray as $array){
    //                 if(!empty(Comment::where('parent_comment_id', '=', $array['id'])->get()->toArray())){
    //                     $currentComment = Comment::where('id', '=', $array['id'])->first();
    //                     array_splice($baseArray, $arryNum, 0, Comment::where('parent_comment_id', '=', $array['id'])->get()->toArray());
    //                 }
    //             }

    //             // 再帰呼び出し
    //             // $currentComment->getrelatedComments($currentComment->id);

    //             // 配列に入れる
    //             array_splice($baseArray, $arryNum, 0, Comment::where('parent_comment_id', '=', $array['id'])->get()->toArray());

    //             return $baseArray;
    //         }
    //     }

        
    // }



}
