<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Sponsorships extends Model
{
    use HasFactory;

    /**
     * @param $params
     * @param $image
     * @return void
     */
    public function addSponsor($params, $image)
    {
        $position = $params->image_position ?? null;
        $imgURL = $image ?? null;
        $sponsorRedirect = $params->prod_link ?? null;
        $sponsorUser = Auth::user()->getAuthIdentifier() ?? null;
        $sponsorName = $params->sponsor_name ?? null;
        $details = $this->getBannerDetails($position);

        DB::table('sponsorships')->insert(
            [
                'banner_position' => $position,
                'width' => $details['width'],
                'height' => $details['height'],
                'price' => $details['price'],
                'days' => $details['days'],
                'is_booked' => false,
                'image_url' => $imgURL,
                'banner_redirect' => $sponsorRedirect,
                'sponsor_user_id' => $sponsorUser,
                'sponsor_name' => $sponsorName,
            ]
        );
    }

    /**
     * @param $params
     * @param $image
     * @return void
     */
    public function updateSponsor($params, $image)
    {
        $position = $params->image_position ?? null;
        $sponsorRedirect = $params->prod_link ?? null;
        $sponsorUser = Auth::user()->getAuthIdentifier() ?? null;
        $sponsorName = $params->sponsor_name ?? null;
        $details = $this->getBannerDetails($position);

        if(strpos($sponsorRedirect, "https://") !== false) {
            $url_ = $sponsorRedirect;
            } else {
            $url_ = 'https://'.$sponsorRedirect;
            }

        DB::table('sponsorships')->where('banner_position', $position)->update(
            [
                'width' => $details['width'],
                'height' => $details['height'],
                'price' => $details['price'],
                'days' => $details['days'],
                'banner_redirect' => $url_,
                'sponsor_user_id' => $sponsorUser,
                'sponsor_name' => $sponsorName,
            ]
        );

        if (isset($image)) {
            DB::table('sponsorships')->where('banner_position', $position)->update(['image_url' => $image]);
        }
    }

    public function getBannerDetails($position): array
    {
        $details = [];
        if ($position == 'first_image') {
            $details['width'] = '1280';
            $details['height'] = '500';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'second_image') {
            $details['width'] = '350';
            $details['height'] = '700';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'third_image') {
            $details['width'] = '350';
            $details['height'] = '700';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'fourth_image') {
            $details['width'] = '350';
            $details['height'] = '700';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'fifth_image') {
            $details['width'] = '1280';
            $details['height'] = '200';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'sixth_image') {
            $details['width'] = '1280';
            $details['height'] = '500';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'seventh_image') {
            $details['width'] = '350';
            $details['height'] = '700';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        if ($position == 'eighth_image') {
            $details['width'] = '350';
            $details['height'] = '700';
            $details['price'] = '20';
            $details['days'] = '15';
        }
        return $details;
    }
}
