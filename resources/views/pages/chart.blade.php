@extends('layout')

@section('content')

<div class="row container" id="wrapper">
    <section>
        <div class="section-bar clearfix">
            <h1 class="section-title"><span>Thống kê lượt xem phim</span></h1>
        </div>
        <div class="halim_box">
            <canvas id="viewStatsChart" style="max-height: 400px;"></canvas>
        </div>
    </section>
</div>

<!-- Biểu đồ Chart.js -->
```chartjs
{
"type": "bar",
"data": {
"labels": ["Ngày", "Tuần", "Tháng", "Năm"],
"datasets": [{
"label": "Lượt xem",
"data": [{{ $stats['daily'] ?? 0 }}, {{ $stats['weekly'] ?? 0 }}, {{ $stats['monthly'] ?? 0 }}, {{ $stats['yearly'] ?? 0 }}],
"backgroundColor": ["#36A2EB", "#FF6384", "#FFCE56", "#4BC0C0"],
"borderColor": ["#2A8BCF", "#E0556B", "#E0B44B", "#3DA6A6"],
"borderWidth": 1
}]
},
"options": {
"responsive": true,
"maintainAspectRatio": false,
"scales": {
"y": {
"beginAtZero": true,
"title": {
"display": true,
"text": "Số lượt xem"
}
},
"x": {
"title": {
"display": true,
"text": "Khoảng thời gian"
}
}
},
"plugins": {
"legend": {
"display": true,
"position": "top"
},
"title": {
"display": true,
"text": "Thống kê lượt xem phim"
}
}
}
}