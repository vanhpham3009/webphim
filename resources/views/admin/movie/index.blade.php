@extends('layouts.app')


@section('navbar')
<div class="container">
    @include('layouts.navbar')
</div>
@endsection

@section('content')

<style>
    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 5px 5px;
    }

    table.dataTable>thead>tr>th,
    table.dataTable>thead>tr>td {
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        align-items: center;
        text-align: center;
    }

    th,
    td {
        align-content: center;
        text-align: center;
    }

    .col-md-0-1 {
        width: 1%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-md-0-5 {
        width: 5%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-md-1-5 {
        width: 12%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-md-1-1 {
        width: 7%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-md-1-2 {
        width: 9%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-md-1-3 {
        width: 10%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .title {
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    .btn-dark {
        background-color: rgb(145, 189, 234);
        color: white;
        border: none;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-dark a {
        color: white;
        text-decoration: none;
    }

    .col-md-12 {
        padding-left: 0;
        padding-right: 0;
    }

    .col-img {
        width: 10%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 10px;">{{ __('Danh sách phim') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('movie.create')}}" class="btn btn-success" style="margin-bottom: 10px;">Thêm phim</a>
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên phim</th>
                            <th>Tập phim</th>
                            <th>Số tập</th>
                            <th>Danh mục</th>
                            <th>Thể loại</th>
                            <th>Quốc gia</th>
                            <th>Phim hot</th>
                            <th>Hình ảnh</th>
                            <th>Trạng thái</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movie as $key => $value)
                        <tr>
                            <td class="col-md-0-1">{{$loop->iteration}}</td>
                            <td class="col-md-1-5">{{$value->title}}</td>
                            <td class="col-md-0-5">
                                <button class="btn-dark">
                                    <a href="{{ route('add-episode', $value->id) }}">Thêm tập phim</a>
                                </button>
                            </td>
                            <td class="col-md-0-5">{{ $value->episode_count }} / {{$value->episode_number}} tập</td>
                            <td class="col-md-1-5">
                                <select class="form-control category-select" data-movie-id="{{ $value->id }}">
                                    @foreach($category as $cate)
                                    <option value="{{ $cate->id }}" {{ $value->category_id == $cate->id ? 'selected' : '' }}>
                                        {{$cate->title}}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <!-- <td class="col-md-0-5">{{$value->category->title}}</td> -->
                            <td class="col-md-1-5">
                                @foreach ($value->movie_genre as $genre)
                                <span class="badge bg-dark text-white">{{$genre->title}}</span>
                                @endforeach
                            </td>
                            <!-- <td>{{$value->country->title}}</td> -->
                            <td class="col-md-1-2">
                                <select class="form-control country-select" data-movie-id="{{ $value->id }}">
                                    @foreach($country as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ $value->country_id == $countryItem->id ? 'selected' : '' }}>
                                        {{ $countryItem->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-md-1-1">
                                <select class="form-control hot-select" data-movie-id="{{ $value->id }}">
                                    <option value="1" {{ $value->is_hot == 1 ? 'selected' : '' }}>Có</option>
                                    <option value="0" {{ $value->is_hot == 0 ? 'selected' : '' }}>Không</option>
                                </select>
                            </td>
                            <td class="col-img"><img src="{{asset('uploads/movie/'.$value->image)}}" style="width: 140px; height: 190px;"></td>
                            <td class="col-md-1-2">
                                <select class="form-control status-select" data-movie-id="{{ $value->id }}">
                                    <option value="1" {{ $value->status == 1 ? 'selected' : '' }}>Hiệu lực</option>
                                    <option value="0" {{ $value->status == 0 ? 'selected' : '' }}>Sắp chiếu</option>
                                    <option value="2" {{ $value->status == 2 ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </td>

                            <td class="col-md-0-5">
                                <a href="{{route('movie.edit', $value->id)}}" class="btn btn-warning mb-1" style="margin-bottom: 3px;">Sửa</a>
                                <form action="{{route('movie.destroy', $value->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button onclick="return confirm('Xác nhận xoá phim này?')" class="btn btn-danger">Xoá</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection