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
                <form action="{{route('movie.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="slug">Tên phim</label>
                        <input type="text" class="form-control" name="title" id="slug" onkeyup="ChangeToSlug()" placeholder="Nhập tên phim">
                    </div>
                    <div class="form-group">
                        <label for="convert_slug">Slug</label>
                        <input type="text" class="form-control" readonly name="slug" id="convert_slug" placeholder="---">
                    </div>
                    <div class="form-group">
                        <label for="original_title">Tên phim gốc</label>
                        <input type="text" class="form-control mb-2" name="original_title" id="original_title" placeholder="Nhập tên phim gốc">
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer</label>
                        <input type="text" class="form-control mb-2" name="trailer" id="trailer" placeholder="Nhập trailer">
                    </div>
                    <div class="form-group">
                        <label for="InputImage">Hình ảnh</label>
                        <input type="file" class="form-control-file" name="image" id="InputImage">
                    </div>
                    <div class="form-group">
                        <label>Danh mục</label>
                        <select required class="form-control choose_category" name="category_id">
                            <option value="0">-----Chọn danh mục phim-----</option>
                            @foreach ($category as $key => $cate)
                            <option value="{{$cate->id}}">{{$cate->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="duration">Thời lượng phim</label>
                        <input type="text" class="form-control mb-2" name="duration" id="duration" placeholder="Nhập thời lượng phim">
                    </div>
                    <div class="form-group mb-2">
                        <label for="episode_number">Số tập</label>
                        <input type="number" class="form-control" name="episode_number" id="episode_number" placeholder="Nhập số tập phim" min="1">
                    </div>
                    <div class="form-group">
                        <label>Chất lượng</label>
                        <select class="form-control" name="resolution">
                            <option value="0" selected>HD</option>
                            <option value="1">4K</option>
                            <option value="2">SD</option>
                            <option value="3">Cam</option>
                            <option value="4">FHD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phụ đề</label>
                        <select class="form-control" name="caption">
                            <option value="0" selected>Vietsub</option>
                            <option value="1">Thuyết minh</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mùa phim</label>
                        <select class="form-control" name="season">
                            @for ($season = 0; $season <= 10; $season++)
                                <option value="{{ $season }}" {{ $season == 0 ? 'selected' : '' }}>
                                {{ $season }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    @php $currentYear = date('Y'); @endphp
                    <div class="form-group">
                        <label>Năm sản xuất</label>
                        <select class="form-control" name="year">
                            @for ($year = 2000; $year <= $currentYear; $year++)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                {{ $year }}
                                </option>
                                @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="InputDescription">Mô tả</label>
                        <textarea class="form-control" name="description" id="InputDescription" placeholder="Nhập mô tả"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags phim</label>
                        <textarea class="form-control" name="tags" id="tags" placeholder="Nhập từ khoá"></textarea>
                    </div>
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
                                        id="genre_{{ $gen->id }}">
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
                            <option value="{{$value->id}}">{{$value->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Độ hot</label>
                        <select class="form-control" name="is_hot">
                            <option value="0" selected>Bình thường</option>
                            <option value="1">Phim hot</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status">
                            <option value="1" selected>Hiệu lực</option>
                            <option value="0">Sắp chiếu</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Thêm phim</button>
                </form>

                <!-- <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ html()->form('POST')->route('movie.store')->open() }}

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