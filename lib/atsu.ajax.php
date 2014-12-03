<?php
class AnythingSetUpper_Ajax {
	
	private static $instance;
	
	public static function instance() {
		if (isset(self::$instance)) 
			return self::$instance;
		
		self::$instance = new AnythingSetUpper_Ajax;
		self::$instance->init();
		return self::$instance;
	}
	
	private function __construct() {
		// Do nothing
	}
	
	protected function init() {
		add_action('wp_ajax_atsu_ajax_handler', array(&$this, 'atsu_ajax_handler'));
	}
	
	public function atsu_ajax_handler() {
		if (!wp_verify_nonce($_POST['nonce'], ATSU_PLUGIN_SLUG . '_submit')) {
			$response = __('Invalid Access!', ATSU_PLUGIN_SLUG);
		} else {
			global $atsu;
			$handler = isset($_POST['handler']) ? $_POST['handler'] : '';
			if (empty($handler)) {
				$response = __('Invalid Access!', ATSU_PLUGIN_SLUG);
			} else {
				if ($handler == 'edit_item') {
					$option_name = isset($_POST['option_name']) ? $_POST['option_name'] : '';
					$item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
					$item_order = isset($_POST['item_order']) ? intval($_POST['item_order']) : 0;
					$atsu_option = get_option(ATSU_PLUGIN_SLUG, []);
					$options_lists = $atsu_option['options'];
					if (array_key_exists($option_name, $options_lists)) {
						if (array_key_exists($item_name, $options_lists[$option_name])) {
							$return_data = $options_lists[$option_name][$item_name];
							$return_data['field_name'] = $item_name;
							$return_data['field_order'] = $item_order;
							$return_data['button_label'] = __('Update Option Item', ATSU_PLUGIN_SLUG);
						}
					}
					if (isset($return_data) && !empty($return_data)) {
						$response = json_encode($return_data);
					} else {
						$response = __('Target Item None!', ATSU_PLUGIN_SLUG);
					}
				}
			}
		}
		if (isset($response) && !empty($response)) {
			die( $response );
		} else {
			die( __('There was no process for handling.', ATSU_PLUGIN_SLUG) );
		}
	}
}