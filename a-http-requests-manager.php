<?php

/*
  Plugin URI: https://veppa.com/http-requests-manager/
  Description: This is a boot module installed by the "HTTP Requests Manager" plugin when you ENABLE "Load before other plugins" option.
  Author: veppa
  Author URI: https://veppa.com/
  Version: 1.0
  Text Domain: http-requests-manager
  Network: true

  Copyright (C) 2015-23 CERBER TECH INC., https://cerber.tech
  Copyright (C) 2015-23 Markov Gregory, https://wpcerber.com

  Licenced under the GNU GPL.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

// If this file is called directly, abort executing.
defined('ABSPATH') or exit;

define('VPHRM_MODE_INIT', 1);

if(in_array('http-requests-manager/http-requests-manager.php', apply_filters('active_plugins', get_option('active_plugins', array()))))
{
	// plugin active load it.
	if(( @include_once WP_PLUGIN_DIR . '/http-requests-manager/http-requests-manager.php' ) == true)
	{
		define('VPHRM_MODE', 1);
	}
}
elseif(__DIR__ === WPMU_PLUGIN_DIR)
{
	// delete self because plugin is disabled without removing mu loader
	@unlink(__FILE__);
}
