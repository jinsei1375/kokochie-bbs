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
        $relatedCommentsArry = Comment::where('base_comment_id', '=', $id)->get()->toArray();

        // 新しい配列作る
        $sortArry = [];
        
        if(!empty($relatedCommentsArry)){
            foreach ($relatedCommentsArry as $relatedComment) {
                
                $childCommentsArryBase = [];

                // 親コメント所得
                $parentComment = Comment::where('id', '=', $relatedComment['parent_comment_id'])->get()->toArray();
                
                // 重複チェックと親コメントが存在しているかチェック
                if(!in_array($relatedComment, $sortArry) && !empty($parentComment)){
                    array_push($sortArry, $relatedComment);
                }
                
                // 配列に入れる順番を取得（親のコメントの次に入れるため）
                $arryNum = array_search($relatedComment, $sortArry) + 1;
                
                // 子コメントを配列に置き換え
                $childCommentsArry = Comment::where('parent_comment_id', '=', $relatedComment['id'])->get()->toArray();

                // 直下の子コメントを配列に$sortArryに追加（重複チェック）
                foreach ($childCommentsArry as $childComment) {
                    if(!in_array($childComment, $sortArry)){
                        array_splice($sortArry, $arryNum, 0, [$childComment]);
                    }
                }


            }
            return $sortArry;
        }
    }



}
