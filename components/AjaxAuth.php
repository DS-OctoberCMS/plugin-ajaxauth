<?php

namespace Wbry\AjaxAuth\Components;

use Lang;
use Flash;
use Session;
use Request;
use Cms\Classes\ComponentBase;

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
