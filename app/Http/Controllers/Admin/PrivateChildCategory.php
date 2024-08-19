<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivateChildCategoryModel;
use App\Models\PrivateSubCategoryModel;
use App\Models\PrivateCategory;
use Illuminate\Http\Request;

class PrivateChildCategory extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $childCategories = PrivateChildCategoryModel::with('subCategory', 'category', 'private_products')->get();
        // $pupoularCategory = PopularCategory::first();
        // $threeColCategory = ThreeColumnCategory::first();
        return view('admin.private_child_category', compact('childCategories'));
    }


    public function create()
    {
        $categories = PrivateCategory::all();
        $SubCategories = PrivateSubCategoryModel::all();
        return view('admin.create_private_child_category', compact('categories', 'SubCategories'));
    }

    public function getSubcategoryByCategory($id)
    {
        $subCategories = PrivateSubCategoryModel::where('private_category_id', $id)->get();
        $response = "<option value=''>" . trans('admin_validation.Select sub category') . "</option>";
        foreach ($subCategories as $subCategory) {
            $response .= "<option value=" . $subCategory->id . ">" . $subCategory->name . "</option>";
        }
        return response()->json(['subCategories' => $response]);
    }

    public function getChildcategoryBySubCategory($id)
    {
        $childCategories = PrivateChildCategoryModel::where('private_sub_category_id', $id)->get();
        $response = '<option value="">' . trans('admin_validation.Select Child Category') . '</option>';
        foreach ($childCategories as $childCategory) {
            $response .= "<option value=" . $childCategory->id . ">" . $childCategory->name . "</option>";
        }
        return response()->json(['childCategories' => $response]);
    }



    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'slug' => 'required|unique:private_child_category_models',
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'sub_category.required' => trans('admin_validation.Sub category is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $childCategory = new PrivateChildCategoryModel();
        $childCategory->private_category_id = $request->category;
        $childCategory->private_sub_category_id = $request->sub_category;
        $childCategory->name = $request->name;
        $childCategory->slug = $request->slug;
        $childCategory->status = $request->status;
        $childCategory->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_child_category.index')->with($notification);
    }

    public function edit($id)
    {
        $childCategory = PrivateChildCategoryModel::find($id);
        $categories = PrivateCategory::all();
        $subCategories = PrivateSubCategoryModel::where('private_category_id', $childCategory->category_id)->get();
        return view('admin.edit_private_child_category', compact('childCategory', 'categories', 'subCategories'));
    }


    public function update(Request $request, $id)
    {
        $childCategory = PrivateChildCategoryModel::find($id);
        $rules = [
            'name' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'slug' => 'required|unique:private_child_category_models,slug,' . $childCategory->id,
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'sub_category.required' => trans('admin_validation.Sub category is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $childCategory->private_category_id = $request->category;
        $childCategory->private_sub_category_id = $request->sub_category;
        $childCategory->name = $request->name;
        $childCategory->slug = $request->slug;
        $childCategory->status = $request->status;
        $childCategory->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_child_category.index')->with($notification);
    }


    public function destroy($id)
    {
        $childCategory = PrivateChildCategoryModel::find($id);
        $childCategory->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_child_category.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $childCategory = PrivateChildCategoryModel::find($id);
        if ($childCategory->status == 1) {
            $childCategory->status = 0;
            $childCategory->save();
            $message = trans('admin_validation.InActive Successfully');
        } else {
            $childCategory->status = 1;
            $childCategory->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}