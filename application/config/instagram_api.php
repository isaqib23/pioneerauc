<?php

/*
|--------------------------------------------------------------------------
| Instagram
|--------------------------------------------------------------------------
|
| Instagram client details
|
*/

$config['instagram_client_name']	= 'mqadeer661';
$config['instagram_client_id']		= '1136665713336405';
$config['instagram_client_secret']	= '9c89677b2deeb6674d2ce43875e5fec9';
$config['instagram_callback_url']	= 'http://pa.yourvteams.com/';//e.g. http://yourwebsite.com/authorize/get_code/
$config['instagram_website']		= 'http://pa.yourvteams.com/';//e.g. http://yourwebsite.com/
$config['instagram_description']	= 'instagram feed on my website';
	
/**
 * Instagram provides the following scope permissions which can be combined as likes+comments
 * 
 * basic - to read any and all data related to a user (e.g. following/followed-by lists, photos, etc.) (granted by default)
 * comments - to create or delete comments on a user’s behalf
 * relationships - to follow and unfollow users on a user’s behalf
 * likes - to like and unlike items on a user’s behalf
 * 
 */
$config['instagram_scope'] = 'user_profile,user_media';

// There was issues with some servers not being able to retrieve the data through https
// If you have this problem set the following to FALSE 

$config['instagram_ssl_verify']		= TRUE;