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

namespace App\Larapen\Listeners;

use App\Larapen\Events\UserWasRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EmailWelcomeMessage extends Email
{
    /**
     * Handle the event.
     *
     * @param  UserWasRegistered $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        try {
            Mail::send('emails.user.signup', ['user' => $event->user], function ($m) use ($event) {
                $m->to($event->user->email, $event->user->name)->subject(trans('mail.Welcome to :app_name :user_name', [
                    'app_name' => config('settings.app_name'),
                    'user_name' => $event->user->name
                ]));
            });
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }
}
