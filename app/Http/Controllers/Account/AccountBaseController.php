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

namespace App\Http\Controllers\Account;

use App\Http\Controllers\FrontController;
use App\Larapen\Models\Ad;
use App\Larapen\Models\SavedAd;
use App\Larapen\Models\SavedSearch;
use App\Larapen\Scopes\ActiveScope;
use App\Larapen\Scopes\ReviewedScope;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\View;
use Larapen\CountryLocalization\Facades\CountryLocalization;
use Larapen\CountryLocalization\Helpers\Country;

abstract class AccountBaseController extends FrontController
{
    public $countries;
    public $my_ads;
    public $archived_ads;
    public $favourite_ads;
    public $pending_ads;

    public function __construct(HttpRequest $request)
    {
        parent::__construct($request);
        
        $this->countries = Country::transAll(CountryLocalization::getCountries(), $this->lang->get('abbr'));
        View::share('countries', $this->countries);
        
        // My Ads
        $this->my_ads = Ad::where('user_id', $this->user->id)->active()->with('city')->take(50)->orderBy('created_at', 'DESC');
        View::share('count_my_ads', $this->my_ads->count());
        
        // Archived Ads
        $this->archived_ads = Ad::where('user_id', $this->user->id)->archived()->with('city')->take(50)->orderBy('created_at', 'DESC');
        View::share('count_archived_ads', $this->archived_ads->count());
        
        // Favourite Ads
        $this->favourite_ads = SavedAd::where('user_id', $this->user->id)->with('ad.city')->take(50)->orderBy('created_at', 'DESC');
        View::share('count_favourite_ads', $this->favourite_ads->count());
        
        // Pending Approval Ads
        $this->pending_ads = Ad::withoutGlobalScopes([ActiveScope::class, ReviewedScope::class])->where('user_id',
            $this->user->id)->pending()->with('city')->take(50)->orderBy('created_at', 'DESC');
        View::share('count_pending_ads', $this->pending_ads->count());
        
        // Save Search
        $saved_search = SavedSearch::where('user_id', $this->user->id)->orderBy('created_at', 'DESC');
        View::share('count_saved_search', $saved_search->count());
    }
}
