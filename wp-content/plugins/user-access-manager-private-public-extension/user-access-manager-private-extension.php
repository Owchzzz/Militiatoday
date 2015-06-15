<?php
/*
Plugin Name: User Access Manager - Private & Public Extension
Plugin URI: http://twostairs.com
Description: Privatize parts of posts from unauthorized users. Begin protected content with [private group=XXX]  and end hidden content with [/private]. Begin public content with [public] and end it with [/public].
Version: 0.2
Author: Enrique Berzosa, "Public Extension" by Marius <marius@twostairs.com>
Author URI: 

Surround the text to be privatized with [private] and [/private] or [private group=XXX] and [/private], where XXX is a UAM group ID or group NAME.
Surround the text to be public (for not logged in users/guests only) with [public] and [/public].
Beware, if NAME is used and group name is changed private parts will become invisible for that group.
*/

/*  Copyright 2011  ENRIQUE BERZOSA  (website : )
    Copyright 2013 "Public Extension" by Marius <marius@twostairs.com> (website: http://twostairs.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define ('UAMPE_UAM_PLUGIN', 'user-access-manager');
	
define('UAMPE_NAME', 'user-access-manager-private-extension');

//Paths
load_plugin_textdomain(UAMPE_NAME, false, UAMPE_NAME.'/lang');

//Defines
require_once 'includes/language.define.php';

if (!function_exists('check_uam_plugin'))
{
	function check_uam_plugin()
	{		
		if (defined('UAM_REALPATH'))
			$uam = UAM_REALPATH;
		else 
			$uam = UAMPE_UAM_PLUGIN.'/'.UAMPE_UAM_PLUGIN.'.php';			
		
		return in_array($uam, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}
}

require_once (dirname(__FILE__) . '/includes/UserAccessManagerPrivateExtension.php');

if (!function_exists("initUamPe")) 
{
    function initUamPe($userAccessManager) 
    {
    	global $uamPe;
    
    	$uamPe->setUserAccessManager($userAccessManager);
    	$uamPe->add_shortcodes($userAccessManager);
    }
}

if (class_exists('UserAccessManagerPrivateExtension'))
	$uamPe = new UserAccessManagerPrivateExtension();
	
if (isset($uamPe))
{   
	// Link with UAM
    if (function_exists('add_action')) {
        add_action('uam_init', 'initUamPe');
    }
    
	// install
    if (function_exists('register_activation_hook')) {
        register_activation_hook(__FILE__, array(&$uamPe, 'activate'));
    }
    
    // uninstall
    if (function_exists('register_uninstall_hook')) {
        register_uninstall_hook(__FILE__, array(&$uamPe, 'deactivate'));
    } 
    
    // deactivation
    if (function_exists('register_deactivation_hook')) {
        register_deactivation_hook(__FILE__, array(&$uamPe, 'deactivate'));
    }
}

?>