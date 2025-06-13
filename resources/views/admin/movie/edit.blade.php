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
            <div class="card-header">{{ __('Thêm phim') }}</div>

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

                <a href="{{route('movie.index')}}" class="btn btn-primary">Quay lại danh sách phim</a>
                <form action="{{route('movie.update', [$movie->id])}}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="slug">Tên phim</label>
                        <input type="text" class="form-control" name="title" id="slug" value="{{$movie->title}}" onkeyup="ChangeToSlug()" placeholder="Nhập tên phim">
                    </div>
                    <div class="form-group">
                        <label for="convert_slug">Slug</label>
                        <input type="text" class="form-control" readonly name="slug" id="convert_slug" value="{{$movie->slug}}" placeholder="---">
                    </div>
                    <div class="form-group mb-2">
                        <label for="original_title">Tên phim gốc</label>
                        <input type="text" class="form-control" name="original_title" id="original_title" value="{{$movie->original_title}}" placeholder="Nhập tên phim gốc">
                    </div>
                    <div class="form-group mb-2">
                        <label for="trailer">Trailer</label>
                        <input type="text" class="form-control" name="trailer" id="trailer" value="{{$movie->trailer}}" placeholder="Nhập trailer">
                    </div>
                    <div class="form-group">
                        <label for="InputImage">Hình ảnh</label>
                        @if ($movie->image)
                        <img src="{{asset('uploads/movie/'.$movie->image)}}" alt="" width="400px" height="200px" class="form-control-file">
                        @endif
                        <input type="file" class="form-control-file" name="image" id="InputImage" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="duration">Thời lượng phim</label>
                        <input type="text" class="form-control" name="duration" id="duration" value="{{$movie->duration}}" placeholder="Nhập thời lượng phim">
                    </div>
                    <div class="form-group mb-2">
                        <label for="episode_number">Số tập</label>
                        <input type="number" class="form-control" name="episode_number" id="episode_number" value="{{$movie->episode_number}}" placeholder="Nhập số tập phim" min="1">
                    </div>
                    <div class="form-group">
                        <label>Chất lượng</label>
                        <select class="form-control" name="resolution">
                            @if ($movie->resolution == 0)
                            <option value="0" selected>HD</option>
                            <option value="1">4K</option>
                            <option value="2">SD</option>
                            <option value="3">Fancam</option>
                            @elseif($movie->resolution == 1)
                            <option value="1">4K</option>
                            <option value="0">HD</option>
                            <option value="2">SD</option>
                            <option value="3">Fancam</option>
                            @elseif($movie->resolution == 2)
                            <option value="2" selected>SD</option>
                            <option value="0">HD</option>
                            <option value="1">4K</option>
                            <option value="3">Fancam</option>
                            @elseif($movie->resolution == 3)
                            <option value="3" selected>Fancam</option>
                            <option value="0">HD</option>
                            <option value="1">4K</option>
                            <option value="2">SD</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phụ đề</label>
                        <select class="form-control" name="caption">
                            @if ($movie->caption == 0)
                            <option value="0" selected>Vietsub</option>
                            <option value="1">Thuyết minh</option>
                            @else($movie->caption == 1)
                            <option value="1">Thuyết minh</option>
                            <option value="0">Vietsub</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Năm phim</label>
                        <select class="form-control" name="year">
                            @php $currentYear = date('Y'); @endphp
                            @for ($year = 2000; $year <= $currentYear; $year++)
                                <option value="{{ $year }}" {{ $year == $movie->year ? 'selected' : '' }}>
                                {{ $year }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mùa phim</label>
                        <select class="form-control" name="season">
                            @for ($season = 0; $season <= 20; $season++)
                                <option value="{{ $season }}" {{ $season == $movie->season ? 'selected' : '' }}>
                                {{ $season }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phụ đề</label>
                        <select class="form-control" name="caption">
                            @if ($movie->caption == 0)
                            <option value="0" selected>Vietsub</option>
                            <option value="1">Thuyết minh</option>
                            @else
                            <option value="1" selected>Thuyết minh</option>
                            <option value="0">Vietsub</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputDescription">Mô tả</label>
                        <textarea class="form-control" name="description" id="InputDescription" placeholder="Nhập mô tả">{{$movie->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags phim</label>
                        <textarea class="form-control" name="tags" id="tags" placeholder="Nhập các tags phim">{{$movie->tags}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select required class="form-control choose_category" name="category_id">
                            <option value="0">-----Chọn danh mục phim-----</option>
                            @foreach ($category as $key => $cate)
                            <option {{$cate->id==$movie->category_id ? 'selected' : ''}} value="{{$cate->id}}">{{$cate->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>Thể loại</label>
                        <select required class="form-control choose_category" name="genre_id">
                            <option value="0">-----Chọn thể loại phim-----</option>
                            @foreach ($genre as $key => $gen)
                            <option {{$gen->id==$movie->genre_id ? 'selected' : ''}} value="{{$gen->id}}">{{$gen->title}}</option>
                            @endforeach
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label>Thể loại</label><br>
                        <div class="row">
                            @foreach($list_genre as $key => $gen)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="genre_id[]"
                                        value="{{ $gen->id }}"
                                        id="genre_{{ $gen->id }}"
                                        @if(in_array($gen->id, $movie_genre)) checked @endif
                                    >
                                    <label class="form-check-label" for="genre_{{ $gen->id }}">
                                        {{ $gen->title }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Quốc gia</label>
                        <select required class="form-control choose_category" name="country_id">
                            <option value="0">-----Chọn quốc gia-----</option>
                            @foreach ($country as $key => $value)
                            <option {{$value->id==$movie->country_id ? 'selected' : ''}} value="{{$value->id}}">{{$value->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Độ hot</label>
                        <select class="form-control" name="is_hot">
                            @if ($movie->is_hot == 1)
                            <option value="1" selected>Phim hot</option>
                            <option value="0">Bình thường</option>
                            @else
                            <option value="0" selected>Bình thường</option>
                            <option value="1">Phim hot</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status">
                            @if ($movie->status == 1)
                            <option value="1" selected>Hiệu lực</option>
                            <option value="0">Sắp chiếu</option>
                            <option value="2">Ẩn</option>
                            @elseif ($movie->status == 0)
                            <option value="0" selected>Sắp chiếu</option>
                            <option value="1">Hiệu lực</option>
                            <option value="2">Ẩn</option>
                            @else
                            <option value="2" selected>Ẩn</option>
                            <option value="1">Hiệu lực</option>
                            <option value="0">Sắp chiếu</option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Cập nhật phim</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection