<?php return [
    'plugin' => [
        'name' => 'Ajax authentication',
        'description' => 'Auxiliary plugin for RainLab.User.'
    ],
    'components' => [
        'ajax_auth' => [
            'name' => 'Ajax authentication',
            'desc' => 'Various additional events and actions for the "Rainlab.User" plugin.',
            'msg'  => [
                'success_restore_password'  => 'Please check your email for the activation code.',
                'success_reset_password'    => 'Password reset is complete, now you can login.',
                'success_register_admin'    => 'Application for registration successfully sent. Wait for activation by the Administrator.',
                'error_login_data'          => 'Username or password entered incorrectly.',
                'error_user_blocked'        => 'The user is blocked.',
                'error_exists'              => 'A user with such data already exists.',
                'error_not_activated_email' => 'The user with such data is not yet activated. Please activate your account by clicking on the link in the message sent to your mail.',
                'error_not_activated_admin' => 'The user with such data is not yet activated. The activation request is pending approval by the Administrator.',
            ],
            'attr' => [
                'email'    => '"Email"',
                'login'    => '"Login"',
                'password' => '"Password"',
            ],
        ],
    ],
];