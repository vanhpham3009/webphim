@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header" style="margin-bottom: 10px;">{{ __('Thống kê lượt truy cập') }}</div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="card">
                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            <table class="table table-hover" id="tableDashboard">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Trình duyệt</th>
                                        <th>Địa chỉ IP</th>
                                        <th>Thiết bị</th>
                                        <th>Hệ điều hành</th>
                                        <th>Ngôn ngữ</th>
                                        <th>Thời gian truy cập</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activities as $activity)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $activity->properties->get('browser') }}</td>
                                        <td>{{ $activity->properties->get('ip_address') }}</td>
                                        <td>{{ $activity->properties->get('device_type') }}</td>
                                        <td>{{ $activity->properties->get('operating_system') }}</td>
                                        <td>{{ $activity->properties->get('language') }}</td>
                                        @php
                                        $accessTime = Carbon\Carbon::parse($activity->properties['access_time']);
                                        $now = now();
                                        @endphp
                                        <td data-order="{{ $accessTime->timestamp }}">
                                            {{ $accessTime->locale('vi')->diffForHumans([
        'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
        'join' => true,
        'parts' => 2,
        'short' => true
    ]) }} trước
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
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [
                [6, "desc"]
            ],
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
            }
        });
    });
</script>
@endpush
@endsection