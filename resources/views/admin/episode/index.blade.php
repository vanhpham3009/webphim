@extends('layouts.app')


@section('navbar')
<div class="container">
    @include('layouts.navbar')
</div>
@endsection

@section('content')

<style>
    table.dataTable>thead>tr>th,
    table.dataTable>thead>tr>td {
        padding: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        align-items: center;
        text-align: center;
    }

    th,
    td {
        align-content: center;
        text-align: center;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 10px;">{{ __('Danh sách tập phim') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('episode.create')}}" class="btn btn-success" style="margin-bottom: 10px;">Thêm tập phim</a>
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên phim</th>
                            <th>Tập phim</th>
                            <th>Link</th>
                            <th>Quản lí</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_episode as $key => $episode)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$episode->movie->title}}</td>
                            <td>{{$episode->episode}}</td>
                            <td class="link-column">{{ $episode->linkphim }}</td>
                            <td>
                                <a href="{{route('episode.edit', $episode->id)}}" class="btn btn-warning mb-1" style="margin-bottom: 3px;">Sửa</a>
                                <form action="{{route('episode.destroy', $episode->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button onclick="return confirm('Xác nhận xoá tập phim này?')" class="btn btn-danger">Xoá</button>
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
<style>
    /* CSS cho iframe trong cột linkphim */
    td iframe {
        max-width: 350px;
        /* Giới hạn chiều rộng tối đa */
        width: 100%;
        height: 200px;
        /* Chiều cao cố định */
        border: none;
        /* Bỏ viền */
    }

    /* CSS cho cột chứa link phim */
    .link-column {
        width: 250px;
        /* Chiều rộng cố định cho cột */
        max-width: 250px;
        overflow: hidden;
        /* Ẩn nội dung bị tràn */
    }
</style>
@endsection