<?php
class AnythingSetUpperAdminOptions {
	
	var $plugin_info;
	
	var $plugin_current_options;
	
	var $plugin_options_schema;
	
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
		$atsu_options_schema = array(
			// option_key	=>	[ 0:TYPE, 1:DEFAULT, 2:PLACEHOLDER|ITEMS_ARRAY, 3:VALIDATE_REGX|IS_MULTI, 4:LABEL, 5:HELPER_TEXT, 6:ENABLE, 7:EXTRA ]
			'option_title' => [ 'string', '', __('Please enter a option title', ATSU_PLUGIN_SLUG), '/^[a-z]{1}+[a-z0-9_\-]+?$/i', __('Setting option title', ATSU_PLUGIN_SLUG), __('In order to become a column name of the options table, you must be a unique name that does not same with other columns in using half-width alphanumeric and hyphen and underscore.', ATSU_PLUGIN_SLUG), true, 'require' ],
			'field_name' => [ 'string', 'test', __('Please enter a field name', ATSU_PLUGIN_SLUG), '/^[a-z]{1}+[a-z0-9_\-]+?$/i', __('Field name', ATSU_PLUGIN_SLUG), __('In order to become a system name of the setting, you must be a unique name that does not same with other settings in using half-width alphanumeric and hyphen and underscore.', ATSU_PLUGIN_SLUG), true, 'require' ],
			'field_order' => [ 'integer', 0, __('Please enter a field order', ATSU_PLUGIN_SLUG), '/^\d{,3}/', __('Field order', ATSU_PLUGIN_SLUG), __('I will be in the order of from the top of this setting item.', ATSU_PLUGIN_SLUG), true, ],
			'field_type' => [ 'select', 0, [
					'string' => __('Textfield', ATSU_PLUGIN_SLUG), 
					'password' => __('Password field', ATSU_PLUGIN_SLUG), 
					'integer' => __('Numerical field', ATSU_PLUGIN_SLUG), 
					'textarea' => __('Multi-line textfield', ATSU_PLUGIN_SLUG), 
					'boolean' => __('Boolean switching', ATSU_PLUGIN_SLUG), 
					'select_single' => __('Single-choice pull-down', ATSU_PLUGIN_SLUG), 
					'radio_single' => __('Single-choice radio-button', ATSU_PLUGIN_SLUG), 
					'select_multi' => __('Multiple-choice select-box', ATSU_PLUGIN_SLUG), 
					'check_multi' => __('Multiple-choice check-list', ATSU_PLUGIN_SLUG), 
					'hidden' => __('Hidden field', ATSU_PLUGIN_SLUG), 
				], 'single', __('The input field format', ATSU_PLUGIN_SLUG), __('Please specify the form format of the input field.', ATSU_PLUGIN_SLUG), true, 'require' ], 
			'default_string_value' => [ 'string', '', __('Please enter a default value', ATSU_PLUGIN_SLUG), null, __('Default value', ATSU_PLUGIN_SLUG), __('Please set only if you want to specify a default value.', ATSU_PLUGIN_SLUG), true, ], 
			'placeholder' => [ 'string', '', __('Please enter a placeholder text', ATSU_PLUGIN_SLUG), null, __('Placeholder text', ATSU_PLUGIN_SLUG), __('Designation of the placeholder text is optional.', ATSU_PLUGIN_SLUG), true, ],
			'item_value' => [ 'string', '', __('Please enter a item value', ATSU_PLUGIN_SLUG), '/^[a-z]{1}+[a-z0-9_\-]+?$/i', __('Item value', ATSU_PLUGIN_SLUG), __('To become a value to be handled by the system, please specify in the alphanumeric and hyphen and underscore. If you do not specify will be replaced by the index number of the item.', ATSU_PLUGIN_SLUG), true, ],
			'item_display_name' => [ 'string', '', __('Please enter a item display name', ATSU_PLUGIN_SLUG), null, __('Item display name', ATSU_PLUGIN_SLUG), __('It will be the name that will be displayed as choices.', ATSU_PLUGIN_SLUG), true, ],
			'validate_regx' => [ 'string', '', __('Please enter a regular expression for validation', ATSU_PLUGIN_SLUG), null, __('Regular expression for validation', ATSU_PLUGIN_SLUG), __('Setting of this item is optional.', ATSU_PLUGIN_SLUG), true, ],
			'is_multi' => [ 'radio', 0, ['single'=>__('Single-choice', ATSU_PLUGIN_SLUG), 'multi'=>__('Multiple-choice', ATSU_PLUGIN_SLUG)], null, __('Selection expression definition', ATSU_PLUGIN_SLUG), __('Please choose which allow multiple selection or a single item only.', ATSU_PLUGIN_SLUG), true, 'require' ],
			'label' => [ 'string', 'TEST', __('Please enter a display label', ATSU_PLUGIN_SLUG), null, __('Display label', ATSU_PLUGIN_SLUG), '', true, 'require' ],
			'helper_text' => [ 'string', '', __('Please enter a description as help', ATSU_PLUGIN_SLUG), null, __('Description as help', ATSU_PLUGIN_SLUG), __('Designation of the placeholder text is optional.', ATSU_PLUGIN_SLUG), true, ],
			'enable_field' => [ 'boolean', true, null, null, __('Enabling input field', ATSU_PLUGIN_SLUG), __('It does not output as a hidden field if you disable the input field.', ATSU_PLUGIN_SLUG), true, 'require' ],
			'unit' => [ 'string', '', __('Please enter a unit', ATSU_PLUGIN_SLUG), null, __('Unit name', ATSU_PLUGIN_SLUG), __('It is additionally displayed as a suffix of the input field, such as unit.', ATSU_PLUGIN_SLUG), true, ],
			'extra' => [ 'string', '', __('Please enter a extra setting', ATSU_PLUGIN_SLUG), null, __('Extra setting', ATSU_PLUGIN_SLUG), __('Designation of the placeholder text is optional.', ATSU_PLUGIN_SLUG), true, ],
		);
		$this->plugin_options_schema = $atsu_options_schema;
		
		$this->plugin_info = get_plugin_data(ATSU_PLUGIN_DIR .'/'. ATSU_PLUGIN_SLUG . '.php', false, false);
		$this->plugin_current_options = get_option(ATSU_PLUGIN_SLUG, []);
		$this->options_setting_action();
		$this->render_setting_options_page();
	}
	
	private function admin_notice() {
		global $atsu_message;
		if (!empty($atsu_message) && is_array($atsu_message)) {
			$echo_class = $atsu_message[0];
			$echo_message = $atsu_message[1];
			unset($atsu_message);
		} else {
			$echo_class = 'description';
			if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode']) && $_REQUEST['mode'] == 'detail') {
				$echo_message = __('You can define the setting pages in this management page as like menu position and items and input format of setting page that you want to create.', ATSU_PLUGIN_SLUG);
			} else {
				$echo_message = '';
			}
		}
?>
    <div class="<?php echo $echo_class; ?>">
      <p><?php echo $echo_message; ?></p>
    </div>
<?php
		// var_dump for debug
		var_dump($this->plugin_current_options);
	}
	
	private function render_setting_options_page() {
		$atsu_tabs = array(
			'list' => __('Registed options list', ATSU_PLUGIN_SLUG), 
			'detail' => __('Setting option detail', ATSU_PLUGIN_SLUG), 
		);
		$current_mode = isset($_REQUEST['mode']) && !empty($_REQUEST['mode']) ? $_GET['mode'] : 'list';
		$tab_contents = '<h3 class="nav-tab-wrapper">';
		foreach ($atsu_tabs as $tab_mode => $tab_name) {
			$classes = array('nav-tab');
			if ($current_mode == $tab_mode) 
				$classes[] = 'nav-tab-active';
			$tab_contents .= sprintf('<a class="%s" href="?page=%s&mode=%s">%s</a>', implode(' ', $classes), ATSU_PLUGIN_SLUG, $tab_mode, $tab_name);
		}
		$tab_contents .= '</h3>';
		$main_components = '';
		switch ($current_mode) {
			case 'list': 
				$table_class = 'table border-table';
				$action = 'add';
				$buttons_html = '';
				
				$main_components .= sprintf('<thead><tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr></thead>', __('Option Name', ATSU_PLUGIN_SLUG), __('Option Items', ATSU_PLUGIN_SLUG), __('Edit Detail', ATSU_PLUGIN_SLUG), __('Delete Options', ATSU_PLUGIN_SLUG));
				$main_components .= '<tbody>';
				foreach ($this->plugin_current_options['options'] as $option_name => $option_schema) {
					$main_components .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $option_name, count($option_schema), get_submit_button( __('Edit options', ATSU_PLUGIN_SLUG), 'secondary small', 'submit', false, array('id'=>'atsu-edit-' . $option_name, 'data-option-name'=>$option_name) ), get_submit_button( __('Delete options', ATSU_PLUGIN_SLUG), 'delete small', 'delete', true, array('data-option-name'=>$option_name) ));
				}
				
				$main_components .= sprintf('<tr><td>%s</td><td colspan="3">%s</td></tr>', $this->render_component_form_element('option_title', $this->plugin_options_schema['option_title'], array(), false), get_submit_button( __('Add new option', ATSU_PLUGIN_SLUG), 'primary large', 'submit', false, array('id'=>'atsu-add-new')) );
				$main_components .= '</tbody>';
				
				break;
			case 'detail': 
				$table_class = 'form-table';
				unset($this->plugin_options_schema['option_title']);
				$option_title = isset($_REQUEST['optnm']) && !empty($_REQUEST['optnm']) ? $_REQUEST['optnm'] : '';
				if (!empty($option_title) && array_key_exists($option_title, $this->plugin_current_options['options'])) {
					if (isset($option_title) && !empty($option_title)) {
						$current_options = $this->plugin_current_options['options'][$option_title];
						$action = 'update';
						$buttons_html = get_submit_button( __('Update options', ATSU_PLUGIN_SLUG), 'primary large', 'submit', false, array('id'=>'update') );
						
					} else {
						$current_options = array();
						$action = 'regist';
						$buttons_html = get_submit_button( __('Regist options', ATSU_PLUGIN_SLUG), 'primary large', 'submit', false, array('id'=>'regist') );
					}
					$buttons_html .= get_submit_button( __('Reset options', ATSU_PLUGIN_SLUG), 'delete large', 'submit', false, array('id'=>'reset') );
					foreach ($this->plugin_options_schema as $option_name => $schema) {
						$main_components .= $this->render_component_form_element($option_name, $schema, $current_options);
					}
				} else {
					global $atsu_message;
					$atsu_message = [ 'error', __('Option name to edit the details have not been specified.', ATSU_PLUGIN_SLUG) ];
					$buttons_html = '';
				}
				break;
		}
?>
  <div class="wrap">
    <h2><?php printf(__('%s Setting', ATSU_PLUGIN_SLUG), $this->plugin_info['Name']); ?></h2>
    <?php echo $tab_contents; ?>
    <?php $this->admin_notice(); ?>
    <form method="post" action="">
      <input type="hidden" name="action" value="<?php echo $action; ?>">
      <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce(ATSU_PLUGIN_SLUG .'_'. $action, '_wpnonce'); ?>">
      <table class="<?php echo $table_class; ?>">
        <?php echo $main_components; ?>
      </table>
      <?php echo $buttons_html; ?>
    </form>
  </div>
<?php
	}
	
	private function render_component_form_element($option_name, $schema, $current_options, $is_wrapper=true) {
		if (!isset($schema) || is_null($schema) || empty($schema) || !is_array($schema)) 
			return;
		$form_elements = '';
		if ($is_wrapper) {
			$form_elements_template = "\t<tr valign=\"top\"><th scope=\"row\">%s</th>\n\t\t<td>\n\t\t\t<fieldset>\n\t\t\t\t<legend class=\"screen-reader-text\"><span>%s</span></legend>\n\t\t\t\t%s\n\t\t\t\t<p class=\"description\">%s</p>\n\t\t\t</fieldset>\n\t\t</td>\n\t</tr>\n";
		} else {
			$form_elements_template = "<div data-helper-text=\"%s\">%s</div>\n";
		}
		$component_type = $schema[0];
		$label_text = $schema[4];
		$description_text = $schema[5];
		$enabled = isset($schema[6]) && !empty($schema[6]) && $schema[6] == 'false' ? false : true;
		$extra = isset($schema[7]) && !empty($schema[7]) ? $schema[7] : '';
		$html_instance = '';
		switch ($component_type) {
			case 'boolean': 
				$html_instance = '<select name="atsu_setting_options['. $option_name .']" id="'. $option_name .'">';
				$html_instance .= '<option '. (array_key_exists($option_name, $current_options) ? selected($current_options[$option_name], true, false) : '') .' value="true">'. __('Yes', ATSU_PLUGIN_SLUG) .'</option>';
				$html_instance .= '<option '. (array_key_exists($option_name, $current_options) ? selected($current_options[$option_name], false, false) : '') .' value="false">'. __('No', ATSU_PLUGIN_SLUG) .'</option>';
				$html_instance .= '</select>';
				break;
			case 'string': 
				$form_type = 'text';
				$form_size = 30;
				if ($schema[3] == '/^http(|s)\:\/\/.*/iU') {
					$form_type = 'url';
					$form_size = 80;
				} elseif ($schema[3] == '/(.*)@(.*)/iU') {
					$form_type = 'email';
					$form_size = 60;
				} elseif (preg_match('/\.\*/i', $schema[3])) {
					$form_size = 45;
				}
				$html_instance = '<input name="atsu_setting_options['. $option_name .']" id="'. $option_name .'" type="'. $form_type .'" size="'. $form_size .'" value="'. (array_key_exists($option_name, $current_options) ? $current_options[$option_name] : $schema[1]) .'" placeholder="'. $schema[2] .'">';
				break;
			case 'password': 
				$form_size = 20;
				if (preg_match('/\{(.*)\}/U', $schema[3], $matches)) {
					$strlen_ary = explode(',', $matches[1]);
					$values = count($strlen_ary);
					$maxlength = !empty($strlen_ary[$values-1]) ? intval($strlen_ary[$values-1]) : intval($strlen_ary[0]);
					$form_size = $maxlength > $form_size ? $maxlength : $form_size;
				}
				if (array_key_exists($option_name, $current_options)) {
					$password_value = ms_do_encrypt($current_options[$option_name], 'composite');
					if (!$password_value) {
						$password_value = '';
					} else {
						$display_strings = '';
						for ($i=0; $i<=strlen($password_value); $i++) {
							$display_strings .= '*';
						}
						$password_value = $display_strings;
					}
				} else {
					$password_value = '';
				}
				$html_instance = '<input name="astu_setting_options['. $option_name .']" id="'. $option_name .'" type="password" size="'. $form_size .'" value="'. $password_value .'" placeholder="'. $schema[2] .'">';
				break;
			case 'integer': 
				$form_size = 10;
				$default_value = isset($schema[1]) && !empty($schema[1]) ? intval($schema[1]) : 0;
				if (preg_match('/\{(.*)\}/U', $schema[3], $matches)) {
					$form_size = 1;
					$strlen_ary = explode(',', $matches[1]);
					$values = count($strlen_ary);
					$maxlength = !empty($strlen_ary[$values-1]) ? intval($strlen_ary[$values-1]) : intval($strlen_ary[0]);
					$form_size += $maxlength > $form_size ? $maxlength : $form_size;
				}
				$add_unit = !empty($extra) ? '<sub class="unit">'. $extra .'</sub>' : '';
				$html_instance = '<input name="atsu_setting_options['. $option_name .']" id="'. $option_name .'" type="number" size="'. $form_size .'" style="width: '. $form_size .'em;" value="'. (array_key_exists($option_name, $current_options) ? $current_options[$option_name] : $default_value) .'">' . $add_unit;
				break;
			case 'select': 
				$box_type = $schema[3];
				$default_index = is_string($schema[1]) ? explode(',', $schema[1]) : intval($schema[1]);
				$attr = $box_type == 'multi' ? 'multiple' : '';
				$box_size = $box_type == 'multi' ? 'size="'. count($schema[2]) .'"' : '';
				$html_instance = '<select name="astu_setting_options['. $option_name .']'. ($box_type == 'multi' ? '[]' : '') .'" id="'. $option_name .'" '. $box_size .' '. $attr .' style="width: %dem;">';
				$index_num = 0;
				$max_strlen_value = 1;
				foreach ($schema[2] as $index_value => $display_value) {
					$max_strlen_value = $max_strlen_value < strlen($display_value) ? strlen($display_value) : $max_strlen_value;
					if ($box_type == 'single') {
						$html_instance .= '<option '. (array_key_exists($option_name, $current_options) ? selected($current_options[$option_name], $index_value, false) : selected($default_index, $index_num, false)) .' value="'. $index_value .'">'. $display_value .'</option>';
					} else {
						if (array_key_exists($option_name, $current_options)) {
							foreach (explode(',', $current_options[$option_name]) as $current_value) {
								if ($current_value == $index_value) break;
							}
							$html_instance .= '<option '. selected($current_value, $index_value, false) .' value="'. $index_value .'">'. $display_value .'</option>';
						} else {
							foreach ($default_index as $current_value) {
								if ($current_value == $index_num) break;
							}
							$html_instance .= '<option '. selected($current_value, $index_num, false) .' value="'. $index_value .'">'. $display_value .'</option>';
						}
					}
					$index_num++;
				}
				$html_instance .= '</select>';
				$html_instance = sprintf($html_instance, $max_strlen_value+1);
				break;
			case 'radio': 
				$default_index = intval($schema[1]);
				$html_instance = '';
				$index_num = 0;
				foreach ($schema[2] as $index_value => $display_value) {
					$add_style = $index_num > 0 ? 'style="margin-left: 1em;"' : '';
					$html_instance .= '<input type="radio" name="atsu_setting_options['. $option_name .']" id="'. $option_name .'-'. $index_num .'" '. (array_key_exists($option_name, $current_options) ? checked($current_options[$option_name], $index_value, false) : checked($default_index, $index_num, false)) .' value="'. $index_value .'" '. $add_style .'><label for="'. $option_name .'-'. $index_num .'">'. $display_value .'</label>';
					$index_num++;
				}
				break;
			case 'check': 
				$default_index = explode(',', $schema[1]);
				$html_instance = '';
				$index_num = 0;
				foreach ($schema[2] as $index_value => $display_value) {
					$add_style = $index_num > 0 ? 'style="margin-left: 1em;"' : '';
					$add_break = '';
					if (isset($schema[7]) && !empty($schema[7]) && intval($schema[7]) > 0) {
						if (($index_num + 1)%intval($schema[7]) == 0) {
							$add_break = '<br>';
							$add_style = intval($schema[7]) == 1 ? '' : $add_style;
						} else if (($index_num + 1)%intval($schema[7]) == 1) {
							$add_style = '';
						}
					}
					
					if (array_key_exists($option_name, $current_options)) {
						foreach (explode(',', $current_options[$option_name]) as $current_value) {
							if ($current_value == $index_value) break;
						}
						$attr_checked = checked($current_value, $index_value, false);
					} else {
						foreach ($default_index as $current_value) {
							if (intval($current_value) == $index_num) break;
						}
						$attr_checked = checked($current_value, $index_num, false);
					}
					$html_instance .= '<input type="checkbox" name="atsu_setting_options['. $option_name .'][]" id="'. $option_name .'-'. $index_num .'" '. $attr_checked .' value="'. $index_value .'" '. $add_style .'><label for="'. $option_name .'-'. $index_num .'">'. $display_value .'</label>' . $add_break;
					$index_num++;
				}
				break;
			default: 
				break;
		}
		if (!empty($html_instance)) {
			if ($is_wrapper) {
				$form_elements .= sprintf($form_elements_template, $label_text, $label_text, $html_instance, $description_text);
			} else {
				$form_elements .= sprintf($form_elements_template, $description_text, $html_instance);
			}
		}
		return $form_elements;
	}
	
	private function options_setting_action() {
		if (!preg_match('/page\='. ATSU_PLUGIN_SLUG .'/i', $_SERVER['REQUEST_URI'])) {
			return;
		}
		if (!isset($_POST['action']) || !isset($_POST['_wpnonce']) || !isset($_POST['atsu_setting_options']) || empty($_POST['atsu_setting_options'])) {
			return;
		}
		if (!wp_verify_nonce($_POST['_wpnonce'], ATSU_PLUGIN_SLUG . '_' . $_POST['action'])) {
			return;
		}
		global $atsu_message;
		$options_list = $this->plugin_current_options['options'];
//var_dump($options_list);
		$set_options = $_POST['atsu_setting_options'];
//var_dump($set_options);
		$mode = (isset($_POST['submit']) && !empty($_POST['submit'])) ? $_POST['action'] : '';
		$status = array();
		$store_options = array();
		
		switch($mode) { // updated after options value validate
			case 'add': 
				foreach ($set_options as $option_name => $option_value) {
					list($message, $fixed_value) = $this->validate_option_values($option_name, $option_value);
					$store_options[$option_name] = $fixed_value;
					if (!empty($message)) 
						$status[] = $message;
				}
				if (array_key_exists($store_options['option_title'], $options_list)) 
					$status[] = __('The option name can not be added because it already exists.', ATSU_PLUGIN_SLUG);
				
				// save or error
				if (empty($status)) {
					$options_list[$store_options['option_title']] = array();
					$this->plugin_current_options['options'] = $options_list;
					update_option(ATSU_PLUGIN_SLUG, $this->plugin_current_options);
					$atsu_message = [ 'updated', sprintf(__('The options of <strong>%s</strong> added new.', ATSU_PLUGIN_SLUG), $store_options['option_title']) ];
				} else {
					$atsu_message = [ 'error', implode("<br>\n", $status) ];
				}
				break;
			case 'update': 
				foreach ($set_options as $option_name => $option_value) {
					list($status[], $fixed_value) = $this->validate_option_values($option_name, $option_value);
					$store_options[$option_name] = $fixed_value;
				}
				
				// save or error
				if (empty($status)) {
					if (array_key_exists('option_title', $store_options)) {
						$current_option_name = $store_options['option_title'];
						unset($store_options['option_title']);
						if (array_key_exists($current_option_name, $options_list)) {
							$atsu_message = [ 'updated', sprintf(__('The options of <strong>%s</strong> have updated.', ATSU_PLUGIN_SLUG), $current_option_name) ];
						} else {
							//$options_list[] = $store_options;
							$atsu_message = [ 'updated', sprintf(__('The options of <strong>%s</strong> added new.', ATSU_PLUGIN_SLUG), $current_option_name) ];
						}
						$options_list[$current_option_name] = $store_options;
						$this->plugin_current_options['options'] = $options_list;
						update_option(ATSU_PLUGIN_SLUG, $this->plugin_current_options);
					} else {
						$atsu_message = [ 'error', __('The option settings to save was none.', ATSU_PLUGIN_SLUG) ];
					}
				} else {
					$atsu_message = [ 'error', implode("<br>\n", $status) ];
				}
				break;
			case 'reset': 
				delete_option(ATSU_PLUGIN_SLUG);
				break;
			default: 
				return;
				break;
		}
		
	}
	
	private function validate_option_values($option_name, $option_value, $options_schema=null) {
		if (empty($options_schema)) 
			$options_schema=$this->plugin_options_schema;
		$message = '';
		$fixed_value = '';
		if (!array_key_exists($option_name, $options_schema)) 
			continue;
		$is_require = (isset($options_schema[$option_name][7]) && !empty($options_schema[$option_name][7]) && $options_schema[$option_name][7] == 'require') ? true : false;
		if ($is_require && empty($option_value)) {
			$message = sprintf(__('<strong>%s</strong> is empty data! This item must be sure to enter.', ATSU_PLUGIN_SLUG), $options_schema[$option_name][4]);
		}
		if (empty($message)) {
			$data_type = $options_schema[$option_name][0];
			if ($data_type == 'boolean') {
				$fixed_value = $option_value == 'true' ? true : false;
			}
			if ($data_type == 'string') {
				$regx = $options_schema[$option_name][3];
				if (!empty($option_value) && !empty($regx)) {
					if (!preg_match($regx, $option_value)) {
						$message = sprintf(__('<strong>%s</strong> is invalid data!', ATSU_PLUGIN_SLUG), $options_schema[$option_name][4]);
					}
				}
				$fixed_value = $option_value;
			}
			if ($data_type == 'integer') {
				$fixed_value = intval($option_value);
			}
			if ($data_type == 'password') {
				$fixed_value = ms_do_encrypt($option_value, 'encrypt');
			}
			if ($data_type == 'select') {
				$box_type = $options_schema[$option_name][3];
				if ($box_type == 'single') {
					$fixed_value = $option_value;
				} else {
					$fixed_value = implode(',', $option_value);
				}
			}
			if ($data_type == 'radio') {
				$fixed_value = $option_value;
			}
			if ($data_type == 'check') {
				$fixed_value = implode(',', $option_value);
			}
		}
		return array($message, $fixed_value);
	}
	
}