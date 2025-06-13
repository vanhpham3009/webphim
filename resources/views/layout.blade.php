<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta name="theme-color" content="#234556">
    <meta http-equiv="Content-Language" content="vi" />
    <meta content="VN" name="geo.region" />
    <meta name="DC.language" scheme="utf-8" content="vi" />
    <meta name="language" content="Việt Nam">

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <link rel="shortcut icon" href="{{ asset('uploads/logo/4b98170449d04c85367f1cf3bfd40791.png') }}" type="image/x-icon" />
    <meta name="revisit-after" content="1 days" />
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <title>{{$meta_title}}</title>
    <meta name="description" content="{{$meta_description}}" />
    <link rel="canonical" href="{{ Request::url() }}" />
    <link rel="next" href="" />
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:title" content="{{$meta_title}}" />
    <meta property="og:description" content="{{$meta_description}}" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:site_name" content="{{$meta_title}}" />
    <meta property="og:image" content="" />
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="55" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel='dns-prefetch' href='//s.w.org' />

    <link rel='stylesheet' id='bootstrap-css' href='{{asset('css/bootstrap.min.css')}}' media='all' />
    <link rel='stylesheet' id='style-css' href='{{asset('css/style.css')}}' media='all' />
    <link rel='stylesheet' id='wp-block-library-css' href='{{asset('css/style.min.css')}}' media='all' />

    <style type="text/css" id="wp-custom-css">
        .textwidget p a img {
            width: 100%;
        }
    </style>
    <style>
        #header .site-title {
            background: url(https://www.pngkey.com/png/detail/360-3601772_your-logo-here-your-company-logo-here-png.png) no-repeat top left;
            background-size: contain;
            text-indent: -9999px;
        }
    </style>
</head>

<body class="home blog halimthemes halimmovies" data-masonry="">
    <header id="header">
        <div class="container">
            <div class="row" id="headwrap">
                <div class="col-md-3 col-sm-6 slogan">
                    <p class="">
                        <a class="logo" href="{{route('homepage')}}" title="Phimhay309">
                            <img style=" 
                                        height: 60px;
                                        margin-left: 5px;
                                        padding-top: 0px;
                                        margin-top: -5px;
                                        position: absolute;"
                                src="{{ asset('uploads/logo/4b98170449d04c85367f1cf3bfd40791.png') }}">
                        </a>
                    </p>
                </div>
                <div class="col-md-5 col-sm-6 halim-search-form hidden-xs">
                    <div class="header-nav">
                        <div class="col-xs-12">
                            <style type="text/css">
                                .loading-spinner {
                                    width: 30px;
                                    height: 30px;
                                    border: 3px solid #f3f3f3;
                                    border-top: 3px solid #3498db;
                                    border-radius: 50%;
                                    animation: spin 1s linear infinite;
                                    margin: 20px auto;
                                    display: none;
                                }

                                @keyframes spin {
                                    0% {
                                        transform: rotate(0deg);
                                    }

                                    100% {
                                        transform: rotate(360deg);
                                    }
                                }

                                #result {
                                    position: absolute;
                                    z-index: 9999;
                                    background: #1b2d3c;
                                    width: 90%;
                                    padding: 10px;
                                    margin: 1px;
                                }

                                #result li {
                                    transition: all 0.3s ease;
                                }

                                #result li:hover {
                                    transform: translateX(10px);
                                    background: #2c3e50;
                                }
                            </style>
                            <div class="form-group form-search">
                                <div class="input-group col-xs-12">
                                    <form action="{{ route('tim-kiem') }}" method="GET">
                                        <input id="search" type="text" name="search" class="form-control" placeholder="Tìm kiếm..." autocomplete="off">
                                    </form>
                                </div>
                            </div>
                            <div class="loading-spinner" id="search-loading"></div>
                            <ul class="list-group" id="result" style="display: none;">
                            </ul>
                        </div>
                    </div>
                </div>
                @php
                $user_ip = request()->cookie('user_ip');
                $favorite_count = $user_ip ? \App\Models\Favourite::join('movies', 'favourites.movie_id', '=', 'movies.id')
                ->where('favourites.user_ip', $user_ip)
                ->count() : 0;
                @endphp
                <div class="col-md-4 hidden-xs">
                    <div id="get-bookmark" class="box-shadow" data-toggle="modal" data-target="#favoritesModal" style="cursor: pointer;">
                        Danh sách phim yêu thích
                        <span id="favorites-count" class="favorite-count badge">{{ $favorite_count ?? 0}}</span>
                        <div id="favorites-dropdown" class="favorites-dropdown" style="display: none;">
                            <div id="favorites-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="navbar-container">
        <div class="container">
            <nav class="navbar halim-navbar main-navigation" role="navigation" data-dropdown-hover="1">
                <div class="collapse navbar-collapse" id="halim">
                    <div class="menu-menu_1-container">
                        <ul id="menu-menu_1" class="nav navbar-nav navbar-left">
                            <li class="current-menu-item active"><a title="Trang Chủ" href="{{route('homepage')}}">Trang Chủ</a></li>
                            @foreach ($category as $key => $cate)
                            <li class="mega"><a title="{{$cate->title}}" href="{{route('category', [$cate->slug])}}">{{$cate->title}}</a></li>
                            @endforeach
                            <li class="mega dropdown">
                                <a title="Thể Loại" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">Thể Loại <span class="caret"></span></a>
                                <ul role="menu" class=" dropdown-menu">
                                    @foreach ($genre as $key => $gen)
                                    <li><a title="{{$gen->title}}" href="{{route('genre', [$gen->slug])}}">{{$gen->title}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="mega dropdown">
                                <a title="Quốc Gia" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">Quốc Gia <span class="caret"></span></a>
                                <ul role="menu" class=" dropdown-menu">
                                    @foreach ($country as $key => $countryvalue)
                                    <li><a title="{{$countryvalue->title}}" href="{{route('country', [$countryvalue->slug])}}">{{$countryvalue->title}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="mega dropdown">
                                <a title="Năm phim" href="#" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">Năm phim <span class="caret"></span></a>
                                <ul role="menu" class="dropdown-menu">
                                    @php $currentYear = date('Y'); @endphp
                                    @for ($year = 2000; $year <= $currentYear; $year++)
                                        <li>
                                        <a title="Năm {{$year}}" href="{{ route('year', [$year]) }}">{{ $year }}</a>
                            </li>
                            @endfor
                        </ul>
                        </li>
                        <li><a href="{{ route('random') }}" style="color:rgb(255, 255, 255);">RANDOM</a></li>
                        <li><a href="{{ route('filter') }}" style="color: #ffed4d;">LỌC PHIM</a></li>
                        </ul>
                    </div>
                    <!-- <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">THÀNH VIÊN <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="">Đăng nhập</a></li>
                                <li><a href="#">Đăng ký</a></li>
                            </ul>
                        </li>
                    </ul> -->
                </div>
            </nav>
            <div class="collapse navbar-collapse" id="search-form">
                <div id="mobile-search-form" class="halim-search-form"></div>
            </div>
            <div class="collapse navbar-collapse" id="user-info">
                <div id="mobile-user-login"></div>
            </div>
        </div>
    </div>
    </div>

    <div class="container">
        <div class="row fullwith-slider"></div>
    </div>
    <div class="container">
        @yield('content')
    </div>

    <div class="clearfix"></div>

    <footer id="footer" class="clearfix">
        <div class="container footer-columns">
            <div class="row container">
                <div class="widget about col-xs-12 col-sm-4 col-md-4">
                    <div class="footer-logo">
                        <img class="img-responsive" style="mix-blend-mode: darken;" src="https://img.favpng.com/9/23/19/movie-logo-png-favpng-nRr1DmYq3SNYSLN8571CHQTEG.jpg" alt="Phim hay 2021- Xem phim hay nhất" />
                    </div>
                    Liên hệ QC: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e5958d8c888d849ccb868aa58288848c89cb868a88">[email&#160;vanhpham3009@gmail.com]</a>
                </div>
                <div class="widget about col-xs-12 col-sm-4 col-md-5 about-section">
                    <h3 class="about-title">Giới thiệu</h3>
                    <div class="about-content">
                        <p class="about-desc">
                            Phimhay309.Com - Trang web xem Anime HD trực tuyến. Kho Anime phong phú nhiều thể loại, cập nhật mới liên tục mỗi ngày.
                        </p>
                        <p class="about-note">
                            Mọi dữ liệu trên Phimhay309.Com đều được tổng hợp và re-upload từ internet.
                        </p>
                        <p class="about-browser">
                            <i class="fa fa-info-circle"></i>
                            Xem tốt nhất với trình duyệt Mozilla Firefox & Google Chrome.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id='easy-top'></div>

    <style>
        .about-section {
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .about-title {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .about-content {
            color: #fff;
        }

        .about-desc {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .about-note {
            font-size: 14px;
            color: #999;
            font-style: italic;
            margin-bottom: 15px;
        }

        .about-browser {
            font-size: 13px;
            color: #6c757d;
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 15px;
            border-radius: 4px;
        }

        .about-browser i {
            margin-right: 5px;
            color: #3498db;
        }

        @media (max-width: 768px) {
            .about-section {
                padding: 15px;
            }

            .about-title {
                font-size: 16px;
            }

            .about-desc {
                font-size: 14px;
            }
        }

        #overlay_mb {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 99999;
            cursor: pointer
        }

        #overlay_mb .overlay_mb_content {
            position: relative;
            height: 100%
        }

        .overlay_mb_block {
            display: inline-block;
            position: relative
        }

        #overlay_mb .overlay_mb_content .overlay_mb_wrapper {
            width: 600px;
            height: auto;
            position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center
        }

        #overlay_mb .overlay_mb_content .cls_ov {
            color: #fff;
            text-align: center;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 999999;
            font-size: 14px;
            padding: 4px 10px;
            border: 1px solid #aeaeae;
            background-color: rgba(0, 0, 0, 0.7)
        }

        #overlay_mb img {
            position: relative;
            z-index: 999
        }

        @media only screen and (max-width: 768px) {
            #overlay_mb .overlay_mb_content .overlay_mb_wrapper {
                width: 400px;
                top: 3%;
                transform: translate(-50%, 3%)
            }
        }

        @media only screen and (max-width: 400px) {
            #overlay_mb .overlay_mb_content .overlay_mb_wrapper {
                width: 310px;
                top: 3%;
                transform: translate(-50%, 3%)
            }
        }
    </style>

    <style>
        #overlay_pc {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 99999;
            cursor: pointer;
        }

        #overlay_pc .overlay_pc_content {
            position: relative;
            height: 100%;
        }

        .overlay_pc_block {
            display: inline-block;
            position: relative;
        }

        #overlay_pc .overlay_pc_content .overlay_pc_wrapper {
            width: 600px;
            height: auto;
            position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        #overlay_pc .overlay_pc_content .cls_ov {
            color: #fff;
            text-align: center;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 999999;
            font-size: 14px;
            padding: 4px 10px;
            border: 1px solid #aeaeae;
            background-color: rgba(0, 0, 0, 0.7);
        }

        #overlay_pc img {
            position: relative;
            z-index: 999;
        }

        @media only screen and (max-width: 768px) {
            #overlay_pc .overlay_pc_content .overlay_pc_wrapper {
                width: 400px;
                top: 3%;
                transform: translate(-50%, 3%);
            }
        }

        @media only screen and (max-width: 400px) {
            #overlay_pc .overlay_pc_content .overlay_pc_wrapper {
                width: 310px;
                top: 3%;
                transform: translate(-50%, 3%);
            }
        }
    </style>

    <style>
        .float-ck {
            position: fixed;
            bottom: 0px;
            z-index: 9
        }

        * html .float-ck

        /* IE6 position fixed Bottom */
            {
            position: absolute;
            bottom: auto;
            /* top: expression(eval (document.documentElement.scrollTop+document.docum entElement.clientHeight-this.offsetHeight-(parseInt(this.currentStyle.marginTop, 10)||0)-(parseInt(this.currentStyle.marginBottom, 10)||0))); */
        }

        #hide_float_left a {
            background: #0098D2;
            padding: 5px 15px 5px 15px;
            color: #FFF;
            font-weight: 700;
            float: left;
        }

        #hide_float_left_m a {
            background: #0098D2;
            padding: 5px 15px 5px 15px;
            color: #FFF;
            font-weight: 700;
        }

        span.bannermobi2 img {
            height: 70px;
            width: 300px;
        }

        #hide_float_right a {
            background: #01AEF0;
            padding: 5px 5px 1px 5px;
            color: #FFF;
            float: left;
        }

        #get-bookmark {
            background: #1b2d3c;
            padding: 10px 15px;
            border-radius: 4px;
            color: #fff;
            transition: all 0.3s ease;
        }

        #get-bookmark:hover {
            background: #2c3e50;
        }

        #get-bookmark .badge {
            border-radius: 40%;
            background: #e74c3c;
            margin-left: 5px;
            padding: 2px;
        }

        .favorites-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            background: #1b2d3c;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            padding: 10px;
            display: none;
        }

        .favorite-item {
            display: flex;
            align-items: center;
            padding: 8px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }

        .favorite-item img {
            width: 40px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
        }

        .favorite-info {
            flex: 1;
            text-align: start;
        }

        .favorite-info h3 {
            font-size: 14px;
            margin: 0 0 5px 0;
            color: #fff;
        }

        .remove-favorite {
            color: #e74c3c;
            cursor: pointer;
            padding: 5px;
        }

        .remove-favorite:hover {
            color: #c0392b;
        }

        .favorites-empty {
            color: #ccc;
            text-align: center;
            padding: 10px;
        }

        .view-more-btn {
            display: block;
            text-align: center;
            padding: 8px;
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .view-more-btn:hover {
            color: #fff;
            transform: translateY(-2px);
        }
    </style>
</body>

<script type='text/javascript' src='{{asset('js/jquery.min.js')}}' id='halim-jquery-js'></script>
<script type='text/javascript' src='{{asset('js/bootstrap.min.js')}}' id='bootstrap-js'></script>
<script type='text/javascript' src='{{asset('js/owl.carousel.min.js')}}' id='carousel-js'></script>
<script type='text/javascript' src='{{asset('js/halimtheme-core.min.js')}}' id='halim-init-js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let isSubmitting = false;

        // Hàm tô sáng sao
        function highlightStars(movie_id, num_stars) {
            for (var count = 1; count <= 5; count++) {
                var star = $('#' + movie_id + '-' + count);
                if (count <= num_stars) {
                    star.removeClass('gray').addClass('yellow');
                } else {
                    star.removeClass('yellow').addClass('gray');
                }
            }
        }

        // Hover chuột
        $('.rating').on('mouseenter', function() {
            var movie_id = $(this).data('movie_id');
            var index = $(this).data('index');
            highlightStars(movie_id, index);
        });

        // Rời chuột
        $('.rating').on('mouseleave', function() {
            var movie_id = $(this).data('movie_id');
            var $parent = $(this).closest('.rating[data-rating]');
            var current_rating = $parent.data('rating') || 0;
            highlightStars(movie_id, current_rating);
        });

        // Click để gửi đánh giá
        $('.rating').on('click', function() {
            if (isSubmitting) return;
            isSubmitting = true;

            var movie_id = $(this).data('movie_id');
            var rating_value = $(this).data('index');
            var $parent = $(this).closest('.rating[data-rating]');

            $.ajax({
                url: '{{ route("add-rating") }}',
                type: 'POST',
                data: {
                    movie_id: movie_id,
                    rating: rating_value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (response.exists) {
                            alert('Bạn đã đánh giá phim này rồi, cảm ơn bạn nhé!');
                        } else {
                            alert('Đánh giá thành công!');
                            // Cập nhật data-rating cho container
                            $parent.data('rating', rating_value);
                            $parent.attr('data-rating', rating_value);
                            // Cập nhật giao diện
                            highlightStars(movie_id, rating_value);
                            // Cập nhật số lượng đánh giá
                            var $ratingCount = $('.rating-count');
                            if ($ratingCount.length) {
                                $ratingCount.text('(' + response.rating_count + ' lượt đánh giá)');
                            }
                        }
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                    alert('Có lỗi xảy ra!');
                },
                complete: function() {
                    isSubmitting = false;
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        let searchTimeout = null;

        $('#search').keyup(function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val();
            $('#result').html('');

            if (searchTerm != '') {
                $('#search-loading').show();
                searchTimeout = setTimeout(function() {
                    const expression = new RegExp(searchTerm, "i");

                    $.getJSON('/json/movies.json', function(response) {
                        let processedMovies = [];
                        const seenMovies = new Set();

                        if (Array.isArray(response)) {
                            processedMovies = response;
                        } else if (response.data && Array.isArray(response.data)) {
                            processedMovies = response.data;
                        }

                        processedMovies.forEach(function(movie) {
                            if (seenMovies.has(movie.id)) return;

                            const movieTags = movie.tags ? movie.tags.toLowerCase().split(',').map(tag => tag.trim()) : [];

                            if (movie && (
                                    (movie.title && movie.title.toLowerCase().search(expression) != -1) ||
                                    (movieTags.some(tag => tag.toLowerCase().search(expression.source.toLowerCase()) != -1))
                                )) {
                                seenMovies.add(movie.id);

                                $('#result').append(`
                                <a href="/chi-tiet/${movie.slug}" style="text-decoration: none;">
                                    <li style="cursor:pointer; display: flex; max-height: 200px;" 
                                        class="list-group-item link-class">
                                        <img src="/uploads/movie/${movie.image || ''}" width="100" class="mr-2" />
                                        <div style="flex-direction: column; margin-left: 10px;">
                                            <h4 style="color: #fff;">${movie.title}</h4>
                                            <span style="display: -webkit-box; 
                                                max-height: 6rem; 
                                                -webkit-box-orient: vertical; 
                                                overflow: hidden; 
                                                text-overflow: ellipsis; 
                                                white-space: normal; 
                                                -webkit-line-clamp: 3; 
                                                line-height: 1.6rem; 
                                                color: #ccc;">
                                                ${movie.description || 'Chưa có mô tả'}
                                            </span>
                                        </div>
                                    </li>
                                </a>
                            `);
                            }
                        });
                        $('#search-loading').hide();

                        if ($('#result li').length > 0) {
                            $('#result').show();
                        } else {
                            $('#result').hide();
                        }
                    });
                }, 500);
            } else {
                $('#result').hide();
            }
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#result, #search').length) {
                $('#result').hide();
            }
        });
        $('#result, #search').click(function(event) {
            event.stopPropagation();
        });
        $('#result').on('click', 'li', function() {
            var title = $(this).find('h4').text();
            $('#search').val(title);
            $('#result').hide();
        });

        // Lấy user_ip từ localStorage (nếu có)
        var userIp = localStorage.getItem('user_ip') || null;

        // Hàm tải danh sách phim yêu thích
        function loadFavorites() {
            $.ajax({
                url: '{{ route("load.favourites") }}',
                method: 'GET',
                data: {
                    user_ip: userIp
                },
                success: function(response) {
                    if (response.user_ip) {
                        localStorage.setItem('user_ip', response.user_ip);
                    }
                    $('#favorites-count').text(response.count);
                    $('#favorites-content').empty();
                    if (response.favorites.length === 0) {
                        $('#favorites-content').html('<div class="favorites-empty">Chưa có phim yêu thích nào</div>');
                    } else {
                        // Limit the displayed favorites to a maximum of 4
                        var displayedFavorites = response.favorites.slice(0, 4);

                        displayedFavorites.forEach(function(item) {
                            $('#favorites-content').append(`
                                <div class="favorite-item" data-id="${item.id}">
                                    <a href="/chi-tiet/${item.movie.slug}" class="favorite-link">
                                        <img src="/Uploads/movie/${item.movie.image}" alt="${item.movie.title}" />
                                    </a>
                                    <div class="favorite-info">
                                        <h3><a href="/chi-tiet/${item.movie.slug}" class="favorite-link" style="color: #fff; text-decoration: none;">${item.movie.title}</a></h3>
                                    </div>
                                    <span class="remove-favorite" data-id="${item.movie.id}"><i class="fa fa-trash"></i></span>
                                </div>
                            `);
                        });

                        if (response.favorites.length >= 1) {
                            const manageUrl = "{{ route('manage.favourite') }}";

                            $('#favorites-content').append(`
                            <a href="${manageUrl}" class="view-more-btn">Xem thêm</a>
                        `);
                        }
                    }
                },
                error: function() {
                    alert('Đã có lỗi xảy ra, vui lòng thử lại!');
                }
            });
        }

        $('#get-bookmark').on('click', function(event) {
            event.preventDefault();
            var $dropdown = $('#favorites-dropdown');
            if ($dropdown.is(':visible')) {
                $dropdown.hide();
                return;
            }
            loadFavorites();
            $dropdown.show();
        });
        $('#favorites-content').on('click', '.favorite-link', function(e) {
            e.stopPropagation(); // Ngăn chặn sự kiện click lan tỏa
            window.location.href = $(this).attr('href');
        });
        $('#favorites-content').on('click', '.view-more-btn', function(e) {
            e.stopPropagation(); // Ngăn chặn sự kiện click lan tỏa
            window.location.href = $(this).attr('href');
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#get-bookmark, #favorites-dropdown').length) {
                $('#favorites-dropdown').hide();
            }
        });

        $('#favorites-content').on('click', '.remove-favorite', function() {
            var favoriteId = $(this).data('id');

            $.ajax({
                url: '{{ route("remove.favourite") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    favorite_id: favoriteId
                },
                success: function(response) {
                    if (response.success) {
                        loadFavorites();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error in remove-favorite:', xhr.responseText, status, error);
                    alert('Đã có lỗi xảy ra khi xóa phim yêu thích');
                    loadFavorites();
                }
            });
        });
    });
</script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v22.0&appId=136669836038584"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function loadRankingData(value = 0) {
            $.ajax({
                url: '{{ route("filter-topview") }}',
                method: 'POST',
                data: {
                    value: value,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#show_data').html('<div class="loading">Đang tải dữ liệu...</div>');
                },
                success: function(response) {
                    if (response) {
                        $('#show_data').html(response);
                    } else {
                        $('#show_data').html('<div class="no-data">Không có dữ liệu</div>');
                    }
                },
                error: function() {
                    $('#show_data').html('<div class="error">Lỗi khi tải dữ liệu. Vui lòng thử lại!</div>');
                }
            });
        }

        // Load dữ liệu mặc định (Ngày)
        loadRankingData();

        // Xử lý click tab
        $('.filter-sidebar').click(function(e) {
            e.preventDefault();
            $('.filter-sidebar').removeClass('active');
            $(this).addClass('active');

            let value;
            switch ($(this).data('period')) {
                case 'day':
                    value = 0;
                    break;
                case 'week':
                    value = 1;
                    break;
                case 'month':
                    value = 2;
                    break;
                case 'year':
                    value = 3;
                    break;
                default:
                    value = 0;
            }

            loadRankingData(value);
        });
    });
</script>
<script>
    jQuery(document).ready(function($) {
        var owl = $('#halim_related_movies-2');
        owl.owlCarousel({
            loop: true,
            margin: 4,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            nav: true,
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
</script>

</html>