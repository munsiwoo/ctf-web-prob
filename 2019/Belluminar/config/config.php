<?php
/* AlephDB config */
define('__DB_FILE__', '/home/alephdbms/server');
define('__DB_HOST__', 'localhost');
define('__DB_PORT__', 1337);
define('__TEMPLATE__', $_SERVER['DOCUMENT_ROOT'].'/templates');

/* Site default config */
define('__SALT__', sha1('shashasha'));
define('__TITLE__', 'AlephpHub');
define('__DOMAIN__', $_SERVER['HTTP_HOST']);
define('__AD_DELAY_SEC__', 60);

/* Menu config */
define('__USER_MENU__', [
    'Main' => '/',
    'Videos' => '/videos',
    'Upload' => '/upload',
    'Mypage' => '/mypage',
    'Logout' => '/logout',
]);

define('__GUEST_MENU__', [
    'Main' => '/',
    'Videos' => '/videos',
    'Login' => '/login',
    'Register' => '/register',
]);
