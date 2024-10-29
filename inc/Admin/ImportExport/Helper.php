<?php

namespace ADQS_Directory\Admin\ImportExport;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Helper
{

    public function read_csv_by_header($filepath)
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            return false;
        }

        $data = [];
        if (($handle = fopen($filepath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            if ($header) {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($header) === count($row)) {
                        $data[] = array_combine($header, $row);
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }

    public function replace_attachment_ids($meta_value = null, $attachment = null, $table = 'postmeta') {
        if (empty($meta_value) || empty($attachment)) {
            return;
        }

        global $wpdb;

        foreach ($meta_value as $value) {
            if ($this->is_serialized($value['meta_value'])) {
                $unserialized_value = maybe_unserialize($value['meta_value']);
                $update_meta_values = [];

                foreach ($unserialized_value as $key => $val) {
                    foreach ($attachment as $row) {
                        if (($row['ID'] ?? '') == $val) {
                            $parent_id = !empty(absint($row['post_parent'] ?? 0)) && isset($value['post_id']) ? absint($value['post_id']) : 0;
                            $attach_id = $this->get_or_upload_attachment($row['guid'], $parent_id);
                            $update_meta_values[$key] = $attach_id;
                        }
                    }
                }

                if (!empty($update_meta_values)) {
                    $wpdb->update($wpdb->$table, ['meta_value' => maybe_serialize(array_filter($update_meta_values))], ['meta_id' => $value['meta_id']]);
                }
            } else {
                if (!empty($value['meta_value'] ?? '')) {
                    foreach ($attachment as $row) {
                        if (($row['ID'] ?? '') == $value['meta_value']) {
                            $parent_id = !empty(absint($row['post_parent'] ?? 0)) && isset($value['post_id']) ? absint($value['post_id']) : 0;
                            $attach_id = $this->get_or_upload_attachment($row['guid'], $parent_id);
                            $wpdb->update(
                                $wpdb->$table,
                                ['meta_value' => $attach_id],
                                ['meta_id' => $value['meta_id']]
                            );
                        }
                    }
                }
            }
        }
    }

    public function get_or_upload_attachment($url, $parent_post_id = null) {
        // Check if the URL is an attachment URL already in the media library
        $attachment_id = attachment_url_to_postid($url);
        if ($attachment_id) {
            return $attachment_id;
        }

        // For external URLs, extract the filename
        $filename = basename($url);

        // Get the upload directory path
        $wp_upload_dir = wp_upload_dir();
        $file_path = $wp_upload_dir['path'] . '/' . $filename;

        // Check if the file already exists in the upload directory
        if (file_exists($file_path)) {
            // If the file exists, check if an attachment with this file path exists
            $attachment_id = $this->get_attachment_id_by_file_path($file_path);
            if ($attachment_id) {
                return $attachment_id;
            }

            // If no attachment found, create one from the existing file
            return $this->wp_insert_existing_attachment($file_path, $parent_post_id);
        }

        // If not found, proceed to download and upload the file
        return $this->wp_insert_attachment_from_url($url, $parent_post_id);
    }

    public function get_attachment_id_by_file_path($file_path) {
        global $wpdb;

        // Convert the file path to URL to use in the search
        $wp_upload_dir = wp_upload_dir();
        $file_url = str_replace($wp_upload_dir['basedir'], $wp_upload_dir['baseurl'], $file_path);

        // Query for attachment ID using the GUID field
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE guid = %s AND post_type = 'attachment'",
            $file_url
        ));

        return $attachment_id;
    }


    public function wp_insert_existing_attachment($file_path, $parent_post_id = null) {
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
        $wp_upload_dir = wp_upload_dir();

        $post_info = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title'     => $attachment_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment.
        $attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);

        // Include image.php.
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Generate the attachment metadata.
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

        // Assign metadata to attachment.
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }

    public function wp_insert_attachment_from_url($url, $parent_post_id = null) {
        if (!class_exists('WP_Http')) {
            require_once ABSPATH . WPINC . '/class-http.php';
        }

        $http = new \WP_Http();
        $response = $http->request($url);
        if (is_wp_error($response)) {
            return false; // Or handle the error as needed
        }
        if (200 !== $response['response']['code']) {
            return false;
        }

        $upload = wp_upload_bits(basename($url), null, $response['body']);
        if (!empty($upload['error'])) {
            return false;
        }

        $file_path = $upload['file'];
        return $this->wp_insert_existing_attachment($file_path, $parent_post_id);
    }



    public function is_serialized($data, $strict = true)
    {
        // If it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
                // Or else fall through.
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
        }
        return false;
    }
    public function send_response($message, $is_ajax)
    {
        if ($is_ajax) {
            wp_send_json_error($message);
        } else {
            wp_die($message);
        }
    }

    public function clean_import_dir($import_dir)
    {
        if (is_dir($import_dir)) {
            // Remove all files in the directory
            $files = glob($import_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            // Remove the directory itself
            rmdir($import_dir);
        }
    }
    public function generate_csv($filepath, $headers, $rows)
    {
        $file = fopen($filepath, 'w');
        fputcsv($file, $headers);

        foreach ($rows as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }

    public function create_zip($source, $destination)
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new \ZipArchive();
        if (!$zip->open($destination, \ZipArchive::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $file = str_replace('\\', '/', realpath($file->getPathname()));

                if (is_file($file) === true) {
                    $file_extension = pathinfo($file, PATHINFO_EXTENSION);
                    if (strtolower($file_extension) === 'csv') {
                        $zip->addFile($file, basename($file));
                    }
                }
            }
        }

        $zip->close();

        return true;
    }

    public function download_zip($zip_filepath)
    {
        if (file_exists($zip_filepath)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename=' . basename($zip_filepath));
            header('Content-Length: ' . filesize($zip_filepath));
            readfile($zip_filepath);
            exit;
        }
    }

    public function cleanup_export_dir($directory)
    {
        $files = glob($directory . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                rmdir($file);
            } elseif (is_file($file)) {
                $file_extension = pathinfo($file, PATHINFO_EXTENSION);
                if (!in_array($file_extension, ['csv', 'zip'])) {
                    unlink($file);
                }
            }
        }
    }

    public function has_pricing_extention()
    {
        $active_plugins = get_option('active_plugins');

        if (in_array('ad-pricing-package/ad-pricing-package.php', $active_plugins)) {
            return true;
        }
        return false;
    }
}
