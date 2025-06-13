@extends('layouts.app')


@section('navbar')
<div class="container">
    @include('layouts.navbar')
</div>
@endsection

@section('content')

<style>
    table.dataTable>thead>tr>th,
    table.dataTable>thead>tr>td {
        padding: 10px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        align-items: center;
        text-align: center;
    }

    th,
    td {
        align-content: center;
        text-align: center;
    }

    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        margin-bottom: 0;
        min-width: 1500px;
        /* Điều chỉnh chiều rộng tối thiểu của bảng */
    }

    /* Cố định header của bảng khi scroll */
    .table thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }

    .content-cell {
        max-width: 300px;
        position: relative;
    }

    .content-cell:hover .tooltip-content {
        display: block;
    }

    .tooltip-content {
        display: none;
        position: absolute;
        left: 0;
        top: 100%;
        background: #333;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        z-index: 1000;
        width: 300px;
        text-align: left;
        white-space: normal;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="table-responsive-wrapper">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tên phim</th>
                            <th>Slug</th>
                            <th>Tên gốc</th>
                            <th>Mô tả</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Thời lượng phim</th>
                            <th>Chất lượng</th>
                            <th>Phụ đề</th>
                            <th>Số tập</th>
                            <th>Thumbnail</th>
                            <th>Hình ảnh poster</th>
                            <th>Thể loại</th>
                            <th>Quốc gia</th>
                            <th>Năm sản xuất</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($response_movie as $key => $res )
                        <tr>
                            <td>{{$res['name']}}</td>
                            <td>{{$res['slug']}}</td>
                            <td>{{$res['origin_name']}}</td>
                            <td class="content-cell">
                                {!! Str::limit(strip_tags($res['content']), 50, '...') !!}
                                <div class="tooltip-content">
                                    {!! $res['content'] !!}
                                </div>
                            </td>
                            <td>{{$res['type']}}</td>
                            <td>{{$res['status']}}</td>
                            <td>{{$res['time']}}</td>
                            <td>{{$res['quality']}}</td>
                            <td>{{$res['lang']}}</td>
                            <td>{{$res['episode_current'] . ' / ' . $res['episode_total']}}</td>
                            <td><img src="{{$res['thumb_url']}}" style="width: 150px; height: 200px;"></td>
                            <td><img src="{{$res['poster_url']}}" style="width: 200px; height: 200px;"></td>
                            <td>
                                @foreach ($res['category'] as $cate)
                                <span class="badge badge-info">{{$cate['name']}}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($res['country'] as $country)
                                <span class="badge badge-info">{{$country['name']}}</span>
                                @endforeach
                            </td>
                            <td>{{$res['year']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

@endsection