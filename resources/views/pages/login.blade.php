@extends('layout')

@section('content')

<div class="row container" id="wrapper">
    <div class="halim-panel-filter">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <div class="yoast_breadcrumb hidden-xs">
                        <span>
                            <span>
                                <span class="breadcrumb_last" aria-current="page">Đăng nhập</span>
                            </span>
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
                <div class="row">
                    <form action="{{ route('dang-nhap') }}" method="GET">
                        <style>
                            .inputlogin {
                                margin-bottom: 20px;
                                margin-left: 25px;
                                width: 95%;
                            }

                            .forgotpassword {
                                margin-top: 10px;
                                margin-left: 25px;
                                text-align: left;
                            }

                            .lbllogin {
                                margin-left: 25px;
                                color: #fff;
                            }

                            .btnlogin {
                                margin-left: 25px;
                                padding: 8px;
                            }
                        </style>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label lbllogin" for="form2Example18">Địa chỉ Email*</label>
                            <input type="email" id="form2Example18" class="form-control form-control-lg inputlogin" />
                        </div>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label lbllogin" for="form2Example28">Mật khẩu*</label>
                            <input type="password" id="form2Example28" class="form-control form-control-lg inputlogin" />
                        </div>
                        <input type="submit" class="btn btn-primary btnlogin" value="Đăng nhập ">
                        <p class="small mb-5 pb-lg-2 forgotpassword"><a class="text-muted" href="#!">Quên mật khẩu ?</a></p>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
    </main>
    <!-- Sidebar -->
    @include('pages.include.sidebar')
</div>
<style>
    #main-contents {
        min-height: 800px;
    }
</style>
<script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
    document.getElementById("currenttitleYear").textContent = 'Phim ' + new Date().getFullYear();
</script>
@endsection