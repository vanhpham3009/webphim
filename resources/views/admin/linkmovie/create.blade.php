@extends('layouts.app')
@section('navbar')

<div class="container">
    @include('layouts.navbar')
</div>
@endsection

@section('content')

<style>
    textarea {
        line-height: 1.5;
        field-sizing: content;
        min-height: 1.5;
        margin-bottom: 5px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Thêm thể loại phim') }}</div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('linkmovie.index')}}" class="btn btn-primary" style="margin-bottom: 5px;">Quay lại danh sách Thể loại phim</a>
                <form action="{{route('linkmovie.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="slug">Tên link</label>
                        <input type="text" class="form-control" name="title" id="slug" onkeyup="ChangeToSlug()" placeholder="Nhập tên thể loại">
                    </div>
                    <div class="form-group">
                        <label for="InputDescription">Mô tả</label>
                        <textarea class="form-control" name="description" id="InputDescription" placeholder="Nhập mô tả"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Thêm link</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection