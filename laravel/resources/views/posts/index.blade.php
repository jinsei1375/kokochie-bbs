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
                                @if(isset($post->user->icon->file_name))
                                  <span>
                                    <img src="{{ '/storage/img/icon/' . $post->user->icon->file_name }}" alt="">
                                  </span>
                                @endif
                                <span>{{ $post->id }}. {{ $post->created_at }}　{{ $post->user->your_name }}</span>
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
                              
                                  @if (empty($comment->parent_comment_id))
                                    <li>
                                      <div class="row">
                                        <div class="colmd-3">
                                          @if(isset($comment->user->icon->file_name))
                                            <span>
                                              <img src="{{ '/storage/img/icon/' . $comment->user->icon->file_name }}" alt="">
                                            </span>
                                          @endif
                                          <span>{{ $comment->id }}. {{ $comment->created_at }}　{{ $comment->user->your_name}}さんからのコメント</span>
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
                                      @Auth
                                        <div class="">
                                          <form action="{{ route('comments.store') }}" method="POST">
                                          {{ csrf_field() }}
                                          @method('POST')
                                              <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                              <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                                              <input type="hidden" name="base_comment_id" value="{{ $comment->id }}">
                                              <input type="hidden" name="post_id" value="{{ $post->id }}">
                                              <input type="submit" value="コメントにコメントする"  class="fas btn btn-teal">
                                          </form>
                                        </div>
                                      @endAuth

                                      @if (!empty($comment->getRelatedComments($comment->id)))
                                        <?php //var_dump($comment->getRelatedComments($comment->id));?> 
                                        <ul>
                                          <?php $beforeCommentId = $comment->id;
                                                $toCommentId = $comment->id;
                                                $kaiso = 0;
                                          ?>
                                          @foreach ($comment->getRelatedComments($comment->id) as $relatedComment)
                                            @if( $beforeCommentId == $relatedComment['parent_comment_id'] )
                                              <ul class="group-list">
                                                <li>
                                                  <p>{{ $relatedComment['id'] }}. {{ $relatedComment['parent_comment_id'] }}へ</p>
                                                  <p>{{ $relatedComment['content'] }}</p>
                                                  
                                                  @Auth
                                                    <div class="">
                                                      <form action="{{ route('comments.store') }}" method="POST">
                                                      {{ csrf_field() }}
                                                      @method('POST')
                                                          <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                                          <input type="hidden" name="parent_comment_id" value="{{ $relatedComment['id'] }}">
                                                          <input type="hidden" name="base_comment_id" value="{{ $comment->id }}">
                                                          <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                          <input type="submit" value="コメントにコメントする"  class="fas btn btn-teal">
                                                      </form>
                                                    </div>
                                                  @endAuth
                                                </li>
                                                <?php 
                                                  $beforeCommentId =  $relatedComment['id'];
                                                  $toCommentId = $relatedComment['parent_comment_id']; 
                                                  $kaiso += 1;
                                                ?>
                                                <?php //echo $beforeCommentId; ?>
                                            @else
                                              @if ($relatedComment['parent_comment_id'] != $toCommentId)
                                                <?php 
                                                  echo $kaiso; 
                                                  for($i=0; $i< ($kaiso - 1); $i++){
                                                    echo '</ul>';
                                                  }
                                                  ?>
                                                <?php $kaiso = 1;?>
                                              @else

                                              @endif
                                              <li>
                                                <p>{{ $relatedComment['id'] }}. {{ $relatedComment['parent_comment_id'] }}へ</p>
                                                <p>{{ $relatedComment['content'] }}</p>
                                                
                                                @Auth
                                                <div class="">
                                                  <form action="{{ route('comments.store') }}" method="POST">
                                                    {{ csrf_field() }}
                                                    @method('POST')
                                                    <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="1"></textarea>
                                                    <input type="hidden" name="parent_comment_id" value="{{ $relatedComment['id'] }}">
                                                    <input type="hidden" name="base_comment_id" value="{{ $comment->id }}">
                                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                    <input type="submit" value="コメントにコメントする"  class="fas btn btn-teal">
                                                  </form>
                                                </div>
                                                @endAuth
                                              </li>
                                              <?php $beforeCommentId =  $relatedComment['id']; ?>
                                              <?php //echo $beforeCommentId; ?>
                                            @endif
                                            @endforeach
                                        </ul>
                                      @endif
                                          
                                    </li>
                                  @endif
                                    
                                @endforeach
                              </ul>
                            </li>
                            <li>
                              <div class="row">
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
