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

namespace App\Http\Controllers\Auth;

use App\Larapen\Events\UserWasRegistered;
use App\Larapen\Helpers\Ip;
use App\Larapen\Helpers\Rules;
use App\Larapen\Scopes\ActiveScope;
use App\Larapen\Scopes\ReviewedScope;
use Illuminate\Support\Collection;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Larapen\Models\Ad;
use App\Larapen\Models\Gender;
use App\Larapen\Models\UserType;
use App\Larapen\Models\User;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request as Request;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Larapen\CountryLocalization\Facades\CountryLocalization;
use Larapen\CountryLocalization\Helpers\Country;

class SignupController extends FrontController
{
    public $msg = [];
    
    public function __construct(HttpRequest $request)
    {
        parent::__construct($request);
        
        /*
         * Messages
         */
        $this->msg['signup']['success'] = "Your account has been created.";
        $this->msg['activation']['success'] = "Congratulation :first_name ! Your account has been activated.";
        $this->msg['activation']['multiple'] = "Your account is already activated.";
        $this->msg['activation']['error'] = "Your account's activation has failed.";
    }
    
    /**
     * Show the form the create a new user account.
     *
     * @return Response
     */
    public function getRegister()
    {
        $data = [];
        
        // References
        $data['countries'] = Country::transAll(CountryLocalization::getCountries(), $this->lang->get('abbr'));
        $data['genders'] = Gender::where('translation_lang', $this->lang->get('abbr'))->get();
        $data['userTypes'] = UserType::all();
        
        // Meta Tags
        MetaTag::set('title', t('Sign Up'));
        MetaTag::set('description', t('Sign Up on :app_name !', ['app_name' => mb_ucfirst(config('settings.app_name'))]));
        
        return view('classified.auth.signup.index', $data);
    }
    
    /**
     * Store a new ad post.
     *
     * @param  Request $request
     * @return Response
     */
    public function postRegister(HttpRequest $request)
    {
        // Form validation
        $validator = Validator::make($request->all(), Rules::Signup($request));
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        
        // Store User
        $userInfo = array(
            'country_code'     => $this->country->get('code'),
            'gender_id'        => $request->input('gender'),
            'name'             => $request->input('name'),
            'user_type_id'     => $request->input('user_type'),
            'phone'            => $request->input('phone'),
            'email'            => $request->input('email'),
            'password'         => bcrypt($request->input('password')),
            'phone_hidden'     => $request->input('phone_hidden'),
            'ip_addr'          => Ip::get(),
            'activation_token' => md5(uniqid()),
            'active'           => (config('settings.require_users_activation') == 1) ? 0 : 1,
        );
        $user = new User($userInfo);
        $user->save();
        
        // Update Ads created by this email
        if (isset($user->id) and $user->id > 0) {
            Ad::withoutGlobalScopes([ActiveScope::class, ReviewedScope::class])->where('seller_email', $request->input('email'))->update(['user_id' => $user->id]);
        }
        
        // Send Welcome Email
        if (config('settings.require_users_activation') == 1) {
            Event::fire(new UserWasRegistered($user));
        }
        
        return redirect($this->lang->get('abbr') . '/signup/success')->with(['success' => 1, 'message' => t($this->msg['signup']['success'])]);
    }
    
    public function success()
    {
        if (!session('success')) {
            return redirect('/');
        }
        
        // Meta Tags
        MetaTag::set('title', session('message'));
        MetaTag::set('description', session('message'));
        
        return view('classified.auth.signup.success');
    }
    
    public function activation()
    {
        $token = Request::segment(4);
        if (trim($token) == '') {
            abort(404);
        }
        
        $user = User::withoutGlobalScope(ActiveScope::class)->where('activation_token', $token)->first();
        
        if ($user) {
            if ($user->active != 1) {
                // Activate
                $user->active = 1;
                $user->save();
                flash()->success(t($this->msg['activation']['success'], ['first_name' => $user->name]));
            } else {
                flash()->error(t($this->msg['activation']['multiple']));
            }
            // Connect the User
            if (Auth::loginUsingId($user->id)) {
                //$this->user = Auth::user();
                //View::share('user', $this->user);
                return redirect($this->lang->get('abbr') . '/account');
            } else {
                return redirect($this->lang->get('abbr') . '/login');
            }
        } else {
            $data = ['error' => 1, 'message' => t($this->msg['activation']['error'])];
        }
        
        // Meta Tags
        MetaTag::set('title', $data['message']);
        MetaTag::set('description', $data['message']);
        
        return view('classified.auth.signup.activation', $data);
    }
}
