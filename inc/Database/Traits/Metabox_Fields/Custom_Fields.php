<?php

namespace ADQS_Directory\Database\Traits\Metabox_Fields;

trait Custom_Fields
{



	/**
	 * Method custom_template_dir
	 *
	 * @return void
	 */
	private function custom_template_dir()
	{
		return __DIR__ . '/fields-template/custom-fields';
	}



	/**
	 * Method get_custom_template
	 *
	 * @param $post_id $post_id 
	 * @param $file_name $file_name 
	 * @param $data $data 
	 *
	 * @return void
	 */
	public function get_custom_template($post_id = 0, $file_name = '', $data = array())
	{
		$Helper    = $this->Field_Helper;
		$file_path = $this->custom_template_dir() . "/{$file_name}.php";
		if (file_exists($file_path)) {
			include $file_path;
		}
	}




	/**
	 * Method get_directory_custom_fields
	 *
	 * @param $directory_id
	 * @param $only_files 
	 *
	 * @return array
	 */
	public function get_directory_custom_fields($directory_id = 0, $only_files = array())
	{
		$directory_id   = !empty($directory_id) ? absint($directory_id) : 0;
		$listing_fields = adqs_get_listing_fields($directory_id);
		if (empty($listing_fields)) {
			return array();
		}
		$getAllCustomFields = array();
		foreach ($listing_fields as $section) {
			$sectionFields = $section['fields'] ?? array();
			foreach ($sectionFields as $field) {
				$input_type = $field['input_type'] ?? '';
				if (in_array($input_type, $only_files)) {
					$fieldid                        = $field['fieldid'] ?? '';
					$getAllCustomFields[$fieldid] = $input_type;
				}
			}
		}
		return $getAllCustomFields;
	}

	/**
	 * Method get_all_custom_fields
	 *
	 * @param $post_id $post_id 
	 * @param $data $data 
	 *
	 * @return void
	 */
	public function get_all_custom_fields($post_id, $data)
	{

		$path          = $this->custom_template_dir() . '/*.php';
		$Helper        = $this->Field_Helper;
		$custom_fields = $Helper->file_name_from_folder($path);
		$input_type    = (isset($data['input_type']) && !empty($data['input_type'])) ? $data['input_type'] : false;
		if (!empty($input_type) && in_array($input_type, $custom_fields)) {
			$this->get_custom_template($post_id, $input_type, $data);
		}
	}


	/**
	 * Method save_all_custom_fields
	 *
	 * @param $post_id $post_id 
	 *
	 * @return void
	 */
	public function save_all_custom_fields($post_id = 0)
	{
		// all key item list
		$Helper = $this->Field_Helper;
		$path   = $this->custom_template_dir() . '/*.php';
		if (!isset($_POST['adqs_select_directory']) || !wp_verify_nonce($_POST['adqs_select_directory'], 'directory_type')) {
			return;
		}
		$custom_fields = $Helper->file_name_from_folder($path);
		$custom_fields = $this->get_directory_custom_fields(get_post_meta($post_id, 'adqs_directory_type', true), $custom_fields);
		if (!empty($custom_fields) && is_array($custom_fields)) {
			foreach ($custom_fields as $id => $input_type) {
				$meta_key = sanitize_key("_{$input_type}_{$id}");
				$getData  = isset($_POST[$meta_key]) ? $_POST[$meta_key] : '';

				switch ($input_type) {
					case 'text':
					case 'number':
					case 'date':
					case 'time':
					case 'select':
					case 'radio':
						update_post_meta($post_id, $meta_key, sanitize_text_field($getData));
						break;
					case 'url':
						update_post_meta($post_id, $meta_key, esc_url_raw($getData));
						break;
					case 'textarea':
						update_post_meta($post_id, $meta_key, sanitize_textarea_field($getData));

						$meta_key_list = sanitize_key("_{$input_type}_list_{$id}");
						$getData_list  = isset($_POST[$meta_key_list]) ? $_POST[$meta_key_list] : '';
						update_post_meta($post_id, $meta_key_list, sanitize_text_field($getData_list));
						break;
					case 'checkbox':
						$getData = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : array();
						$getData = array_map('sanitize_text_field', $getData);
						update_post_meta($post_id, $meta_key, $getData);
						break;
					case 'field_images':
						$getData = isset($_POST[$meta_key]) ? (array) $_POST[$meta_key] : array();
						$getData = array_map('absint', $getData);
						update_post_meta($post_id, $meta_key, $getData);
						break;
				}
			}
		}
	}
}
