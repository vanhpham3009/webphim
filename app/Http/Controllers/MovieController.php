<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Country;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Movie_Genre;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function update_country(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id']);
        $movie->country_id = $data['country_id'];
        $movie->save();
        return response()->json(['success' => 'Cập nhật quốc gia phim thành công!']);
    }

    public function update_status(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id']);
        $movie->status = $data['status'];
        $movie->save();
        return response()->json(['success' => 'Cập nhật trạng thái phim thành công!']);
    }

    public function update_hot(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id']);
        $movie->is_hot = $data['is_hot'];
        $movie->save();
        return response()->json(['success' => 'Cập nhật phim hot thành công!']);
    }

    public function index()
    {
        $all_movies = Movie::with('category', 'country', 'genre')->orderBy('id', 'DESC')->get();
        $movie = Movie::with('category', 'country', 'genre', 'movie_genre')->withCount('episode')->orderBy('id', 'DESC')->get();
        $category = Category::orderBy('title', 'ASC')->get();
        $country = Country::orderBy('title', 'ASC')->get();
        $path = public_path() . '/json/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        File::put($path . 'movies.json', json_encode($all_movies));
        return view('admin.movie.index', compact('movie', 'category', 'country'));
    }

    public function filter_topview(Request $request)
    {
        $value = $request->input('value', 0);
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Tạo query base để đếm số lượt xem từ bảng movie_views
        $movies = Movie::select([
            'movies.*',
            DB::raw('COUNT(movie_views.id) as views_count')
        ])
            ->leftJoin('movie_views', 'movies.id', '=', 'movie_views.movie_id')
            ->where('movies.status', 1)
            ->where(function ($query) use ($value, $now) {
                switch ($value) {
                    case 0: // Ngày
                        $query->whereDate('movie_views.created_at', $now->toDateString());
                        break;
                    case 1: // Tuần
                        $query->whereBetween('movie_views.created_at', [
                            $now->copy()->startOfWeek(),
                            $now->copy()->endOfWeek()
                        ]);
                        break;
                    case 2: // Tháng
                        $query->whereYear('movie_views.created_at', $now->year)
                            ->whereMonth('movie_views.created_at', $now->month);
                        break;
                    case 3: // Năm
                        $query->whereYear('movie_views.created_at', $now->year);
                        break;
                }
            })
            ->groupBy([
                'movies.id',
                'movies.title',
                'movies.original_title',
                'movies.slug',
                'movies.duration',
                'movies.episode_number',
                'movies.tags',
                'movies.description',
                'movies.image',
                'movies.season',
                'movies.category_id',
                'movies.genre_id',
                'movies.country_id',
                'movies.status',
                'movies.is_hot',
                'movies.resolution',
                'movies.caption',
                'movies.year',
                'movies.created_at',
                'movies.updated_at',
                'movies.trailer',
            ])
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        $output = '';
        if ($movies->isEmpty()) {
            $output = '<div class="no-data" style="margin-top:5px">Không có phim nào trong khoảng thời gian này.</div>';
        } else {
            foreach ($movies as $mov) {
                $rating = round(Rating::where('movie_id', $mov->id)->avg('rating'), 1);

                switch ($mov->resolution) {
                    case 0:
                        $text = 'HD';
                        break;
                    case 1:
                        $text = '4K';
                        break;
                    case 2:
                        $text = 'SD';
                        break;
                    default:
                        $text = 'Cam';
                }

                $output .= '<div class="item">
                <a href="' . url('chi-tiet/' . $mov->slug) . '" title="' . $mov->title . '">
                    <div class="item-link">
                        <img src="' . url('uploads/movie/' . $mov->image) . '" class="lazy post-thumb" alt="' . $mov->title . '" title="' . $mov->title . '" />
                        <span class="is_trailer">' . $text . '</span>
                    </div>
                    <p class="title">' . $mov->title . '</p>
                </a>
                <div class="viewsCount" style="color: #9d9d9d;">' . $mov->views_count . ' lượt truy cập</div>
                <div class="rating-block">
                    <div class="rating-stars">';

                // Thêm 5 ngôi sao
                for ($count = 1; $count <= 5; $count++) {
                    $starClass = $count <= $rating ? 'yellow' : 'gray';
                    $output .= '<span class="star ' . $starClass . '">&#9733;</span>';
                }

                $output .= '</div>
                </div>
            </div>';
            }
        }
        return $output;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $country = Country::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->where('status', 1)->get();
        $list_genre = Genre::all();
        return view('admin.movie.create', compact('category', 'country', 'genre', 'list_genre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|unique:movies|max:255',
                'original_title' => 'max:255',
                'slug' => 'required|max:255',
                'duration' => 'required|max:255',
                'episode_number' => 'required|max:255',
                'tags' => 'max:255',
                'description' => 'max:255',
                'is_hot' => 'required|int',
                'caption' => 'max:255',
                'trailer' => 'max:255',
                'season' => 'max:255',
                'genre_id' => 'array',
                'category_id' => 'required|int',
                'country_id' => 'required|int',
                'resolution' => 'required|int',
                'year' => 'required|max:255',
                'image' => 'required|max:2048',
            ],
            [
                'title.unique' => 'Tên phim đã tồn tại, yêu cầu không được trùng',
                'title.required' => 'Yêu cầu tên phim không được để trống',
                'original_title.required' => 'Yêu cầu tên gốc không được để trống',
                'slug.required' => 'Yêu cầu đường dẫn phim không được để trống',
                'duration.required' => 'Yêu cầu thời gian không được để trống',
                'episode_number.required' => 'Yêu cầu số tập không được để trống',
                'category_id.required' => 'Yêu cầu thể loại không được để trống',
                'country_id.required' => 'Yêu cầu quốc gia không được để trống',
                'resolution.required' => 'Yêu cầu độ phân giải không được để trống',
                'year.required' => 'Yêu cầu năm sản xuất không được để trống',
                'image.required' => 'Yêu cầu hình ảnh không được để trống',
                'image.max' => 'Hình ảnh không được lớn hơn 2MB',
            ]
        );

        $movie = new Movie();
        $movie->title = $data['title'];
        $movie->original_title = $data['original_title'];
        $movie->slug = $data['slug'];
        $movie->duration = $data['duration'];
        $movie->episode_number = $data['episode_number'];
        $movie->tags = $data['tags'];
        $movie->description = $data['description'] ?? '';
        $movie->category_id = $data['category_id'];
        // $movie->genre_id = $data['genre_id'];
        if (isset($data['genre_id']) && is_array($data['genre_id'])) {
            $movie->genre_id = $data['genre_id'][0];
        }
        $movie->country_id = $data['country_id'];
        $movie->is_hot = $data['is_hot'];
        $movie->resolution = $data['resolution'];
        $movie->caption = $data['caption'];
        $movie->year = $data['year'];
        $movie->season = $data['season'];
        $movie->trailer = $data['trailer'];
        $movie->created_at = $data['created_at'] ?? Carbon::now('Asia/Ho_Chi_Minh');
        $movie->updated_at = $data['updated_at'] ?? Carbon::now('Asia/Ho_Chi_Minh');
        $movie->status = $data['status'] ?? 1;

        $movie->image = $data['image'];
        $get_image = $request->image;
        $path = 'uploads/movie/';
        $get_name_image = $get_image->getClientOriginalName(); //123.png
        $name_image = current(explode('.', $get_name_image)); // 123
        $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
        $get_image->move($path, $new_image);
        $movie->image = $new_image;

        $movie->save();

        $movie->movie_genre()->attach($data['genre_id']);
        toastr()->success('Thêm mới phim thành công!', 'Thông báo');
        return redirect()->route('movie.index')->with('status', 'Thêm phim thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        $movie_genre = $movie->movie_genre->pluck('id')->toArray();
        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $country = Country::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->where('status', 1)->get();
        $list_genre = Genre::all();
        return view('admin.movie.edit', compact('movie', 'category', 'country', 'genre', 'list_genre', 'movie_genre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $movie = Movie::find($id);
        $movie->title = $data['title'];
        $movie->original_title = $data['original_title'];
        $movie->slug = $data['slug'];
        $movie->duration = $data['duration'];
        $movie->episode_number = $data['episode_number'];
        $movie->tags = $data['tags'];
        $movie->description = $data['description'];
        $movie->category_id = $data['category_id'];
        if (isset($data['genre_id']) && is_array($data['genre_id'])) {
            $movie->genre_id = $data['genre_id'][0];
        }
        $movie->country_id = $data['country_id'];
        $movie->status = $data['status'];
        $movie->is_hot = $data['is_hot'];
        $movie->resolution = $data['resolution'];
        $movie->caption = $data['caption'];
        $movie->year = $data['year'];
        $movie->season = $data['season'];
        $movie->trailer = $data['trailer'];
        $movie->updated_at = $data['updated_at'] ?? Carbon::now('Asia/Ho_Chi_Minh');

        $get_image = $request->image;
        if ($get_image) {
            $path_unlink = 'uploads/movie/' . $movie->image;
            if (file_exists($path_unlink)) {
                unlink($path_unlink);
            }
            $path = 'uploads/movie/';
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move($path, $new_image);

            $movie->image = $new_image;
        }
        $movie->save();
        $movie->movie_genre()->sync($data['genre_id']);
        toastr()->warning('Chỉnh sửa thành công!', 'Thông báo');
        return redirect()->route('movie.index')->with('status', 'Chỉnh sửa thành công!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        $path_unlink = 'uploads/movie/' . $movie->image;
        if (file_exists($path_unlink)) {
            unlink($path_unlink);
        }
        Movie_Genre::whereIn('movie_id', [$movie->id])->delete();
        Episode::whereIn('movie_id', [$movie->id])->delete();
        $movie->delete();
        toastr()->error('Xoá thành công!', 'Thông báo');
        return redirect()->back();
    }
}
