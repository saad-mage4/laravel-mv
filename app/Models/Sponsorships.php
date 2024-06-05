<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Support\Facades\DB;

class Sponsorships extends Model
{
    use HasFactory;

    public function addSponsor($params, $image)
    {
        $position = $params->image_position ?? null;
        $isBooked = $params->is_booked ?? false;
        $imgURL = $image ?? null;
        $sponsorRedirect = $params->prod_link ?? null;
        $sponsorTitle = $params->sponsor_title ?? null;
        $sponsorName = $params->sponsor_name ?? null;
        $details = $this->getBannerDetails($position);

        DB::table('sponsorships')->insert(
            [
                'banner_position' => $position,
                'width' => $details['width'],
                'height' => $details['height'],
                'price' => $details['price'],
                'days' => $details['days'],
                'is_booked' => $isBooked,
                'image_url' => $imgURL,
                'banner_redirect' => $sponsorRedirect,
                'sponsor_title' => $sponsorTitle,
                'sponsor_name' => $sponsorName,
            ]
        );
    }

    /**
     * @param $params
     * @return void
     */
    public function updateSponsor($params, $image)
    {
        $position = $params->image_position ?? null;
        $isBooked = $params->is_booked ?? false;
        $imgURL = $image ?? null;
        $sponsorRedirect = $params->prod_link ?? null;
        $sponsorTitle = $params->sponsor_title ?? null;
        $sponsorName = $params->sponsor_name ?? null;
        $details = $this->getBannerDetails($position);
        $width = $details['width'];
        $height = $details['height'];
        $price = $details['price'];
        $days = $details['days'];

        DB::table('sponsorships')->where('banner_position', $position)->update(
            [
                'width' => $width,
                'height' => $height,
                'price' => $price,
                'days' => $days,
                'is_booked' => $isBooked,
                'image_url' => $imgURL,
                'banner_redirect' => $sponsorRedirect,
                'sponsor_title' => $sponsorTitle,
                'sponsor_name' => $sponsorName,
            ]
        );
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
