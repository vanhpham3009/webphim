<?php

namespace App\Http\Controllers;

use App\Models\Movie_View;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Đếm tổng lượt xem cho các khoảng thời gian
        $stats = [
            'daily' => Movie_View::whereDate('created_at', $now->toDateString())->count(),
            'weekly' => Movie_View::whereBetween('created_at', [
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek()
            ])->count(),
            'monthly' => Movie_View::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->count(),
            'yearly' => Movie_View::whereYear('created_at', $now->year)->count(),
        ];

        return view('pages.stats', compact('stats'));
    }
}
