<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'LanguageLoader',
    'function' => 'initialize',
    'filename' => 'LanguageLoader.php',
    'filepath' => 'hooks'
);

// Set timezone for PHP
$hook['pre_system'][] = function(){
    date_default_timezone_set('Asia/Dubai');
};

// Set timezone for MySQL based on PHP timezone
$hook['post_controller_constructor'][] = function(){
    $CI =& get_instance();
    $CI->db->query('SET time_zone = "' . date('P') . '"');
};

// $hook['pre_controller'][] = array(
// 	'class'    => 'RedirectLoader',
//     'function' => 'redirect_ssl',
//     'filename' => 'RedirectLoader.php',
//     'filepath' => 'hooks'
// );
