@extends('layout')

@section('content')

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <div class="yoast_breadcrumb hidden-xs">
                        <span>
                            <span><a>Kết quả tìm kiếm với từ khóa: </a>
                                <span class="breadcrumb_last" aria-current="page">{{ $keyword }} ({{ $movie->total() }} kết quả)</span>
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
                <h1 class="section-title"><span>Từ khoá: {{ $keyword }}</span></h1>
            </div>
            <div class="halim_box">
                @foreach ($movie as $key => $mov)
                <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-27021">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{route('detail', [$mov->slug])}}" title="{{$mov->title}}">
                            <figure><img class="lazy img-responsive" src="{{asset(('uploads/movie/'.$mov->image))}}" alt="{{$mov->title}}" title="{{$mov->title}}"></figure>
                            <span class="status">
                                @if ($mov->resolution == 0) HD @elseif($mov->resolution == 1) 4K @elseif($mov->resolution == 2) SD @elseif($mov->resolution == 3) Cam @endif
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