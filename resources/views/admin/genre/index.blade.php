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
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Danh sách thể loại phim') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('genre.create')}}" class="btn btn-success" style="margin-bottom: 5px;"> Thêm thể loại</a>
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên thể loại</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($genre as $key => $gen )
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$gen->title}}</td>
                            <td>{{$gen->slug}}</td>
                            <td>{{$gen->description ?? 'Chưa có mô tả'}}</td>
                            @if ($gen->status == 0)
                            <td>Hết hiệu lực</td>
                            @else
                            <td>Hiệu lực</td>
                            @endif

                            <td>
                                <a href="{{route('genre.edit', $gen->id)}}" class="btn btn-warning mb-1" style="margin-bottom: 3px;">Sửa</a>
                                <form action="{{route('genre.destroy', $gen->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button onclick="return confirm('Xác nhận xoá thể loại phim này?')" class="btn btn-danger">Xoá</button>
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

@endsection