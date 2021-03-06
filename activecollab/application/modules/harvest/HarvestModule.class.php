<?php


class HarvestModule extends Module
{
	
	/**
	 * Plain module name
	 * @var string
	 */
	var $name = 'harvest';
	
	/**
	 * Is system module flag
	 * @var boolean
	 */
	var $is_system = false;
	
	/**
	* Module version
	*
	* @var string
	*/
	var $version = '2.0';
	
	
	// ---------------------------------------------------
	//  Events and Routes
	// ---------------------------------------------------
	
	/**
	 * Define module routes
	 *
	 * @param	Router $router
	 * @return	void
	 */
	function defineRoutes(&$router)
	{
		$router->map('project_harvest', 'projects/:project_id/harvest', array('controller' => 'project_harvest', 'action' => 'index'), array('project_id' => '\d+'));
		$router->map('project_time_harvest_submit', 'projects/:project_id/time/harvest/submit', array('controller' => 'project_time_harvest', 'action' => 'submit'), array('project_id' => '\d+'));
		$router->map('project_time_harvest_sync', 'projects/:project_id/time/harvest/sync', array('controller' => 'project_time_harvest', 'action' => 'sync'), array('project_id' => '\d+'));
		$router->map('admin_harvest', 'admin/harvest', array('controller' => 'admin_harvest', 'action' => 'index'));
	}
	
	
	/**
	 * Define event handlers
	 *
	 * @param	EventsManager $events
	 * @return	void
	 */
	function defineHandlers(&$events)
	{
		$events->listen('on_build_menu', 'on_build_menu');
		$events->listen('on_admin_sections', 'on_admin_sections');
		$events->listen('on_system_permissions', 'on_system_permissions');
		$events->listen('on_project_options', 'on_project_options');
		$events->listen('on_project_created', 'on_project_created');
		$events->listen('on_project_updated', 'on_project_updated');
		$events->listen('on_project_completed', 'on_project_completed');
		$events->listen('on_project_opened', 'on_project_opened');
		$events->listen('on_project_user_added', 'on_project_user_added');
		$events->listen('on_project_user_removed', 'on_project_user_removed');
		$events->listen('on_daily', 'on_daily');
		$events->listen('on_hourly', 'on_hourly');
		$events->listen('on_frequently', 'on_frequently');
	}
	
	
	// ---------------------------------------------------
	//  (Un)Install
	// ---------------------------------------------------
	
	
	/**
	 * Can this module be installed or not
	 *
	 * @param	array $log
	 * @return	boolean
	 */
	function canBeInstalled(&$log)
	{
		if (class_exists('DOMDocument'))
		{
			$log[] = lang('OK: DOM extension loaded');
			
			if (extension_loaded('curl') && function_exists('curl_init'))
			{
				$log[] = lang('OK: CURL extension loaded');
				
				return true;
			}
			else
			{
				$log[] = lang('This module requires CURL PHP extension to be installed. Read more about CURL extension in PHP documentation: http://www.php.net/manual/en/book.curl.php');
			
				return false;
			}
		}
		else
		{
			$log[] = lang('This module requires DOM PHP extension to be installed. Read more about DOM extension in PHP documentation: http://www.php.net/manual/en/book.dom.php');
			
			return false;
		}
	}
	
	
	/**
	 * Install this module
	 *
	 * @param	void
	 * @return	boolean
	 */
	function install()
	{
		// system config options
		$this->addConfigOption('harvest_account', SYSTEM_CONFIG_OPTION, null);
		$this->addConfigOption('harvest_user', SYSTEM_CONFIG_OPTION, null);
		$this->addConfigOption('harvest_pass', SYSTEM_CONFIG_OPTION, null);
		$this->addConfigOption('harvest_create_project', SYSTEM_CONFIG_OPTION, false);
		$this->addConfigOption('harvest_create_client', SYSTEM_CONFIG_OPTION, false);
		$this->addConfigOption('harvest_sync_interval', SYSTEM_CONFIG_OPTION, 0);

		// project config options
		$this->addConfigOption('harvest_project', PROJECT_CONFIG_OPTION, 0);
		
		return parent::install();
	}
	
	
	/**
	 * Uninstall this module
	 *
	 * @param	void
	 * @return	boolean
	 */
	function uninstall()
	{
		return parent::uninstall();
	}
	
	
	/**
	 * Get module display name
	 *
	 * @return	string
	 */
	function getDisplayName()
	{
		return lang('Harvest');
	}
	
	
	/**
	 * Return module description
	 *
	 * @param	void
	 * @return	string
	 */
	function getDescription()
	{
		return lang('Integrates time tracking with Harvest');
	}
}

