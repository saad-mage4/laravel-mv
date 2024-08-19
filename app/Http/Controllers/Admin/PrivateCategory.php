<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularCategory;
use App\Models\PrivateCategory as PrivateCategoryModel;
use App\Models\ThreeColumnCategory;
use App\Models\MegaMenuSubCategory;
use App\Models\MegaMenuCategory;
use Illuminate\Http\Request;
use  Image;
use File;
use Str;

class PrivateCategory extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = PrivateCategoryModel::with('private_subCategories', 'private_products')->get();
        // $pupoularCategory = PopularCategory::first();
        // $threeColCategory = ThreeColumnCategory::first();
        return view('admin.private_category', compact('categories'));
    }


    public function create()
    {
        return view('admin.create_private_category');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:private_categories',
            'slug' => 'required|unique:private_categories',
            'status' => 'required',
            'icon' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $category = new PrivateCategoryModel();

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->icon = $request->icon;
        $category->save();


        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_category.index')->with($notification);
    }

    public function edit($id)
    {
        $category = PrivateCategoryModel::find($id);
        return view('admin.edit_private_category', compact('category'));
    }


    public function update(Request $request, $id)
    {
        $category = PrivateCategoryModel::find($id);
        $rules = [
            'name' => 'required|unique:private_categories,name,' . $category->id,
            'slug' => 'required|unique:private_categories,name,' . $category->id,
            'status' => 'required',
            'icon' => 'required'
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_category.index')->with($notification);
    }

    public function destroy($id)
    {
        $category = PrivateCategoryModel::find($id);
        $category->delete();
        // $megaMenuCategory = MegaMenuCategory::where('category_id', $id)->first();
        // if ($megaMenuCategory) {
        //     $cat_id = $megaMenuCategory->id;
        //     $megaMenuCategory->delete();
        //     MegaMenuSubCategory::where('mega_menu_category_id', $cat_id)->delete();
        // }

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_category.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $category = PrivateCategoryModel::find($id);
        if ($category->status == 1) {
            $category->status = 0;
            $category->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $category->status = 1;
            $category->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
