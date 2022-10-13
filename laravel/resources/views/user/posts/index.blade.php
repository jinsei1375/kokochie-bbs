@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="col-10 col-md-8 offset-1 offset-md-2 mb-5">
          @if(!isset(Auth::user()->icon->file_name))
            アイコン登録
            <form action="{{ route('icon.store') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="file" name="icon">
              <input type="submit" value="保存" class="btn btn-primary">
            </form>
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
                  </ul>
              </div>
            @endif
          @else
            <div class="icon-wrap">
              <img src="{{ '/storage/img/icon/' . Auth::user()->icon->file_name }}" alt="">
            </div>
            <a href="{{ route('icon.edit', Auth::user()->icon->id ) }}" class="btn btn-primary">アイコン変更</a>
          @endif
        </div>
          <div class="col-10 col-md-8 offset-1 offset-md-2">
            <a href="{{ route('user.posts.create') }}" class="btn btn-primary">新規投稿</a>
              <table class="table">
                  <tbody>
                      @foreach ($posts as $post)
                      <tr class="border">
                        <ul>
                            <li>{{ $post->user->your_name }}</li>
                            <li>{{ $post->created_at }}</li>
                            <li>{{ $post->content }}</li>
                            <li>
                              @if($post->user_id == Auth::id())
                              <div class="d-flex">
                                  <a href="{{ route('user.posts.edit', $post->id) }}" class="btn btn-success">編集</a>
                                  <form action="{{ route('user.posts.destroy', $post->id) }}" method="POST">
                                      {{ csrf_field() }}
                                      @method('DELETE')
                                      <input type="submit" value="削除" class="btn btn-danger post_del_btn" onclick="return Check()">
                                  </form>
                                @endif
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
