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
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Thêm phim') }}</div>

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

                <a href="{{route('episode.index')}}" class="btn btn-primary" style="margin-bottom: 5px;">Quay lại danh sách tập phim</a>
                <form action="{{route('episode.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Chọn phim</label>
                        <select required class="form-control select_movie" name="movie_id">
                            <option value="0">-----Chọn phim-----</option>
                            @foreach ($movie as $key => $mov)
                            <option value="{{$mov->id}}">{{$mov->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Chọn tập phim</label>
                        <select required class="form-control" name="episode" id="episode">
                            <option value="0">-----Chọn tập phim-----</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="linkphim">Link phim</label>
                        <input type="text" class="form-control" name="linkphim" id="linkphim" placeholder="...">
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Thêm tập phim</button>
                </form>
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

@endsection