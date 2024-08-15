<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdType;
use App\Rules\NotSvg;
use Illuminate\Http\Request;
use  Image;
use File;
use Str;

class PrivateAdType extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $ads = AdType::all();
        return view('admin.private_adtype', compact('ads'));
    }

    public function create()
    {
        return view('admin.create_private_adtype');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:brands',
            'slug' => 'required|unique:brands',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $ad_type = new AdType();
        $ad_type->name = $request->name;
        $ad_type->slug = $request->slug;
        $ad_type->status = $request->status;
        $ad_type->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_adtype.index')->with($notification);
    }


    public function edit($id)
    {
        $ad = AdType::find($id);
        return view('admin.edit_private_adtype', compact('ad'));
    }


    public function update(Request $request, $id)
    {
        $ad = AdType::find($id);
        $rules = [
            'name' => 'required|unique:brands,name,' . $ad->id,
            'slug' => 'required|unique:brands,slug,' . $ad->id,
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
        ];
        $this->validate($request, $rules, $customMessages);


        $ad->name = $request->name;
        $ad->slug = $request->slug;
        $ad->status = $request->status;
        $ad->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_adtype.index')->with($notification);
    }


    public function destroy($id)
    {
        $ad = AdType::find($id);
        $ad->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.private_adtype.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $ad = AdType::find($id);
        if ($ad->status == 1) {
            $ad->status = 0;
            $ad->save();
            $message = trans('admin_validation.InActive Successfully');
        } else {
            $ad->status = 1;
            $ad->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
