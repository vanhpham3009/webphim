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
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Danh sách Quốc gia') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('country.create')}}" class="btn btn-success" style="margin-bottom: 5px;"> Thêm Quốc gia</a>
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên Quốc gia</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($country as $key => $value )
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$value->title}}</td>
                            <td>{{$value->slug}}</td>
                            <td>{{$value->description ?? 'Chưa có mô tả'}}</td>
                            @if ($value->status == 0)
                            <td>Hết hiệu lực</td>
                            @else
                            <td>Hiệu lực</td>
                            @endif

                            <td>
                                <a href="{{route('country.edit', $value->id)}}" class="btn btn-warning mb-1" style="margin-bottom: 3px;">Sửa</a>
                                <form action="{{route('country.destroy', $value->id)}}" method="POST">
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