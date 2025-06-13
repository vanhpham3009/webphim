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

                <a href="{{route('genre.index')}}" class="btn btn-primary" style="margin-bottom: 5px;">Quay lại danh sách Thể loại phim</a>
                <form action="{{route('genre.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="slug">Tên thể loại</label>
                        <input type="text" class="form-control" name="title" id="slug" onkeyup="ChangeToSlug()" placeholder="Nhập tên thể loại">
                    </div>
                    <div class="form-group">
                        <label for="convert_slug">Slug thể loại</label>
                        <input type="text" class="form-control" readonly name="slug" id="convert_slug" placeholder="---">
                    </div>
                    <div class="form-group">
                        <label for="InputDescription">Mô tả</label>
                        <textarea class="form-control" name="description" id="InputDescription" placeholder="Nhập mô tả"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Thêm thể loại</button>
                </form>

                <!-- <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ html()->form('POST')->route('genre.store')->open() }}

                    <div class="form-group">
                        {{ html()->label('Tên thể loại', 'title') }}
                        {{ html()->text('title')->class('form-control')->placeholder('Nhập tên thể loại') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Mô tả', 'description') }}
                        {{ html()->textarea('description')->class('form-control')->placeholder('Nhập mô tả')->style('line-height: 1.5; field-sizing: content; min-height: 1.5;') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Trạng thái', 'status') }}
                        {{ html()->select('status')->class('form-control')->options([1 => 'Hiệu lực'])->class('form-control') }}
                    </div>
                    {{ html()->submit('Thêm thể loại')->class('btn btn-success mt-2')}}
                    {{ html()->form()->close() }}
                </div> -->
            </div>
        </div>
    </div>
</div>

@endsection