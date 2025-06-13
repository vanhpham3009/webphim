<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4">
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
            <div class="section-title">
                <span>BẢNG XẾP HẠNG TOP 5 PHIM</span>
            </div>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link filter-sidebar active" id="pills-day-tab" data-period="day" data-toggle="pill" href="#day" role="tab" aria-controls="day" aria-selected="true">Ngày</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-sidebar" id="pills-week-tab" data-period="week" data-toggle="pill" href="#week" role="tab" aria-controls="week" aria-selected="false">Tuần</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-sidebar" id="pills-month-tab" data-period="month" data-toggle="pill" href="#month" role="tab" aria-controls="month" aria-selected="false">Tháng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-sidebar" id="pills-year-tab" data-period="year" data-toggle="pill" href="#year" role="tab" aria-controls="year" aria-selected="false">Năm</a>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div id="halim-ajax-popular-post" class="popular-post">
                <span id="show_data"></span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</aside>
<style>
    .rating-block {
        margin-top: 5px;
    }

    .rating-stars {
        display: flex;
        gap: 2px;
    }

    .star {
        font-size: 14px;
    }

    .star.yellow {
        color: #ffcc00;
    }

    .star.gray {
        color: #ccc;
    }
</style>
<aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4">
    <div id="halim_tab_popular_videos-widget-7" class="widget halim_tab_popular_videos-widget">
        <div class="section-bar clearfix">
            <div class="section-title">
                <span>Phim sắp chiếu</span>
            </div>
        </div>
        <section class="tab-content">
            <div role="tabpanel" class="tab-pane active halim-ajax-popular-post">
                <div class="halim-ajax-popular-post-loading hidden"></div>
                <div id="halim-ajax-popular-post" class="popular-post">
                    @foreach ($movie_hot_sidebar as $key => $mov)
                    <div class="item post-37176">
                        <a href="{{route('detail', ['slug' => $mov->slug])}}" title="{{ $mov->title }}">
                            <div class="item-link">
                                <img src="{{asset('uploads/movie/'.$mov->image)}}" class="lazy post-thumb" alt="{{ $mov->title }}" title="{{ $mov->title }}" />
                                <span class="is_trailer">
                                    <span class="status">
                                        @if ($mov->resolution == 0) HD @elseif($mov->resolution == 1) 4K @elseif($mov->resolution == 2) SD @elseif($mov->resolution == 3) Cam @endif
                                    </span>
                                </span>
                            </div>
                            <p class="title">{{ $mov->title }}</p>
                        </a>
                        <div style="float: left;">
                            <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                                <span style="width: 0%"></span>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <div class="clearfix"></div>
    </div>
</aside>