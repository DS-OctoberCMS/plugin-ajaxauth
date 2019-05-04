<?php return [
    'plugin' => [
        'name' => 'Ajax аутентификация',
        'description' => 'Вспомогательный плагин для RainLab.User.'
    ],
    'components' => [
        'ajax_auth' => [
            'name' => 'Ajax аутентификация',
            'desc' => 'Разные дополнительные события и действия для плагина "Rainlab.User".',
            'msg'  => [
                'success_restore_password'  => 'Пожалуйста, проверьте свою электронную почту для кода активации.',
                'success_reset_password'    => 'Сброс пароля завершен, теперь вы можете войти.',
                'success_register_admin'    => 'Заявка на регистрацию успешно отправлена. Дождитесь активации Администратором.',
                'error_login_data'          => 'Логин или пароль введены неверно.',
                'error_user_blocked'        => 'Пользователь заблокирован.',
                'error_exists'              => 'Пользователь с такими данными уже существует.',
                'error_not_activated_email' => 'Пользователь с такими данными еще не активирован. Пожалуйста, активируйте свой аккаунт, перейдя по ссылке в отправленном на вашу почту сообщении.',
                'error_not_activated_admin' => 'Пользователь с такими данными еще не активирован. Заявка на активацию находится в очереди одобрения Администратором.',
            ],
            'attr' => [
                'email'    => '"Email"',
                'login'    => '"Логин"',
                'password' => '"Пароль"',
            ],
        ],
    ],
];