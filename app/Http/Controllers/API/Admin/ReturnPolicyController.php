<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ReturnPolicy;
use Illuminate\Http\Request;

class ReturnPolicyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $policies = ReturnPolicy::with('products')->get();
        return response()->json(['policies' => $policies], 200);
    }


    public function create()
    {
        return view('admin.create_return_policy');
    }

    public function store(Request $request)
    {
        $rules = [
            'title'=>'required|unique:return_policies',
            'details'=>'required',
            'status'=>'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'details.required' => trans('admin_validation.Details is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $returnPolicy = new ReturnPolicy();
        $returnPolicy->title = $request->title;
        $returnPolicy->details = $request->details;
        $returnPolicy->status = $request->status;
        $returnPolicy->save();

        $notification=trans('admin_validation.Created Successfully');
        return response()->json(['message' => $notification],200);
    }

    public function show($id)
    {
        $returnPolicy = ReturnPolicy::find($id);
        return response()->json(['returnPolicy' => $returnPolicy], 200);
    }

    public function edit($id)
    {
        $returnPolicy = ReturnPolicy::find($id);
        return view('admin.edit_return_policy',compact('returnPolicy'));
    }


    public function update(Request $request,$id)
    {
        $returnPolicy = ReturnPolicy::find($id);
        $rules = [
            'title'=>'required|unique:return_policies,title,'.$returnPolicy->id,
            'details'=>'required',
            'status'=>'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'details.required' => trans('admin_validation.Price is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $returnPolicy->title = $request->title;
        $returnPolicy->details = $request->details;
        $returnPolicy->status = $request->status;
        $returnPolicy->save();

        $notification= trans('admin_validation.Update Successfully');
        return response()->json(['message' => $notification],200);
    }

    public function destroy($id)
    {
        $returnPolicy = ReturnPolicy::find($id);
        $returnPolicy->delete();

        $notification=trans('admin_validation.Delete Successfully');
        return response()->json(['message' => $notification],200);
    }

    public function changeStatus($id){
        $returnPolicy = ReturnPolicy::find($id);
        if($returnPolicy->status==1){
            $returnPolicy->status=0;
            $returnPolicy->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $returnPolicy->status=1;
            $returnPolicy->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
