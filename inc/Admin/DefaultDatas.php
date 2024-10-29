<?php

namespace ADQS_Directory\Admin;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * DefaultDatas handlers class
 */
class DefaultDatas
{
	/**
	 * Method builder
	 *
	 * @return array
	 */
	public static function builder()
	{
		return array(
			array(
				'sectiontitle' => esc_html__('General Section', 'adirectory'),
				'id'           => '02652d5f-7374-43b5-be3d-8ad92345c11d',
				'fields'       => array(
					array(
						'fieldid'     => '9c206ad8-8110-495b-82f8-40ff45bb92c5',
						'input_type'  => 'pricing',
						'label'       => esc_html__('Pricing', 'adirectory'),
						'name'        => esc_html__('Pricing', 'adirectory'),
						'placeholder' => esc_html__('Pricing placeholder', 'adirectory'),
						'is_required' => 1,
					),
					array(
						'fieldid'     => '90641e6c-d62d-4e36-af4c-77ecc34193f4',
						'input_type'  => 'view_count',
						'label'       => esc_html__('View Count', 'adirectory'),
						'name'        => esc_html__('View Count', 'adirectory'),
						'placeholder' => esc_html__('view count placeholder', 'adirectory'),
						'is_required' => 1,
					),
				),
			),
			array(
				'sectiontitle' => esc_html__('Contact Section', 'adirectory'),
				'id'           => '80dc2f81-fbe6-4b55-ad6c-7a9c04e1e639',
				'fields'       => array(
					array(
						'fieldid'     => '607f0712-44a5-4379-92e6-598fb932c9af',
						'input_type'  => 'phone',
						'label'       => esc_html__('Phone', 'adirectory'),
						'name'        => esc_html__('Phone', 'adirectory'),
						'placeholder' => esc_html__('Enter phone number', 'adirectory'),
						'is_required' => 1,
					),
					array(
						'fieldid'     => 'e0b3572d-a708-4461-af7c-cc4386d43866',
						'input_type'  => 'zip',
						'label'       => esc_html__('Zip Code', 'adirectory'),
						'name'        => esc_html__('Zip Code', 'adirectory'),
						'placeholder' => esc_html__('Enter zip code', 'adirectory'),
					),
					array(
						'fieldid'     => 'a66f663a-2465-4d42-a0ca-77463173fd41',
						'input_type'  => 'website',
						'label'       => esc_html__('Website', 'adirectory'),
						'name'        => esc_html__('Website', 'adirectory'),
						'placeholder' => esc_html__('Enter website url', 'adirectory'),
					),
				),
			),
			array(
				'sectiontitle' => esc_html__('Map and Address', 'adirectory'),
				'id'           => 'ac1f4d3a-bca0-41fd-be1c-e6caf65649e7',
				'fields'       => array(
					array(
						'fieldid'     => 'f45b3f63-1a9e-4143-b0a1-e9c8a3009988',
						'input_type'  => 'address',
						'label'       => esc_html__('Address', 'adirectory'),
						'name'        => esc_html__('Address', 'adirectory'),
						'placeholder' => esc_html__('Enter address', 'adirectory'),
						'is_required' => 1,
					),
					array(
						'fieldid'     => '4c50f6ff-cdc4-4b29-96df-50468aee20ef',
						'input_type'  => 'map',
						'label'       => esc_html__('Map', 'adirectory'),
						'name'        => esc_html__('Map', 'adirectory'),
						'placeholder' => esc_html__('Enter map', 'adirectory'),
						'is_required' => 1,
					),
				),
			),
			array(
				'sectiontitle' => esc_html__('Video', 'adirectory'),
				'id'           => '98d51901-c1a2-4a44-b9c6-51ad84e2a494',
				'fields'       => array(
					array(
						'fieldid'     => '9d3c1b4e-29f0-42ed-8d2c-30cddaec8a77',
						'input_type'  => 'video',
						'label'       => esc_html__('Video', 'adirectory'),
						'name'        => esc_html__('Video', 'adirectory'),
						'placeholder' => esc_html__('Enter video url', 'adirectory'),
					),
				),
			),
		);
	}


	public static function email_templates()
	{
		return array(
			'admin' => array(
				'new_user_reg' => array(
					'subject' => "A new user has registered",
					'html_body' => "
						<tr>
							<td>
								<h1>New User Registration</h1>
								<p>A new user has just registered on your website.</p>
								<p><strong>Username:</strong> {user_name}</p>
								<p><strong>Email:</strong> {user_email}</p>
								<p>Please review their details and take the necessary actions.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'new_listing_sub' => array(
					'subject' => "A new listing has been submitted",
					'html_body' => "
						<tr>
							<td>
								<h1>New Listing Submitted</h1>
								<p>A new listing has been submitted on your website.</p>
								<p><strong>Listing Title:</strong> {listing_title}</p>
								<p><strong>Submitted By:</strong> {user_name}</p>
								<p>Please review the listing for approval.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'new_listing_up' => array(
					'subject' => "A listing has been updated",
					'html_body' => "
						<tr>
							<td>
								<h1>Listing Updated</h1>
								<p>A listing has been updated on your website.</p>
								<p><strong>Listing Title:</strong> {listing_title}</p>
								<p><strong>Updated By:</strong> {user_name}</p>
								<p>Please review the changes.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'order_created' => array(
					'subject' => "A new order has been created",
					'html_body' => "
						<tr>
							<td>
								<h1>New Order Created</h1>
								<p>A new order has been placed on your website.</p>
								<p><strong>Order ID:</strong> {order_id}</p>
								<p><strong>Customer:</strong> {customer_name}</p>
								<p>Please process the order as soon as possible.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'order_completed' => array(
					'subject' => "An order has been completed",
					'html_body' => "
						<tr>
							<td>
								<h1>Order Completed</h1>
								<p>An order has been completed on your website.</p>
								<p><strong>Order ID:</strong> {order_id}</p>
								<p><strong>Customer:</strong> {customer_name}</p>
								<p>Thank you for using our service.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
			),
			'user' => array(
				'new_user_reg' => array(
					'subject' => "Welcome to the website",
					'html_body' => "
						<tr>
							<td>
								<h1>Welcome, {user_name}!</h1>
								<p>Thank you for registering on our website.</p>
								<p>We are excited to have you with us. You can now start exploring and make the most of our services.</p>
								<p>If you have any questions, feel free to reach out to our support team.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'new_listing_sub' => array(
					'subject' => "Your listing has been submitted",
					'html_body' => "
						<tr>
							<td>
								<h1>Listing Submitted Successfully</h1>
								<p>Thank you, {user_name}, for submitting your listing on our website.</p>
								<p><strong>Listing Title:</strong> {listing_title}</p>
								<p>Your listing is now under review. You will be notified once it is approved.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'listing_is_approved' => array(
					'subject' => "Your listing has been approved",
					'html_body' => "
						<tr>
							<td>
								<h1>Congratulations, {user_name}!</h1>
								<p>Your listing titled '{listing_title}' has been approved.</p>
								<p>It is now live on our website. We hope it brings you great success.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'listing_about_expire' => array(
					'subject' => "Your listing is about to expire",
					'html_body' => "
						<tr>
							<td>
								<h1>Listing Expiration Notice</h1>
								<p>Dear {user_name}, your listing titled '{listing_title}' is about to expire.</p>
								<p>Please renew your listing to continue its visibility on our website.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'listing_expired' => array(
					'subject' => "Your listing has expired",
					'html_body' => "
						<tr>
							<td>
								<h1>Listing Expired</h1>
								<p>Dear {user_name}, your listing titled '{listing_title}' has expired.</p>
								<p>If you would like to renew your listing, please visit your account dashboard.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'order_created' => array(
					'subject' => "Your order has been received",
					'html_body' => "
						<tr>
							<td>
								<h1>Order Confirmation</h1>
								<p>Dear {customer_name}, your order has been received successfully.</p>
								<p><strong>Order ID:</strong> {order_id}</p>
								<p>We are processing your order and will notify you once it is completed.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
				'order_completed' => array(
					'subject' => "Your order is complete",
					'html_body' => "
						<tr>
							<td>
								<h1>Order Completed</h1>
								<p>Dear {customer_name}, your order has been completed successfully.</p>
								<p><strong>Order ID:</strong> {order_id}</p>
								<p>Thank you for your purchase. We hope you enjoy your product.</p>
								<p>This is an automated message, please do not reply.</p>
							</td>
						</tr>"
				),
			),
		);
	}


	public static function onborading_pages()
	{
		return array(
			array(

				'post_title'   => esc_html__('All Listings', 'adirectory'),
				'post_content' => '[adqs_listings]',
				'post_status'  => 'publish',
				'post_type'    => 'page',

				'page_key'     => 'adqs_all_listing',
			),
			array(
				'post_title'   => esc_html__('All Location', 'adirectory'),
				'post_content' => '[adqs_taxonomies tax_name="adqs_location"]',
				'post_status'  => 'publish',
				'post_type'    => 'page',

				'page_key'     => 'adqs_all_locations',
			),
			array(

				'post_title'   => esc_html__('All Categories', 'adirectory'),
				'post_content' => '[adqs_taxonomies tax_name="adqs_category"]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_all_categories',
			),
			array(
				'post_title'   => esc_html__('Add Listing', 'adirectory'),
				'post_content' => '[adqs_add_listing]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_add_listing',
			),
			array(
				'post_title'   => esc_html__('User Dashboard', 'adirectory'),
				'post_content' => '[adqs_dashboard]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_user_dashboard',
			),
			array(
				'post_title'   => esc_html__('Login - Registration', 'adirectory'),
				'post_content' => '[adqs_user_log_regi]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_login_regi',
			),
			array(
				'post_title'   => esc_html__('All Agents', 'adirectory'),
				'post_content' => '[adqs_agents]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'page_key'     => 'adqs_agents',
			),
		);
	}
}
