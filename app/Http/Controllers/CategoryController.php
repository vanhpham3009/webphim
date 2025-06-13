<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('id', 'ASC')->get();
        return view('admin.category.index', compact('category'));
        // return view('admin.category.form');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
                'title' => 'required|unique:categories|max:255',
                'slug' => 'required|unique:categories|max:255',
                'description' => 'max:255',
            ],
            [
                'title.unique' => 'Tên danh mục đã tồn tại, yêu cầu không được trùng',
                'title.required' => 'Yêu cầu tên danh mục không được để trống',
                'slug.unique' => 'Yêu cầu không được trùng slug',
                'slug.required' => 'Yêu cầu slug không được để trống',
            ]
        );


        $category = new Category();
        $category->title = $data['title'];
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->status = 1;

        $category->save();
        toastr()->success('Thêm danh mục thành công!', 'Thông báo');
        return redirect()->route('category.index')->with('status', 'Thêm danh mục thành công!');
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
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
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

        $category = Category::find($id);
        $category->title = $data['title'];
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->status = $data['status'];

        $category->save();
        // return redirect()->back()->with('status', 'Chỉnh sửa thành công!');
        toastr()->warning('Chỉnh sửa thành công!', 'Thông báo');
        return redirect()->route('category.index')->with('status', 'Chỉnh sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete();
        toastr()->error('Xóa danh mục thành công!', 'Thông báo');
        return redirect()->back();
    }
}
