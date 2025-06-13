<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use Carbon\Carbon;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Return_;

class LeechMovieController extends Controller
{

    public function watch_leech_detail($slug, Request $request)
    {
        $slug = $request->slug;
        $resp = Http::get('https://ophim1.com/phim/' . $slug)->json();
        $resp_array[] = $resp['movie'];

        $output['content_title'] = '<h3 style="text-align: center;text-transform: uppercase;">' . $resp['movie']['name'] . '</h3>';

        $output['content_detail'] = '
            <div class="row">
                <div class="col-md-5"><img src="' . $resp['movie']['thumb_url'] . '" width="100%"></div>
                <div class="col-md-7">
                    <h5><b>Tên phim: </b>' . $resp['movie']['name'] . '</h5>
                    <p><b>Tên gốc: ' . $resp['movie']['origin_name'] . '</b></p>
                    <p><b>Trạng thái: </b> ' . $resp['movie']['episode_current'] . '</p>
                    <p><b>Số tập: </b> ' . $resp['movie']['episode_total'] . '</p>
                    <p><b>Thời lượng: </b>' . $resp['movie']['time'] . '</p>
                    <p><b>Năm sản xuất: </b>' . $resp['movie']['year'] . '</p>
                    <p><b>Chất lượng: </b>' . $resp['movie']['quality'] . '</p>
                    <p><b>Ngôn ngữ: </b>' . $resp['movie']['lang'] . '</p>';
        $output['content_detail'] .= '<b>Thể loại :</b>';

        foreach ($resp['movie']['category'] as $cate) {
            $output['content_detail'] .= '
                        <p><span class="badge badge-pill badge-info">' . $cate['name'] . '</span></p>';
        }
        $output['content_detail'] .= '<b>Quốc gia :</b>';
        foreach ($resp['movie']['country'] as $country) {
            $output['content_detail'] .= '
                        <p><span class="badge badge-pill badge-info">' . $country['name'] . '</span></p>';
        }
        $output['content_detail'] .= '

                </div>
            </div>
        ';

        echo json_encode($output);
    }

    public function leech_movie(Request $request)
    {
        $page = $request->get('page', 1); // Mặc định là trang 1
        $response = Http::get("https://ophim1.com/danh-sach/phim-moi-cap-nhat?page={$page}");

        $response = $response->json();
        return view('admin.leech.index', compact('response'));
    }


    public function leech_episode($slug)
    {
        $response = Http::get('https://ophim1.com/phim/' . $slug)->json();
        return view('admin.leech.episode', compact('response'));
    }

    public function leech_detail($slug)
    {
        $response = Http::get('https://ophim1.com/phim/' . $slug);
        $response_movie[] = $response['movie'];
        return view('admin.leech.detail', compact('response_movie'));
    }

    public function leech_episode_store(Request $request, $slug)
    {
        $movie = Movie::where('slug', $slug)->first();
        $response = Http::get('https://ophim1.com/phim/' . $slug);
        foreach ($response['episodes'] as $res) {
            foreach ($res['server_data'] as $res_data) {
                if (is_numeric($res_data['name']) || strtolower($res_data['name']) == 'full') {
                    $episode = new Episode();
                    $episode->movie_id = $movie->id;
                    if (strtolower($res_data['name']) == 'full') {
                        $episode->episode = 1;
                    } else {
                        $episode->episode = $res_data['name'];
                    }
                    $episode->linkphim = '<p><iframe width="560" height="315" src="' . $res_data['link_embed'] . '" frameborder="0" allowfullscreen></iframe></p>';
                    $episode->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                    $episode->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
                    $episode->save();
                }
            }
        }
        toastr()->success('Thêm tập phim thành công!', 'Thông báo');
        return redirect()->back();
    }

    public function leech_store(Request $request, $slug)
    {
        $response = Http::get('https://ophim1.com/phim/' . $slug);
        $response_movie[] = $response['movie'];
        $movie = new Movie();
        foreach ($response_movie as $key => $res) {
            $movie->title = $res['name'];
            $movie->original_title = $res['origin_name'];
            $movie->slug = $res['slug'];
            $movie->duration = $res['time'];
            $episode_total = $res['episode_total'];
            $episode_number = (int) preg_replace('/[^0-9]/', '', $episode_total);
            $movie->episode_number = $episode_number;
            $movie->tags = $res['name'] . ' | ' . $res['origin_name'] . ' | ' .  $res['slug'];
            $movie->description = $res['content'] ?? '';
            if ($res['type'] === 'series' || $res['type'] === 'hoathinh') {
                $movie->category_id = 3;
            } else {
                $movie->category_id = 2;
            }

            $genres = [];
            if (!empty($res['category'])) {
                foreach ($res['category'] as $category) {
                    // Tìm genre tương ứng trong DB
                    $genre = DB::table('genres')
                        ->where('title', 'like', '%' . $category['name'] . '%')
                        ->first();

                    if ($genre) {
                        $genres[] = $genre->id;
                    }
                }
            }
            if (!empty($genres)) {
                $movie->genre_id = $genres[0];
            } else {
                $movie->genre_id = 1;
            }

            $country_name = $res['country'][0]['name'] ?? null;

            // Tìm country_id tương ứng trong DB
            if ($country_name) {
                $country = DB::table('countries')
                    ->where('title', 'like', '%' . $country_name . '%')
                    ->first();
                if ($country) {
                    $movie->country_id = $country->id;
                } else {
                    $movie->country_id = 1;
                }
            }

            $movie->is_hot = 0;
            $quality = $res['quality'];
            switch (strtoupper($quality)) {
                case 'HD':
                    $movie->resolution = 0;
                    break;
                case '4K':
                    $movie->resolution = 1;
                    break;
                case 'SD':
                    $movie->resolution = 2;
                    break;
                case 'CAM':
                    $movie->resolution = 3;
                    break;
                case 'FHD':
                    $movie->resolution = 4;
                    break;
                default:
                    $movie->resolution = 0; // Mặc định HD
            }
            $movie->caption = 0;
            $movie->year = $res['year'];
            $movie->season = isset($res['tmdb']['season']) ? $res['tmdb']['season'] : 0;
            $trailer_url = $res['trailer_url'];
            if ($trailer_url) {
                // Pattern để match YouTube ID
                $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                if (preg_match($pattern, $trailer_url, $matches)) {
                    $youtube_id = $matches[1];
                    $movie->trailer = $youtube_id;
                } else {
                    $movie->trailer = null;
                }
            } else {
                $movie->trailer = null;
            }
            $movie->created_at = $res['created_at'] ?? Carbon::now('Asia/Ho_Chi_Minh');
            $movie->updated_at = $res['updated_at'] ?? Carbon::now('Asia/Ho_Chi_Minh');
            $movie->status = 1;

            $image_url = $res['thumb_url'];
            if ($image_url) {
                $path = 'uploads/movie/';
                // Tạo tên file mới
                $image_name = basename($image_url);
                $new_image = time() . '_' . $image_name;

                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                // Tải ảnh về và lưu
                $image_content = file_get_contents($image_url);
                file_put_contents(public_path($path . $new_image), $image_content);

                $movie->image = $new_image;
            }
            $movie->save();
            if (!empty($genres)) {
                $movie->movie_genre()->attach($genres);
            }
            return redirect()->back()->with('success', 'Thêm phim thành công!');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
