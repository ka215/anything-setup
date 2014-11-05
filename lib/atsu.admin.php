<?php
class AnythingSetUpperAdminOptions {
	
	private static $instance;
	
	public static function instance() {
		if (isset(self::$instance)) 
			return self::$instance;
		
		self::$instance = new AnythingSetUpperAdminOptions;
		self::$instance->init();
		return self::$instance;
	}
	
	private function __costruct() {
		// Do nothing
	}
	
	protected function init() {
		//add_action('wp_ajax_cdbt_ajax_core', array(&$this, 'cdbt_ajax_core'));
		echo 'TEST';
	}
	
}