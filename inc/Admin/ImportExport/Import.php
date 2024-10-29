<?php

namespace ADQS_Directory\Admin\ImportExport;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Import
{
	private $db;
	private $Helper;
	private $terms_ids = [];
	private $term_taxonomy_ids = [];
	private $posts_ids = [];
	private $comments_ids = [];
	private $pricings_ids = [];

	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->Helper = new Helper();
		// action loaded
		add_action('wp_ajax_adqs_import_data', [$this, 'ajax_import_data']);
	}


	public function ajax_import_data()
	{
		check_ajax_referer('import_export_nonce', 'nonce');

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('You do not have permission to access this resource.', 'adirectory'));
		}

		if (!isset($_FILES['import_file'])) {
			wp_send_json_error(__('No file uploaded.', 'adirectory'));
		}

		$this->handle_import();
	}

	private function handle_import($is_ajax = true)
	{
		// Check if a file was uploaded
		if (empty($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
			$this->Helper->send_response(__('File upload failed.', 'adirectory'), $is_ajax);
			return;
		}

		$file = $_FILES['import_file'];

		// Upload the file securely using WordPress function
		$upload_overrides = ['test_form' => false];
		$uploaded_file = wp_handle_upload($file, $upload_overrides);

		if (isset($uploaded_file['error'])) {
			$this->Helper->send_response(__('Failed to upload the file.', 'adirectory'), $is_ajax);
			return;
		}

		$uploaded_file_path = $uploaded_file['file']; // Full path to the uploaded file

		// Ensure the import directory exists
		$upload_dir = wp_upload_dir();
		$import_dir = $upload_dir['basedir'] . '/adqs_imports';

		if (!file_exists($import_dir)) {
			mkdir($import_dir, 0755, true);
		}

		// Extract the CSV files from the uploaded ZIP file
		$zip = new \ZipArchive();
		if ($zip->open($uploaded_file_path) === TRUE) {
			$zip->extractTo($import_dir);
			$zip->close();
		} else {
			$this->Helper->send_response(__('Failed to open ZIP file.', 'adirectory'), $is_ajax);
			return;
		}

		// Clean up: remove the uploaded ZIP file
		unlink($uploaded_file_path);

		// Define the CSV files to be imported
		$tables = [
			'terms' => 'terms.csv',
			'term_taxonomy' => 'term_taxonomy.csv',
			'termmeta' => 'termmeta.csv',
			'posts' => 'posts.csv',
			'postmeta' => 'postmeta.csv',
			'comments' => 'comments.csv',
			'commentmeta' => 'commentmeta.csv',
			'term_relationships' => 'term_relationships.csv',
			'attachment' => 'attachment.csv',
			'options' => 'options.csv',
		];

		if ($this->Helper->has_pricing_extention()) {
			$tables = wp_parse_args([
				'adqs_pricings' => 'adqs_pricings.csv',
				'adqs_pricing_meta' => 'adqs_pricing_meta.csv',
			], $tables);
		}

		// Import data from each CSV file
		foreach ($tables as $table => $file) {
			$file_path = $import_dir . '/' . $file;
			if (file_exists($file_path)) {
				$this->import_csv($file_path, $table);
			}
		}

		// Clean up: remove all files in the directory and the directory itself
		$this->Helper->clean_import_dir($import_dir);

		// Handle response
		if ($is_ajax) {
			wp_send_json_success([
				'status' => 'success',
				'redirect_url' => admin_url('edit.php?post_type=adqs_directory'),
			]);
		} else {
			wp_redirect(admin_url('edit.php?post_type=adqs_directory'));
			exit;
		}
	}




	private function import_terms_data($filepath)
	{

		$this->delete_all_exists_terms_taxonomies();
		$terms = $this->Helper->read_csv_by_header($filepath);
		foreach ($terms as $row) {
			$old_term_id = $row['term_id'] ?? '';
			unset($row['term_id']);
			$this->db->insert($this->db->terms, $row);
			if (!empty($this->db->insert_id)) {
				$this->terms_ids["_{$old_term_id}"] = absint($this->db->insert_id);
			}
		}
	}

	private function import_termmeta_data($filepath)
	{

		$termmeta = $this->Helper->read_csv_by_header($filepath);
		foreach ($termmeta as $row) {
			unset($row['meta_id']);
			$old_id = $row['term_id'] ?? '';
			$old_id = $this->terms_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['term_id'] = absint($old_id);
				if ($row['meta_key'] === 'listing_types') {
					if ($this->Helper->is_serialized($row['meta_value'])) {
						$unserialized_value = maybe_unserialize($row['meta_value']);
						$update_meta_values = [];

						foreach ($unserialized_value as $key => $val) {

							$update_meta_values[$key] =  $this->terms_ids["_{$val}"] ?? '';
						}
						if (!empty($update_meta_values)) {
							$row['meta_value'] = maybe_serialize($update_meta_values);
						}
					}
				}
				$this->db->insert($this->db->termmeta, $row);
			}
		}
	}


	private function import_term_taxonomy_data($filepath)
	{

		$term_taxonomy = $this->Helper->read_csv_by_header($filepath);
		foreach ($term_taxonomy as $row) {
			$old_term_taxonomy_id = $row['term_taxonomy_id'] ?? '';
			unset($row['term_taxonomy_id']);
			$old_id = $row['term_id'] ?? '';
			$old_id = $this->terms_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['term_id'] = absint($old_id);
				$this->db->insert($this->db->term_taxonomy, $row);
			}

			if (!empty($this->db->insert_id)) {
				$this->term_taxonomy_ids["_{$old_term_taxonomy_id}"] = absint($this->db->insert_id);
			}
		}
	}

	private function import_posts_data($filepath)
	{

		$posts = $this->Helper->read_csv_by_header($filepath);
		foreach ($posts as $row) {
			$old_post_id = $row['ID'] ?? '';
			unset($row['ID']);
			$this->db->insert($this->db->posts, $row);
			if (!empty($this->db->insert_id)) {
				$this->posts_ids["_{$old_post_id}"] = absint($this->db->insert_id);
			}
		}
	}
	private function import_attachment_data($filepath)
	{
		$attachment = $this->Helper->read_csv_by_header($filepath);

		$postmeta_values = $this->db->get_results(
			"SELECT * FROM {$this->db->postmeta}
            WHERE meta_key IN ('_thumbnail_id', '_images')
            OR meta_key LIKE '_field_images_%'",
			ARRAY_A
		);

		$termmeta_values = $this->db->get_results(
			"SELECT * FROM {$this->db->termmeta}
            WHERE meta_key IN ('adqs_category_image_id', 'adqs_location_image_id')",
			ARRAY_A
		);

		$this->Helper->replace_attachment_ids($postmeta_values, $attachment, 'postmeta');
		$this->Helper->replace_attachment_ids($termmeta_values, $attachment, 'termmeta');
	}


	private function import_postmeta_data($filepath)
	{

		$postmeta = $this->Helper->read_csv_by_header($filepath);
		foreach ($postmeta as $row) {
			unset($row['meta_id']);
			$old_id = $row['post_id'] ?? '';
			$old_id = $this->posts_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['post_id'] = absint($old_id);
				if ($row['meta_key'] === 'adqs_directory_type') {
					$old_term = $row['meta_value'] ?? '';
					$row['meta_value'] = $this->terms_ids["_{$old_term}"] ?? '';
				}
				$this->db->insert($this->db->postmeta, $row);
			}
		}
	}

	private function import_comments_data($filepath)
	{

		$comments = $this->Helper->read_csv_by_header($filepath);
		foreach ($comments as $row) {
			$old_comment_id = $row['comment_ID'] ?? '';
			unset($row['comment_ID']);
			$old_id = $row['comment_post_ID'] ?? '';
			$old_id = $this->posts_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['comment_post_ID'] = absint($old_id);
				$this->db->insert($this->db->comments, $row);

				if (!empty($this->db->insert_id)) {
					$this->comments_ids["_{$old_comment_id}"] = absint($this->db->insert_id);
				}
			}
		}
	}

	private function import_commentmeta_data($filepath)
	{

		$commentmeta = $this->Helper->read_csv_by_header($filepath);
		foreach ($commentmeta as $row) {
			unset($row['meta_id']);
			$old_id = $row['comment_id'] ?? '';
			$old_id = $this->comments_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['comment_id'] = absint($old_id);
				$this->db->insert($this->db->commentmeta, $row);
			}
		}
	}

	private function import_term_relationships_data($filepath)
	{
		$term_relationships = $this->Helper->read_csv_by_header($filepath);

		foreach ($term_relationships as $row) {
			$old_id = $row['object_id'] ?? '';
			$old_id = $this->posts_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['object_id'] = absint($old_id);
			}

			$old_tax_id = $row['term_taxonomy_id'] ?? '';
			$old_tax_id = $this->term_taxonomy_ids["_{$old_tax_id}"] ?? '';
			if (!empty($old_tax_id)) {
				$row['term_taxonomy_id'] = absint($old_tax_id);
			}

			if (!empty($old_id) || !empty($old_tax_id)) {
				// Check if the entry already exists
				$exists_query = $this->db->get_row(
					$this->db->prepare(
						"SELECT object_id,term_taxonomy_id FROM {$this->db->term_relationships} WHERE object_id = %d AND term_taxonomy_id = %d",
						$row['object_id'],
						$row['term_taxonomy_id']
					)
				);

				if (!$exists_query) {
					// Insert the record only if it doesn't already exist
					$this->db->insert($this->db->term_relationships, $row);
				}
			}
		}
	}


	private function delete_all_exists_terms_taxonomies($taxonomies = ['adqs_listing_types', 'adqs_category', 'adqs_location', 'adqs_tags'])
	{

		// Ensure $taxonomies is an array
		if (!is_array($taxonomies)) {
			$taxonomies = (array) $taxonomies;
		}

		foreach ($taxonomies as $taxonomy) {
			// Sanitize taxonomy name
			$taxonomy = sanitize_key($taxonomy);

			// Get term IDs associated with the taxonomy
			$term_ids = $this->db->get_col(
				$this->db->prepare(
					"SELECT t.term_id FROM {$this->db->terms} t
                    JOIN {$this->db->term_taxonomy} tt ON t.term_id = tt.term_id
                    WHERE tt.taxonomy = %s",
					$taxonomy
				)
			);

			if (!empty($term_ids)) {
				// Delete term relationships
				$this->db->query(
					$this->db->prepare(
						"DELETE tr FROM {$this->db->term_relationships} tr
                        JOIN {$this->db->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                        WHERE tt.taxonomy = %s",
						$taxonomy
					)
				);

				// Delete term taxonomy
				$this->db->query(
					$this->db->prepare(
						"DELETE tt FROM {$this->db->term_taxonomy} tt
                        WHERE tt.taxonomy = %s",
						$taxonomy
					)
				);

				// Delete terms
				$this->db->query(
					$this->db->prepare(
						"DELETE t FROM {$this->db->terms} t
                        JOIN {$this->db->term_taxonomy} tt ON t.term_id = tt.term_id
                        WHERE tt.taxonomy = %s",
						$taxonomy
					)
				);

				// Delete term meta
				foreach ($term_ids as $term_id) {
					$this->db->delete($this->db->termmeta, ['term_id' => $term_id], ['%d']);
				}
			}
		}
	}

	private function import_options_data($filepath)
	{
		// First, delete existing options that match the criteria
		$this->delete_existing_options();

		// Read new options from the provided CSV file
		$options = $this->Helper->read_csv_by_header($filepath);

		// Insert new options into the database
		foreach ($options as $row) {
			unset($row['option_id']); // Ensure option_id is not set

			if ($row['option_name'] === 'sidebars_widgets') {
				$sw = $this->db->get_row(
					$this->db->prepare(
						"SELECT option_id, option_value FROM {$this->db->options} WHERE option_name = %s",
						'sidebars_widgets'
					)
				);

				if ($sw) {
					$default_sw = maybe_unserialize($sw->option_value);
					$set_sw = maybe_unserialize($row['option_value']);

					if (is_array($default_sw) && is_array($set_sw)) {
						$insert_sw = wp_parse_args($set_sw, $default_sw);
						$this->db->update(
							$this->db->options,
							['option_value' => maybe_serialize(array_filter($insert_sw))],
							['option_id' => $sw->option_id]
						);
					}
				}
			} else {
				$this->db->insert($this->db->options, $row);
			}
		}
	}

	private function delete_existing_options()
	{
		// Fetch all option IDs with names starting with 'widget_adqs_%'
		$options = $this->db->get_results(
			"SELECT option_id
         FROM {$this->db->options}
         WHERE option_name LIKE 'widget_adqs_%'",
			ARRAY_A
		);

		// Loop through the results and delete each option
		if (!empty($options)) {
			foreach ($options as $option) {
				$this->db->delete(
					$this->db->options,
					['option_id' => $option['option_id']]
				);
			}
		}
	}

	private function import_adqs_pricings_data($filepath)
	{

		$pricings = $this->Helper->read_csv_by_header($filepath);
		foreach ($pricings as $row) {
			$old_price_id = $row['id'] ?? '';
			unset($row['id']);
			$this->db->insert("{$this->db->prefix}adqs_pricings", $row);
			if (!empty($this->db->insert_id)) {
				$this->pricings_ids["_{$old_price_id}"] = absint($this->db->insert_id);
			}
		}
	}
	private function import_adqs_pricing_meta_data($filepath)
	{

		$pricing_meta = $this->Helper->read_csv_by_header($filepath);
		foreach ($pricing_meta as $row) {
			unset($row['meta_id']);
			$old_id = $row['pricing_id'] ?? '';
			$old_id = $this->pricings_ids["_{$old_id}"] ?? '';
			if (!empty($old_id)) {
				$row['pricing_id'] = absint($old_id);
				$this->db->insert("{$this->db->prefix}adqs_pricing_meta", $row);
			}
		}
	}



	private function import_csv($filepath, $table)
	{
		if (!file_exists($filepath)) {
			wp_die(__('File not found: ', 'adirectory') . $filepath);
		}
		switch ($table) {
			case 'terms':
				$this->import_terms_data($filepath);
				break;
			case 'termmeta':
				$this->import_termmeta_data($filepath);
				break;
			case 'term_taxonomy':
				$this->import_term_taxonomy_data($filepath);
				break;
			case 'posts':
				$this->import_posts_data($filepath);
				break;
			case 'postmeta':
				$this->import_postmeta_data($filepath);
				break;
			case 'comments':
				$this->import_comments_data($filepath);
				break;
			case 'commentmeta':
				$this->import_commentmeta_data($filepath);
				break;
			case 'term_relationships':
				$this->import_term_relationships_data($filepath);
				break;
			case 'attachment':
				$this->import_attachment_data($filepath);
				break;
			case 'options':
				$this->import_options_data($filepath);
				break;
			case 'adqs_pricings':
				$this->import_adqs_pricings_data($filepath);
				break;
			case 'adqs_pricing_meta':
				$this->import_adqs_pricing_meta_data($filepath);
				break;
		}
	}
}
