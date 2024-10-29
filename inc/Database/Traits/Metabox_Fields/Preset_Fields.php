<?php

namespace ADQS_Directory\Database\Traits\Metabox_Fields;

trait Preset_Fields
{

	/**
	 * Method preset_template_dir
	 *
	 * @return void
	 */
	private function preset_template_dir()
	{
		return __DIR__ . '/fields-template/preset-fields';
	}


	/**
	 * Method get_preset_template
	 *
	 * @param $post_id $post_id 
	 * @param $file_name $file_name 
	 * @param $data $data 
	 *
	 * @return void
	 */
	public function get_preset_template($post_id = 0, $file_name = '', $data = array())
	{

		$Helper    = $this->Field_Helper;
		$file_path = $this->preset_template_dir() . "/{$file_name}.php";
		if (file_exists($file_path)) {
			include $file_path;
		}
	}

	/**
	 * Method get_all_preset_fields
	 *
	 * @param $post_id $post_id 
	 * @param $data $data 
	 *
	 * @return void
	 */
	public function get_all_preset_fields($post_id, $data)
	{
		$input_type = (isset($data['input_type']) && !empty($data['input_type'])) ? $data['input_type'] : false;
		if (!empty($input_type)) {
			$this->get_preset_template($post_id, $input_type, $data);
		}
	}


	/**
	 * Method save_all_preset_fields
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_all_preset_fields($post_id = 0)
	{
		// all key item list
		$Helper = $this->Field_Helper;
		$path   = $this->preset_template_dir() . '/*.php';

		$filenames = glob($path);
		if (!empty($filenames) && is_array($filenames)) {
			foreach ($filenames as $name_slug) {
				$name_slug = basename($name_slug, '.php');
				$meta_key  = sanitize_key("_{$name_slug}");
				$getData   = isset($_POST[$meta_key]) ? $_POST[$meta_key] : '';

				switch ($name_slug) {
					case 'address':
					case 'fax':
					case 'phone':
					case 'tagline':
					case 'view_count':
					case 'email':
					case 'zip':
						update_post_meta($post_id, $meta_key, sanitize_text_field($getData));
						break;
					case 'video':
					case 'website':
						update_post_meta($post_id, $meta_key, esc_url_raw($getData));
						break;
					case 'map':
						$map_lat  = isset($_POST['_map_lat']) ? $_POST['_map_lat'] : '';
						$map_lon  = isset($_POST['_map_lon']) ? $_POST['_map_lon'] : '';
						$hide_map = isset($_POST['_hide_map']) ? $_POST['_hide_map'] : '';

						update_post_meta($post_id, '_map_lat', sanitize_text_field($map_lat));
						update_post_meta($post_id, '_map_lon', sanitize_text_field($map_lon));
						update_post_meta($post_id, '_hide_map', sanitize_text_field($hide_map));
						break;
					case 'pricing':
						$price_type  = isset($_POST['_price_type']) ? $_POST['_price_type'] : '';
						$price       = isset($_POST['_price']) ? $_POST['_price'] : '';
						$price_sub   = isset($_POST['_price_sub']) ? $_POST['_price_sub'] : '';
						$price_range = isset($_POST['_price_range']) ? $_POST['_price_range'] : '';

						update_post_meta($post_id, '_price_type', sanitize_text_field($price_type));
						update_post_meta($post_id, '_price', sanitize_text_field($price));
						update_post_meta($post_id, '_price_sub', sanitize_text_field($price_sub));
						update_post_meta($post_id, '_price_range', sanitize_text_field($price_range));
						break;
					case 'businesshour':
						$bhour_choose = isset($_POST['_bhour_choose']) ? $_POST['_bhour_choose'] : '';
						update_post_meta($post_id, '_bhour_choose', sanitize_text_field($bhour_choose));

						$business_hour_data = isset($_POST['bhc']) ? $_POST['bhc'] : array();
						$business_hour_data = map_deep($business_hour_data, 'sanitize_text_field');
						update_post_meta($post_id, 'adqs_bsuiness_data', $business_hour_data);
						break;
				}
			}
		}
	}
}
