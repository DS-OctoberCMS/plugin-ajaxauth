<?php

namespace Wbry\AjaxAuth;

use Event;
use Session;
use Request;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Wbry\Base\Classes\Helpers;
use October\Rain\Mail\Mailer;

/**
 * Auth clients plugin
 *
 * @package Wbry\AjaxAuth
 * @author Diamond Systems
 */
class Plugin extends PluginBase
{
    public $require = [
        'Wbry.Base',
        'RainLab.User',
    ];

    public function boot()
    {
        Helpers::setLocale();

        if (! Request::ajax())
            return;

        $this->ajaxEventsList();
    }

    public function registerSettings()
    {}

    public function registerComponents()
    {
        return [
            Components\AjaxAuth::class => 'ajaxAuth'
        ];
    }

    /*
     * Actions
     */

    protected function ajaxEventsList()
    {
        # success restore password message
        if (post('email'))
        {
            Event::listen('mailer.send', function (Mailer $mailer, $view) {
                if ($view === 'rainlab.user::mail.restore')
                    Session::put('ajax_auth_events', 'success_restore_password_message');
            });
        }

        # success reset password message
        if (get('reset') && post('code') && post('password'))
        {
            Event::listen('eloquent.saved: '.User::class, function() {
                Session::put('ajax_auth_events', 'success_reset_password_message');
            });
        }
    }
}
