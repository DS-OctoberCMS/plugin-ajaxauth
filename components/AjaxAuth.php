<?php

namespace Wbry\AjaxAuth\Components;

use App;
use Lang;
use Flash;
use Session;
use Request;
use Wbry\Base\Classes\Helpers;
use Cms\Classes\ComponentBase;
use October\Rain\Auth\AuthException;

/**
 * Ajax auth component
 *
 * @package Wbry\AjaxAuth\Components
 * @author Diamond Systems
 */
class AjaxAuth extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'wbry.ajaxauth::lang.components.name',
            'description' => 'wbry.ajaxauth::lang.components.description'
        ];
    }

    public function init()
    {
        App::error(function(AuthException $e)
        {
            Helpers::setLocale();
            $appLocal = App::getLocale();
            $authMessage = $e->getMessage();

            if ($appLocal == 'en')
                return $authMessage;

            // For error messages see October\Rain\Auth\Manager
            if (strrpos($authMessage,'hashed credential') !== false ||
                strrpos($authMessage,'user was not found') !== false ||
                strrpos($authMessage,'not activated') !== false)
            {
                return Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.error_login_data');
            }
            elseif (strrpos($authMessage,'has been suspended') !== false)
                return Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.error_user_blocked');
            else
                return $authMessage;
        });
    }

    public function onRun()
    {
        if (Session::has('ajax_auth_events'))
        {
            switch (Session::get('ajax_auth_events'))
            {
                case 'success_restore_password_message':
                    Flash::success(Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.success_restore_password'));
                    break;
                case 'success_reset_password_message':
                    Flash::success(Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.success_reset_password'));
                    break;
                case 'ajax_auth_events_clear':
                    Session::forget('ajax_auth_events');
                    Flash::forget('success');
                    break;
            }
            Session::put('ajax_auth_events', 'ajax_auth_events_clear');
        }
    }
}
