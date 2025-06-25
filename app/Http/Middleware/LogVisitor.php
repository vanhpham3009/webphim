<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $currentTime = Carbon::now('Asia/Ho_Chi_Minh');
        $ip = $request->ip();
        $browser = $this->getBrowser($request->header('User-Agent'));

        // Khóa duy nhất dựa trên IP và browser
        $uniqueKey = md5($ip . $browser);

        // Lưu visitor vào cache với khóa duy nhất
        Cache::put('visitor_' . $uniqueKey, [
            'last_active' => $currentTime,
            'ip' => $ip,
            'browser' => $browser
        ], now()->addMinutes(30)); // Tăng thời gian cache để tránh hết hạn nhanh

        // Quản lý danh sách keys dựa trên uniqueKey
        $keys = Cache::get('online_visitors_keys', []);
        if (!in_array('visitor_' . $uniqueKey, $keys)) {
            $keys[] = 'visitor_' . $uniqueKey;
            Cache::put('online_visitors_keys', $keys, now()->addMinutes(30));
        }

        // Chỉ xử lý log khi truy cập trang chủ
        if ($request->is('/')) {
            // Kiểm tra bản ghi hiện có dựa trên IP
            $existingLog = Activity::where('properties->ip_address', $ip)
                ->where('properties->browser', $browser)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($existingLog) {
                // Cập nhật bản ghi hiện có
                $properties = $existingLog->properties;
                $properties['access_time'] = $currentTime;
                $properties['device_type'] = $this->getDeviceType($request->header('User-Agent'));
                $properties['operating_system'] = $this->getOS($request->header('User-Agent'));
                $properties['language'] = $request->getPreferredLanguage();

                $existingLog->update([
                    'updated_at' => $currentTime,
                    'properties' => $properties
                ]);
            } else {
                // Tạo bản ghi mới nếu chưa có
                activity()
                    ->withProperties([
                        'browser' => $browser,
                        'ip_address' => $ip,
                        'device_type' => $this->getDeviceType($request->header('User-Agent')),
                        'operating_system' => $this->getOS($request->header('User-Agent')),
                        'language' => $request->getPreferredLanguage(),
                        'access_time' => $currentTime
                    ])
                    ->log('Visitor accessed homepage');
            }
        }

        // Cập nhật số lượng người dùng trực tuyến
        Cache::put('online_users_count', $this->getOnlineVisitorsCount(), now()->addMinutes(30));

        return $next($request);
    }

    private function getOnlineVisitorsCount()
    {
        $count = 0;
        $keys = Cache::get('online_visitors_keys', []);
        $activeKeys = [];

        foreach ($keys as $key) {
            $visitor = Cache::get($key);
            if ($visitor && $visitor['last_active'] >= now()->subMinutes(5)) {
                $count++;
                $activeKeys[] = $key;
            }
        }

        // Cập nhật lại danh sách keys chỉ với các session còn active
        Cache::put('online_visitors_keys', $activeKeys, now()->addMinutes(5));

        return $count;
    }

    private function getBrowser($userAgent)
    {
        if (preg_match('/Edg/i', $userAgent)) {
            return 'Microsoft Edge';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Google Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
            return 'Opera';
        } elseif (preg_match('/MSIE|Trident/i', $userAgent)) {
            return 'Internet Explorer';
        }
        return 'Unknown Browser';
    }

    private function getDeviceType($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($userAgent))) {
            return 'Máy tính bảng';
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($userAgent))) {
            return 'Điện thoại di động';
        }

        return 'Máy tính';
    }

    private function getOS($userAgent)
    {
        $os_array = [
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/macintosh|mac os x/i' =>  'Mac OS X'
        ];

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                return $value;
            }
        }

        return 'Unknown OS';
    }
}
