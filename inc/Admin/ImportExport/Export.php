<?php

namespace ADQS_Directory\Admin\ImportExport;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Export
{
    private $db;
    private $Helper;
    private $attachment_ids = [];

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->Helper = new Helper();

        // action loaded
        add_action('init', [$this, 'delete_existing_export_files']);
        add_action('wp_ajax_adqs_export_data', [$this, 'export_data']);
    }

    private function export_dir()
    {
        $upload_dir = wp_upload_dir();
        return $upload_dir['basedir'] . '/adqs_exports';
    }

    public function delete_existing_export_files()
    {
        $export_dir = $this->export_dir();

        // Check if directory exists and is a directory
        if (!is_dir($export_dir)) {
            return;
        }

        // Remove all files in the directory
        $files = glob($export_dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the directory itself
        rmdir($export_dir);
    }

    public function export_data()
    {
        check_ajax_referer('import_export_nonce', 'nonce');

        // Delete existing directory files
        $this->delete_existing_export_files();

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to access this resource.', 'adirectory'));
        }

        $export_dir = $this->export_dir();

        // Create export directory if it does not exist
        if (!file_exists($export_dir)) {
            mkdir($export_dir, 0755, true);
        }

        // Define the CSV files and their content
        $tables = [
            'terms' => $this->get_terms_data(),
            'termmeta' => $this->get_termmeta_data(),
            'term_taxonomy' => $this->get_term_taxonomy_data(),
            'posts' => $this->get_posts_data(),
            'postmeta' => $this->get_postmeta_data(),
            'comments' => $this->get_comments_data(),
            'commentmeta' => $this->get_commentmeta_data(),
            'term_relationships' => $this->get_term_relationships_data(),
            'attachment' => $this->get_attachment_data(),
            'options' => $this->get_options_data(),
        ];

        if ($this->Helper->has_pricing_extention()) {
            $tables = wp_parse_args([
                'adqs_pricings' => $this->get_adqs_pricings_data(),
                'adqs_pricing_meta' => $this->get_adqs_pricing_meta_data(),
            ], $tables);
        }

        // Generate CSV files
        foreach ($tables as $table => $data) {
            $this->Helper->generate_csv($export_dir . '/' . $table . '.csv', $data['headers'], $data['rows']);
        }

        // Create ZIP file
        $zip_filepath = $export_dir . '/exported_data.zip';
        $this->Helper->create_zip($export_dir, $zip_filepath);

        // Output ZIP file for download
        $this->Helper->download_zip($zip_filepath);

        // Clean up generated files
        $this->Helper->cleanup_export_dir($export_dir);
    }

    private function get_terms_data()
    {
        $term_taxonomy_data = $this->get_term_taxonomy_data();
        $term_ids = array_column($term_taxonomy_data['rows'], 'term_id');
        $term_ids = array_filter($term_ids, 'intval'); // Ensure term IDs are integers

        if (empty($term_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $term_ids_string = implode(',', $term_ids);

        // Use subquery to ensure distinct slugs
        $query = "
        SELECT DISTINCT t.*
        FROM {$this->db->terms} t
        INNER JOIN (
            SELECT MIN(term_id) as term_id
            FROM {$this->db->terms}
            WHERE term_id IN ({$term_ids_string})
            GROUP BY slug
        ) as sub ON t.term_id = sub.term_id
    ";

        $terms = $this->db->get_results($query, ARRAY_A);

        if (empty($terms)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($terms[0]), 'rows' => $terms];
    }


    private function get_termmeta_data()
    {
        $terms_data = $this->get_terms_data();
        $term_ids = array_column($terms_data['rows'], 'term_id');
        $term_ids = implode(',', array_filter($term_ids));

        if (empty($term_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $termmeta = $this->db->get_results("SELECT * FROM {$this->db->termmeta} WHERE term_id IN ({$term_ids})", ARRAY_A);

        if (empty($termmeta)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($termmeta[0]), 'rows' => $termmeta];
    }

    private function get_term_relationships_data()
    {
        $term_relationships = $this->db->get_results("SELECT * FROM {$this->db->term_relationships}", ARRAY_A);

        if (empty($term_relationships)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($term_relationships[0]), 'rows' => $term_relationships];
    }

    private function get_term_taxonomy_data()
    {
        $term_taxonomy = $this->db->get_results("SELECT * FROM {$this->db->term_taxonomy} WHERE taxonomy IN('adqs_listing_types','adqs_category','adqs_location','adqs_tags')", ARRAY_A);

        if (empty($term_taxonomy)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($term_taxonomy[0]), 'rows' => $term_taxonomy];
    }

    private function get_posts_data()
    {
        $posts = $this->db->get_results("SELECT * FROM {$this->db->posts} WHERE post_type='adqs_directory'", ARRAY_A);

        if (empty($posts)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($posts[0]), 'rows' => $posts];
    }

    private function get_postmeta_data()
    {
        $posts_data = $this->get_posts_data();
        $post_ids = array_column($posts_data['rows'], 'ID');
        $post_ids = implode(',', array_filter($post_ids));

        if (empty($post_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $postmeta = $this->db->get_results("SELECT * FROM {$this->db->postmeta} WHERE post_id IN ({$post_ids})", ARRAY_A);

        if (empty($postmeta)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($postmeta[0]), 'rows' => $postmeta];
    }

    private function get_comments_data()
    {
        $posts_data = $this->get_posts_data();
        $post_ids = array_column($posts_data['rows'], 'ID');
        $post_ids = implode(',', array_filter($post_ids));

        if (empty($post_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $comments = $this->db->get_results("SELECT * FROM {$this->db->comments} WHERE comment_post_ID IN ({$post_ids})", ARRAY_A);

        if (empty($comments)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($comments[0]), 'rows' => $comments];
    }
    private function get_commentmeta_data()
    {
        $comments_data = $this->get_comments_data();
        $comment_ids = array_column($comments_data['rows'], 'comment_ID');
        $comment_ids = implode(',', array_filter($comment_ids));

        if (empty($comment_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $commentmeta = $this->db->get_results("SELECT * FROM {$this->db->commentmeta} WHERE comment_id IN ({$comment_ids})", ARRAY_A);

        if (empty($commentmeta)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($commentmeta[0]), 'rows' => $commentmeta];
    }

    private function get_attachment_data()
    {
        $postmeta_values = $this->db->get_results(
            "SELECT meta_value 
             FROM {$this->db->postmeta} 
             WHERE meta_key IN ('_thumbnail_id', '_images') 
             OR meta_value LIKE '_field_images_%'",
            ARRAY_A
        );
        $this->push_value($postmeta_values);

        $termmeta_values = $this->db->get_results(
            "SELECT meta_value 
             FROM {$this->db->termmeta} 
             WHERE meta_key IN ('adqs_category_image_id', 'adqs_location_image_id')",
            ARRAY_A
        );
        $this->push_value($termmeta_values);

        $attachment = $this->db->get_results(
            "SELECT ID, post_parent, guid 
             FROM {$this->db->posts} 
             WHERE post_type = 'attachment' 
             AND ID IN (" . implode(',', array_filter($this->attachment_ids)) . ")",
            ARRAY_A
        );

        if (empty($attachment)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($attachment[0]), 'rows' => $attachment];
    }

    private function get_options_data()
    {
        $options = $this->db->get_results(
            "SELECT * 
             FROM {$this->db->options} 
             WHERE option_name='sidebars_widgets' OR option_name LIKE 'widget_adqs_%'",
            ARRAY_A
        );

        if (empty($options)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($options[0]), 'rows' => $options];
    }

    private function get_adqs_pricings_data()
    {
        $pricings = $this->db->get_results("SELECT * FROM {$this->db->prefix}adqs_pricings", ARRAY_A);

        if (empty($pricings)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($pricings[0]), 'rows' => $pricings];
    }

    private function get_adqs_pricing_meta_data()
    {
        $adqs_pricings_data = $this->get_adqs_pricings_data();
        $pricing_ids = array_column($adqs_pricings_data['rows'], 'id');
        $pricing_ids = implode(',', array_filter($pricing_ids));

        if (empty($pricing_ids)) {
            return ['headers' => [], 'rows' => []];
        }

        $pricing_meta = $this->db->get_results("SELECT * FROM {$this->db->prefix}adqs_pricing_meta WHERE pricing_id IN ({$pricing_ids})", ARRAY_A);

        if (empty($pricing_meta)) {
            return ['headers' => [], 'rows' => []];
        }

        return ['headers' => array_keys($pricing_meta[0]), 'rows' => $pricing_meta];
    }


    private function push_value($meta_value)
    {
        foreach ($meta_value as $value) {
            if ($this->Helper->is_serialized($value['meta_value'])) {
                $unserialized_value = maybe_unserialize($value['meta_value']);
                $this->attachment_ids = array_merge($this->attachment_ids, (array)$unserialized_value);
            } else {
                $this->attachment_ids[] = $value['meta_value'];
            }
        }
    }
}
