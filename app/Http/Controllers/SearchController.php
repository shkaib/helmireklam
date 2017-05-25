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
use App\Larapen\Helpers\Search;
use App\Larapen\Models\Ad;
use App\Larapen\Models\SubAdmin1;
use App\Larapen\Models\SubAdmin2;
use App\Larapen\Models\AdType;
use App\Larapen\Models\Category;
use App\Larapen\Models\City;
use App\Larapen\Models\Language;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request as Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Larapen\CountryLocalization\Facades\CountryLocalization;
use Larapen\CountryLocalization\Helpers\Country;
use Larapen\LaravelLocalization\Facades\LaravelLocalization;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SearchController extends FrontController
{
    protected $city = null;
    
    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        parent::__construct($request);
        
        try {
            // Check Country URL for SEO
            $countries = Country::transAll(CountryLocalization::getCountries(), $this->lang->get('abbr'));
            View::share('countries', $countries);
            
            $url_has_country = $countries->contains(function ($key, $value) {
                return slugify($value->get('name')) == Request::segment(3);
            });
            if ($url_has_country) {
                if (slugify($this->country->get('name')) != Request::segment(3)) {
                    $goodUrl = str_replace(Request::segment(3), slugify($this->country->get('name')), Request::url());
                    header('Location: ' . $goodUrl);
                    exit();
                }
            }
            
            // CATEGORIES COLLECTION
            $cats = Category::where('translation_lang', $this->lang->get('abbr'))->orderBy('lft')->get();
            if (!is_null($cats)) {
                $cats = collect($cats)->keyBy('translation_of');
            }
            View::share('cats', $cats);
            
            
            // COUNT CATEGORIES ADS COLLECTION
            $sql = 'SELECT sc.id, c.parent_id, count(*) as total
                    FROM ' . DB::getTablePrefix() . 'ads as a
                    INNER JOIN ' . DB::getTablePrefix() . 'categories as sc ON sc.id=a.category_id AND sc.active=1
				    INNER JOIN ' . DB::getTablePrefix() . 'categories as c ON c.id=sc.parent_id AND c.active=1
                    WHERE a.country_code = :country_code AND a.active=1 AND a.archived!=1 AND a.deleted_at IS NULL
                    GROUP BY sc.id';
            $bindings = ['country_code' => $this->country->get('code')];
            $count_sub_cat_ads = DB::select(DB::raw($sql), $bindings);
            $count_sub_cat_ads = collect($count_sub_cat_ads)->keyBy('id');
            View::share('count_sub_cat_ads', $count_sub_cat_ads);
            
            // COUNT PARENT CATEGORIES ADS COLLECTION
            $sql = 'SELECT c.id, count(*) as total
                    FROM ' . DB::getTablePrefix() . 'ads as a
                    INNER JOIN ' . DB::getTablePrefix() . 'categories as sc ON sc.id=a.category_id AND sc.active=1
				    INNER JOIN ' . DB::getTablePrefix() . 'categories as c ON c.id=sc.parent_id AND c.active=1
                    WHERE a.country_code = :country_code AND a.active=1 AND a.archived!=1 AND a.deleted_at IS NULL
                    GROUP BY c.id';
            $bindings = ['country_code' => $this->country->get('code')];
            $count_cat_ads = DB::select(DB::raw($sql), $bindings);
            $count_cat_ads = collect($count_cat_ads)->keyBy('id');
            View::share('count_cat_ads', $count_cat_ads);
            
            
            // CATEGORY SELECTED
            $cat = null;
            // @todo: Fix country translation problem with $this->country->get('name')
            if (Input::has('c') or Request::segment(3) == slugify($this->country->get('name')) or (Request::segment(5) != '' and Request::segment(3) != trans('routes.t-search') and Request::segment(3) != trans('routes.t-search-location') and Request::segment(4) != 'user')) {
                if (Input::has('c')) {
                    $cat = $cats->get((int)Input::get('c'));
                } else {
                    $cat_slug = Request::segment(4);
                    if (Request::segment(5) == '') {
                        $cat = $cats->where('slug', $cat_slug)->flatten()->get(0);
                    } else {
                        $sub_cat_slug = Request::segment(5);
                        $cat = $cats->where('slug', $cat_slug)->flatten()->get(0);
                        $sub_cat = $cats->where('slug', $sub_cat_slug)->flatten()->get(0);
                        View::share('sub_cat', $sub_cat);
                    }
                }
                
                if (!isset($cat) or count($cat) <= 0) {
                    abort(404);
                }
                $this->cat = $cat;
                View::share('cat', $cat);
            }
            
            
            // CITIES COLLECTION
            $cities = City::where('country_code', '=', $this->country->get('code'))->take(100)->orderBy('population',
                'DESC')->orderBy('name')->get();
            View::share('cities', $cities);
            
            
            // ADTYPE COLLECTION
            $ad_types = AdType::orderBy('name')->get();
            View::share('ad_types', $ad_types);
            
            
            // CITY SELECTED
            if (Input::has('l') or Request::segment(3) == trans('routes.t-search-location') or Input::has('r')) {
                if (Input::has('r')) {
                    // NOTE: city = SubAdmin1 (Just for Search result page title)
                    $region = rawurldecode(Input::get('r'));
                    $city = SubAdmin2::where('code', 'LIKE', $this->country->get('code') . '.%')->where('name', 'LIKE',
                        $region . '%')->orderBy('name')->first();
                    if (is_null($city)) {
                        $city = SubAdmin1::where('code', 'LIKE', $this->country->get('code') . '.%')->where('name', 'LIKE',
                            $region . '%')->orderBy('name')->first();
                    }
                    if (is_null($city)) {
                        $tmp = preg_split('#(-| )+#', $region);
                        usort($tmp, function ($a, $b) {
                            return strlen($b) - strlen($a);
                        });
                        foreach ($tmp as $p_region) {
                            $city = SubAdmin2::where('code', 'LIKE', $this->country->get('code') . '.%')->where('name', 'LIKE',
                                '%' . $p_region . '%')->orderBy('name')->first();
                            if ($city) {
                                break;
                            }
                        }
                        if (is_null($city)) {
                            foreach ($tmp as $p_region) {
                                $city = SubAdmin1::where('code', 'LIKE', $this->country->get('code') . '.%')->where('name', 'LIKE',
                                    '%' . $p_region . '%')->orderBy('name')->first();
                                if ($city) {
                                    break;
                                }
                            }
                        }
                    }
                    
                    // If empty... then return collection of URL parameters
                    if (is_null($city)) {
                        $city = Arr::toObject(['name' => $region . ' (-)', 'subadmin1_code' => 0]);
                    }
                } else {
                    if (Input::has('l')) {
                        $city = City::find(Input::get('l'));
                    } else {
                        // Get City by Id
                        $city = City::find((int)Request::segment(5));
                        
                        // Get City by (raw) Name
                        if (is_null($city)) {
                            $city = City::where('country_code', $this->country->get('code'))->where('name', 'LIKE',
                                rawurldecode(Request::segment(4)))->first();
                            // Check helper 'core.php'
                            if (is_null($city)) {
                                $city = City::where('country_code', $this->country->get('code'))->where('name', 'LIKE',
                                    '% ' . rawurldecode(Request::segment(4)))->first();
                                if (is_null($city)) {
                                    $city = City::where('country_code', $this->country->get('code'))->where('name', 'LIKE',
                                        rawurldecode(Request::segment(4)) . ' %')->first();
                                }
                            }
                        }
                    }
                }
                
                if (!isset($city) or count($city) <= 0) {
                    abort(404);
                }
                $this->city = $city;
                View::share('city', $city);
            }
            
            // STATES COLLECTION => MODAL
            $states = SubAdmin1::where('code', 'LIKE', $this->country->get('code') . '.%')->orderBy('name')->get(['code', 'name'])->keyBy('code');
            View::share('states', $states);
        } catch (\ErrorException $e) {
            //echo "Error. Please try later. " . $e; exit();
            abort(404);
        }
    }
    
    public function index(HttpRequest $request)
    {
        $search = new Search($request, $this->country, $this->lang);
        $data = $search->fechAll();
        View::share('count', $data['count']);
        View::share('ads', $data['ads']);
        
        
        // HEAD: BUILD TITLE & DESCRIPTION
        if (Request::segment(3) == trans('routes.t-search')) {
            $title = t('Search for') . ' ';
            if (Input::has('q') and Input::has('c') and Input::has('l')) {
                $title .= Input::get('q') . ' ' . $this->cat->name . ' - ' . $this->city->name;
            } else {
                if (Input::has('q') and Input::has('c') and !Input::has('l')) {
                    $title .= Input::get('q') . ' ' . $this->cat->name;
                } else {
                    if (Input::has('q') and !Input::has('c') and Input::has('l')) {
                        $title .= Input::get('q') . ' - ' . $this->city->name;
                    } else {
                        if (!Input::has('q') and Input::has('c') and Input::has('l')) {
                            $title .= $this->cat->name . ' - ' . $this->city->name;
                        } else {
                            if (Input::has('q') and !Input::has('c') and !Input::has('l')) {
                                $title .= Input::get('q');
                            } else {
                                if (!Input::has('q') and Input::has('c') and !Input::has('l')) {
                                    $title .= t('free ads') . ' ' . $this->cat->name;
                                } else {
                                    if (!Input::has('q') and !Input::has('c') and Input::has('l')) {
                                        $title .= t('free ads in') . ' - ' . $this->city->name;
                                    } else {
                                        if (Input::has('r')) {
                                            $title .= t('free ads in') . ' ' . $this->city->name;
                                        } else {
                                            if (!Input::has('q') and !Input::has('c') and !Input::has('l') and !Input::has('r')) {
                                                $title = t('Latest free ads');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $title = t('Free ads in');
            if (Request::segment(3) == slugify($this->country->get('name'))) {
                $title .= ' ' . $this->cat->name;
            } else {
                if (Request::segment(3) == trans('routes.t-search-location')) {
                    $title .= ' ' . $this->city->name;
                }
            }
        }
        // Meta Tags
        MetaTag::set('title', $title . ', ' . $this->country->get('name'));
        MetaTag::set('description', $title);
        
        return view('classified.search.serp');
    }
    
    public function category(HttpRequest $request)
    {
        // Get cat id
        $cat = Category::where('translation_lang', $this->lang->get('abbr'))->where('slug', 'LIKE', Request::segment(4))->first();
        
        $cat_id = ($cat) ? $cat->tid : 0;
        if (!isset($cat_id) or $cat_id <= 0 or !is_numeric($cat_id)) {
            abort(404);
        }
        
        $search = new Search($request, $this->country, $this->lang);
        $data = $search->setCategory($cat_id)->setRequestFilters()->fetch();
        
        // SEO
        $title = $cat->name . ' - ' . t('Free ads :category in :location', ['category' => $cat->name, 'location' => $this->country->get('name')]);
        $description = str_limit(t('Free ads :category in :location', [
                'category' => $cat->name,
                'location' => $this->country->get('name')
            ]) . '. ' . t('Looking for a product or service') . ' - ' . $this->country->get('name'), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description)->type('website');
        if ($data['count']->get('all') > 0) {
            $filtered = $data['ads']->getCollection();
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            /*
            foreach($pictures->get() as $picture) {
                $this->og->image(url('pic/x/cache/large/' . $picture->filename),
                    [
                        'width'     => 600,
                        'height'    => 600
                    ]);
            }
            */
        }
        View::share('og', $this->og);
        
        return view('classified.search.serp', $data);
    }
    
    public function subCategory(HttpRequest $request)
    {
        // Get sub-cat id
        $cat = Category::where('translation_lang', $this->lang->get('abbr'))->where('slug', 'LIKE', Request::segment(4))->with([
            'children' => function ($query) {
                $query->where('translation_lang', '=', $this->lang->get('abbr'))->where('slug', 'LIKE', Request::segment(5));
            }
        ])->first();
        $sub_cat_id = ($cat and count($cat->children) > 0) ? $cat->children->get(0)->tid : 0;
        
        
        if (!isset($sub_cat_id) or $sub_cat_id <= 0 or !is_numeric($sub_cat_id)) {
            if (!is_null($cat)) {
                return redirect($this->lang->get('abbr') . '/' . $this->country->get('icode') . '/' . slugify($this->country->get('name')) . '/' . $cat->slug);
            } else {
                abort(404);
            }
        }
        
        $search = new Search($request, $this->country, $this->lang);
        $data = $search->setSubCategory($sub_cat_id)->setRequestFilters()->fetch();
        
        // Meta Tags
        MetaTag::set('title', $cat->children->get(0)->name . ' - ' . t('Free ads :category in :location',
                ['category' => $cat->name, 'location' => $this->country->get('name')]));
        MetaTag::set('description', t('Free ads :category in :location', [
                'category' => $cat->children->get(0)->name,
                'location' => $this->country->get('name')
            ]) . '. ' . t('Looking for a product or service') . ' - ' . $this->country->get('name'));
        
        return view('classified.search.serp', $data);
    }
    
    public function location(HttpRequest $request)
    {
        $location = $this->city;
        if (is_null($location)) {
            abort(404);
        }
        
        $search = new Search($request, $this->country, $this->lang);
        $data = $search->setLocation($location->latitude, $location->longitude)->setRequestFilters()->fetch();
        
        // Meta Tags
        MetaTag::set('title',
            $location->name . ' - ' . t('Free ads in :location', ['location' => $location->name]) . ', ' . $this->country->get('name'));
        MetaTag::set('description', t('Free ads in :location',
                ['location' => $location->name]) . ', ' . $this->country->get('name') . '. ' . t('Looking for a product or service') . ' - ' . $location->name . ', ' . $this->country->get('name'));
        
        return view('classified.search/serp', $data);
    }
    
    public function user(HttpRequest $request)
    {
        $search = new Search($request, $this->country, $this->lang);
        $data = $search->setUser(Request::segment(5))->setRequestFilters()->fetch();
        
        return view('classified.search.serp', $data);
    }
}
