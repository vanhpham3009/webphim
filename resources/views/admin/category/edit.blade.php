@extends('layouts.app')


@section('navbar')
<div class="container">
</div>
@endsection

@section('content')

<style>
    textarea {
        line-height: 1.5;
        field-sizing: content;
        min-height: 1.5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Cập nhật danh mục phim') }}</div>

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

                <form action="{{route('category.update', $category->id)}}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="slug">Tên danh mục</label>
                        <input type="text" class="form-control" name="title" id="slug" onkeyup="ChangeToSlug()" value="{{$category->title}}" placeholder="Nhập tên danh mục">
                    </div>
                    <div class="form-group">
                        <label for="convert_slug">Slug danh mục</label>
                        <input type="text" class="form-control" name="slug" id="convert_slug" value="{{$category->slug}}" placeholder="Nhập slug">
                    </div>
                    <div class="form-group">
                        <label for="InputDescription">Mô tả</label>
                        <textarea class="form-control" name="description" id="InputDescription" placeholder="Nhập mô tả">{{$category->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status">
                            @if ($category->status == 1)
                            <option value="1" selected>Hiệu lực</option>
                            <option value="0">Hết hiệu lực</option>
                            @else
                            <option value="0" selected>Hết hiệu lực</option>
                            <option value="1">Hiệu lực</option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success mt-1">Cập nhật danh mục</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection