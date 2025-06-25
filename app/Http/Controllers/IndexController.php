<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Episode;
use App\Models\Favourite;
use App\Models\Info;
use App\Models\Movie;
use App\Models\Movie_Genre;
use App\Models\Movie_View;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IndexController extends Controller
{

    public function login(Request $request)
    {
        // $email = $request->email;
        // $password = $request->password;

        // if (auth()->attempt(['email' => $email, 'password' => $password])) {
        //     return redirect()->route('homepage')->with('success', 'Đăng nhập thành công');
        // } else {
        //     return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
        // }
        $meta_title = 'Đăng nhập';
        $meta_description = 'Đăng nhập để quản lý bộ sưu tập phim yêu thích của bạn.';
        return view('pages.login', compact('meta_title', 'meta_description'));
    }

    public function filter(Request $request)
    {

        $meta_title = 'Bộ lọc nâng cao tìm kiếm phim';
        $meta_description = 'Lọc phim theo thể loại, quốc gia, năm phát hành và sắp xếp theo ngày cập nhật, năm phát hành hoặc tên phim.';
        // Lấy các tham số filter
        $order = $request->order;
        $genre_id = $request->genre;
        $country_id = $request->country;
        $year = $request->year;

        $movie = Movie::where('status', 1);

        if (!empty($country_id)) {
            $movie->where('country_id', $country_id);
        }
        if (!empty($genre_id)) {
            $movie->whereHas('movie_genre', function ($query) use ($genre_id) {
                $query->where('genre_id', $genre_id);
            });
        }
        if (!empty($year)) {
            $movie->where('year', $year);
        }
        if (!empty($order)) {
            switch ($order) {
                case 'date':
                    $movie->orderBy('created_at', 'desc');
                    break;
                case 'publish_year':
                    $movie->orderBy('year', 'desc');
                    break;
                case 'alphabet':
                    $movie->orderBy('title', 'asc');
                    break;
                default:
                    $movie->orderBy('updated_at', 'desc');
            }
        } else {
            $movie->orderBy('updated_at', 'desc');
        }
        $movie = $movie->withCount('episode')->paginate(20)->appends(request()->query());

        return view('pages.filter', compact(
            'movie',
            'order',
            'genre_id',
            'country_id',
            'year',
            'meta_title',
            'meta_description'
        ));
    }

    public function search()
    {
        if (isset($_GET['search'])) {
            $keyword = $_GET['search'];
            $movie = Movie::where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('original_title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tags', 'LIKE', '%' . $keyword . '%');
            })->withCount('episode')->orderBy('updated_at', 'DESC')->paginate(12);
            $meta_title = 'Kết quả tìm kiếm cho từ khoá: ' . $keyword;
            $meta_description = 'Kết quả tìm kiếm cho từ khóa: ' . $keyword;
            return view('pages.search', compact('movie', 'keyword', 'meta_title', 'meta_description'));
        } else {
            return redirect()->back();
        }
    }

    public function home()
    {
        $info = Info::find(1);
        $meta_title = $info->title;
        $meta_description = $info->description;

        $category_home = Category::with(['movies' => function ($mov) {
            $mov->where('status', 1)->withCount('episode');
        }])->orderBy('id', 'desc')->where('status', 1)->get();
        $movie_hot = Movie::withCount('episode')->where('status', 1)->where('is_hot', 1)->orderBy('updated_at', 'desc')->take(5)->get();
        return view('pages/home', compact('category_home', 'movie_hot', 'meta_title', 'meta_description'));
    }

    public function category($slug)
    {
        $category_slug = Category::where('slug', $slug)->first();
        $meta_title = $category_slug->title . ' - Xem ' . $category_slug->title . ' mới mhất, hay nhất';
        $meta_description = $category_slug->description;
        $movie = Movie::where('category_id', $category_slug->id)->withCount('episode')->orderBy('updated_at', 'desc')->where('status', 1)->paginate(12);
        return view('pages/category', compact('category_slug', 'movie', 'meta_title', 'meta_description'));
    }

    public function genre($slug)
    {
        $genre_slug = Genre::where('slug', $slug)->first();
        $meta_title = $genre_slug->title . ' - Xem phim thể loại ' . $genre_slug->title . ' mới mhất, hay nhất';
        $meta_description = $genre_slug->description;
        $movie_genre = Movie_Genre::where('genre_id', $genre_slug->id)->get();
        $many_genre = [];
        foreach ($movie_genre as $item) {
            $many_genre[] = $item->movie_id;
        }
        $movie = Movie::whereIn('id', $many_genre)->withCount('episode')->orderBy('updated_at', 'desc')->where('status', 1)->paginate(12);
        return view('pages/genre', compact('genre_slug', 'movie', 'meta_title', 'meta_description'));
    }

    public function country($slug)
    {
        $country_slug = Country::where('slug', $slug)->first();
        $meta_title = $country_slug->title . ' - Xem phim quốc gia ' . $country_slug->title . ' mới mhất, hay nhất';
        $meta_description = $country_slug->description;
        $movie = Movie::where('country_id', $country_slug->id)->withCount('episode')->orderBy('updated_at', 'desc')->where('status', 1)->paginate(12);
        return view('pages/country', compact('country_slug', 'movie', 'meta_title', 'meta_description'));
    }

    public function detail($slug)
    {
        $movie = Movie::with('category', 'country', 'genre', 'movie_genre')->withCount('episode')->where('slug', $slug)->first();
        $meta_title = 'Xem phim ' . $movie->title . ' - Vietsub HD ';
        $meta_description = $movie->description;
        Movie_View::create([
            'movie_id' => $movie->id,
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);
        $relatedMovies = Movie::with('category', 'country', 'genre', 'movie_genre')->withCount('episode')->where('category_id', $movie->category_id)->where('status', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->take(5)->get();
        $first_episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'ASC')->first();
        $episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'DESC')->take(5)->get();
        $episode_current_list = Episode::with('movie')->where('movie_id', $movie->id)->get();
        $episode_count = $movie->episode->count();

        $rating = Rating::where('movie_id', $movie->id)->avg('rating');
        $rating = round($rating, 1);
        $count_total = Rating::where('movie_id', $movie->id)->count();
        return view('pages/detail', compact('movie', 'relatedMovies', 'episode', 'first_episode', 'episode_count', 'episode_current_list', 'rating', 'count_total', 'meta_title', 'meta_description'));
    }

    public function add_rating(Request $request)
    {
        try {
            $movie_id = $request->movie_id;
            $rating = $request->rating;
            $user_ip = $request->ip();

            // Kiểm tra xem user đã đánh giá phim này chưa
            $rating_exists = Rating::where('movie_id', $movie_id)
                ->where('user_ip', $user_ip)
                ->first();

            if ($rating_exists) {
                return response()->json([
                    'success' => true,
                    'exists' => true
                ]);
            }

            // Nếu chưa đánh giá, thêm đánh giá mới
            Rating::create([
                'movie_id' => $movie_id,
                'rating' => $rating,
                'user_ip' => $user_ip
            ]);

            // Tính lại rating trung bình
            $rating_count = Rating::where('movie_id', $movie_id)->count();
            $avg_rating = Rating::where('movie_id', $movie_id)->avg('rating');

            return response()->json([
                'success' => true,
                'exists' => false,
                'rating_count' => $rating_count,
                'avg_rating' => round($avg_rating, 1)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function year($year)
    {
        $year = str_replace('-', '', $year);
        $meta_title = 'Xem phim năm ' . $year;
        $meta_description = 'Xem phim thuộc năm ' . $year . ' mới nhất, hay nhất';
        $movie = Movie::where('year', $year)->withCount('episode')->orderBy('updated_at', 'desc')->where('status', 1)->paginate(12);
        return view('pages/year', compact('year', 'movie', 'meta_title', 'meta_description'));
    }

    public function tag($tag)
    {
        $tag = str_replace('|', ' ', $tag);
        $movie = Movie::where('tags', 'LIKE', '%' . $tag . '%')->withCount('episode')->orderBy('updated_at', 'desc')->paginate(12);
        $meta_title = 'Tag phim ' . $tag . ' mới nhất, hay nhất';
        $meta_description = 'Xem phim có tag ' . $tag . ' mới nhất, hay nhất';
        return view('pages/tag', compact('tag', 'movie', 'meta_title', 'meta_description'));
    }

    public function watch($slug, $tap)
    {
        $movie = Movie::with('category', 'country', 'genre', 'movie_genre', 'episode')->where('slug', $slug)->where('status', 1)->first();
        $meta_title = 'Xem phim ' . $movie->title . ' - Vietsub HD ';
        $meta_description = $movie->description;
        $relatedMovies = Movie::with('category', 'country', 'genre', 'movie_genre')->withCount('episode')->where('category_id', $movie->category_id)->where('status', 1)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->take(5)->get();
        if (isset($tap)) {
            $tapphim = $tap;
            $tapphim = substr($tap, 4, 20);
            $episode = Episode::where('movie_id', $movie->id)->where('episode', $tapphim)->first();
        } else {
            $tapphim = 1;
            $episode = Episode::where('movie_id', $movie->id)->where('episode', $tapphim)->first();
        }

        return view('pages/watch', compact('movie', 'relatedMovies', 'episode', 'tapphim', 'meta_title', 'meta_description'));
    }

    public function random()
    {
        $movie = Movie::with('category', 'country', 'genre', 'movie_genre', 'episode')->withCount('episode')->where('status', 1)->inRandomOrder()->first();
        Movie_View::create([
            'movie_id' => $movie->id,
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);
        $meta_title = 'Xem phim ' . $movie->title;
        $meta_description = $movie->description;
        $first_episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'ASC')->first();
        $relatedMovies = Movie::with(['category', 'country', 'genre', 'movie_genre'])
            ->withCount('episode')
            ->where('category_id', $movie->category_id)
            ->where('status', 1)
            ->where('id', '!=', $movie->id)
            ->inRandomOrder()
            ->take(5)
            ->get();
        $episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'DESC')->take(5)->get();
        $episode_current_list = Episode::with('movie')->where('movie_id', $movie->id)->get();
        $episode_count = $episode->count();

        session([
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'first_episode' => $first_episode,
            'relatedMovies' => $relatedMovies,
            'episode' => $episode,
            'episode_current_list' => $episode_current_list,
            'episode_count' => $episode_count
        ]);
        return redirect()->route('detail', ['slug' => $movie->slug]);
    }

    public function add_favourite(Request $request)
    {
        $movie_id = $request->movie_id;
        $created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $client_user_ip = $request->input('user_ip'); // Lấy user_ip từ client (nếu có)

        $user_ip = $request->cookie('user_ip');
        if (!$user_ip && $client_user_ip) {
            $user_ip = $client_user_ip;
            // Khôi phục cookie với user_ip từ localStorage
            Cookie::queue('user_ip', $user_ip, 525600);
        }
        if (!$user_ip) {
            $user_ip = Str::uuid()->toString();
            Cookie::queue('user_ip', $user_ip, 525600);
        }
        try {
            $created_at = Carbon::parse($created_at);
        } catch (\Exception $e) {
            $created_at = now();
        }
        $favorite = Favourite::where('user_ip', $user_ip)
            ->where('movie_id', $movie_id)
            ->first();

        if ($favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Phim đã có trong danh sách yêu thích',
                'user_ip' => $user_ip // Trả về user_ip để client lưu vào localStorage
            ]);
        }
        Favourite::create([
            'user_ip' => $user_ip,
            'movie_id' => $movie_id,
            'created_at' => $created_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích',
            'user_ip' => $user_ip,
            'count' => Favourite::where('user_ip', $user_ip)->count()
        ]);
    }

    public function manage_favourite()
    {
        $user_ip = request()->cookie('user_ip');
        $meta_title = 'Quản lý bộ sưu tập phim yêu thích';
        $meta_description = 'Quản lý danh sách yêu thích của bạn';
        $movie = Favourite::with(['movie' => function ($query) {
            $query->withCount('episode');
        }])
            ->where('user_ip', $user_ip)
            ->paginate(20);
        return view('pages/favouritemanage', compact('movie', 'user_ip', 'meta_title', 'meta_description'));
    }

    public function load_favourites(Request $request)
    {
        $user_ip = $request->cookie('user_ip');
        $client_user_ip = $request->input('user_ip');
        if (!$user_ip && $client_user_ip) {
            $user_ip = $client_user_ip;
            Cookie::queue('user_ip', $user_ip, 525600);
        }

        $favorites = [];

        if ($user_ip) {
            $favorites = Favourite::with('movie')
                ->where('user_ip', $user_ip)
                ->latest()
                ->get();
        }

        return response()->json([
            'favorites' => $favorites,
            'count' => count($favorites),
            'user_ip' => $user_ip
        ]);
    }

    public function remove_favourite(Request $request)
    {
        $id = $request->input('favorite_id');
        $user_ip = $request->cookie('user_ip');

        if (!$user_ip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin người dùng'
            ]);
        }

        $favorite = Favourite::where('movie_id', $id)
            ->where('user_ip', $user_ip)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy phim trong danh sách yêu thích'
            ]);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa phim khỏi danh sách yêu thích'
        ]);
    }

    public function remove_all_favourite(Request $request)
    {
        $user_ip = $request->cookie('user_ip');

        if (!$user_ip) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin người dùng'
            ]);
        }

        Favourite::where('user_ip', $user_ip)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả phim khỏi danh sách yêu thích'
        ]);
    }
}
