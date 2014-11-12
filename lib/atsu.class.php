<?php
class AnythingSetUpper {
	
	/**
	 * plugin version
	 * @var string
	 */
	var $version = ATSU_PLUGIN_VERSION;
	
	/**
	 * database version
	 * @var float
	 */
	var $db_version = ATSU_DB_VERSION;
	
	/**
	 * plugins directory path
	 * @var string
	 */
	var $dir;
	
	/**
	 * plugins directory url
	 * @var string
	 */
	var $dir_url;
	
	/**
	 * domain name for i18n
	 * @var string
	 */
	const DOMAIN = ATSU_PLUGIN_SLUG;
	
	/**
	 * information message on admin panel
	 * @var array
	 */
	var $message = array();
	
	/**
	 * error message on admin panel
	 * @var array
	 */
	var $error = array();
	
	/**
	 * constructor for PHP5
	 * @return array
	 */
	function __construct() {
		global $wpdb;
		
		foreach (explode(ATSU_DS, dirname(__FILE__)) as $dir_name) {
			$this->dir .= (!empty($dir_name)) ? ATSU_DS . $dir_name : '';
			if (self::DOMAIN == $dir_name) 
				break;
		}
		$path_list = explode('/', plugin_basename(__FILE__));
		$this->dir_url = @plugin_dir_url() . array_shift($path_list);
		
		load_plugin_textdomain(self::DOMAIN, false, basename($this->dir) . ATSU_DS . 'langs');
		
		$this->options = get_option(self::DOMAIN);
		
		if ($this->options['plugin_version'] != $this->version) {
			if (version_compare($this->version, $this->options['plugin_version']) > 0) {
				$this->activate();
			}
		}
		if ($this->options['db_version'] != $this->db_version) {
			if (version_compare($this->db_version, $this->options['db_version']) > 0) {
				$this->activate();
			}
		}
		
		add_filter('plugin_action_links', array($this, 'add_action_links'), 10, 2);
		add_action('admin_menu', array($this, 'create_admin'));
	}
	
	/**
	 * constructor for PHP4
	 * @return void
	 */
	function AnythingSetUpper() {
		if (empty($this->options)) 
			$this->__construct();
	}
	
	/**
	 * check version and table structure on plugin activation
	 * @return void
	 */
	function activate(){
		global $wpdb;
		$default_options = array(
			'plugin_version' => $this->version, 
			'db_version' => $this->db_version, 
			'options' => array(
				//'{option_name}' => array( {option_data} ), 
			), 
		);
		
		$pre_option = get_option(self::DOMAIN);
		if ($pre_option) {
			$this->options = $pre_option;
		} else {
			$this->options = $default_options;
		}
		if (array_key_exists('plugin_version', $this->options)) {
			if ($this->options['plugin_version'] != $default_options['plugin_version']) {
				if (version_compare($default_options['plugin_version'], $this->options['plugin_version']) > 0) {
					if (!empty($this->options['options'])) {
						foreach ($this->options['options'] as $option_name => $option_data) {
							if (!array_key_exists($option_name, $default_options['opions'])) 
								$default_options['options'][$option_name] = $option_data;
						}
					}
					$this->options = $default_options;
				}
			}
		} else {
			$this->options = $default_options;
		}
		
		if (get_option(self::DOMAIN) !== false) {
			update_option(self::DOMAIN, $this->options);
		} else {
			add_option(self::DOMAIN, $this->options, '', 'no');
		}
	}
	
	/**
	 * plugin deactivation
	 * @return void
	 */
	function deactivation(){
		$revision_option = self::DOMAIN . '_previous_revision_backup';
		if (get_option($revision_option) !== false) {
			update_option($revision_option, $this->options);
		} else {
			add_option($revision_option, $this->options, '', 'no');
		}
		//$this->log_info('Anything Set Upper plugin deactivated.');
	}
	
	/**
	 * append action links to this plugin on list page
	 * @return array
	 */
	function add_action_links($links, $file){
		if ($file == self::DOMAIN . '/anything-set-upper.php') {
			$links[] = '<a href="'. admin_url('options-general.php?page=' . self::DOMAIN) .'">'. __('Settings', self::DOMAIN) .'</a>';
		}
		return $links;
	}
	
	/**
	 * create admin panel
	 * @return void
	 */
	function create_admin(){
		$atsu_plugin_page = add_options_page(__('Anything Set Upper Option: ', self::DOMAIN), __('Anything Set Upper', self::DOMAIN), 'manage_options', self::DOMAIN, array($this, 'admin_controller'), plugin_dir_url(__FILE__) . 'assets/img/undo.png');
		wp_parse_str($_SERVER['QUERY_STRING'], $this->query);
		add_action("admin_head-$atsu_plugin_page", array($this, 'admin_header'));
		add_action("admin_enqueue_scripts", array($this, 'admin_assets'));
		add_action("admin_footer-$atsu_plugin_page", array($this, 'admin_footer'));
		add_action('admin_notice', array($this, 'admin_notice'));
	}
	
	/**
	 * render admin panel from template
	 * @return void
	 */
	function admin_controller(){
		require_once ATSU_PLUGIN_LIB_DIR . ATSU_DS . 'atsu.admin.php';
		
		AnythingSetUpperAdminOptions::instance();
	}
	
		/**
	 * load header for Anything Set Upper setting options in admin panel
	 * @return void
	 */
	function admin_header(){
		if (array_key_exists('page', $this->query) && $this->query['page'] == self::DOMAIN) {
			//
		}
	}
	
	/**
	 * load assets for Anything Set Upper setting options in admin panel
	 * @return void
	 */
	function admin_assets(){
		if (!is_admin()) 
			return;
		if (array_key_exists('page', $this->query) && $this->query['page'] == self::DOMAIN) {
			$cdbt_admin_assets = array(
				'styles' => array(
					'atsu-admin-style' => array( $this->dir_url . '/assets/atsu-style.css', true, $this->version, 'all' ), 
				), 
				'scripts' => array(
					'jquery-ui-core' => null, 
					'jquery-ui-accordion' => null, 
//					'jquery-ui-widget' => null, 
//					'jquery-ui-mouse' => null, 
//					'jquery-ui-position' => null, 
//					'jquery-ui-sortable' => null, 
//					'jquery-ui-autocomplete' => null, 
					'jquery-ui-tooltip' => null, 
					'atsu-admin-script' => array( $this->dir_url . '/assets/atsu-script.js', array(), null, false ), 
				)
			);
			foreach ($cdbt_admin_assets as $asset_type => $asset_instance) {
				if ($asset_type == 'styles') {
					foreach ($asset_instance as $asset_name => $asset_values) {
						wp_enqueue_style($asset_name, $asset_values[0], $asset_values[1], $asset_values[2], $asset_values[3]);
					}
				}
				if ($asset_type == 'scripts') {
					foreach ($asset_instance as $asset_name => $asset_values) {
						if (!empty($asset_values)) {
							wp_register_script($asset_name, $asset_values[0], $asset_values[1], $asset_values[2], $asset_values[3]);
						}
						wp_enqueue_script($asset_name);
					}
				}
			}
		}
	}
	
	/**
	 * load footer for Anything Set Upper setting options in admin panel
	 * @return void
	 */
	function admin_footer(){
		if (array_key_exists('page', $this->query) && $this->query['page'] == self::DOMAIN) {
			//
		}
	}
	
	/**
	 * show notice on Anything Set Upper setting options in admin panel
	 * @return void
	 */
	function admin_notice(){
		if (array_key_exists('page', $this->query) && $this->query['page'] == self::DOMAIN) {
			$notice_base = '<div class="%s"><ul>%s</ul></div>';
			if (!empty($this->error)) {
				$notice_list = '';
				foreach ($this->error as $error) {
					$notice_list .= '<li>' . $error . '</li>';
				}
				printf($notice_base, 'error', $notice_list);
			}
			if (!empty($this->message)) {
				$notice_list = '';
				foreach ($this->message as $message) {
					$notice_list .= '<li>' . $message . '</li>';
				}
				printf($notice_base, 'updated', $notice_list);
			}
		}
	}
	
}