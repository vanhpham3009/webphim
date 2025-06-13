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
            <div class="card-header" style="margin-bottom: 5px;">{{ __('Danh sách danh mục phim') }}</div>

            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <a href="{{route('category.create')}}" class="btn btn-success" style="margin-bottom: 5px;"> Thêm danh mục</a>
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên danh mục</th>
                            <th>Slug</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody class="order_position">
                        @foreach ($category as $key => $cate )
                        <tr id="item-{{$cate->id}}">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$cate->title}}</td>
                            <td>{{$cate->slug}}</td>
                            <td>{{$cate->description}}</td>
                            @if ($cate->status == 0)
                            <td>Hết hiệu lực</td>
                            @else
                            <td>Hiệu lực</td>
                            @endif

                            <td>
                                <a href="{{route('category.edit', $cate->id)}}" class="btn btn-warning mb-1" style="margin-bottom: 3px;">Sửa</a>
                                <form action="{{route('category.destroy', $cate->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button onclick="return confirm('Xác nhận xoá danh mục này?')" class="btn btn-danger">Xoá</button>
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