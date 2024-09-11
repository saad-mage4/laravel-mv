<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialLoginHelper
{
   public static function handleSocialLogin($socialUser, $provider)
   {
      // Find the user by provider_id or email
      $user = User::where('provider_id', $socialUser->id)
         ->orWhere('email', $socialUser->email)
         ->first();

      if (!$user) {
         // If the user does not exist, create a new user
         $user = User::create([
            'name'     => $socialUser->name,
            'email'    => $socialUser->email,
            'provider' => $provider,
            'provider_id' => $socialUser->id,
            'provider_avatar' => $socialUser->avatar,
            'status' => 1,
            'email_verified' => 1,
         ]);
      } else {
         // If the user exists, update their information
         $user->update([
            'name' => $socialUser->name,
            'provider_avatar' => $socialUser->avatar,
            'email_verified' => 1, // Assuming the email is verified
         ]);
      }

      // Log the user in
      Auth::login($user);

      return $user;
   }
}