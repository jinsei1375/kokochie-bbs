@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="col-10 col-md-6 offset-1 offset-md-3">
            <div class="icon-wrap">
                <img src="{{ '/storage/img/icon/' . Auth::user()->icon->file_name }}" alt="">
            </div>
            <form action="{{ route('icon.update', Auth::user()->icon->id) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                @method('PUT')
                <div class="form-group">
                    <div class="text-center mt-3">
                    <input type="file" name="icon">
                    <input type="submit" value="更新" class="btn btn-primary">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="_method" value="PUT">
                    </div>
                </div>
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
            <a href="{{ route('user.posts.index') }}">管理画面へ</a>
        </div>
        </div>
    </div>
</div>
@endsection
