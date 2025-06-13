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

    #pagination {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
    }

    #pagination a.active {
        background-color: #0d6efd !important;
        color: white !important;
        pointer-events: none;
        border-color: #0d6efd !important;
    }

    .table td img {
        border-radius: 5px;
        object-fit: cover;
    }

    #goto-page-form {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 4px;
        background: transparent;
        border: 1px solid #dee2e6;
    }

    #goto-page-form input[type="number"] {
        width: 60px;
        height: 30px;
        text-align: center;
        padding: 2px 4px;
        border: 1px solid #ced4da;
        border-radius: 3px;
        font-size: 14px;
    }

    #goto-page-form label {
        font-size: 14px;
        margin: 0;
        white-space: nowrap;
        color: #555;
    }

    #goto-page-form button {
        padding: 4px 12px;
        font-size: 14px;
        line-height: 1.5;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    #goto-page-form input[type="number"]::-webkit-inner-spin-button,
    #goto-page-form input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<!-- Modal chi tiết -->
<div class="modal fade" id="chitietphim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <h4><span id="content-title"></span></h4>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="content-detail"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal tập phim -->
<div class="modal fade" id="tapphim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <h4><span id="content-episode"></span></h4>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="content-detail"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 5px;">
                <div id="pagination" class=" d-flex align-items-center flex-wrap gap-2 mb-3">
                    @php
                    $currentPage = (int) request('page', 1);
                    $totalPages = $response['pagination']['totalPages'] ?? 1;
                    $pageRange = 2; // số trang hiển thị hai bên

                    $start = max(2, $currentPage - $pageRange);
                    $end = min($totalPages - 1, $currentPage + $pageRange);
                    @endphp

                    <span>Danh sách số trang:</span>

                    {{-- Trang đầu tiên --}}
                    <a href="{{ route('leech-movie', ['page' => 1]) }}"
                        class="btn btn-outline-primary btn-sm {{ $currentPage == 1 ? 'active' : '' }}">
                        1
                    </a>

                    {{-- Dấu ... nếu cần --}}
                    @if ($start > 2)
                    <span class="mx-1">...</span>
                    @endif

                    {{-- Các trang xung quanh trang hiện tại --}}
                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ route('leech-movie', ['page' => $i]) }}"
                        class="btn btn-outline-primary btn-sm {{ $currentPage == $i ? 'active' : '' }}">
                        {{ $i }}
                        </a>
                        @endfor

                        {{-- Dấu ... nếu cần --}}
                        @if ($end < $totalPages - 1)
                            <span class="mx-1">...</span>
                            @endif

                            {{-- Trang cuối cùng --}}
                            @if ($totalPages > 1)
                            <a href="{{ route('leech-movie', ['page' => $totalPages]) }}"
                                class="btn btn-outline-primary btn-sm {{ $currentPage == $totalPages ? 'active' : '' }}">
                                {{ $totalPages }}
                            </a>
                            @endif
                            <form id="goto-page-form"
                                class="d-flex align-items-center ms-3 gap-2"
                                action="{{ route('leech-movie') }}"
                                method="GET"
                                onsubmit="return validatePageInput()">
                                <label for="goto-page" class="form-label mb-0">Đến trang:</label>
                                <input type="number"
                                    min="1"
                                    max="{{ $totalPages }}"
                                    name="page"
                                    id="goto-page"
                                    class="form-control form-control-sm"
                                    style="width: 80px;"
                                    value="{{ request('page', 1) }}">
                                <button type="submit" class="btn btn-secondary btn-sm">Đi đến</button>
                            </form>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên phim</th>
                            <th>Slug</th>
                            <th>Tên gốc</th>
                            <th>Thumbnail</th>
                            <th>Hình ảnh poster</th>
                            <th>Năm sản xuất</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($response['items'] as $key => $res )
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$res['name']}}</td>
                            <td>{{$res['slug']}}</td>
                            <td>{{$res['origin_name']}}</td>
                            <td><img src="{{$response['pathImage'].$res['thumb_url']}}" style="width: 100px; height: 150px;"></td>
                            <td><img src="{{$response['pathImage'].$res['poster_url']}}" style="width: 150px; height: 150px;"></td>
                            <td>{{$res['year']}}</td>
                            <td>
                                <button type="button" data-movie_slug="{{ $res['slug'] }}" class="btn btn-primary leech_detail" data-toggle="modal" data-target="#chitietphim">
                                    Chi tiết phim
                                </button>
                                <button type="button" data-movie_slug="{{ $res['slug'] }}" class="btn btn-primary leech_detail" data-toggle="modal" data-target="#chitietphim">
                                    Tập phim
                                </button>
                                <!-- <a href="{{ route('leech-detail', $res['slug']) }}" class="btn btn-warning mb-1 btn-sm" style="margin-bottom: 3px;">Chi tiết phim</a>
                                @php
                                $movie = \App\Models\Movie::where('slug', $res['slug'])->first();
                                @endphp
                                @if (!$movie)
                                <form method="POST" action="{{ route('leech-store', $res['slug']) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Thêm vào DB</button>
                                </form>
                                @endif
                                <a href="{{ route('leech-episode', $res['slug']) }}" class="btn btn-dark mb-1 btn-sm" style="margin-top: 3px;">Tập phim</a> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection