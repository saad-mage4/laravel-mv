<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\Setting;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerShippingMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $admin_shipping_enabled = ShippingMethod::where('super_admin_status', '1')->exists();
        $userId = Auth::id();
        $vender_id = Vendor::select('id')->where('user_id', $userId)->first();
        $shippings = ShippingMethod::where('vendor_id', $vender_id->id)->orderBy('id', 'asc')->get();
        $setting = Setting::first();
        return view('seller.shipping_method', compact('shippings', 'setting', 'admin_shipping_enabled'));
    }

    public function create()
    {
        $setting = Setting::first();
        return view('seller.create_shipping_method', compact('setting'));
    }

    // public function store(Request $request)
    // {
    //     $rules = [
    //         'title' => 'required|unique:shipping_methods',
    //         'shipping_coast' => 'required|numeric',
    //         'description' => 'required'
    //     ];
    //     $customMessages = [
    //         'title.required' => trans('admin_validation.Title is required'),
    //         'title.unique' => trans('admin_validation.Title already exist'),
    //         'shipping_coast.required' => trans('admin_validation.Shipping coast is required'),
    //         'description.required' => trans('admin_validation.Description is required'),
    //     ];
    //     $this->validate($request, $rules, $customMessages);

    //     $userId = Auth::id();
    //     $vender_id = Vendor::select('id')->where('user_id', $userId)->first();

    //     $shipping = new ShippingMethod();
    //     $shipping->vendor_id = $vender_id->id;
    //     $shipping->title = $request->title;
    //     $shipping->fee = $request->shipping_coast;
    //     $shipping->description = $request->description;
    //     $shipping->status = 1;
    //     $shipping->save();

    //     $notification = trans('admin_validation.Created Successfully');
    //     $notification = array('messege' => $notification, 'alert-type' => 'success');
    //     return redirect()->route('seller.shipping.index')->with($notification);
    // }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|unique:shipping_methods',
            'shipping_coast' => 'required|numeric',
            'description' => 'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'shipping_coast.required' => trans('admin_validation.Shipping coast is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $userId = Auth::id();
        $vendor = Vendor::where('user_id', $userId)->first();

        // Check if the vendor already has a shipping method
        if (ShippingMethod::where('vendor_id', $vendor->id)->exists()) {
            $notification = 'Vendor already has a shipping method';
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('seller.shipping.index')->with($notification);
        }

        $shipping = new ShippingMethod();
        $shipping->vendor_id = $vendor->id;
        $shipping->title = $request->title;
        $shipping->fee = $request->shipping_coast;
        $shipping->description = $request->description;
        $shipping->status = 1;
        $shipping->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('seller.shipping.index')->with($notification);
    }

    public function edit($id)
    {
        $shipping = ShippingMethod::find($id);
        $setting = Setting::first();
        return view('seller.edit_shipping_method', compact('shipping', 'setting'));
    }

    public function update(Request $request, $id)
    {
        $shipping = ShippingMethod::find($id);

        if ($shipping->is_free == 1) {
            $rules = [
                'title' => 'required|unique:shipping_methods,title,' . $shipping->id,
                'minimum_order' => 'required|numeric',
                'description' => 'required'
            ];
            $customMessages = [
                'title.required' => trans('admin_validation.Title is required'),
                'title.unique' => trans('admin_validation.Title already exist'),
                'minimum_order.required' => trans('admin_validation.Minimum order is required'),
                'description.required' => trans('admin_validation.Description is required'),
            ];
            $this->validate($request, $rules, $customMessages);

            $shipping->title = $request->title;
            $shipping->description = $request->description;
            $shipping->minimum_order = $request->minimum_order;
            $shipping->save();
        } else {
            $rules = [
                'title' => 'required|unique:shipping_methods,title,' . $shipping->id,
                'shipping_coast' => 'required|numeric',
                'description' => 'required'
            ];
            $customMessages = [
                'title.required' => trans('admin_validation.Title is required'),
                'title.unique' => trans('admin_validation.Title already exist'),
                'shipping_coast.required' => trans('admin_validation.Shipping coast is required'),
                'description.required' => trans('admin_validation.Description is required'),
            ];
            $this->validate($request, $rules, $customMessages);

            $shipping->title = $request->title;
            $shipping->fee = $request->shipping_coast;
            $shipping->description = $request->description;
            $shipping->status = 1;
            $shipping->update();
        }


        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('seller.shipping.index')->with($notification);
    }

    public function destroy($id)
    {
        $shipping = ShippingMethod::find($id);
        $shipping->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('seller.shipping.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $shipping = ShippingMethod::find($id);
        if ($shipping->status == 1) {
            $shipping->status = 0;
            $shipping->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $shipping->status = 1;
            $shipping->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
