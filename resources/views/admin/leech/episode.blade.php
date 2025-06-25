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
        padding: 5px;
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
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table th {
        background: #f8f9fa;
        padding: 8px;
        border: 1px solid #dee2e6;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table td {
        padding: 4px;
        border: 1px solid #dee2e6;
    }

    .table ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .table li {
        display: flex;
        align-items: center;
        margin: 2px 0;
    }

    .table input[type="text"] {
        flex: 1;
        padding: 2px 4px;
        border: 1px solid #ced4da;
        border-radius: 3px;
        margin-left: 8px;
        font-size: 13px;
    }

    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tr:hover {
        background-color: #f5f5f5;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <a href="{{route('leech-movie')}}" class="btn btn-primary" style="margin-bottom: 10px;">Quay lại danh sách phim mới nhất</a>

            <div class="table-responsive-wrapper">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Link Embed</th>
                            <th>Link M3U8</th>
                            <th>Tên phim</th>
                            <th>Slug</th>
                            <th>Tổng số tập phim</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($response['episodes'] as $key => $res )
                        <tr>
                            <td>
                                @foreach ($res['server_data'] as $server1)
                                <ul>
                                    <li>Tập {{ $server1['name']}}
                                        <input type="text" value="{{ $server1['link_embed'] }}">
                                    </li>
                                </ul>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($res['server_data'] as $server2)
                                <ul>
                                    <li>Tập {{ $server2['name']}}
                                        <input type="text" value="{{ $server2['link_m3u8'] }}">
                                    </li>
                                </ul>
                                @endforeach
                            </td>
                            <td>{{$response['movie']['name']}}</td>
                            <td>{{$response['movie']['slug']}}</td>
                            <td>{{$response['movie']['episode_total']}}</td>
                            <td>
                                <form method="POST" action="{{ route('leech-episode-store', [$response['movie']['slug']]) }}">
                                    @csrf
                                    <input type="submit" value="Đồng bộ tập phim" class="btn btn-success btn-sm">
                                </form>
                            </td>
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