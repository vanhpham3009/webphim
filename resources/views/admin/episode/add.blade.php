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
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Danh sách tập phim') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                <a href="{{route('movie.index')}}" class="btn btn-primary" style="margin-bottom: 10px;">Quay lại danh sách phim</a>
                <form action="{{route('episode.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Tên phim</label>
                        <input type="hidden" class="form-control" name="movie_id" id="movie_id" value="{{$movie->id}}" />
                        <input required class="form-control" name="movie_title" id="movie_title" value="{{$movie->title}}" disabled />
                    </div>
                    <div class="form-group">
                        <label>Chọn tập phim</label>
                        <select required class="form-control" name="episode" id="episode">
                            <option value="">-----Chọn tập phim-----</option>
                            @for($i = 1; $i <= $movie->episode_number; $i++)
                                <option value="{{$i}}">Tập {{$i}}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="linkphim">Link phim</label>
                        <input type="text" class="form-control" name="linkphim" id="linkphim" placeholder="...">
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Thêm tập phim</button>
                </form>
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
                            <td class="link-column">{{$episode->linkphim}}</td>
                            <td>
                                <a href="{{route('episode.edit', $episode->id)}}" class="btn btn-warning mb-1">Sửa</a>
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
<script>
    $('.select_movie').change(function() {
        var movie_id = $(this).val();
        $.ajax({
            url: "{{route('select-movie')}}",
            method: "GET",
            data: {
                movie_id: movie_id
            },
            success: function(data) {
                $('#episode').html(data);
            }
        });
    });
</script>
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