<?php
/**
 * LaraClassified - Geo Classified Ads CMS
 * Copyright (c) Mayeul Akpovi. All Rights Reserved
 *
 * Email: mayeul.a@larapen.com
 * Website: http://larapen.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers;

use App\Larapen\Helpers\Arr;
use App\Larapen\Models\Ad;
use App\Larapen\Models\Category;
use App\Larapen\Models\SubAdmin1;
use App\Larapen\Models\City;
use App\Larapen\Models\User;
use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class HomeController extends FrontController
{
    public function index()
    {
        $data = array();
        
        // Get Categories
        $cats = Category::where('parent_id', 0)->where('translation_lang', $this->lang->get('abbr'))->orderBy('lft')->get();
        $data['cats'] = collect($cats)->keyBy('id');
        
        
        // Count Country Ads (Ads with activated categories)
        $count_ads = Ad::where('country_code', $this->country->get('code'))->count();
        $data['count_ads'] = $count_ads;
        
        
        // Get Cities
        $taken_cities = 7; //35;
        $cities_q = City::where('country_code', '=', $this->country->get('code'))->orderBy('population', 'DESC')->orderBy('name');
        $data['taken_cities'] = $taken_cities;
        $data['count_cities'] = $cities_q->count();
        
        if ($taken_cities < $data['count_cities']) {
            $cities = City::where('country_code', $this->country->get('code'))->take($taken_cities)->orderBy('population', 'DESC')->orderBy('name')->get();
            $cities = collect($cities)->push(Arr::toObject([
                'id' => 999999999,
                'name' => t('More cities') . ' &raquo;',
                'subadmin1_code' => 0
            ]));
        } else {
            $cities = $cities_q->get();
        }
        $cols = round($cities->count() / 2, 0); // PHP_ROUND_HALF_EVEN
        $cols = ($cols > 0) ? $cols : 1; // Fix array_chunk with 0
        $data['city_cols'] = $cities->chunk($cols);
        
        
        // Bottom Infos
        if (config('settings.activation_home_stats')) {
            // COUNT USERS
            $data['count_users'] = User::where('active', 1)->count();
            // COUNT FACEBOOK FANS
            $data['count_facebook_fans'] = countFacebookFans(config('settings.facebook_page_id'));
        }
        
        
        // Modal - States Collection
        $states = SubAdmin1::where('code', 'LIKE', $this->country->get('code') . '.%')->orderBy('name')->get(['code', 'name'])->keyBy('code');
        View::share('states', $states);
        
        
        // SEO
        if (config('settings.app_slogan')) {
            $title = config('settings.app_slogan');
        } else {
            $title = t('Free local classified ads in :location', ['location' => $this->country->get('name')]);
        }
        $description = str_limit(str_strip(t('Sell and Buy products and services on :app_name in Minutes',
                ['app_name' => mb_ucfirst(config('settings.app_name'))]) . ' ' . $this->country->get('name') . '. ' . t('Free ads in :location',
                ['location' => $this->country->get('name')]) . '. ' . t('Looking for a product or service') . ' - ' . $this->country->get('name')),
            200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', strip_tags($description));
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.home.index', $data);
    }
}
