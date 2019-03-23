<?php

namespace Wbry\AjaxAuth\Components;

use App;
use Lang;
use Flash;
use Session;
use Request;
use Redirect;
use Exception;
use Validator;
use ValidationException;
use October\Rain\Auth\AuthException;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Components\Account as ComponentAccount;

/**
 * Ajax auth component
 *
 * @package Wbry\AjaxAuth\Components
 * @author Diamond Systems
 */
class AjaxAuth extends ComponentAccount
{
    public function componentDetails()
    {
        return [
            'name'        => 'wbry.ajaxauth::lang.components.ajax_auth.name',
            'description' => 'wbry.ajaxauth::lang.components.ajax_auth.desc'
        ];
    }

    public function init()
    {
        App::error(function(AuthException $e)
        {
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
        parent::onRun();

        if ($code = $this->activationCode())
            return Redirect::to($this->property('redirect', '/') ?: '/');

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

    public function onSignin()
    {
        try {
            /*
             * Validate input
             */
            $data = post();
            $rules = [
                'password' => 'required|between:4,255'
            ];

            $rules['login'] = $this->loginAttribute() == UserSettings::LOGIN_USERNAME
                ? 'required|between:2,255'
                : 'required|email|between:6,255';

            if (! array_key_exists('login', $data))
                $data['login'] = post('username', post('email'));

            $validation = Validator::make($data, $rules);
            $validation->setAttributeNames([
                'login'    => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.attr.login'),
                'password' => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.attr.password'),
            ]);
            if ($validation->fails())
                throw new ValidationException($validation);

            if (! ($user = UserModel::where('email', $data['login'])->orWhere('username', $data['login'])->first()) ||
                ! $user->checkPassword(array_get($data, 'password')))
                throw new ValidationException(['email' => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.error_login_data')]);

            parent::onSignin();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }

    public function onRegister()
    {
        try {
            /*
             * Validate input
             */
            $data = post();
            $isUsenName = ($this->loginAttribute() == UserSettings::LOGIN_USERNAME);
            $rules = [
                'email'    => 'required|email|between:6,255',
                'password' => 'required|between:4,255'
            ];
            if ($isUsenName)
                $rules['username'] = 'required|between:2,255';

            $validation = Validator::make($data, $rules);
            $validation->setAttributeNames([
                'email' => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.attr.email'),
                'password' => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.attr.password'),
            ]);
            if ($validation->fails())
                throw new ValidationException($validation);

            $query = UserModel::where('email', array_get($data, 'email'));
            if ($isUsenName)
                $query->where('username', array_get($data, 'username'));

            if ($query->first())
                throw new ValidationException(['email' => Lang::get('wbry.ajaxauth::lang.components.ajax_auth.msg.error_exists')]);

            parent::onRegister();
        }
        catch (Exception $ex) {
            if (Request::ajax()) throw $ex;
            else Flash::error($ex->getMessage());
        }
    }
}
