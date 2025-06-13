@extends('layout')
@section('content')

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
            <div class="ajax"></div>
        </div>
    </div>
    <div id="halim_related_movies-2xx" class="wrap-slider">
        <div class="section-bar clearfix">
            <h3 class="section-title"><span>PHIM HOT</span></h3>
        </div>
        <div id="halim_related_movies-2" class="owl-carousel owl-theme related-film">
            @foreach ($movie_hot as $key => $mov)
            <article class="thumb grid-item post-38498">
                <div class="halim-item">
                    <a class="halim-thumb" href="{{route('detail', [$mov->slug])}}" title="{{$mov->title}}">
                        <figure><img class="lazy img-responsive" src="{{asset('uploads/movie/'.$mov->image)}}" alt="{{$mov->title}}" title="{{$mov->title}}"></figure>
                        <span class="status">
                            @if ($mov->resolution == 0) HD @elseif($mov->resolution == 1) 4K @elseif($mov->resolution == 2) SD @elseif($mov->resolution == 3) Cam @endif
                        </span>
                        <span class="episode"><i class="fa fa-play" aria-hidden="true"> {{ $mov->episode_count }} / {{$mov->episode_number}} tập</i></span>
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
    </div>

    <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
        @foreach ($category_home as $key => $cate_home)
        <section id="halim-advanced-widget-2">
            <div class="section-heading">
                <span class="h-text">{{$cate_home->title}}</span>
                <a class="xemthem" href="{{route('category', [$cate_home->slug])}}" title="{{$cate_home->title}}">
                    <span class="h-text">Xem thêm</span>
                </a>
            </div>
            <div id="halim-advanced-widget-2-ajax-box" class="halim_box">
                @foreach ($cate_home->movies->sortByDesc('updated_at')->take(12) as $key => $mov)
                <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-37606">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{route('detail', ['slug' => $mov->slug])}}">
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
        </section>
        @endforeach
        <div class="clearfix"></div>
        <div class="clearfix"></div>
    </main>
    <!-- Sidebar -->
    @include('pages.include.sidebar')
</div>
<style>
    .xemthem {
        float: right;
        font-size: 16px;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
    }
</style>
@endsection