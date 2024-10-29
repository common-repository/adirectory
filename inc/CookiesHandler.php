<?php

namespace ADQS_Directory;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class CookiesHandler
{
	private static $instance = null;
	private $cookie_name = 'adqs_visitor_data';
	private $cookie_lifetime = 3600; // 1 hour
	private $default_data = ['visitor' => 'guest']; // Default data

	private $cookie_options = [
		'path' => '/',
		'domain' => '',
		'secure' => false,
		'httponly' => true,
		'samesite' => 'Lax'
	];

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct()
	{
		$this->cookie_options['domain'] = $_SERVER['HTTP_HOST'] ?? '';
	}

	public function set($key, $value, $cookie_lifetime = null)
	{
		if (empty($cookie_lifetime)) {
			$cookie_lifetime = $this->cookie_lifetime;
		}


		$data = $this->get() ?? $this->default_data;

		if (!is_array($data)) {
			$data = $this->default_data;
		}

		$data[$key] = $value;

		$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
		if ($json_data === false) {
			return $this;
		}

		setcookie($this->cookie_name, $json_data, [
			'expires' => time() + $cookie_lifetime,
			'path' => $this->cookie_options['path'] ?? '/',
			'domain' => $this->cookie_options['domain'] ?? '',
			'secure' => $this->cookie_options['secure'] ?? false,
			'httponly' => $this->cookie_options['httponly'] ?? false,
			'samesite' => $this->cookie_options['samesite'] ?? 'None'
		]);

		return $this; // Allows method chaining
	}

	public function get($key = null, $default = null)
	{
		if (!isset($_COOKIE[$this->cookie_name])) {
			return $key === null ? $this->default_data : $default;
		}

		// Use wp_unslash to remove any extra slashes
		$cookie_value = wp_unslash($_COOKIE[$this->cookie_name]);

		$data = json_decode($cookie_value, true);

		if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
			return $key === null ? $cookie_value : $default;
		}

		return $key !== null ? ($data[$key] ?? $default) : $data;
	}

	public function has($key)
	{
		$data = $this->get();
		return isset($data[$key]);
	}

	public function remove($key)
	{
		$data = $this->get();
		if (isset($data[$key])) {
			unset($data[$key]);

			$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
			if ($json_data === false) {
				error_log('JSON encoding failed: ' . json_last_error_msg());
				return $this;
			}

			setcookie($this->cookie_name, $json_data, [
				'expires' => time() + $this->cookie_lifetime,
				'path' => $this->cookie_options['path'] ?? '/',
				'domain' => $this->cookie_options['domain'] ?? '',
				'secure' => $this->cookie_options['secure'] ?? false,
				'httponly' => $this->cookie_options['httponly'] ?? false,
				'samesite' => $this->cookie_options['samesite'] ?? 'None'
			]);
		}

		return $this;
	}

	public function clear()
	{
		setcookie($this->cookie_name, '', [
			'expires' => time() - 3600,
			'path' => $this->cookie_options['path'] ?? '/',
			'domain' => $this->cookie_options['domain'] ?? '',
			'secure' => $this->cookie_options['secure'] ?? false,
			'httponly' => $this->cookie_options['httponly'] ?? false,
			'samesite' => $this->cookie_options['samesite'] ?? 'None'
		]);

		return $this;
	}
}
