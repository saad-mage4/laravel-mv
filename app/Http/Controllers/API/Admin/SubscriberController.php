<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

use App\Mail\SubscirberSendMail;
use App\Helpers\MailHelper;
use Str;
use Mail;
use Hash;
use Auth;
use Swift_TransportException;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $subscribers = Subscriber::where('is_verified',1)->get();
        return response()->json(['subscribers' => $subscribers]);
    }

    public function destroy($id){
        $subscriber = Subscriber::find($id);
        $subscriber->delete();

        $notification = trans('admin_validation.Delete Successfully');
        return response()->json(['message' => $notification], 200);
    }

    public function specificationSubscriberEmail(Request $request,$id){
        $rules = [
            'subject' => 'required',
            'message' => 'required',
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $subscriber = Subscriber::find($id);
        if($subscriber){
            MailHelper::setMailConfig();

            try {
                Mail::to($subscriber->email)->send(new SubscirberSendMail($request->subject, $request->message));
            } catch (Swift_TransportException $e) {
                echo $e->getMessage();
            }

            $notification = trans('admin_validation.Email Send Successfully');
            return response()->json(['message' => $notification], 200);
        }else{

            $notification = trans('admin_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 400);
        }
    }

    public function eachSubscriberEmail(Request $request){
        $rules = [
            'subject' => 'required',
            'message' => 'required',
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $subscribers = Subscriber::where('is_verified',1)->get();
        if($subscribers->count() > 0){
            MailHelper::setMailConfig();
            foreach($subscribers as $index => $subscriber){
                try {
                    Mail::to($subscriber->email)->send(new SubscirberSendMail($request->subject, $request->message));
                } catch (Swift_TransportException $e) {
                    echo $e->getMessage();
                }
            }

            $notification = trans('admin_validation.Email Send Successfully');
            return response()->json(['message' => $notification], 200);
        }else{

            $notification = trans('admin_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 400);
        }
    }
}