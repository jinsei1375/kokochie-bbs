@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="col-10 offset-1 offset-md-2">
            <a href="{{ route('user.posts.create') }}" class="btn btn-primary">新規投稿</a>
              <table class="table post_list">
                  <tbody>
                      @foreach ($posts as $post)
                      <tr class="border">
                        <ul class="post_list">
                            <li><div class="row">
                              <div class="colmd-3">
                                @if(isset($post->user->icon))
                                  <span>
                                    <img src="{{ '/storage/img/icon/' . $post->user->icon }}" alt="">
                                  </span>
                                @endif
                                <span>{{ $post->created_at }}　{{ $post->user->your_name }}</span>
                              </div>
                            </li>
                            <li><div class="row"><div class="colmd-3">{{ $post->content }}</div></div></li>
                            <li>
                              @if($post->user_id == Auth::id())
                                <div class="d-flex">
                                  <a href="{{ route('user.posts.edit', $post->id) }}" class="btn btn-success">編集</a>
                                  <form action="{{ route('user.posts.destroy', $post->id) }}" method="POST">
                                      {{ csrf_field() }}
                                      @method('DELETE')
                                      <input type="submit" value="削除" class="btn btn-danger post_del_btn" onclick="Check()">
                                  </form>
                                </div>
                              @endif
                            </li>
                            <li>
                              @Auth
                              <div class="">
                                  <form action="{{ route('comments.store') }}" method="POST">
                                  {{ csrf_field() }}
                                  @method('POST')
                                      <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                      <input type="hidden" name="post_id" value="{{ $post->id }}"  class="fas btn btn-primary">
                                      <input type="submit" value="コメントする"  class="fas btn btn-primary">
                                  </form>
                              </div>
                              @endAuth
                            </li>
                            <li class="commets-list">
                              <ul>
                              @foreach ($post->comments as $comment)
                                <li>
                                  <div class="row">
                                    <div class="colmd-3">
                                      @if(isset($comment->user->icon))
                                        <span>
                                          <img src="{{ '/storage/img/icon/' . $comment->user->icon }}" alt="">
                                        </span>
                                      @endif
                                      <span>{{ $comment->created_at }}　{{ $comment->user->your_name}}さんからのコメント</span>
                                    </div>
                                  </div>
                                  <p>{{ $comment->content }}</p>
                                  @if($comment->user->id == Auth::id())
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                      {{ csrf_field() }}
                                      @method('DELETE')
                                      <input type="submit" value="削除" class="btn btn-danger comment_del_btn" onclick="Check()"> 
                                    </form>
                                  @endif
                                  <ul>
                                    <li>
                                      @Auth
                                      <div class="">
                                          <form action="{{ route('reply.store') }}" method="POST">
                                          {{ csrf_field() }}
                                          @method('POST')
                                              <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                              <input type="hidden" name="reply_id" value="{{ $comment->id }}"  class="fas btn btn-primary">
                                              <input type="submit" value="返信する"  class="fas btn btn-green">
                                          </form>
                                      </div>
                                      @endAuth
                                    </li>
                                      @foreach($comment->replies as $reply)
                                        <li class="reply_list">
                                            <div class="row">
                                              <div class="colmd-3">
                                                @if(isset($reply->user->icon))
                                                  <span>
                                                    <img src="{{ '/storage/img/icon/' . $reply->user->icon }}" alt="">
                                                  </span>
                                                @endif
                                                <span>{{ $reply->created_at }}　{{ $reply->user->your_name}}さんからの返信</span>
                                              </div>
                                            </div>
                                            <p>{{ $reply->content }}</p>
                                            @Auth
                                              <div class="">
                                                  <form action="{{ route('reply.store') }}" method="POST">
                                                  {{ csrf_field() }}
                                                  @method('POST')
                                                      <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                                      <input type="hidden" name="reply_id" value="{{ $reply->id }}"  class="fas btn btn-primary">
                                                      <input type="submit" value="返信する"  class="fas btn btn-green">
                                                  </form>
                                              </div>
                                            @endAuth
                                            @if($reply->user->id == Auth::id())
                                              <form action="{{ route('reply.destroy', $reply->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                @method('DELETE')
                                                <input type="submit" value="削除" class="btn btn-danger comment_del_btn" onclick="Check()"> 
                                              </form>
                                            @endif
                                            
                                        </li>
                                      @endforeach
                                  </ul>
                                </li>
                              @endforeach
                              </ul>
                            </li>
                            <li>
                              <div class="row">
                                  <!-- @Auth
                                    @if($post->favoriteusers()->where('user_id', Auth::id())->exists())
                                      <div class="">
                                          <form action="{{ route('unfavorites', $post) }}" method="POST">
                                          {{ csrf_field() }}
                                              <input type="submit" value="いいね取り消す" class="fas btn btn-danger">
                                          </form>
                                      </div>
                                    @else
                                      <div class="">
                                          <form action="{{ route('favorites', $post) }}" method="POST">
                                          {{ csrf_field() }}
                                              <input type="submit" value="いいね" class="fas btn btn-success">
                                          </form>
                                      </div>
                                    @endif
                                  @endAuth -->
                                  <!-- <div class="">
                                    <p>いいね数：{{ $post->favoriteusers()->count() }}</p>
                                  </div> -->
                                  @Auth
                                    @if($like_model->like_exist(Auth::id(),$post->id))
                                      <p class="favorite-marke">
                                        <a class="js-like-toggle loved" href="" data-postid="{{ $post->id }}"><i class="fas fa-heart"></i></a>
                                        <span class="likesCount">{{$post->likes_count}}</span>
                                      </p>
                                    @else
                                      <p class="favorite-marke">
                                        <a class="js-like-toggle" href="" data-postid="{{ $post->id }}"><i class="fas fa-heart"></i></a>
                                        <span class="likesCount">{{$post->likes_count}}</span>
                                      </p>
                                      @endif​
                                  @else
                                      <p class="favorite-marke">
                                        <a class="" href="{{ route('login') }}" data-postid="{{ $post->id }}"><i class="fas fa-heart"></i></a>
                                        <span class="likesCount">{{$post->likes_count}}</span>
                                      </p>
                                  @endAuth
                              </div>
                            </li>
                        </ul>
                      </tr>
                      @endforeach
                  </tbody>
              </table> 
          </div>
        </div>
    </div>
</div>
@endsection
