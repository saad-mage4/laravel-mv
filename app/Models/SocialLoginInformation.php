<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLoginInformation extends Model
{
    use HasFactory;

    // public function setGoogleLoginInfo()
    // {
    //     $googleInfo = SocialLoginInformation::first();
    //     if ($googleInfo) {
    //         \Config::set('services.google.client_id', $googleInfo->gmail_client_id);
    //         \Config::set('services.google.client_secret', $googleInfo->gmail_secret_id);
    //         \Config::set('services.google.redirect', $googleInfo->gmail_redirect_url);
    //     }
    // }

    // public function setFacebookLoginInfo()
    // {
    //     $facebookInfo = SocialLoginInformation::first();
    //     if ($facebookInfo) {
    //         \Config::set('services.facebook.client_id', $facebookInfo->facebook_client_id);
    //         \Config::set('services.facebook.client_secret', $facebookInfo->facebook_secret_id);
    //         \Config::set('services.facebook.redirect', $facebookInfo->facebook_redirect_url);
    //     }
    // }

    public static function setProviderLoginInfo($provider)
    {
        $Info = SocialLoginInformation::first();

        if (!$Info) {
            throw new \Exception("Social login information is missing.");
        }

        switch ($provider) {
            case 'google':
                \Config::set('services.google.client_id', $Info->gmail_client_id);
                \Config::set('services.google.client_secret', $Info->gmail_secret_id);
                \Config::set('services.google.redirect', $Info->gmail_redirect_url);
                break;
            case 'facebook':
                \Config::set('services.facebook.client_id', $Info->facebook_client_id);
                \Config::set('services.facebook.client_secret', $Info->facebook_secret_id);
                \Config::set('services.facebook.redirect', $Info->facebook_redirect_url);
                break;
            case 'github':
                // Custom configuration for GitHub
                break;
            case 'linkedin':
                // Custom configuration for LinkedIn
                break;
            default:
                throw new \Exception("Unsupported provider: $provider");
        }
    }


    protected $casts = [
        'is_facebook' => 'integer',
        'is_gmail' => 'integer',
    ];
}
