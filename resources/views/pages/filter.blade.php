@extends('layout')

@section('content')

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <div class="yoast_breadcrumb hidden-xs">
                        <span>
                            <span>
                                <span class="breadcrumb_last" aria-current="page">Bộ lọc nâng cao</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
            <div class="ajax"></div>
        </div>
    </div>
    <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
        <section>
            <div class="section-bar clearfix">
                <h1 class="section-title"><span>Bộ lọc phim</span></h1>
            </div>
            <div class="section-bar clearfix">
                <div class="row">
                    <form action="{{ route('filter') }}" method="GET">
                        <style>
                            .combobox_filter {
                                background: #12171b;
                                color: white;
                                border: none;
                            }

                            .col-md-2-5 {
                                width: 19%;
                                float: left;
                                position: relative;
                                min-height: 1px;
                                padding-right: 15px;
                                padding-left: 15px;
                            }

                            .btn_filter {
                                background: #12171b;
                                color: white;
                                border: none;
                                padding: 8px;
                                border: white 1px solid;
                            }
                        </style>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control combobox_filter" name="order" id="exampleFormControlSelect1">
                                    <option value="">--- Sắp xếp theo ---</option>
                                    @php
                                    $orderOptions = [
                                    'date' => 'Ngày đăng',
                                    'publish_year' => 'Năm sản xuất',
                                    'alphabet' => 'Tên phim'
                                    ];
                                    @endphp
                                    @foreach($orderOptions as $value => $label)
                                    <option value="{{ $value }}" {{ (isset($_GET['order']) && $_GET['order'] == $value) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2-5">
                            <div class="form-group">
                                <select class="form-control combobox_filter" name="genre" id="exampleFormControlSelect1">
                                    <option value="">--- Thể loại ---</option>
                                    @foreach ($genre as $key => $gen)
                                    @if (isset($_GET['genre']) && $_GET['genre'] == $gen->id)
                                    <option value="{{$_GET['genre']}}" selected>{{$gen->title}}</option>
                                    @else
                                    <option value="{{$gen->id}}">{{$gen->title}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2-5">
                            <div class="form-group">
                                <select class="form-control combobox_filter" name="country" id="exampleFormControlSelect1">
                                    <option value="">--- Quốc gia ---</option>
                                    @foreach ($country as $key => $coun)
                                    @if (isset($_GET['country']) && $_GET['country'] == $coun->id)
                                    <option value="{{$_GET['country']}}" selected>{{$coun->title}}</option>
                                    @else
                                    <option value="{{$coun->id}}">{{$coun->title}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @php $currentYear = date('Y'); @endphp
                            <div class="form-group">
                                <select class="form-control combobox_filter" name="year" id="exampleFormControlSelect1">
                                    <option value="">--- Năm sản xuất ---</option>
                                    @for ($year = $currentYear; $year >= 2000; $year--)
                                    @if (isset($_GET['year']) && $_GET['year']==$year)
                                    <option value="{{$_GET['year']}}" selected>{{ $year }}</option>
                                    @else
                                    <option value="{{$year}}">{{ $year }}</option>
                                    @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-sm btn-default btn_filter" value="Lọc phim">
                    </form>
                </div>
            </div>
            <div class="halim_box">
                @foreach ($movie as $key => $mov)
                <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-27021">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{route('detail', [$mov->slug])}}" title="{{$mov->title}}">
                            <figure><img class="lazy img-responsive" src="{{asset(('uploads/movie/'.$mov->image))}}" alt="{{$mov->title}}" title="{{$mov->title}}"></figure>
                            <span class="status">
                                @if ($mov->resolution == 0) HD @elseif($mov->resolution == 1) 4K @elseif($mov->resolution == 2) SD @elseif($mov->resolution == 3) Cam @elseif($mov->resolution == 4) FHD @endif
                            </span>
                            <span class="episode"><i class="fa fa-play" aria-hidden="true"></i> {{ $mov->episode_count }} / {{$mov->episode_number}} tập</span>
                            <div class="icon_overlay"></div>
                            <div class="halim-post-title-box">
                                <div class="halim-post-title ">
                                    <p class="entry-title">{{$mov->title}}</p>
                                    <p class="original_title">{{$mov->original_title}}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
            <div class="clearfix"></div>
            <div class="text-center">
                {!! $movie->withQueryString()->links('pagination::bootstrap-4') !!}
            </div>
        </section>
    </main>
    <!-- Sidebar -->
    @include('pages.include.sidebar')
</div>
<style>
    #main-contents {
        min-height: 800px;
    }
</style>
<script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
    document.getElementById("currenttitleYear").textContent = 'Phim ' + new Date().getFullYear();
</script>
@endsection