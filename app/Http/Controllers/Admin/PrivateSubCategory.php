<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivateCategory;
use App\Models\PrivateSubCategoryModel;
use Illuminate\Http\Request;

class PrivateSubCategory extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $subCategories = PrivateSubCategoryModel::with('category', 'childCategories', 'private_products')->get();
        // $pupoularCategory = PopularCategory::first();
        // $threeColCategory = ThreeColumnCategory::first();
        return view('admin.private_sub_category', compact('subCategories'));
    }


    public function create()
    {
        $categories = PrivateCategory::all();
        return view('admin.create_private_sub_category', compact('categories'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:private_sub_category_models',
            'category' => 'required',
            'status' => 'required'
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $subCategory = new PrivateSubCategoryModel();
        $subCategory->private_category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->status = $request->status;
        $subCategory->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_sub_category.index')->with($notification);
    }

    public function edit($id)
    {
        $subCategory = PrivateSubCategoryModel::find($id);
        $categories = PrivateCategory::all();
        return view('admin.edit_private_sub_category', compact('subCategory', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $subCategory = PrivateSubCategoryModel::find($id);
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:private_sub_category_models,slug,' . $subCategory->id,
            'category' => 'required',
            'status' => 'required'
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $subCategory->private_category_id = $request->category;
        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->status = $request->status;
        $subCategory->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_sub_category.index')->with($notification);
    }


    public function destroy($id)
    {
        $subCategory = PrivateSubCategoryModel::find($id);
        $subCategory->delete();
        // MegaMenuSubCategory::where('sub_category_id', $id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_sub_category.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $subCategory = PrivateSubCategoryModel::find($id);
        if ($subCategory->status == 1) {
            $subCategory->status = 0;
            $subCategory->save();
            $message = trans('admin_validation.InActive Successfully');
        } else {
            $subCategory->status = 1;
            $subCategory->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}