<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignProduct;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Product;
class CampaignProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index($id)
    {
        $campaign = Campaign::find($id);
        if($campaign){
            $products = Product::where('status',1)->get();
            $campaignProducts = CampaignProduct::where('campaign_id',$id)->get();

            return response()->json(['campaign' => $campaign, 'products' => $products, 'campaignProducts' => $campaignProducts]);

        }else{
            $notification=trans('admin_validation.Something went wrong');
            return response()->json(['message' => $notification], 400);
        }


    }

    public function store(Request $request)
    {
        $rules = [
            'campaign_id'=>'required',
        ];
        $customMessages = [
            'campaign_id.required' => trans('admin_validation.Campaign is required'),

        ];
        $this->validate($request, $rules,$customMessages);

        $campaign = Campaign::find($request->campaign_id);
        if($campaign){
            $isProductExist = CampaignProduct::where(['product_id' => $request->product_id])->count();
            $rules = [
                'campaign_id'=>'required',
                'product_id'=> $isProductExist == 0 ? 'required' : 'required|unique:campaign_products',
                'status'=>'required',
                'show_homepage'=>'required'
            ];
            $customMessages = [
                'product_id.required' => trans('admin_validation.Product is required'),
                'product_id.unique' => trans('admin_validation.Product already exist'),
            ];
            $this->validate($request, $rules,$customMessages);

            $campaignProduct = new CampaignProduct();
            $campaignProduct->campaign_id = $request->campaign_id;
            $campaignProduct->product_id = $request->product_id;
            $campaignProduct->show_homepage = $request->show_homepage;
            $campaignProduct->status = $request->status;
            $campaignProduct->save();

            $notification=trans('admin_validation.Added Successfully');
            return response()->json(['message' => $notification], 200);
        }else{
            $notification=trans('admin_validation.Something went wrong');
            return response()->json(['message' => $notification], 400);
        }
    }

    public function destroy($id)
    {
        $campaignProduct = CampaignProduct::find($id);
        $campaignProduct->delete();

        $notification=trans('admin_validation.Delete Successfully');
        return response()->json(['message' => $notification], 200);
    }

    public function changeStatus($id){
        $campaignProduct = CampaignProduct::find($id);
        if($campaignProduct->status==1){
            $campaignProduct->status=0;
            $campaignProduct->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $campaignProduct->status=1;
            $campaignProduct->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function homepageVisibility($id){
        $campaignProduct = CampaignProduct::find($id);
        if($campaignProduct->show_homepage == 1){
            $campaignProduct->show_homepage = 0;
            $campaignProduct->save();
            $message= trans('admin_validation.Visibility Disabled');
        }else{
            $campaignProduct->show_homepage = 1;
            $campaignProduct->save();
            $message= trans('admin_validation.Visibility Enable');
        }
        return response()->json($message);
    }


}
