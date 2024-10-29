<?php

namespace ADQS_Directory;

use ADQS_Directory\EmailSender;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Formhandler
{
	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		add_action('wp_loaded', array($this, 'login_register_processor'));
	}

	/**
	 * Method login_register_processor
	 *
	 * @return void
	 */
	public function login_register_processor()
	{
		if (wp_doing_ajax()) {
			return false;
		}

		if (isset($_POST['adqs_login_regi_submit'])) {

			if (isset($_POST['adqs_login_form'])) {
				$this->process_login();
			} else {
				$this->process_registration();
			}
		}
	}

	/**
	 * Method process_login
	 *
	 * @return void
	 */
	public function process_login()
	{

		$login_nonce = isset($_POST['adqs-login-nonce']) ? sanitize_text_field(wp_unslash($_POST['adqs-login-nonce'])) : '';

		if (!wp_verify_nonce($login_nonce, 'adqs-login')) {
			$this->display_error('Something went wrong!');
			return;
		}


		$username = sanitize_text_field(wp_unslash($_POST['log']));
		$password = $_POST['password'];



		$credentials = [
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => isset($_POST['keeplogged']) ? sanitize_text_field($_POST['keeplogged']) : false,
		];

		$redirect = isset($_POST['redirect_to']) ? sanitize_url($_POST['redirect_to']) : (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$user = wp_signon($credentials, false);

		if (is_wp_error($user)) {
			$this->display_error('Something went wrong!');
		} else {
			wp_redirect($redirect);
			exit;
		}
	}

	/**
	 * Method process_registration
	 *
	 * @return void
	 */
	public function process_registration()
	{
		$regi_nonce = isset($_POST['adqs-regi-nonce']) ? sanitize_text_field(wp_unslash($_POST['adqs-regi-nonce'])) : '';

		if (!wp_verify_nonce($regi_nonce, 'adqs-regi')) {
			$this->display_error('Something went wrong!');
			return;
		}

		$terms = isset($_POST['termscondition']) ? true : false;
		$newsletter_check = isset($_POST['newsletter_check']) ? true : false;

		if (!$terms) {
			$this->display_error('You must accept terms and privacy poilcy');
			return;
		}

		$firstname = isset($_POST['fname']) ? sanitize_text_field(wp_unslash($_POST['fname'])) : '';
		$lastname = isset($_POST['lname']) ? sanitize_text_field(wp_unslash($_POST['lname'])) : '';

		$username = $firstname . " " . $lastname;

		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		$confirm_password = isset($_POST['confirmpass']) ? trim($_POST['confirmpass']) : '';
		$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';


		if (!username_exists($username) && $password === $confirm_password && !empty($email)) {
			$user_id = wp_insert_user(array(
				'user_login' => $username,
				'user_email' => $email,
				'user_pass'  => $password,
				'role' => 'subscriber'
			));

			if (is_wp_error($user_id)) {
				$this->display_error('Something went wrong!');
			}

			if (!is_wp_error($user_id)) {
				do_action("adqs_after_new_registration", $user_id, $firstname, $lastname, $email, $newsletter_check);
				$this->display_success("Registration completed do login ");
			}
		} else {
			$this->display_error('Username already exists or password doesn’t match!');
		}
	}

	/**
	 * Method display_error
	 *
	 * @param $message $message [explicite description]
	 *
	 * @return void
	 */
	public function display_error($message)
	{
		add_action('adqs_regi_login_error', function () use ($message) {
			echo "<div class='adqs-login-regi-error'>{$message}</div>";
		});
	}


	public function display_success($message)
	{
		add_action('adqs_regi_login_error', function () use ($message) {
			echo "<div class='adqs-login' style='background-color:green;'>{$message}</div>";
		});
	}
}
