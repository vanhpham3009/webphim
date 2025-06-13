<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genre = Genre::orderBy('id', 'ASC')->get();
        return view('admin.genre.index', compact('genre'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.genre.create');
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
                'title' => 'required|unique:genres|max:255',
                'slug' => 'max:255',
                'description' => 'max:255'

            ],
            [
                'title.unique' => 'Tên thể loại đã tồn tại, yêu cầu không được trùng',
                'title.required' => 'Yêu cầu tên thể loại không được để trống',
            ]
        );


        $genre = new Genre();
        $genre->title = $data['title'];
        $genre->slug = $data['slug'];
        $genre->description = $data['description'];
        $genre->status = 1;

        $genre->save();
        toastr()->success('Thêm thể loại thành công!', 'Thông báo');
        return redirect()->route('genre.index')->with('status', 'Thêm thể loại thành công!');
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
        $genre = Genre::find($id);
        return view('admin.genre.edit', compact('genre'));
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

        $genre = Genre::find($id);
        $genre->title = $data['title'];
        $genre->slug = $data['slug'];
        $genre->description = $data['description'];
        $genre->status = $data['status'];

        $genre->save();
        // return redirect()->back()->with('status', 'Chỉnh sửa thành công!');
        toastr()->warning('Chỉnh sửa thành công!', 'Thông báo');
        return redirect()->route('genre.index')->with('status', 'Chỉnh sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        $genre->delete();
        toastr()->error('Xóa thể loại thành công!', 'Thông báo');
        return redirect()->back();
    }
}
