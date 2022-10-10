@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="col-10 col-md-6 offset-1 offset-md-3">
            <form action="{{ route('user.posts.update', $post->id) }}" method="POST">
                {{ csrf_field() }}
                @method('PUT')
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">編集</label>
                    <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="3">{{ $post->content }}</textarea>
                    <div class="text-center mt-3">
                        <input class="btn btn-primary" type="submit" value="更新する">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="PUT">
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection
