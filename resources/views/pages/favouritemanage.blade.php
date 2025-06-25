@extends('layout')

@section('content')

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <div class="yoast_breadcrumb hidden-xs">
                        <span>
                            <span><a>Danh sách phim yêu thích</a></span>
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
                <h1 class="section-title"><span>Bộ sưu tập</span></h1>
            </div>
            @if ($movie->isEmpty())
            <div class="text-center empty-collection">
                <p>Chưa có phim nào trong bộ sưu tập</p>
            </div>
            @else
            <div class="remove-all-section text-center">
                <button class="btn btn-outline-danger remove-all-btn">Xóa tất cả</button>
            </div>
            <div class="halim_box">
                @foreach ($movie as $key => $mov)
                <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-27021">
                    <div class="halim-item">
                        <a class="halim-thumb" href="{{route('detail', [$mov->movie->slug])}}" title="{{$mov->movie->title}}">
                            <figure><img class="lazy img-responsive" src="{{asset(('uploads/movie/'.$mov->movie->image))}}" alt="{{$mov->movie->title}}" title="{{$mov->movie->title}}"></figure>
                            <span class="status">
                                @if ($mov->resolution == 0) HD @elseif($mov->resolution == 1) 4K @elseif($mov->resolution == 2) SD @elseif($mov->resolution == 3) Cam @elseif($mov->resolution == 4) FHD @endif
                            </span>
                            <span class="episode"><i class="fa fa-play" aria-hidden="true"></i> {{ $mov->movie->episode_count }} / {{$mov->movie->episode_number}} tập</span>
                            <div class="icon_overlay"></div>
                            <div class="halim-post-title-box">
                                <div class="halim-post-title ">
                                    <p class="entry-title">{{$mov->movie->title}}</p>
                                    <p class="original_title">{{$mov->movie->original_title}}</p>
                                </div>
                            </div>
                        </a>
                        <button class="btn btn-danger btn-sm remove-fav" data-id="{{ $mov->movie->id }}"><i class="fa fa-trash"></i> Xóa</button>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
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

    .halim-item {
        position: relative;
    }

    .remove-fav {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }

    .remove-all-section {
        margin: 10px 0;
        display: flex;
    }

    .remove-all-btn {
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .btn-outline-danger {
        background-color: transparent;
        border: 1px solid #dc3545;
        color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    .pagination-wrapper {
        margin-top: 15px;
    }

    .episode-count {
        margin-left: 5px;
        font-size: 12px;
    }
</style>

<script>
    $(document).ready(function() {
        $('.remove-fav').on('click', function(e) {
            e.preventDefault();

            var favorite_id = $(this).data('id');

            $.ajax({
                url: '{{ route("remove.favourite") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    favorite_id: favorite_id
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    alert('Đã có lỗi xảy ra khi xóa phim yêu thích');
                }
            });
        });

        $('.remove-all-btn').on('click', function(e) {
            e.preventDefault();

            if (confirm('Bạn có chắc chắn muốn xóa tất cả phim yêu thích?')) {
                $.ajax({
                    url: '{{ route("remove.all.favourite") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Đã xóa toàn bộ danh sách phim yêu thích');
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert('Đã có lỗi xảy ra khi xóa tất cả phim yêu thích');
                    }
                });
            }
        });
    });
</script>
@endsection