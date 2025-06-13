@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Quản lí danh mục</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ html()->form('POST')->route('category.store')->open() }}

                    <div class="form-group">
                        {{ html()->label('Tên danh mục', 'title') }}
                        {{ html()->text('title')->class('form-control')->placeholder('Nhập tên danh mục...') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Mô tả', 'description') }}
                        {{ html()->textarea('description')->class('form-control')->placeholder('Nhập mô tả...')->style('line-height: 1.5; field-sizing: content; min-height: 1.5;') }}
                    </div>
                    <div class="form-group">
                        {{ html()->label('Trạng thái', 'status') }}
                        {{ html()->select('status')->class('form-control')->options([1 => 'Hiệu lực'])->class('form-control') }}
                    </div>
                    {{ html()->submit('Thêm danh mục')->class('btn btn-success mt-2')}}
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection