<?php return [
    'plugin' => [
        'name' => 'Ajax аутентификация',
        'description' => 'Вспомогательный плагин для RainLab.User.'
    ],
    'components' => [
        'ajax_auth' => [
            'name'        => 'Ajax аутентификация',
            'description' => 'Разные дополнительные события и действия для плагина "Rainlab.User".',
            'msg' => [
                'success_restore_password' => 'Пожалуйста, проверьте свою электронную почту для кода активации.',
                'success_reset_password'   => 'Сброс пароля завершен, теперь вы можете войти.',
                'error_login_data'   => 'Введенные данные не верны.',
                'error_user_blocked' => 'Пользователь заблокирован.',
                'error_exists'       => 'Пользователь с такими данными уже существует.',
            ],
            'attr' => [
                'email'    => '"Email"',
                'login'    => '"Логин"',
                'password' => '"Пароль"',
            ],
        ],
    ],
];