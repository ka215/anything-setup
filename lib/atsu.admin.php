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
			'option_title' => [ 'string', '', __('Please enter option title', ATSU_PLUGIN_SLUG), '^[a-z]{1}+[a-z0-9_\-]+?$', __('Setting Option Name', ATSU_PLUGIN_SLUG), __('In order to become a column name of the options table, you must be a unique name that does not same with other columns in using half-width alphanumeric and hyphen and underscore.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ],
			'field_name' => [ 'string', '', __('Please enter field name', ATSU_PLUGIN_SLUG), '^[a-z]{1}+[a-z0-9_\-]+?$', __('Field Name', ATSU_PLUGIN_SLUG), __('In order to become a system name of the setting, you must be a unique name that does not same with other settings in using half-width alphanumeric and hyphen and underscore.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ],
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
				], 'single', __('The input field format', ATSU_PLUGIN_SLUG), __('Please specify the form format of the input field.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ], 
			'label' => [ 'string', '', __('Please enter display label', ATSU_PLUGIN_SLUG), null, __('Display Label', ATSU_PLUGIN_SLUG), __('It will be the title of the item that appears to the left of the input field.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ],
			'enable_field' => [ 'boolean', true, null, null, __('Enabling Input Field', ATSU_PLUGIN_SLUG), __('It does not output as a hidden field if you disable the input field.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ],
//			'is_multi' => [ 'radio', 0, ['single'=>__('Single-choice', ATSU_PLUGIN_SLUG), 'multi'=>__('Multiple-choice', ATSU_PLUGIN_SLUG)], null, __('Selection Expression Definition', ATSU_PLUGIN_SLUG), __('Please choose which allow multiple selection or a single item only.', ATSU_PLUGIN_SLUG), true, ['require'=>true] ],
			'extra' => [ 'check', '', [ 'require'=>__('Input required fields', ATSU_PLUGIN_SLUG), 'encrypt'=>__('Encryption of the input value', ATSU_PLUGIN_SLUG) ], 'multi', __('Advanced Settings', ATSU_PLUGIN_SLUG), __('Please check the item you want.', ATSU_PLUGIN_SLUG), true, ],
			'field_order' => [ 'integer', 0, __('Please enter field order', ATSU_PLUGIN_SLUG), '^\d{,3}', __('Field Order', ATSU_PLUGIN_SLUG), __('I will be in the order of from the top of this setting item.', ATSU_PLUGIN_SLUG), true, ],
			'item_value' => [ 'string', '', __('Please enter item value(s)', ATSU_PLUGIN_SLUG), '^[a-z0-9_\-]+?$', __('Item Value(s)', ATSU_PLUGIN_SLUG), __('To become a value to be handled by the system, please specify in the alphanumeric and hyphen and underscore. If you do not specify will be replaced by the index number of the item.', ATSU_PLUGIN_SLUG), true, ],
			'item_display_name' => [ 'string', '', __('Please enter item display name(s)', ATSU_PLUGIN_SLUG), null, __('Item Display Name(s)', ATSU_PLUGIN_SLUG), __('It will be the name that will be displayed as choices.', ATSU_PLUGIN_SLUG), true, ],
			'default_string_value' => [ 'string', '', __('Please enter default value(s)', ATSU_PLUGIN_SLUG), null, __('Default Value(s)', ATSU_PLUGIN_SLUG), __('Please set only if you want to specify default value(s).', ATSU_PLUGIN_SLUG), true, ], 
			'validate_regx' => [ 'string', '', __('Please enter regular expression for validation', ATSU_PLUGIN_SLUG), null, __('Regular Expression for Validation', ATSU_PLUGIN_SLUG), __('Input values is validated what conform to this regular expression. If you chose as general regular expression pattern from the preset, UI of the input field will be optimized.', ATSU_PLUGIN_SLUG), true, ],
			'placeholder' => [ 'string', '', __('Please enter placeholder text', ATSU_PLUGIN_SLUG), null, __('Placeholder Text', ATSU_PLUGIN_SLUG), __('Designation of the placeholder text is optional.', ATSU_PLUGIN_SLUG), true, ],
			'helper_text' => [ 'textarea', '', __('Please enter description as help', ATSU_PLUGIN_SLUG), null, __('Description as Help', ATSU_PLUGIN_SLUG), __('Designation of the placeholder text is optional.', ATSU_PLUGIN_SLUG), true, ['cols'=>50, 'rows'=>2] ],
			'unit' => [ 'string', '', __('Please enter unit', ATSU_PLUGIN_SLUG), null, __('Unit Name', ATSU_PLUGIN_SLUG), __('It is additionally displayed as a suffix of the input field, such as unit.', ATSU_PLUGIN_SLUG), true, ['field_size'=>10] ],
			'field_size' => [ 'integer', 30, __('Please enter field size', ATSU_PLUGIN_SLUG), '^\d{,3}', __('Field Size', ATSU_PLUGIN_SLUG), __('This number is the width of the input field.', ATSU_PLUGIN_SLUG), true, ],
			'cols' => [ 'integer', 45, __('Please enter cols size', ATSU_PLUGIN_SLUG), '^\d{,3}', __('Cols Size', ATSU_PLUGIN_SLUG), __('It is the value of the cols attribute is the width of the textarea.', ATSU_PLUGIN_SLUG), true, ],
			'rows' => [ 'integer', 4, __('Please enter rows size', ATSU_PLUGIN_SLUG), '^\d{,3}', __('Rows Size', ATSU_PLUGIN_SLUG), __('This is the number of rows that becomes the height of the input field.', ATSU_PLUGIN_SLUG), true, ],
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
			if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode']) && $_REQUEST['mode'] == 'configure') {
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
##		var_dump($this->plugin_current_options);
##		var_dump(esc_js(esc_url_raw(admin_url('admin-ajax.php', is_ssl() ? 'https' : 'http'))));
	}
	
	private function render_setting_options_page() {
		$atsu_tabs = array(
			'list' => __('Management of registration options', ATSU_PLUGIN_SLUG), 
			'configure' => __('Editing of configuration items', ATSU_PLUGIN_SLUG), 
		);
		$current_mode = isset($_REQUEST['mode']) && !empty($_REQUEST['mode']) ? $_REQUEST['mode'] : 'list';
		$tab_contents = '<h3 class="nav-tab-wrapper">';
		foreach ($atsu_tabs as $tab_mode => $tab_name) {
			$classes = array('nav-tab');
			if ($current_mode == $tab_mode) 
				$classes[] = 'nav-tab-active';
			$tab_contents .= sprintf('<a class="%s" href="?page=%s&mode=%s">%s</a>', implode(' ', $classes), ATSU_PLUGIN_SLUG, $tab_mode, $tab_name);
		}
		$tab_contents .= '</h3>';
		$before_main_contents = '';
		$main_contents = '';
		$after_main_contents = '';
		switch ($current_mode) {
			case 'list': 
				$main_tag = 'table';
				$parent_class = 'table table-bordered table-hover';
				$action = 'add_option';
				$buttons_html = '';
				
				$main_contents .= sprintf('<thead><tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr></thead>', __('Option Name', ATSU_PLUGIN_SLUG), __('Option Items', ATSU_PLUGIN_SLUG), __('Edit Detail', ATSU_PLUGIN_SLUG), __('Delete Options', ATSU_PLUGIN_SLUG));
				$main_contents .= '<tbody>';
				foreach ($this->plugin_current_options['options'] as $option_name => $option_schema) {
					$main_contents .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $option_name, count($option_schema), get_submit_button( __('Edit options', ATSU_PLUGIN_SLUG), 'secondary small', 'edit_option', false, array('id'=>'atsu-edit-' . $option_name, 'data-option-name'=>$option_name) ), get_submit_button( __('Delete options', ATSU_PLUGIN_SLUG), 'secondary small', 'delete_option', false, array('id'=>'atsu-delete-' . $option_name, 'data-option-name'=>$option_name) ));
				}
				$main_contents .= '</tbody>';
				$after_main_contents .= sprintf('<div class="brick-controller">%s %s</div>', $this->render_component_form_element('option_title', $this->plugin_options_schema['option_title'], array(), false), get_submit_button( __('Add new option', ATSU_PLUGIN_SLUG), 'primary large pull-left', 'add_option', false, array('id'=>'atsu-add-new')) );
				
				break;
			case 'configure': 
				$main_tag = 'ul';
				$parent_class = 'configure-items';
				unset($this->plugin_options_schema['option_title']);
				$option_title = isset($_REQUEST['optnm']) && !empty($_REQUEST['optnm']) ? $_REQUEST['optnm'] : '';
				if (!empty($option_title) && array_key_exists($option_title, $this->plugin_current_options['options'])) {
					$current_options = $this->plugin_current_options['options'][$option_title];
					$options_values = get_option($option_title, array());
					
					$before_main_contents .= "<div id=\"atsu-collapse\" class=\"accordion-list\">\n";
					$before_main_contents .= '<h4 id="atsu-vsp-head" class="accordion-toggle"><div class="accordion-icon"><span class="dashicons dashicons-arrow-right-alt2"></span></div> '. __('Verifying Setting Page (preview)', ATSU_PLUGIN_SLUG) ."<strong class=\"highlight\"></strong></h4>\n";
					$before_main_contents .= "<div id=\"atsu-vsp-body\" class=\"accordion-content\">\n";
					
					$vsp_content = "<table id=\"sortable\" class=\"table table-nobordered table-hover\">\n";
					$order_index = 1;
					foreach ($current_options as $option_name => $schema) {
						$base_tr = $this->render_component_form_element($option_name, $schema, $options_values, 'table');
						$add_ctrl = '<td class="ctrl-edit"><button id="atsu-edit_item-iconbtn-'. $option_name .'" class="button button-default button-small" data-option-item="'. $option_name .'" data-order-index="'. $order_index .'"><span class="dashicons dashicons-edit"></span></td>';
						$add_ctrl .= '<td class="ctrl-delete"><button id="atsu-delete_item-iconbtn-'. $option_name .'" class="button button-default button-small" data-option-item="'. $option_name .'" data-order-index="'. $order_index .'"><span class="dashicons dashicons-no-alt"></span></button></td>';
						$vsp_content .= sprintf(str_replace('</tr>', '%s</tr>', $base_tr), $add_ctrl);
						$order_index++;
					}
					$vsp_content .= "</table>\n";
					$vsp_content .= "<div>". get_submit_button( __('Save Change', ATSU_PLUGIN_SLUG), 'primary large', 'update_order', false, array('id'=>'atsu-update_order-' . $option_title, 'data-option-name'=>$option_title) ) ."</div>\n";
					
					$before_main_contents .= $vsp_content . "</div>\n";
					$before_main_contents .= '<h4 id="atsu-poic-head" class="accordion-toggle"><div class="accordion-icon"><span class="dashicons dashicons-arrow-right-alt2"></span></div> '. __('Preview Option Item Component', ATSU_PLUGIN_SLUG) ."<strong class=\"highlight\"></strong></h4>\n";
					$before_main_contents .= "<div id=\"atsu-poic-body\" class=\"accordion-content\"></div>\n";
					$before_main_contents .= '<h4 id="atsu-cie-head" class="accordion-toggle"><div class="accordion-icon"><span class="dashicons dashicons-arrow-right-alt2"></span></div> '. __('Option Name', ATSU_PLUGIN_SLUG) .': <strong class="highlight">'. $option_title .'</strong> '. __('configuration items editing', ATSU_PLUGIN_SLUG) ."</h4>\n";
					$before_main_contents .= "<div id=\"atsu-cie-body\" class=\"accordion-content\">\n";
					
					$buttons_html = '<div class="button-brick group-add_item">';
					$buttons_html .= get_submit_button( __('Add Option Item', ATSU_PLUGIN_SLUG), 'primary large', 'add_item', false, array('id'=>'atsu-add_item-' . $option_title, 'data-option-name'=>$option_title) );
					$buttons_html .= get_submit_button( __('Reset Option Item', ATSU_PLUGIN_SLUG), 'secondary large', 'reset', false, array('id'=>'atsu-reset-' . $option_title) );
					$buttons_html .= "</div>\n";
					$buttons_html .= '<div class="button-brick group-update_item">';
					$buttons_html .= get_submit_button( __('Update Option Item', ATSU_PLUGIN_SLUG), 'primary large', 'updte_item', false, array('id'=>'atsu-update_item-' . $option_title, 'data-option-name'=>$option_title) );
					$buttons_html .= get_submit_button( __('Cancel Edit Item', ATSU_PLUGIN_SLUG), 'secondary large', 'cancel', false, array('id'=>'atsu-cancel-' . $option_title) );
					$buttons_html .= "</div>\n";
					foreach ($this->plugin_options_schema as $option_name => $schema) {
						$main_contents .= $this->render_component_form_element($option_name, $schema, $current_options, 'list', 'nolabel');
					}
					$after_main_contents .= $buttons_html . "</div>\n</div>\n";
					$buttons_html = '';
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
    <form method="post" action="" id="atsu-admin-form">
      <input type="hidden" name="action" value="<?php echo $action; ?>">
      <input type="hidden" name="option_name" value="<?php echo isset($option_title) ? $option_title : ''; ?>">
      <input type="hidden" name="option_schema" value="">
      <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce(ATSU_PLUGIN_SLUG .'_submit', '_wpnonce'); ?>">
      <?php echo $before_main_contents; ?>
      <<?php echo $main_tag; ?> class="<?php echo $parent_class; ?>">
        <?php echo $main_contents; ?>
      </<?php echo $main_tag; ?>>
      <?php echo $after_main_contents; ?>
      <?php echo $buttons_html; ?>
    </form>
  </div>
<?php
	}
	
	private function render_component_form_element($option_name, $schema, $current_options, $wrapper='table', $extend=null) {
		if (!isset($schema) || is_null($schema) || empty($schema) || !is_array($schema)) 
			return;
		$form_elements = '';
		if ($wrapper == 'table') {
			$form_elements_template = $extend != 'colspan' ? "<tr valign=\"top\">\n" : '';
			$form_elements_template .= "<th scope=\"row\">%s</th>\n<td>\n<fieldset>\n<legend class=\"screen-reader-text\"><span>%s</span></legend>\n%s\n<p class=\"description\">%s</p>\n</fieldset>\n</td>\n";
			$form_elements_template .= $extend != 'colspan' ? "</tr>\n" : '';
		} elseif ($wrapper == 'list') {
			$form_elements_template = "<li><label for=\"%s\">%s</label>\n<div>%s <div class=\"baloon_box hide-content\">%s</div></div>\n</li>\n";
		} else {
			$form_elements_template = "<div>%s <p class=\"helper-text\">%s</p></div>\n";
		}
		$post_array_name = 'atsu_setting_options';
		$component_type = $schema[0];
		$label_text = $schema[4];
		$description_text = $schema[5];
		$enabled = isset($schema[6]) && !empty($schema[6]) && $schema[6] == 'false' ? false : true;
		$extra = array();
		if (isset($schema[7]) && !empty($schema[7])) {
			$extra =  is_array($schema[7]) ? $schema[7] : array();
		}
		$is_require = isset($extra['require']) && !empty($extra['require']) ? $extra['require'] : false;
		$is_encrypt = isset($extra['encrypt']) && !empty($extra['encrypt']) ? $extra['encrypt'] : false;
		$field_size = isset($extra['field_size']) && !empty($extra['field_size']) ? intval($extra['field_size']) : null;
		$cols = isset($extra['cols']) && !empty($extra['cols']) ? intval($extra['cols']) : null;
		$rows = isset($extra['rows']) && !empty($extra['rows']) ? intval($extra['rows']) : null;
		$html_instance = '';
		switch ($component_type) {
			case 'boolean': 
				$html_instance = '<select name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'">';
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
				$form_size = !empty($field_size) ? $field_size : $form_size;
				$html_instance = '<input name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'" type="'. $form_type .'" size="'. $form_size .'" value="'. (array_key_exists($option_name, $current_options) ? $current_options[$option_name] : $schema[1]) .'" placeholder="'. $schema[2] .'">';
				break;
			case 'password': 
				$form_size = 20;
				if (preg_match('/\{(.*)\}/U', $schema[3], $matches)) {
					$strlen_ary = explode(',', $matches[1]);
					$values = count($strlen_ary);
					$maxlength = !empty($strlen_ary[$values-1]) ? intval($strlen_ary[$values-1]) : intval($strlen_ary[0]);
					$form_size = $maxlength > $form_size ? $maxlength : $form_size;
				}
				$form_size = !empty($field_size) ? $field_size : $form_size;
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
				$html_instance = '<input name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'" type="password" size="'. $form_size .'" value="'. $password_value .'" placeholder="'. $schema[2] .'">';
				break;
			case 'textarea': 
				$attr_cols = !empty($cols) ? ' cols="'. $cols .'"' : '';
				$attr_rows = !empty($rows) ? ' rows="'. $rows .'"' : '';
				$html_instance = '<textarea name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'" placeholder="'. $schema[2] .'"'. $attr_cols . $attr_rows .'>'. (array_key_exists($option_name, $current_options) ? $current_options[$option_name] : $schema[1]) .'</textarea>';
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
				$form_size = !empty($field_size) ? $field_size : $form_size;
				$add_unit = '';
				if (!empty($extra)) {
					foreach($extra as $i => $val) {
						if (preg_match('/^unit\:(.*)/U', $val, $matches)) {
							$add_unit = '<sub class="unit">'. $matches[1] .'</sub>';
							unset($extra[$i]);
							break;
						}
					}
				}
				$html_instance = '<input name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'" type="number" size="'. $form_size .'" style="width: '. $form_size .'em;" value="'. (array_key_exists($option_name, $current_options) ? $current_options[$option_name] : $default_value) .'">' . $add_unit;
				break;
			case 'select': 
				$box_type = $schema[3];
				$default_index = is_string($schema[1]) ? explode(',', $schema[1]) : intval($schema[1]);
				$attr = $box_type == 'multi' ? 'multiple' : '';
				$box_size = $box_type == 'multi' ? 'size="'. count($schema[2]) .'"' : '';
				$box_size = !empty($rows) ? $rows : $box_size;
				$html_instance = '<select name="'. $post_array_name .'['. $option_name .']'. ($box_type == 'multi' ? '[]' : '') .'" id="'. $option_name .'" '. $box_size .' '. $attr .' style="width: %dem;">';
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
					$html_instance .= '<input type="radio" name="'. $post_array_name .'['. $option_name .']" id="'. $option_name .'-'. $index_num .'" '. (array_key_exists($option_name, $current_options) ? checked($current_options[$option_name], $index_value, false) : checked($default_index, $index_num, false)) .' value="'. $index_value .'" '. $add_style .'><label for="'. $option_name .'-'. $index_num .'">'. $display_value .'</label>';
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
					$html_instance .= '<input type="checkbox" name="'. $post_array_name .'['. $option_name .'][]" id="'. $option_name .'-'. $index_num .'" '. $attr_checked .' value="'. $index_value .'" '. $add_style .'><label for="'. $option_name .'-'. $index_num .'">'. $display_value .'</label>' . $add_break;
					$index_num++;
				}
				break;
			default: 
				break;
		}
		if (!empty($html_instance)) {
			if ($is_require) 
				$label_text .= ' <span class="badge badge-notice">'. __('Require', ATSU_PLUGIN_SLUG) .'</span>';
			if ($wrapper == 'table') {
				$form_elements .= sprintf($form_elements_template, $label_text, $label_text, $html_instance, $description_text);
			} elseif ($wrapper == 'list') {
				$form_elements .= sprintf($form_elements_template, $option_name, $label_text, $html_instance, $description_text);
			} else {
				$form_elements .= sprintf($form_elements_template, $html_instance, $description_text);
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
		if (!wp_verify_nonce($_POST['_wpnonce'], ATSU_PLUGIN_SLUG . '_submit')) {
			return;
		}
		global $atsu_message;
		$options_list = $this->plugin_current_options['options'];
		$set_options = $_POST['atsu_setting_options'];
		$action = $_POST['action'];
		$option_name = isset($_POST['option_name']) && !empty($_POST['option_name']) ? $_POST['option_name'] : '';
		$status = array();
		$store_options = array();
		
		switch($action) { // updated after options value validate
			case 'add_option': 
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
			case 'edit': 
				if (empty($option_name)) 
					$status[] = __('Option name has not been specified.', ATSU_PLUGIN_SLUG);
				if (!array_key_exists($option_name, $options_list)) 
					$status[] = __('The specified option setting does not exist.', ATSU_PLUGIN_SLUG);
				
				if (empty($status)) {
					//$redirect_url = rawurlencode(implode('&', array($_SERVER['REQUEST_URI'], 'mode=configure', 'optnm=' . $option_name)));
					//header("Location: $redirect_url");
					return;
				} else {
					$atsu_message = [ 'error', implode("<br>\n", $status) ];
				}
				break;
			case 'delete': 
				if (empty($option_name)) 
					$status[] = __('Option name has not been specified.', ATSU_PLUGIN_SLUG);
				if (!array_key_exists($option_name, $options_list)) 
					$status[] = __('The specified option setting does not exist.', ATSU_PLUGIN_SLUG);
				
				if (empty($status)) {
					unset($options_list[$option_name]);
					$this->plugin_current_options['options'] = $options_list;
					update_option(ATSU_PLUGIN_SLUG, $this->plugin_current_options);
					$atsu_message = [ 'updated', sprintf(__('The options of <strong>%s</strong> deleted.', ATSU_PLUGIN_SLUG), $option_name) ];
				} else {
					$atsu_message = [ 'error', implode("<br>\n", $status) ];
				}
				break;
			case 'add_item': 
				$error_msg = array();
				foreach ($set_options as $item_name => $item_value) {
					list($status, $fixed_value) = $this->validate_option_values($item_name, $item_value);
					if (empty($status)) {
						$store_options[$item_name] = $item_name == 'validate_regx' ? stripcslashes($fixed_value) : $fixed_value;
					} else {
						$error_msg[] = $status;
					}
				}
				
				// save or error
				if (empty($error_msg)) {
					if (array_key_exists($option_name, $options_list)) {
						if (!array_key_exists($store_options['field_name'], $options_list[$option_name])) {
							$insert_order = $store_options['field_order'] > 0 ? $store_options['field_order'] : null;
							$insert_item = array($store_options['field_name'] => array(
								$store_options['field_type'], 
								$store_options['default_string_value'], 
								$store_options['placeholder'], 
								$store_options['validate_regx'], 
								$store_options['label'], 
								$store_options['helper_text'], 
								$store_options['enable_field'], 
								array('field_size'=>$store_options['field_size']), 
							));
							$this->array_insert($options_list[$option_name], $insert_item, $insert_order);
							$this->plugin_current_options['options'] = $options_list;
							update_option(ATSU_PLUGIN_SLUG, $this->plugin_current_options);
							$atsu_message = [ 'update', __('Added the option settings.', ATSU_PLUGIN_SLUG) ];
						} else {
							$atsu_message = [ 'error', __('The item already exists. The same name of the item can not be added.', ATSU_PLUGIN_SLUG) ];
						}
					} else {
						$atsu_message = [ 'error', __('The saving option settings does not exists.', ATSU_PLUGIN_SLUG) ];
					}
				} else {
					$atsu_message = [ 'error', implode("<br>\n", $error_msg) ];
				}
				break;
			case 'update_item': 
				
				
				break;
			case 'edit_item': 
				// because it is processed in the ajax is not handling here.
				break;
			case 'update_order': 
				if (array_key_exists('options_order', $set_options) && count($set_options['options_order']) > 0) {
					for ($i = 0; $i<count($set_options['options_order']); $i++) {
						$item_name = $set_options['options_order'][$i];
						if (array_key_exists($item_name, $options_list[$option_name])) {
							$store_options[$item_name] = $options_list[$option_name][$item_name];
							$store_options[$item_name]['field_order'] = $i;
						}
					}
				} else {
					$status = [ 'error', __('Options order is invalid.', ATSU_PLUGIN_SLUG) ];
				}
				if (empty($status)) {
					$options_list[$option_name] = $store_options;
					$this->plugin_current_options['options'] = $options_list;
					update_option(ATSU_PLUGIN_SLUG, $this->plugin_current_options);
					$atsu_message = [ 'update', __('Saved the option order.', ATSU_PLUGIN_SLUG) ];
				} else {
					$atsu_message = $status;
				}
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
			return;
		$is_require = (isset($options_schema[$option_name][7]) && !empty($options_schema[$option_name][7]) && $options_schema[$option_name][7] == 'require') ? true : false;
		if ($is_require && empty($option_value)) {
			$message = sprintf(__('<strong>%s</strong> is empty data! This item must be sure to enter.', ATSU_PLUGIN_SLUG), $options_schema[$option_name][4]);
		}
		if (empty($message)) {
			$data_type = $options_schema[$option_name][0];
			if ($data_type == 'boolean') {
				$fixed_value = $option_value == 'true' ? true : false;
			}
			if ($data_type == 'string' || $data_type == 'textarea') {
				$regx = sprintf('/%s/', stripcslashes($options_schema[$option_name][3]));
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
	
	/**
	 * 配列（連想配列にも対応）の指定位置に要素（配列にも対応）を挿入して、挿入後の配列を返す
	 * 
	 * @param array &$base_array 挿入したい配列
	 * @param mixed $insert_value 挿入する値（文字列、数値、配列のいずれか）
	 * @param int $position 挿入位置（省略可能。先頭は0、省略時は配列末尾に挿入される）
	 * @return boolean 挿入成功時にtrue
	 **/
	public function array_insert(&$base_array, $insert_value, $position=null) {
		if (!is_array($base_array)) 
			return false;
		$position = is_null($position) ? count($base_array) : intval($position);
		$base_keys = array_keys($base_array);
		$base_values = array_values($base_array);
		if (is_array($insert_value)) {
			$insert_keys = array_keys($insert_value);
			$insert_values = array_values($insert_value);
		} else {
			$insert_keys = array(0);
			$insert_values = array($insert_value);
		}
		$insert_keys_after = array_splice($base_keys, $position);
		$insert_values_after = array_splice($base_values, $position);
		foreach ($insert_keys as $insert_keys_value) {
			array_push($base_keys, $insert_keys_value);
		}
		foreach ($insert_values as $insert_values_value) {
			array_push($base_values, $insert_values_value);
		}
		$base_keys = array_merge($base_keys, $insert_keys_after);
		$is_key_numric = true;
		foreach ($base_keys as $key_value) {
			if (!is_integer($key_value)) {
				$is_key_numric = false;
				break;
			}
		}
		$base_values = array_merge($base_values, $insert_values_after);
		if ($is_key_numric) {
			$base_array = $base_values;
		} else {
			$base_array = array_combine($base_keys, $base_values);
		}
		return true;
	}
	
	/**
	 * 配列（連想配列にも対応）の任意の位置から指定数分の要素を削除して、削除後の配列を返す
	 * 
	 * @param array &$base_array 要素を削除したい配列
	 * @param int $delete_position 削除を開始する要素位置
	 * @param int $delete_items 削除する要素数（省略可能。省略時は1つだけ削除）
	 * @param boolean $reroll_index 削除後の配列の添字振り直しフラグ（省略可能。省略時はtrueで添字を振り直す。※数値添字のみの配列でない場合は振り直しは行わない）
	 * @return boolean 削除成功時にtrue
	 **/
	public function array_delete(&$base_array, $delete_position=null, $delete_items=1, $reroll_index=true) {
		if (!is_array($base_array)) 
			return false;
		if (is_null($delete_position) || !is_integer($delete_position)) 
			return false;
		if (!is_integer($delete_items) || intval($delete_items) == 0) 
			return false;
		$index_num = 0;
		foreach ($base_array as $key => $value) {
			if ($delete_position == $index_num) {
				unset($base_array[$key]);
				$delete_items--;
				$delete_position++;
			}
			if ($delete_items == 0) {
				break;
			}
			$index_num++;
		}
		$is_key_numric = true;
		foreach (array_keys($base_array) as $key_value) {
			if (!is_integer($key_value)) {
				$is_key_numric = false;
				break;
			}
		}
		if ($is_key_numric && $reroll_index) {
			$base_array = array_merge($base_array, array());
		}
		return true;
	}
	
	
}