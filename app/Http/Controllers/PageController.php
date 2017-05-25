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

use App\Larapen\Events\ContactFormWasSent;
use App\Larapen\Helpers\Arr;
use App\Larapen\Helpers\Rules;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Larapen\TextToImage\Facades\TextToImage;

class PageController extends FrontController
{
    public function about()
    {
        // SEO
        $title = trans('page.Who Are We?');
        $description = str_limit(str_strip(trans('page.about_us', ['domain' => getDomain(), 'country' => $this->country->get('name')])), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.about');
    }
    
    public function faq()
    {
        // SEO
        $title = trans('page.Site FAQ');
        $description = str_limit(str_strip(trans('page.How do I place an ad?')), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.faq');
    }
    
    public function phishing()
    {
        // SEO
        $title = trans('page.Phishing');
        $description = str_limit(str_strip(trans('page.phishing_content', ['contactUrl' => '#'])), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.phishing');
    }
    
    public function antiScam()
    {
        // SEO
        $title = trans('page.Anti-scam');
        $description = str_limit(str_strip(trans('page.anti_scam_content', ['contactUrl' => '#'])), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.anti-scam');
    }
    
    public function contact()
    {
        // SEO
        $title = trans('page.Contact Us');
        $description = str_limit(str_strip(trans('page.Contact Us')), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.contact');
    }
    
    public function contactPost(HttpRequest $request)
    {
        // Form validation
        $validator = Validator::make($request->all(), Rules::ContactUs($request, 'POST'));
        if ($validator->fails()) {
            // BugFix with : $request->except('pictures')
            return back()->withErrors($validator)->withInput();
        }
        
        // Store Contact Info
        $contact_form = array(
            'country_code' => $this->country->get('code'),
            'country' => $this->country->get('name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'company_name' => $request->input('company_name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
        );
        
        // Send Welcome Email
        Event::fire(new ContactFormWasSent(Arr::toObject($contact_form)));
        
        if (!session('flash_notification')) {
            flash()->success(t("Your message has been sent to our moderators. Thank you"));
        }
        
        return redirect($this->lang->get('abbr') . '/' . trans('routes.contact'));
    }
    
    public function terms()
    {
        // SEO
        $title = trans('page.Terms Of Use');
        $description = str_limit(str_strip(trans('page.definition_content',
            ['app_name' => mb_ucfirst(config('settings.app_name')), 'country' => mb_ucfirst($this->country->get('name')), 'url' => url('/')])), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.terms');
    }
    
    public function privacy()
    {
        // SEO
        $title = trans('page.Privacy');
        $description = str_limit(str_strip(trans('page.privacy_content',
            ['app_name' => mb_ucfirst(config('settings.app_name')), 'domain' => getDomain(), 'country' => $this->country->get('name')])), 200);
        
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        
        // Open Graph
        $this->og->title($title)->description($description);
        View::share('og', $this->og);
        
        return view('classified.pages.privacy');
    }
}
