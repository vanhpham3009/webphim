@extends('layout')
@section('content')
<style>
    .btn-favorite {
        background: #1b2d3c;
        border: none;
        color: #fff;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-favorite.active {
        background: #e74c3c;
    }

    .btn-favorite i {
        margin-right: 5px;
    }

    .favorites-list {
        background: #1b2d3c;
        padding: 15px;
        border-radius: 5px;
    }

    .favorite-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
    }

    .favorite-item img {
        width: 50px;
        margin-right: 10px;
    }

    .remove-favorite {
        margin-left: auto;
        color: #e74c3c;
        cursor: pointer;
    }

    /* New styles for positioning the favorite button */
    .movie-poster {
        position: relative;
        /* Make the container a positioning context */
    }

    .btn-favorite {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        /* Ensure it appears above the image */
        font-size: 12px;
        padding: 5px 10px;
        /* Smaller padding to match the HD badge size */
    }
</style>
<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <div class="yoast_breadcrumb hidden-xs"><span><span><a href="{{route('category', [$movie->category->slug])}}">{{$movie->category->title}}</a> » <span><a href="{{route('country', [$movie->country->slug])}}">{{$movie->country->title}}</a> » <span class="breadcrumb_last" aria-current="page">{{$movie->title}}</span></span></span></span></div>
                </div>
            </div>
        </div>
        <div id="ajax-filter" class="panel-collapse collapse" aria-expanded="true" role="menu">
            <div class="ajax"></div>
        </div>
    </div>
    <main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
        <section id="content" class="test">
            <div class="clearfix wrap-content">

                <div class="halim-movie-wrapper">
                    <div class="movie_info col-xs-12">
                        <div class="movie-poster col-md-3">
                            <img class="movie-thumb" src="{{asset(('uploads/movie/'.$movie->image))}}" alt="{{$movie->title}}">
                            @php
                            $user_ip = request()->cookie('user_ip');
                            $is_favorited = $user_ip ? \App\Models\Favourite::where('user_ip', $user_ip)
                            ->where('movie_id', $movie->id)
                            ->exists() : false;
                            @endphp
                            <button class="btn-favorite {{ $is_favorited ? 'active' : '' }}"
                                data-movie="{{ $movie->id }}">
                                <i class="fa fa-heart"></i>
                                <span>{{ $is_favorited ? 'Đã thích' : 'Yêu thích' }}</span>
                            </button>
                            @if($episode_count>0)
                            <div class="bwa-content">
                                <div class="loader"></div>
                                <a href="{{url('xem-phim/' . $movie->slug . '/tap-' . $first_episode->episode)}}" class="bwac-btn">
                                    <i class="fa fa-play"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="film-poster col-md-9">
                            <h1 class="movie-title title-1" style="display:block;line-height:35px;margin-bottom: -14px;color: #ffed4d;text-transform: uppercase;font-size: 18px;">{{$movie->title}}</h1>
                            <h2 class="movie-title title-2" style="font-size: 12px;">{{$movie->original_title}}</h2>
                            <ul class="list-info-group">
                                <li class="list-info-group-item"><span>Trạng Thái</span>:
                                    <span class="quality">
                                        @if ($movie->resolution == 0) HD @elseif($movie->resolution == 1) 4K @elseif($movie->resolution == 2) SD @elseif($movie->resolution == 3) Cam @elseif($movie->resolution == 4) FHD @endif
                                    </span>
                                    <span class="episode">
                                        @if ($movie->caption == 0) Vietsub @else Thuyết minh @endif
                                    </span>
                                </li>
                                <li class="list-info-group-item"><span>Thời lượng</span>: {{$movie->duration}}</li>
                                <li class="list-info-group-item"><span>Số tập</span>: {{ $episode_count }} / {{$movie->episode_number}} tập</li>
                                <li class="list-info-group-item"><span>Tập mới cập nhật</span>:
                                    @if($episode_count>0)
                                    @if ($movie->category_id == 3)
                                    @foreach($episode as $key => $ep)
                                    <a href="{{url('xem-phim/' . $ep->movie->slug . '/tap-' . $ep->episode)}}" rel="category tag">Tập {{$ep->episode}} |</a>
                                    @endforeach
                                    @elseif($movie->category_id == 2)
                                    @foreach($episode as $key => $ep)
                                    <a href="{{url('xem-phim/' . $movie->slug . '/tap-' . $ep->episode)}}" rel="category tag">{{$ep->episode}}</a>
                                    @endforeach

                                    @endif
                                    @else
                                    Đang cập nhật
                                    @endif
                                </li>
                                <li class="list-info-group-item"><span>Danh mục</span>:
                                    <a href="{{route('category', [$movie->category->slug])}}" rel="category tag">{{$movie->category->title}}</a>
                                </li>
                                <li class="list-info-group-item"><span>Thể loại</span>:
                                    @foreach($movie->movie_genre as $key => $value)
                                    <a href="{{route('genre', [$value->slug])}}" rel="category tag">{{$value->title}}</a>
                                    @endforeach
                                </li>
                                <li class="list-info-group-item"><span>Quốc gia</span>:
                                    <a href="{{route('country', [$movie->country->slug])}}" rel="tag">{{$movie->country->title}}</a>
                                </li>
                                <li style="margin-bottom: 5px;">
                                    <style>
                                        .rating {
                                            cursor: pointer;
                                            font-size: 30px;
                                            display: inline-block;
                                            margin: 0;
                                            padding: 0;
                                        }

                                        .rating.gray {
                                            color: #ccc;
                                        }

                                        .rating.yellow {
                                            color: #ffcc00;
                                        }

                                        .rating-count {
                                            font-size: 14px;
                                            color: #fff;
                                            margin-left: 5px;
                                            position: relative;
                                            top: 12px;
                                        }
                                    </style>
                                    <ul class="list-inline rating" title="Average Rating" style="float:left;">
                                        @for($count=5; $count>=1; $count--)
                                        @php
                                        if($count>$rating){
                                        $color='color:#ccc;';
                                        } else {
                                        $color='color:#ffcc00;';
                                        }
                                        @endphp
                                        <li title="star_rating"
                                            id="{{$movie->id}}-{{$count}}"
                                            data-index="{{$count}}"
                                            data-movie_id="{{$movie->id}}"
                                            data-rating="{{$rating}}"
                                            class="rating {{ $count > $rating ? 'gray' : 'yellow' }}">
                                            ★
                                        </li>
                                        @endfor
                                    </ul>
                                    <span class="rating-count">({{$count_total}} lượt đánh giá)</span>
                                </li>
                            </ul>
                            <div class="movie-trailer hidden"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div id="halim_trailer"></div>
                <div class="clearfix"></div>
                <div class="section-bar clearfix">
                    <h2 class="section-title"><span style="color:#ffed4d">Nội dung phim</span></h2>
                </div>
                <div class="entry-content htmlwrap clearfix">
                    <div class="video-item halim-entry-box">
                        <article id="post-38424" class="item-content">
                            {{ $movie->description }}
                        </article>
                    </div>
                </div>
                <div class="section-bar clearfix">
                    <h2 class="section-title"><span style="color:#ffed4d">Tags</span></h2>
                </div>
                <div class="entry-content htmlwrap clearfix">
                    <div class="video-item halim-entry-box">
                        <article id="post-38424" class="item-content">
                            @if ($movie->tags != null)
                            @php
                            $tags = array();
                            $tags = explode('|', $movie->tags);
                            @endphp
                            @foreach ($tags as $key => $tag)
                            <a href="{{url('tag/'.$tag)}}" rel="tag">{{$tag}}</a>|
                            @endforeach
                            @endif
                        </article>
                    </div>
                </div>
                @if ($movie->trailer != null)
                <div class="section-bar clearfix">
                    <h2 class="section-title"><span style="color:#ffed4d">Tags</span></h2>
                </div>
                <div class="entry-content htmlwrap clearfix">
                    <div class="video-item halim-entry-box">
                        <article id="post-38424" class="item-content">
                            <iframe width="100%" height="400" src="https://www.youtube.com/embed/{{$movie->trailer}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>
                        </article>
                    </div>
                </div>
                @endif
                @php
                $current_url = Request::url();
                @endphp
                <div class="section-bar clearfix" style="background:rgb(244, 245, 246); padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <div class="fb-comments" data-href="{{ $current_url }}" data-width="100%" data-numposts="10"></div>
                </div>
            </div>
        </section>
        <section class="related-movies">
            <div id="halim_related_movies-2xx" class="wrap-slider">
                <div class="section-bar clearfix">
                    <h3 class="section-title"><span>CÓ THỂ BẠN MUỐN XEM</span></h3>
                </div>
                <div id="halim_related_movies" class="owl-carousel owl-theme related-film">
                    @foreach($relatedMovies as $relatedMovie)
                    <article class="thumb grid-item post-38498">
                        <div class="halim-item">
                            <a class="halim-thumb" href="{{route('detail', [$relatedMovie->slug])}}" title="{{$relatedMovie->title}}">
                                <figure><img class="lazy img-responsive" src="{{asset(('uploads/movie/'.$relatedMovie->image))}}" alt="{{$relatedMovie->title}}" title="{{$relatedMovie->title}}"></figure>
                                <span class="status">
                                    @if ($relatedMovie->resolution == 0) HD @elseif($relatedMovie->resolution == 1) 4K @elseif($relatedMovie->resolution == 2) SD @elseif($relatedMovie->resolution == 3) Cam @endif
                                </span>
                                <span class="episode"><i class="fa fa-play" aria-hidden="true"></i>
                                    {{ $relatedMovie->episode_count }} / {{$relatedMovie->episode_number}} tập
                                </span>
                                <div class="icon_overlay"></div>
                                <div class="halim-post-title-box">
                                    <div class="halim-post-title ">
                                        <p class="entry-title">{{$relatedMovie->title}}</p>
                                        <p class="original_title">{{$relatedMovie->original_title}}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                <script>
                    jQuery(document).ready(function($) {
                        var owl = $('#halim_related_movies');
                        owl.owlCarousel({
                            loop: true,
                            margin: 4,
                            autoplay: true,
                            autoplayTimeout: 4000,
                            autoplayHoverPause: true,
                            nav: true,
                            navText: [
                                '<i style="font-size:24px;color:#ffed4d;padding:5px;">←</i>',
                                '<i style="font-size:24px;color:#ffed4d;padding:5px;">→</i>'
                            ],
                            responsiveClass: true,
                            responsive: {
                                0: {
                                    items: 2
                                },
                                480: {
                                    items: 3
                                },
                                600: {
                                    items: 4
                                },
                                1000: {
                                    items: 4
                                }
                            }
                        })
                    });

                    var userIp = localStorage.getItem('user_ip') || null;
                    // Handle favorite button click
                    $('.btn-favorite').on('click', function(event) {
                        event.preventDefault(); // Prevent default behavior
                        console.log('Favorite button clicked');
                        var button = $(this);
                        var movieId = button.data('movie');

                        $.ajax({
                            url: '{{ route("yeu-thich") }}',
                            method: 'POST',
                            data: {
                                movie_id: movieId,
                                user_ip: userIp,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Success:', response);
                                if (response.user_ip) {
                                    // Lưu user_ip vào localStorage để backup
                                    localStorage.setItem('user_ip', response.user_ip);
                                }
                                if (response.success) {
                                    button.addClass('active');
                                    button.find('span').text('Đã thích');
                                    $('#favorites-count').text(response.count);
                                    alert(response.message);
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error:', xhr.responseText);
                                alert('Đã có lỗi xảy ra, vui lòng thử lại!');
                            }
                        });
                    });
                </script>
            </div>
        </section>
    </main>
    <!-- Sidebar -->
    @include('pages.include.sidebar')
</div>

@endsection