<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MegaMenuCategory;
use App\Models\MegaMenuSubCategory;
use App\Models\SubCategory;
use App\Models\Category;
class MegaMenuSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index($id){
        $megaMenuCategory = MegaMenuCategory::with('category','subCategories')->find($id);
        $subCategories = MegaMenuSubCategory::with('subCategory','megaMenuCategory')->where('mega_menu_category_id', $id)->orderBy('serial','asc')->get();

        return response()->json(['megaMenuCategory' => $megaMenuCategory, 'subCategories' => $subCategories], 200);

    }

    public function create($id){
        $megaMenuCategory = MegaMenuCategory::with('category','subCategories')->find($id);
        $subCategories = SubCategory::where(['status' => 1, 'category_id' => $megaMenuCategory->category_id])->get();

        return response()->json(['megaMenuCategory' => $megaMenuCategory, 'subCategories' => $subCategories], 200);

    }

    public function store(Request $request, $id){
        $subCategoryExist = MegaMenuSubCategory::where(['mega_menu_category_id' => $id, 'sub_category_id' => $request->sub_category])->count();

        $rules = [
            'sub_category' => $subCategoryExist == 0 ? 'required' : 'required|unique:mega_menu_sub_categories,sub_category_id',
            'status' => 'required',
            'serial' => 'required',
        ];
        $customMessages = [
            'sub_category.required' => trans('admin_validation.Sub category is required'),
            'sub_category.unique' => trans('admin_validation.sub category already exist'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial text is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $subCategory = new MegaMenuSubCategory();
        $subCategory->mega_menu_category_id = $id;
        $subCategory->sub_category_id = $request->sub_category;
        $subCategory->status = $request->status;
        $subCategory->serial = $request->serial;
        $subCategory->save();

        $notification= trans('admin_validation.Created Successfully');
        return response()->json(['notification' => $notification], 200);

    }


    public function show($id){
        $megaMenuSubCategory = MegaMenuSubCategory::with('megaMenuCategory','subCategory')->find($id);
        $categoryId = $megaMenuSubCategory->megaMenuCategory->category_id;
        $subCategories = SubCategory::where(['status' => 1, 'category_id' => $categoryId])->get();

        return response()->json(['megaMenuSubCategory' => $megaMenuSubCategory, 'categoryId' => $categoryId, 'subCategories' => $subCategories], 200);

    }


    public function edit($id){
        $megaMenuSubCategory = MegaMenuSubCategory::with('megaMenuCategory')->find($id);
        $categoryId = $megaMenuSubCategory->megaMenuCategory->category_id;
        $subCategories = SubCategory::where(['status' => 1, 'category_id' => $categoryId])->get();
        return view('admin.edit_mega_menu_sub_category', compact('subCategories','megaMenuSubCategory'));

    }




    public function update(Request $request, $id){
        $megaMenuSubCategory = MegaMenuSubCategory::with('megaMenuCategory')->find($id);
        $subCategoryExist = MegaMenuSubCategory::where(['mega_menu_category_id' => $megaMenuSubCategory->mega_menu_category_id, 'sub_category_id' => $request->sub_category])->count();

        $rules = [
            'sub_category' => $subCategoryExist == 0 ? 'required' : 'required|unique:mega_menu_sub_categories,sub_category_id,'.$id,
            'status' => 'required',
            'serial' => 'required',
        ];
        $customMessages = [
            'sub_category.required' => trans('admin_validation.Sub category is required'),
            'sub_category.unique' => trans('admin_validation.sub category already exist'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial text is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $megaMenuSubCategory->sub_category_id = $request->sub_category;
        $megaMenuSubCategory->status = $request->status;
        $megaMenuSubCategory->serial = $request->serial;
        $megaMenuSubCategory->save();

        $notification= trans('admin_validation.Created Successfully');
        return response()->json(['notification' => $notification], 200);

    }


    public function destroy($id){
        $megaMenuSubCategory = MegaMenuSubCategory::find($id);
        $megaMenuSubCategory->delete();
        $notification= trans('admin_validation.Delete Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function changeStatus($id){
        $megaMenuSubCategory = MegaMenuSubCategory::find($id);
        if($megaMenuSubCategory->status==1){
            $megaMenuSubCategory->status=0;
            $megaMenuSubCategory->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $megaMenuSubCategory->status=1;
            $megaMenuSubCategory->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

}
