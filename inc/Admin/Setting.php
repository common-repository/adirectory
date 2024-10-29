<?php

namespace ADQS_Directory\Admin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class Setting
{
	public $settingsNavs;
	public $settingFields;
	public $currencyList;

	public function __construct()
	{

		$this->currencyList = [
			["label" => esc_html__("Argentine Peso $", "adirectory"), "value" => 'ARS__$__before'],
			["label" => esc_html__("Australian Dollar $", "adirectory"), "value" => 'AUD__$__before'],
			["label" => esc_html__("Bangladeshi Taka ৳", "adirectory"), "value" => 'BDT__৳__after'],
			["label" => esc_html__("Bahraini Dinar BD", "adirectory"), "value" => 'BHD__BD__before'],
			["label" => esc_html__("Botswana Pula P", "adirectory"), "value" => 'BWP__P__before'],
			["label" => esc_html__("Canadian Dollar $", "adirectory"), "value" => 'CAD__$__before'],
			["label" => esc_html__("Swiss Franc CHF", "adirectory"), "value" => 'CHF__CHF__before'],
			["label" => esc_html__("Chilean Peso $", "adirectory"), "value" => 'CLP__$__before'],
			["label" => esc_html__("Chinese Yuan ¥", "adirectory"), "value" => 'CNY__¥__before'],
			["label" => esc_html__("Colombian Peso $", "adirectory"), "value" => 'COP__$__before'],
			["label" => esc_html__("Czech Koruna Kč", "adirectory"), "value" => 'CZK__Kč__after'],
			["label" => esc_html__("Danish Krone kr", "adirectory"), "value" => 'DKK__kr__after'],
			["label" => esc_html__("Euro €", "adirectory"), "value" => 'EUR__€__before'],
			["label" => esc_html__("British Pound £", "adirectory"), "value" => 'GBP__£__before'],
			["label" => esc_html__("Hong Kong Dollar HK$", "adirectory"), "value" => 'HKD__$__before'],
			["label" => esc_html__("Croatian Kuna kn", "adirectory"), "value" => 'HRK__kn__after'],
			["label" => esc_html__("Hungarian Forint Ft", "adirectory"), "value" => 'HUF__Ft__after'],
			["label" => esc_html__("Indian Rupee ₹", "adirectory"), "value" => 'INR__₹__before'],
			["label" => esc_html__("Indonesian Rupiah Rp", "adirectory"), "value" => 'IDR__Rp__before'],
			["label" => esc_html__("Israeli New Shekel ₪", "adirectory"), "value" => 'ILS__₪__before'],
			["label" => esc_html__("Japanese Yen ¥", "adirectory"), "value" => 'JPY__¥__before'],
			["label" => esc_html__("South Korean Won ₩", "adirectory"), "value" => 'KRW__₩__before'],
			["label" => esc_html__("Sri Lankan Rupee ₨", "adirectory"), "value" => 'LKR__₨__before'],
			["label" => esc_html__("Mexican Peso $", "adirectory"), "value" => 'MXN__$__before'],
			["label" => esc_html__("Malaysian Ringgit RM", "adirectory"), "value" => 'MYR__RM__before'],
			["label" => esc_html__("Nigerian Naira ₦", "adirectory"), "value" => 'NGN__₦__before'],
			["label" => esc_html__("Norwegian Krone kr", "adirectory"), "value" => 'NOK__kr__after'],
			["label" => esc_html__("New Zealand Dollar NZ$", "adirectory"), "value" => 'NZD__$__before'],
			["label" => esc_html__("Pakistani Rupee ₨", "adirectory"), "value" => 'PKR__₨__before'],
			["label" => esc_html__("Polish Zloty zł", "adirectory"), "value" => 'PLN__zł__after'],
			["label" => esc_html__("Russian Ruble ₽", "adirectory"), "value" => 'RUB__₽__after'],
			["label" => esc_html__("Saudi Riyal ر.س", "adirectory"), "value" => 'SAR__ر.س__after'],
			["label" => esc_html__("Swedish Krona kr", "adirectory"), "value" => 'SEK__kr__after'],
			["label" => esc_html__("Singapore Dollar S$", "adirectory"), "value" => 'SGD__$__before'],
			["label" => esc_html__("Thai Baht ฿", "adirectory"), "value" => 'THB__฿__before'],
			["label" => esc_html__("Trinidad and Tobago Dollar TT$", "adirectory"), "value" => 'TTD__$__before'],
			["label" => esc_html__("Turkish Lira ₺", "adirectory"), "value" => 'TRY__₺__before'],
			["label" => esc_html__("United States Dollar US$", "adirectory"), "value" => 'USD__$__before'],
			["label" => esc_html__("Vietnamese Dong ₫", "adirectory"), "value" => 'VND__₫__after'],
			["label" => esc_html__("South African Rand R", "adirectory"), "value" => 'ZAR__R__before'],
		];



		$active_plugins = get_option('active_plugins');



		$this->settingsNavs = array(
			array(
				"title" => esc_html__("General", "adirectory"),
				"path"  => "general",
				'icon'  => "fa-solid fa-sliders",
			),

			array(
				"title" => esc_html__("Pages", "adirectory"),
				"path"  => "pages",
				"sub_setting" => array(),
				'icon'  => "fa-regular fa-file-lines",
			),
			array(
				"title" => esc_html__("Shortcodes", "adirectory"),
				"path" => "shortcodes",
				'icon'  => "fa-solid fa-code",
				"sub_setting" => array(),
			),
			array(
				"title" => esc_html__("Emails", "adirectory"),
				"path" => "emails",
				'icon'  => "fa-regular fa-envelope",
				"sub_settings" => array(
					array(
						"title" => esc_html__("Email General", "adirectory"),
						"path" => "triggers",
					),
					array(
						"title" => esc_html__(" Email Templates", "adirectory"),
						"path" => "templates",
					),
				)
			),
			array(
				"title" => esc_html__("Seo Schema", "adirectory"),
				"path" => "seo_schema",
				'icon'  => "fa-solid fa-search",
				"sub_setting" => array(),
			),
		);



		// Add Payment settings if ad-pricing-package plugin is active
		if (in_array('ad-pricing-package/ad-pricing-package.php', $active_plugins)) {
			$this->settingsNavs[] = array(
				"title" => esc_html__("Payment", "adirectory"),
				"path"  => "payment",
				"icon"  => "fa-regular fa-credit-card",
				"sub_settings" => array(
					array(
						"title" => esc_html__("Bank Transfer", "adirectory"),
						"path" => "bank_transfer"
					),
					array(
						"title" => esc_html__("Paypal", "adirectory"),
						"path" => "paypal"
					),
				),
			);
		}

		// Add Stripe sub-setting if ad-pricing-stripe-payment plugin is active
		if (in_array('ad-pricing-stripe-payment/ad-pricing-stripe-payment.php', $active_plugins)) {
			foreach ($this->settingsNavs as &$nav) {
				if ($nav['path'] === 'payment') {
					$nav['sub_settings'][] = array(
						"title" => esc_html__("Stripe", "adirectory"),
						"path" => "stripe"
					);
					break;
				}
			}
		}

		$this->settingsNavs[] = array(
			"title" => esc_html__("Extensions", "adirectory"),
			"path"  => "extension",
			"sub_setting" => array(),
		);





		$this->settingFields = array(
			array(
				"path" => "general",
				"input" => "number",
				"label" => esc_html__("Listing default expire days", "adirectory"),
				'option_name' => 'listing_expiry_date',
				'value' => 15,
			),
			array(
				"path" => "general",
				"input" => "number",
				"label" => esc_html__("Listing per page", "adirectory"),
				'option_name' => 'listing_per_page',
				'value' => 6,
			),
			array(
				"path" => "general",
				"input" => "media",
				"label" => esc_html__('Default Preview Image', 'adirectory'),
				"option_name" => "default_preview_image",
			),
			array(
				"path" => "general",
				"input" => "toggle",
				"label" => esc_html__('Hide email agent page', 'adirectory'),
				"option_name" => "hide_author_email",
				'value' => '0',
			),
			array(
				"path" => "general",
				"input" => "dropdown",
				"label" => esc_html__("Select Currency", "adirectory"),
				"option_name" => "select_currency",
				"value" => 'USD__$__before',
				"options" => $this->currencyList,
			),
			array(
				"path" => "all_listing",
				"input" => "text",
				"label" => esc_html__("Filters Button Text", "adirectory"),
				'option_name' => 'filter_btn_text'
			),
			array(
				"path" => "paypal",
				"input" => "textarea",
				"label" => esc_html__("Client ID", "adirectory"),
				'option_name' => "paypal_client_id"
			),
			array(
				"path" => "stripe",
				"input" => "toggle",
				"label" => esc_html__('Card With Ajax', 'adirectory'),
				"option_name" => "stripe_card_ajax",
				'value' => '1'
			),
			array(
				"path" => "stripe",
				"input" => "textarea",
				"label" => esc_html__("Publishable Key", "adirectory"),
				'option_name' => "stripe_key"
			),
			array(
				"path" => "stripe",
				"input" => "textarea",
				"label" => esc_html__("Secret Key", "adirectory"),
				'option_name' => "stripe_secret"
			),
			array(
				"path" => "payment",
				"input" => "checkbox",
				"label" => esc_html__("Payment Methods", "adirectory"),
				'option_name' => 'payment_methods',
				'value' => array(
					'paypal',
					'stripe',
					'paddle'
				),
				'options' => array(
					array(
						"label" => esc_html__("Paypal", "adirectory"),
						"value" => "paypal"
					),
					array(
						"label" => esc_html__("Stripe", "adirectory"),
						"value" => "stripe"
					),
					array(
						"label" => esc_html__("Paddle", "adirectory"),
						"value" => "paddle"
					),
				)
			),
			array(
				"path" => "bank_transfer",
				"input" => "toggle",
				"label" => esc_html__("Enable/Disable", "adirectory"),
				'option_name' => 'enable_bank_transfer',
				'value' => '1',
			),
			array(
				"path" => "bank_transfer",
				"input" => "editor",
				"label" => esc_html__('Bank Account Details', 'adirectory'),
				"option_name" => "bank_ac_details",
				"value" => '<b>Bank Name:</b> Your bank name.<br>
                <b>Account Number:</b> Your bank account number.<br>
                <b>Routing Number:</b> Bank routing number.<br>
                <b>Branch:</b> Your bank branch name.',
			),
			array(
				"path" => "pages",
				"input" => "asynccb",
				"label" => esc_html__("Regenerate Pages", "adirectory"),
				"cb" => "const formdata = new FormData();
formdata.append('action', 'adqs_gen_common_asset');
formdata.append('security', window.qsdObj.adqs_admin_nonce);

const spinner = buttonRefs.current[index].querySelector('.asyn-btn-spinner');
const fieldlabel = buttonRefs.current[index].querySelector('.field-async-label');
spinner.style.display = 'block';

try {
    const request = await fetch(window.ajaxurl, {
        method: 'POST',
        body: formdata,
    });
    const response = await request.json();
    if(response){
        if (buttonRefs.current[index]) {
            fieldlabel.innerHTML = 'Pages Regenerated';
            spinner.style.display = 'none';
        }
    }
} catch (error) {
    alert('failed');
}",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("All Listing Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_listings]",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("All Location Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_taxonomies tax_name='adqs_location']",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("All Categories Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_taxonomies tax_name='adqs_category']",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("Add Listing Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_add_listing]",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("User Dashboard Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_dashboard]",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("Login/Registration Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_user_log_regi]",
			),
			array(
				"path" => "shortcodes",
				"input" => "shortcode",
				"label" => esc_html__("All Agents Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "[adqs_agents]",
			),
			array(
				"path" => "triggers",
				"input" => "text",
				"label" => esc_html__("From email name", "adirectory"),
				'option_name' => 'from_email_name',
				'value' => '',
			),
			array(
				"path" => "triggers",
				"input" => "text",
				"label" => esc_html__("From email address", "adirectory"),
				'option_name' => 'from_email_address',
				'value' => '',
			),
			array(
				"path" => "triggers",
				"input" => "checkbox",
				"label" => esc_html__("Notify admin via email when", 'adirectory'),
				'option_name' => 'adqs_admin_emails_triggers',
				'value' => array(
					esc_html__('new_user_reg', 'adirectory'),
					esc_html__('new_listing_sub', 'adirectory'),
					esc_html__('new_listing_up', 'adirectory'),
					esc_html__('order_created', 'adirectory'),
					esc_html__('order_completed', 'adirectory'),
				),
				'options' => array(
					array(
						"label" => esc_html__("New user register", 'adirectory'),
						"value" => esc_html__("new_user_reg", 'adirectory')
					),
					array(
						"label" => esc_html__("New listing submitted", 'adirectory'),
						"value" => esc_html__("new_listing_sub", 'adirectory')
					),
					array(
						"label" => esc_html__("Listing updated", 'adirectory'),
						"value" => esc_html__("new_listing_up", 'adirectory')
					),
					array(
						"label" => esc_html__("Oredr created", 'adirectory'),
						"value" => esc_html__("order_created", 'adirectory')
					),
					array(
						"label" => esc_html__("Order complete", 'adirectory'),
						"value" => esc_html__("order_completed", 'adirectory')
					),
				)
			),
			array(
				"path" => "triggers",
				"input" => "checkbox",
				"label" => esc_html__("Notify user via email when", 'adirectory'),
				'option_name' => 'adqs_user_emails_triggers',
				'value' => array(
					esc_html__('new_user_reg', 'adirectory'),
					esc_html__('new_listing_sub', 'adirectory'),
					esc_html__('listing_is_approved', 'adirectory'),
					esc_html__('listing_about_expire', 'adirectory'),
					esc_html__('listing_expired', 'adirectory'),
					esc_html__('order_created', 'adirectory'),
					esc_html__('order_completed', 'adirectory'),
				),
				'options' => array(
					array(
						"label" => esc_html__("New user register", 'adirectory'),
						"value" => esc_html__("new_user_reg", 'adirectory')
					),
					array(
						"label" => esc_html__("New listing submitted", 'adirectory'),
						"value" => esc_html__("new_listing_sub", 'adirectory')
					),
					array(
						"label" => esc_html__("Listing approved", 'adirectory'),
						"value" => esc_html__("listing_is_approved", 'adirectory')
					),
					array(
						"label" => esc_html__("Listing about to expire", 'adirectory'),
						"value" => esc_html__("listing_about_expire", 'adirectory')
					),
					array(
						"label" => esc_html__("Listing expired", 'adirectory'),
						"value" => esc_html__("listing_expired", 'adirectory')
					),
					array(
						"label" => esc_html__("Oredr created", 'adirectory'),
						"value" => esc_html__("order_created", 'adirectory')
					),
					array(
						"label" => esc_html__("Order complete", 'adirectory'),
						"value" => esc_html__("order_completed", 'adirectory')
					),
				)
			),
			array(
				"path" => "templates",
				"input" => "component",
				"label" => esc_html__("All Agents Shortcode", "adirectory"),
				"help_desc" => "",
				"value" => "",
			),
			array(
				"path" => "seo_schema",
				"input" => "checkbox",
				"label" => esc_html__("Enable Schema Options", 'adirectory'),
				'option_name' => 'schema_options',
				'value' => array(
					esc_html__('headline', 'adirectory'),
					esc_html__('tagline', 'adirectory'),
					esc_html__('description', 'adirectory'),
					esc_html__('image', 'adirectory'),
					esc_html__('address', 'adirectory'),
					esc_html__('geo', 'adirectory'),
					esc_html__('telephone', 'adirectory'),
					esc_html__('fax', 'adirectory'),
					esc_html__('email', 'adirectory'),
					esc_html__('website', 'adirectory'),
					esc_html__('author', 'adirectory'),
					esc_html__('rating', 'adirectory'),
					esc_html__('date', 'adirectory'),
				),
				'options' => array(
					array(
						"label" => esc_html__("Headline", 'adirectory'),
						"value" => esc_html__("headline", 'adirectory')
					),
					array(
						"label" => esc_html__("Tagline", 'adirectory'),
						"value" => esc_html__("tagline", 'adirectory')
					),
					array(
						"label" => esc_html__("Description", 'adirectory'),
						"value" => esc_html__("description", 'adirectory')
					),
					array(
						"label" => esc_html__("Image", 'adirectory'),
						"value" => esc_html__("image", 'adirectory')
					),
					array(
						"label" => esc_html__("Address", 'adirectory'),
						"value" => esc_html__("address", 'adirectory')
					),
					array(
						"label" => esc_html__("Geo Location", 'adirectory'),
						"value" => esc_html__("geo", 'adirectory')
					),
					array(
						"label" => esc_html__("Telephone", 'adirectory'),
						"value" => esc_html__("telephone", 'adirectory')
					),
					array(
						"label" => esc_html__("Fax", 'adirectory'),
						"value" => esc_html__("fax", 'adirectory')
					),
					array(
						"label" => esc_html__("Email", 'adirectory'),
						"value" => esc_html__("email", 'adirectory')
					),
					array(
						"label" => esc_html__("Website", 'adirectory'),
						"value" => esc_html__("website", 'adirectory')
					),
					array(
						"label" => esc_html__("Author", 'adirectory'),
						"value" => esc_html__("author", 'adirectory')
					),
					array(
						"label" => esc_html__("Rating", 'adirectory'),
						"value" => esc_html__("rating", 'adirectory')
					),
					array(
						"label" => esc_html__("Date", 'adirectory'),
						"value" => esc_html__("date", 'adirectory')
					),
				)
			),

		);
	}

	public static function get_settings_nav()
	{
		$self = new self();
		return apply_filters("adqs_settings_nav", $self->settingsNavs);
	}

	public static function get_settings_fields()
	{
		$self = new self();
		return apply_filters("adqs_settings_fields", $self->settingFields);
	}

	public static function get_single_setting($option_name, $default = '')
	{
		$options = get_option('adqs_admin_settings', array());
		return $options[$option_name] ?? $default;
	}
}
