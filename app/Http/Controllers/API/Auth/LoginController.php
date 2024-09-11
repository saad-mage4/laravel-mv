<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\BreadcrumbImage;
use App\Models\GoogleRecaptcha;
use App\Models\User;
use App\Models\Vendor;
use App\Rules\Captcha;
use Auth;
use Hash;
use App\Mail\UserForgetPassword;
use App\Helpers\MailHelper;
use App\Helpers\SocialLoginHelper;
use App\Models\EmailTemplate;
use App\Models\SocialLoginInformation;
use Mail;
use Str;
use Validator, Redirect, Response, File;
use Socialite;
use Carbon\Carbon;
use Swift_TransportException;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest:api')->except('userLogout');
    }

    public function loginPage()
    {
        $banner = BreadcrumbImage::where(['id' => 5])->first();
        $background = BannerImage::whereId('13')->first();
        $recaptchaSetting = GoogleRecaptcha::first();
        $socialLogin = SocialLoginInformation::first();
        return view('login', compact('banner', 'background', 'recaptchaSetting', 'socialLogin'));
    }

    public function storeLogin(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->email_verified == 1) {
                if ($user->status == 1) {
                    if (Hash::check($request->password, $user->password)) {

                        if (! $token = Auth::guard('api')->attempt($credential, ['exp' => Carbon::now()->addDays(365)->timestamp])) {
                            return response()->json(['error' => 'Unauthorized'], 401);
                        }

                        $isVendor = Vendor::where('user_id', $user->id)->first();
                        if ($isVendor) {
                            if ($isVendor->status == 1) {
                                return $this->respondWithToken($token, 1, $user);
                            }
                        } else {
                            return $this->respondWithToken($token, 0, $user);
                        }
                    } else {
                        $notification = trans('user_validation.Credentials does not exist');
                        return response()->json(['notification' => $notification], 403);
                    }
                } else {
                    $notification = trans('user_validation.Disabled Account');
                    return response()->json(['notification' => $notification], 402);
                }
            } else {
                $notification = trans('Please verify your acount. If you didn\'t get OTP, please resend your OTP and verify');
                return response()->json(['notification' => $notification], 402);
            }
        } else {
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['notification' => $notification], 402);
        }
    }


    protected function respondWithToken($token, $vendor, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'is_vendor' => $vendor,
            'user' => $user
        ]);
    }


    public function forgetPage()
    {
        $banner = BreadcrumbImage::where(['id' => 5])->first();
        $recaptchaSetting = GoogleRecaptcha::first();
        return view('forget_password', compact('banner', 'recaptchaSetting'));
    }

    public function sendForgetPassword(Request $request)
    {
        $rules = [
            'email' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->forget_password_token = random_int(100000, 999999);
            $user->save();

            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id', 1)->first();
            $subject = $template->subject;
            $message = $template->description;
            $message = str_replace('{{name}}', $user->name, $message);
            try {
                Mail::to($user->email)->send(new UserForgetPassword($message, $subject, $user));
            } catch (Swift_TransportException $e) {
                echo $e->getMessage();
            }

            $notification = trans('user_validation.Reset password link send to your email.');
            return response()->json(['notification' => $notification], 200);
        } else {
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['notification' => $notification], 402);
        }
    }


    public function resetPasswordPage($token)
    {
        $user = User::where('forget_password_token', $token)->first();
        $banner = BreadcrumbImage::where(['id' => 5])->first();
        $recaptchaSetting = GoogleRecaptcha::first();

        return response()->json(['user' => $user, 'banner' => $banner, 'recaptchaSetting' => $recaptchaSetting], 200);

        return view('reset_password', compact('banner', 'recaptchaSetting', 'user', 'token'));
    }

    public function storeResetPasswordPage(Request $request, $token)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|min:4|confirmed',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = User::where(['email' => $request->email, 'forget_password_token' => $token])->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->forget_password_token = null;
            $user->save();

            $notification = trans('user_validation.Password Reset successfully');
            return response()->json(['notification' => $notification], 200);
        } else {
            $notification = trans('Email or token does not exist');
            return response()->json(['notification' => $notification], 402);
        }
    }

    public function userLogout()
    {
        Auth::guard('api')->logout();
        $notification = trans('user_validation.Logout Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    // public function redirectToGoogle()
    // {
    //     SocialLoginInformation::setGoogleLoginInfo();
    //     return Socialite::driver('google')->redirect();
    // }

    // public function googleCallBack()
    // {
    //     SocialLoginInformation::setGoogleLoginInfo();
    //     $user = Socialite::driver('google')->user();
    //     $user = $this->createUser($user, 'google');
    //     auth()->login($user);
    //     return redirect()->intended(route('user.dashboard'));
    // }

    // public function redirectToFacebook()
    // {
    //     SocialLoginInformation::setFacebookLoginInfo();
    //     return Socialite::driver('facebook')->redirect();
    // }

    // public function facebookCallBack()
    // {
    //     SocialLoginInformation::setFacebookLoginInfo();
    //     $user = Socialite::driver('facebook')->user();
    //     $user = $this->createUser($user, 'facebook');
    //     auth()->login($user);
    //     return redirect()->intended(route('user.dashboard'));
    // }



    // function createUser($getInfo, $provider)
    // {
    //     $user = User::where('provider_id', $getInfo->id)->first();
    //     if (!$user) {
    //         $user = User::create([
    //             'name'     => $getInfo->name,
    //             'email'    => $getInfo->email,
    //             'provider' => $provider,
    //             'provider_id' => $getInfo->id,
    //             'provider_avatar' => $getInfo->avatar,
    //             'status' => 1,
    //             'email_verified' => 1,
    //         ]);
    //     }
    //     return $user;
    // }


    // New Code

    /**
     * Redirect the user to the OAuth Provider (Google, Facebook, GitHub, LinkedIn, etc.).
     */
    public function redirectToProvider($provider)
    {
        SocialLoginInformation::setProviderLoginInfo($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle callback from OAuth Provider (Google, Facebook, GitHub, LinkedIn, etc.).
     */
    public function handleProviderCallback($provider)
    {
        SocialLoginInformation::setProviderLoginInfo($provider);
        $socialUser = Socialite::driver($provider)->user();
        $user = SocialLoginHelper::handleSocialLogin($socialUser, $provider);
        auth()->login($user);
        $notification = trans('user_validation.Login Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('home')->with($notification);
    }
}