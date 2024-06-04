<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Support\Facades\DB;

class Sponsorships extends Model
{
    use HasFactory;

    /**
     * @param $params
     * @return void
     */
    public function addSponsor($params)
    {
        DB::table('sponsorships')->insert(
            [
                'banner_name' => $params->banner_name,
                'width' => $params->width,
                'height' => $params->height,
                'price' => $params->price,
                'hours' => $params->hours,
                'is_booked' => $params->is_booked,
                'image_url' => $params->image_url,
                'sponsor_title' => $params->sponsor_title,
                'sponsor_name' => $params->sponsor_name,
            ]
        );
    }

    /**
     * @param $params
     * @return void
     */
    public function updateSponsor($params)
    {
        DB::table('sponsorships')->where('banner_name', $params->banner_name)->update(
            [
                ['width' => $params->width],
                ['height' => $params->height],
                ['price' => $params->price],
                ['hours' => $params->hours],
                ['is_booked' => $params->is_booked],
                ['image_url' => $params->image_url],
                ['sponsor_title' => $params->sponsor_title],
                ['sponsor_name' => $params->sponsor_name],
            ]
        );
    }
}
