<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ErrorPage;
class ErrorPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $errorPages = ErrorPage::all();

        return response()->json(['errorPages' => $errorPages], 200);
    }

    public function show($id){
        $errorPage = ErrorPage::find($id);
        return response()->json(['errorPage' => $errorPage], 200);
    }

    public function update(Request $request, $id)
    {
        $errorPage = ErrorPage::find($id);

        $rules = [
            'page_name'=>'required',
            'page_number'=>'required',
            'header'=>'required',
            'button_text'=>'required',
            'description'=>'required',
        ];
        $customMessages = [
            'page_name.required' => trans('admin_validation.Page name is required'),
            'page_number.required' => trans('admin_validation.Page number is required'),
            'header.required' => trans('admin_validation.Header is required'),
            'button_text.required' => trans('admin_validation.Button text is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $errorPage->page_name=$request->page_name;
        $errorPage->page_number=$request->page_number;
        $errorPage->header=$request->header;
        $errorPage->button_text=$request->button_text;
        $errorPage->description=$request->description;
        $errorPage->save();

        $notification= trans('admin_validation.Updated Successfully');
        return response()->json(['notification' => $notification], 200);
    }
}
