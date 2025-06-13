<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $country = Country::orderBy('id', 'ASC')->get();
        return view('admin.country.index', compact('country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.country.create');
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
                'title' => 'required|unique:countries|max:255',
                'slug' => 'max:255',
                'description' => 'max:255'
            ],
            [
                'title.unique' => 'Tên quốc gia đã tồn tại, yêu cầu không được trùng',
                'title.required' => 'Yêu cầu tên quốc gia không được để trống',
            ]
        );


        $country = new Country();
        $country->title = $data['title'];
        $country->slug = $data['slug'];
        $country->description = $data['description'];
        $country->status = 1;

        $country->save();
        toastr()->success('Thêm danh mục quốc gia thành công!', 'Thông báo');
        return redirect()->route('country.index')->with('status', 'Thêm quốc gia thành công!');
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
        $country = Country::find($id);
        return view('admin.country.edit', compact('country'));
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

        $country = Country::find($id);
        $country->title = $data['title'];
        $country->slug = $data['slug'];
        $country->description = $data['description'];
        $country->status = $data['status'];

        $country->save();
        // return redirect()->back()->with('status', 'Chỉnh sửa thành công!');
        toastr()->warning('Chỉnh sửa quốc gia thành công!', 'Thông báo');
        return redirect()->route('country.index')->with('status', 'Chỉnh sửa thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);

        $country->delete();
        toastr()->error('Xóa quốc gia thành công!', 'Thông báo');
        return redirect()->back();
    }
}
