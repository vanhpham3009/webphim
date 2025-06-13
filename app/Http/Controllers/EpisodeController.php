<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_episode = Episode::with('movie')->orderBy('movie_id', 'desc')->get();
        return view('admin.episode.index', compact('list_episode'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $movie = Movie::orderBy('updated_at', 'desc')->get();
        return view('admin.episode.create', compact('movie'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required',
            'episode' => 'required|numeric',
            'linkphim' => 'required|string'
        ], [
            'linkphim.required' => 'Vui lòng nhập nhập đầy đủ thông tin!',
            'episode.required' => 'Vui lòng chọn số tập!',
            'movie_id.required' => 'Vui lòng chọn phim!'
        ]);
        $data = $request->all();
        $episode_check = Episode::where('movie_id', $data['movie_id'])->where('episode', $data['episode'])->count();
        if ($episode_check > 0) {
            return redirect()->back()->with('error', 'Tập phim đã tồn tại!');
        }
        $episode = new Episode();
        $episode->movie_id = $data['movie_id'];
        $episode->episode = $data['episode'];
        $episode->linkphim = $data['linkphim'];
        $episode->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $episode->updated_at = Carbon::now('Asia/Ho_Chi_Minh');

        $episode->save();
        toastr()->success('Thêm tập phim thành công!', 'Thông báo');
        return redirect()->route('movie.index')->with('status', 'Thêm tập phim thành công!');
    }

    public function add_episode($id)
    {
        $movie = Movie::find($id);
        $list_episode = Episode::where('movie_id', $id)->orderBy('episode', 'desc')->get();
        return view('admin.episode.add', compact('movie', 'list_episode'));
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
        $episode = Episode::find($id);
        $movie = Movie::orderBy('updated_at', 'desc')->get();
        return view('admin.episode.edit', compact('episode', 'movie'));
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

        $episode = Episode::find($id);
        $episode->linkphim = $data['linkphim'];
        $episode->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $episode->updated_at = Carbon::now('Asia/Ho_Chi_Minh');

        $episode->save();
        toastr()->warning('Chỉnh sửa tập phim thành công!', 'Thông báo');
        return redirect()->route('movie.index')->with('status', 'Chỉnh sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $episode = Episode::find($id);
        $episode->delete();
        toastr()->error('Xóa tập phim thành công!', 'Thông báo');
        return redirect()->route('episode.index')->with('status', 'Xóa tập phim thành công!');
    }


    public function select_movie(Request $request)
    {
        $movie_id = $request->movie_id;
        $movie = Movie::find($movie_id);
        $output = '<option>-----Chọn tập phim-----</option>';
        if ($movie->category_id == 3) {
            if ($movie->episode_number > 0) {
                for ($i = 1; $i <= $movie->episode_number; $i++) {
                    $output .= '<option value="' . $i . '">Tập ' . $i . '</option>';
                }
            }
        } else {
            for ($i = 1; $i <= $movie->episode_number; $i++) {
                $output .= '<option value="' . $i . '">Tập ' . $i . '</option>';
            }
        }

        echo $output;
    }
}
