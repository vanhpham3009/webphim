<?php

namespace App\Http\Controllers;

use App\Models\LinkMovie;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class LinkMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $linkmovie = LinkMovie::orderBy('id', 'ASC')->paginate();
        return view('admin.linkmovie.index', compact('linkmovie'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.linkmovie.create');
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
                'title' => 'required|unique:linkmovies|max:255',
                'description' => 'nullable|max:500',

            ],
            [
                'title.unique' => 'Tên server phim đã tồn tại, yêu cầu không được trùng',
                'title.required' => 'Yêu cầu server link phim không được để trống',
            ]
        );


        $linkmovie = new LinkMovie();
        $linkmovie->title = $data['title'];
        $linkmovie->description = $data['description'];
        $linkmovie->status = 1;

        $linkmovie->save();
        toastr()->success('Thêm server phim thành công!', 'Thông báo');
        return redirect()->route('linkmovie.index')->with('status', 'Thêm server phim thành công!');
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
        $linkmovie = LinkMovie::find($id);
        return view('admin.linkmovie.edit', compact('linkmovie'));
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
        // $data = $request->validate(
        //     [
        //         'title' => 'required|max:255',
        //         'slug' => 'required|max:255',
        //         'description' => 'required|max:255',
        //     ],
        //     [
        //         'title.required' => 'Yêu cầu tên danh mục không được để trống',
        //         'slug.required' => 'Yêu cầu slug danh mục không được để trống',
        //         'description.required' => 'Yêu cầu mô tả không được để trống',
        //     ]
        // );

        $linkmovie = LinkMovie::find($id);
        $linkmovie->title = $data['title'];
        $linkmovie->description = $data['description'];
        $linkmovie->status = $data['status'];

        $linkmovie->save();
        // return redirect()->back()->with('status', 'Chỉnh sửa thành công!');
        toastr()->warning('Chỉnh sửa thành công!', 'Thông báo');
        return redirect()->route('linkmovie.index')->with('status', 'Chỉnh sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $linkmovie = LinkMovie::find($id);

        $linkmovie->delete();
        toastr()->error('Xóa thành công!', 'Thông báo');
        return redirect()->back();
    }
}
