<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactPage;
use Image;
use File;
class ContactPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $contact = ContactPage::first();
        return response()->json(['contact' => $contact]);
    }
    public function store(Request $request){
        $rules = [
            'banner_image' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'title' => 'required',
            'google_map' => 'required',
            'description' => 'required',
        ];
        $customMessages = [
            'banner_image.required' => trans('admin_validation.Banner Image is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'phone.unique' => trans('admin_validation.Phone is required'),
            'address.unique' => trans('admin_validation.Address is required'),
            'title.unique' => trans('admin_validation.Title is required'),
            'google_map.unique' => trans('admin_validation.Google Map is required'),
            'description.unique' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $contact = new ContactPage();
        if($request->banner_image){
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'contact-us'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $contact->banner = $banner_name;
        }

        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->address = $request->address;
        $contact->title = $request->title;
        $contact->map = $request->google_map;
        $contact->description = $request->description;
        $contact->save();


        $notification = trans('admin_validation.Create Successfully');
        return response()->json(['message' => $notification], 200);
    }

    public function update(Request $request, $id){
        $rules = [
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'title' => 'required',
            'google_map' => 'required',
            'description' => 'required',
        ];
        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'phone.unique' => trans('admin_validation.Phone is required'),
            'address.unique' => trans('admin_validation.Address is required'),
            'title.unique' => trans('admin_validation.Title is required'),
            'google_map.unique' => trans('admin_validation.Google Map is required'),
            'description.unique' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $contact = ContactPage::find($id);
        if($request->banner_image){
            $exist_banner = $contact->banner;
            $extention = $request->banner_image->getClientOriginalExtension();
            $banner_name = 'contact-us'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/custom-images/'.$banner_name;
            Image::make($request->banner_image)
                ->save(public_path().'/'.$banner_name);
            $contact->banner = $banner_name;
            $contact->save();
            if($exist_banner){
                if(File::exists(public_path().'/'.$exist_banner))unlink(public_path().'/'.$exist_banner);
            }
        }

        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->address = $request->address;
        $contact->title = $request->title;
        $contact->map = $request->google_map;
        $contact->description = $request->description;
        $contact->save();

        $notification = trans('admin_validation.Updated Successfully');
        return response()->json(['message' => $notification], 200);
    }

}
