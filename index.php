<?php
/**
		Ride Stats - Statistics system for non-competitive associations.
    Copyright (C) 2008 - Iván Sánchez Luque

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('source/template.php');
require_once('settings.php');

global $RS_URL;

$actionArray = array(	
	'admin' => array('admin.php'),
	'admin_login' => array('admin_login.php'),	
	'contact' => array('contact.php'),
	'delete_route' => array('delete_route.php'),
	'delete_tour' => array('delete_tour.php'),
	'delete_user' => array('delete_user.php'),
	'edit_route' => array('edit_route.php'),		
	'edit_tour' => array('edit_tour.php'),		
	'edit_user' => array('edit_user.php'),		
	'faq' => array('faq.php'),			
	'index' => array('start.php'),
	'login' => array('login.php'),	
	'login_error' => array('login_error.php'),
	'new_route' => array('new_route.php'),
	'new_tour' => array('new_tour.php'),
	'new_user' => array('new_user.php'),
	'route' => array('route.php'),
	'routes' => array('routes.php'),	
	'save_route' => array('save_route.php'),	
	'save_tour' => array('save_tour.php'),
	'save_user' => array('save_user.php'),	
	'tour' => array('tour.php'),
	'tours' => array('tours.php'),	
	'upload_track' => array('upload_track.php'),
	'upload_user_photo' => array('upload_user_photo.php'),	
	'user' => array('user.php'),
	'users' => array('users.php') 
	);

// Creates the template
$theme = $_COOKIE['stylesheet'];
if (!$theme) $theme = 'blue';
//$tpl = & new Template($RS_URL.'/themes/'.$theme.'/index.template.php');
$tpl = & new Template($RS_URL.'/source/index.template.php');
$tpl->set('title', 'Ride Stats');
$tpl->set('style', $RS_URL.'/themes/'.$theme.'/style.css');
$tpl->set('admin_rights', isset($_COOKIE["user"]));

// Sets action page
if (isset($_REQUEST['action']))
	$action = $actionArray[$_REQUEST['action']][0];

// If no action or unrecognized action, use start page
if (!$action)
	$action = $actionArray['index'][0];
	
// Creates body and adds it to the template
$body = & new Template('source/'.$action);
$body->set('something', 0);
$tpl->set('body', $body->fetch('source/'.$action));

// Fetch template
//echo $tpl->fetch('themes/'.$theme.'/index.template.php');
echo $tpl->fetch('source/index.template.php');
?> 