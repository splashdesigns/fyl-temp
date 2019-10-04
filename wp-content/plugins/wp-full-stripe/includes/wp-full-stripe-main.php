<?php

/*
WP Full Stripe
https://paymentsplugin.com
Complete Stripe payments integration for Wordpress
Mammothology
4.0.2
https://paymentsplugin.com
*/

class MM_WPFS {

	const VERSION = '4.0.2';
	const REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS = 'wpfs_rendered_forms';

	const HANDLE_WP_FULL_STRIPE_JS = 'wp-full-stripe-js';

	const SHORTCODE_FULLSTRIPE_FORM = 'fullstripe_form';
	const SHORTCODE_FULLSTRIPE_THANKYOU = 'fullstripe_thankyou';
	const SHORTCODE_FULLSTRIPE_THANKYOU_SUCCESS = 'fullstripe_thankyou_success';
	const SHORTCODE_FULLSTRIPE_THANKYOU_DEFAULT = 'fullstripe_thankyou_default';
	/**
	 * @deprecated
	 */
	const SHORTCODE_FULLSTRIPE_SUBSCRIPTION_CHECKOUT = 'fullstripe_subscription_checkout';
	/**
	 * @deprecated
	 */
	const SHORTCODE_FULLSTRIPE_CHECKOUT = 'fullstripe_checkout';
	/**
	 * @deprecated
	 */
	const SHORTCODE_FULLSTRIPE_SUBSCRIPTION = 'fullstripe_subscription';
	/**
	 * @deprecated
	 */
	const SHORTCODE_FULLSTRIPE_PAYMENT = 'fullstripe_payment';
	/**
	 * @deprecated
	 */
	const HANDLE_WP_FULL_STRIPE_JS_V_3 = 'wp-full-stripe-v3-js';
	const HANDLE_WP_FULL_STRIPE_UTILS_JS = 'wp-full-stripe-utils-js';
	const HANDLE_SPRINTF_JS = 'sprintf-js';
	const HANDLE_STRIPE_CHECKOUT_JS = 'checkout-js';
	/**
	 * @deprecated
	 */
	const HANDLE_STRIPE_JS_V_2 = 'stripe-js-v2';
	const HANDLE_STRIPE_JS_V_3 = 'stripe-js-v3';
	const HANDLE_STYLE_WPFS_VARIABLES = 'wpfs-variables-css';
	const HANDLE_STYLE_WPFS_FORMS = 'wpfs-forms-css';
	const HANDLE_GOOGLE_RECAPTCHA_V_2 = 'google-recaptcha-v2';
	const URL_RECAPTCHA_API_SITEVERIFY = 'https://www.google.com/recaptcha/api/siteverify';
	const SOURCE_GOOGLE_RECAPTCHA_V2_API_JS = 'https://www.google.com/recaptcha/api.js';

	const FILTER_NAME_GET_VAT_PERCENT = 'fullstripe_get_vat_percent';

	const FORM_TYPE_PAYMENT = 'payment';
	const FORM_TYPE_CHECKOUT_SUBSCRIPTION = 'checkout-subscription';
	const FORM_TYPE_SUBSCRIPTION = 'subscription';
	const FORM_TYPE_CHECKOUT = 'checkout';

	// tnagy new form type denominations 
	const FORM_TYPE_INLINE_PAYMENT = 'inline_payment';
	const FORM_TYPE_POPUP_PAYMENT = 'popup_payment';
	const FORM_TYPE_INLINE_SUBSCRIPTION = 'inline_subscription';
	const FORM_TYPE_POPUP_SUBSCRIPTION = 'popup_subscription';
	const FORM_TYPE_INLINE_SAVE_CARD = 'inline_save_card';
	const FORM_TYPE_POPUP_SAVE_CARD = 'popup_save_card';

	const VAT_RATE_TYPE_NO_VAT = 'no_vat';
	const VAT_RATE_TYPE_FIXED_VAT = 'fixed_vat';
	const VAT_RATE_TYPE_CUSTOM_VAT = 'custom_vat';

	const NO_VAT_PERCENT = 0.0;

	const DEFAULT_BILLING_COUNTRY_INITIAL_VALUE = 'US';

	const PREFERRED_LANGUAGE_AUTO = 'auto';

	const DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT = 10;

	const PAYMENT_TYPE_LIST_OF_AMOUNTS = 'list_of_amounts';
	const PAYMENT_TYPE_CUSTOM_AMOUNT = 'custom_amount';
	const PAYMENT_TYPE_SPECIFIED_AMOUNT = 'specified_amount';
	const PAYMENT_TYPE_CARD_CAPTURE = 'card_capture';

	const CURRENCY_USD = 'usd';

	const OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA = 'secure_inline_forms_with_google_recaptcha';
	const OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA = 'secure_subscription_update_with_google_recaptcha';
	const OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY = 'google_recaptcha_site_key';
	const OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY = 'google_recaptcha_secret_key';

	const CHARGE_TYPE_IMMEDIATE = 'immediate';
	const CHARGE_TYPE_AUTHORIZE_AND_CAPTURE = 'authorize_and_capture';

	const PAYMENT_METHOD_CARD = 'card';

	const STRIPE_CHARGE_STATUS_SUCCEEDED = 'succeeded';
	const STRIPE_CHARGE_STATUS_PENDING = 'pending';
	const STRIPE_CHARGE_STATUS_FAILED = 'failed';

	const STRIPE_SUBSCRIPTION_STATUS_TRAILING = 'trialing';
	const STRIPE_SUBSCRIPTION_STATUS_ACTIVE = 'active';
	const STRIPE_SUBSCRIPTION_STATUS_PAST_DUE = 'past_due';
	const STRIPE_SUBSCRIPTION_STATUS_CANCELED = 'canceled';
	const STRIPE_SUBSCRIPTION_STATUS_UNPAID = 'unpaid';

	const PAYMENT_STATUS_UNKNOWN = 'unknown';
	const PAYMENT_STATUS_FAILED = 'failed';
	const PAYMENT_STATUS_REFUNDED = 'refunded';
	const PAYMENT_STATUS_EXPIRED = 'expired';
	const PAYMENT_STATUS_PAID = 'paid';
	const PAYMENT_STATUS_AUTHORIZED = 'authorized';
	const PAYMENT_STATUS_PENDING = 'pending';
	const PAYMENT_STATUS_RELEASED = 'released';

	const REFUND_STATUS_SUCCEEDED = 'succeeded';
	const REFUND_STATUS_FAILED = 'failed';
	const REFUND_STATUS_PENDING = 'pending';
	const REFUND_STATUS_CANCELED = 'canceled';

	const SUBSCRIPTION_STATUS_ENDED = 'ended';
	const SUBSCRIPTION_STATUS_CANCELLED = 'cancelled';

	const AMOUNT_SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';
	const AMOUNT_SELECTOR_STYLE_DROPDOWN = 'dropdown';
	const AMOUNT_SELECTOR_STYLE_BUTTON_GROUP = 'button-group';

	const PLAN_SELECTOR_STYLE_DROPDOWN = 'dropdown';
	const PLAN_SELECTOR_STYLE_LIST = 'list';

	const JS_VARIABLE_AJAX_URL = 'wpfsAjaxURL';
	const JS_VARIABLE_STRIPE_KEY = 'wpfsStripeKey';
	const JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY = 'wpfsGoogleReCAPTCHASiteKey';
	const JS_VARIABLE_L10N = 'wpfsL10n';
	const JS_VARIABLE_FORM_FIELDS = 'wpfsFormFields';

	public static $instance;

	private $debugLog = false;

	/* @var MM_WPFS_Customer */
	private $customer = null;
	/* @var MM_WPFS_Admin */
	private $admin = null;
	/* @var MM_WPFS_Database */
	private $database = null;
	/* @var MM_WPFS_Stripe */
	private $stripe = null;
	/* @var MM_WPFS_Admin_Menu */
	private $adminMenu = null;

	/* @var MM_WPFS_TransactionDataService */
	private $transactionDataService = null;

	/* @var MM_WPFS_CardUpdateService */
	private $cardUpdateService = null;
	/**
	 * @var bool Choose to load scripts and styles the WordPress way. We should move this field to a wp_option later.
	 */
	private $loadScriptsAndStylesWithActionHook = false;

	public function __construct() {

		$this->includes();
		$this->setup();
		$this->hooks();

	}

	function includes() {

		include 'wp-full-stripe-admin.php';
		include 'wp-full-stripe-admin-menu.php';
		include 'wp-full-stripe-form-models.php';
		include 'wp-full-stripe-assets.php';
		include 'wp-full-stripe-card-update-service.php';
		include 'wp-full-stripe-countries.php';
		include 'wp-full-stripe-currencies.php';
		include 'wp-full-stripe-customer.php';
		include 'wp-full-stripe-database.php';
		include 'wp-full-stripe-mailer.php';
		include 'wp-full-stripe-news-feed-url.php';
		include 'wp-full-stripe-patcher.php';
		include 'wp-full-stripe-payments.php';
		include 'wp-full-stripe-public-form-views.php';
		include 'wp-full-stripe-transaction-data-service.php';
		include 'wp-full-stripe-validators.php';
		include 'wp-full-stripe-web-hook-events.php';

		do_action( 'fullstripe_includes_action' );
	}

	function setup() {

		//set option defaults
		$options = get_option( 'fullstripe_options' );
		if ( ! $options || $options['fullstripe_version'] != self::VERSION ) {
			$this->set_option_defaults( $options );
			// tnagy reload saved options
			$options = get_option( 'fullstripe_options' );
		}

		$this->update_option_defaults( $options );

		// tnagy reload saved options and check edd license status
		$options       = get_option( 'fullstripe_options' );
		$licenseStatus = $options['edd_license_status'];
		if ( $licenseStatus == 'unknown' || $licenseStatus == 'inactive' ) {
			$this->activate_license();
		}

		//set API key
		if ( $options['apiMode'] === 'test' ) {
			$this->fullstripe_set_api_key_and_version( $options['secretKey_test'] );
		} else {
			$this->fullstripe_set_api_key_and_version( $options['secretKey_live'] );
		}

		//setup subclasses to handle everything
		$this->admin                  = new MM_WPFS_Admin();
		$this->adminMenu              = new MM_WPFS_Admin_Menu();
		$this->customer               = new MM_WPFS_Customer();
		$this->database               = new MM_WPFS_Database();
		$this->stripe                 = new MM_WPFS_Stripe();
		$this->transactionDataService = new MM_WPFS_TransactionDataService();
		$this->cardUpdateService      = new MM_WPFS_CardUpdateService();

		do_action( 'fullstripe_setup_action' );

	}

	function set_option_defaults( $options ) {
		if ( ! $options ) {

			$emailReceipts = MM_WPFS_Utils::create_default_email_receipts();

			/** @noinspection PhpUndefinedClassInspection */
			$arr = array(
				'secretKey_test'                                                  => 'YOUR_TEST_SECRET_KEY',
				'publishKey_test'                                                 => 'YOUR_TEST_PUBLISHABLE_KEY',
				'secretKey_live'                                                  => 'YOUR_LIVE_SECRET_KEY',
				'publishKey_live'                                                 => 'YOUR_LIVE_PUBLISHABLE_KEY',
				'apiMode'                                                         => 'test',
				'form_css'                                                        => ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}",
				'includeStyles'                                                   => '1',
				'receiptEmailType'                                                => 'plugin',
				'email_receipts'                                                  => json_encode( $emailReceipts ),
				'email_receipt_sender_address'                                    => '',
				'admin_payment_receipt'                                           => '0',
				'lock_email_field_for_logged_in_users'                            => '1',
				'fullstripe_version'                                              => self::VERSION,
				'webhook_token'                                                   => $this->create_webhook_token(),
				'edd_license_key'                                                 => MM_WPFS_License::KEY,
				'edd_license_status'                                              => 'unknown',
				'custom_input_field_max_count'                                    => MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT,
				MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA        => '0',
				MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA => '0',
				MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY                        => 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY',
				MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY                      => 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY'
			);

			update_option( 'fullstripe_options', $arr );
		} else {

			// different version

			$options['fullstripe_version'] = self::VERSION;
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( 'form_css', $options ) ) {
				$options['form_css'] = ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}";
			}
			if ( ! array_key_exists( 'includeStyles', $options ) ) {
				$options['includeStyles'] = '1';
			}
			if ( ! array_key_exists( 'receiptEmailType', $options ) ) {
				$options['receiptEmailType'] = 'plugin';
			}
			if ( ! array_key_exists( 'email_receipts', $options ) ) {
				$emailReceipts             = MM_WPFS_Utils::create_default_email_receipts();
				$options['email_receipts'] = json_encode( $emailReceipts );
			} else {
				$emailReceipts = json_decode( $options['email_receipts'] );
				if ( ! array_key_exists( 'cardCaptured', $emailReceipts ) ) {
					$emailReceipts->cardCaptured = MM_WPFS_Utils::create_default_card_captured_email_receipt();
					$options['email_receipts']   = json_encode( $emailReceipts );
				}
				if ( ! array_key_exists( 'cardUpdateConfirmationRequest', $emailReceipts ) ) {
					$emailReceipts->cardUpdateConfirmationRequest = MM_WPFS_Utils::create_default_card_update_confirmation_request_email_receipt();
					$options['email_receipts']                    = json_encode( $emailReceipts );
				}
			}
			if ( ! array_key_exists( 'email_receipt_sender_address', $options ) ) {
				$options['email_receipt_sender_address'] = '';
			}
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = '0';
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->create_webhook_token();
			}
			if ( ! array_key_exists( 'edd_license_key', $options ) ) {
				$options['edd_license_key'] = MM_WPFS_License::KEY;
			}
			if ( ! array_key_exists( 'edd_license_status', $options ) ) {
				$options['edd_license_status'] = 'unknown';
			}
			if ( ! array_key_exists( 'custom_input_field_max_count', $options ) ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			} elseif ( $options['custom_input_field_max_count'] != MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY';
			}
			update_option( 'fullstripe_options', $options );
		}

		// also, if version changed then the DB might be out of date
		MM_WPFS::setup_db( false );
	}

	/**
	 * Generates a unique random token for authenticating webhook callbacks.
	 *
	 * @return string
	 */
	private function create_webhook_token() {
		$siteURL           = get_site_url();
		$randomToken       = hash( 'md5', rand() );
		$generatedPassword = substr( hash( 'sha512', rand() ), 0, 6 );

		return hash( 'md5', $siteURL . '|' . $randomToken . '|' . $generatedPassword );
	}

	public static function setup_db( $network_wide ) {
		if ( $network_wide ) {
			MM_WPFS_Utils::log( "setup_db() - Activating network-wide" );
			if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
				$site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
			} else {
				$site_ids = MM_WPFS_Database::get_site_ids();
			}

			foreach ( $site_ids as $site_id ) {
				switch_to_blog( $site_id );
				self::setup_db_single_site();
				restore_current_blog();
			}
		} else {
			MM_WPFS_Utils::log( "setup_db() - Activating for single site" );
			self::setup_db_single_site();
		}
	}

	public static function setup_db_single_site() {
		MM_WPFS_Database::fullstripe_setup_db();
		MM_WPFS_Patcher::apply_patches();
	}

	function update_option_defaults( $options ) {
		if ( $options ) {
			if ( ! array_key_exists( 'secretKey_test', $options ) ) {
				$options['secretKey_test'] = 'YOUR_TEST_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_test', $options ) ) {
				$options['publishKey_test'] = 'YOUR_TEST_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'secretKey_live', $options ) ) {
				$options['secretKey_live'] = 'YOUR_LIVE_SECRET_KEY';
			}
			if ( ! array_key_exists( 'publishKey_live', $options ) ) {
				$options['publishKey_live'] = 'YOUR_LIVE_PUBLISHABLE_KEY';
			}
			if ( ! array_key_exists( 'apiMode', $options ) ) {
				$options['apiMode'] = 'test';
			}
			if ( ! array_key_exists( 'form_css', $options ) ) {
				$options['form_css'] = ".fullstripe-form-title{ font-size: 120%;  color: #363636; font-weight: bold;}\n.fullstripe-form-input{}\n.fullstripe-form-label{font-weight: bold;}";
			}
			if ( ! array_key_exists( 'includeStyles', $options ) ) {
				$options['includeStyles'] = '1';
			}
			if ( ! array_key_exists( 'receiptEmailType', $options ) ) {
				$options['receiptEmailType'] = 'plugin';
			}
			if ( ! array_key_exists( 'email_receipts', $options ) ) {
				$emailReceipts             = MM_WPFS_Utils::create_default_email_receipts();
				$options['email_receipts'] = json_encode( $emailReceipts );
			} else {
				$emailReceipts = json_decode( $options['email_receipts'] );
				if ( ! array_key_exists( 'cardCaptured', $emailReceipts ) ) {
					$emailReceipts->cardCaptured = MM_WPFS_Utils::create_default_card_captured_email_receipt();
					$options['email_receipts']   = json_encode( $emailReceipts );
				}
				if ( ! array_key_exists( 'cardUpdateConfirmationRequest', $emailReceipts ) ) {
					$emailReceipts->cardUpdateConfirmationRequest = MM_WPFS_Utils::create_default_card_update_confirmation_request_email_receipt();
					$options['email_receipts']                    = json_encode( $emailReceipts );
				}
			}
			if ( ! array_key_exists( 'email_receipt_sender_address', $options ) ) {
				$options['email_receipt_sender_address'] = '';
			}
			if ( ! array_key_exists( 'admin_payment_receipt', $options ) ) {
				$options['admin_payment_receipt'] = 'no';
			} else {
				if ( $options['admin_payment_receipt'] == '0' ) {
					$options['admin_payment_receipt'] = 'no';
				}
				if ( $options['admin_payment_receipt'] == '1' ) {
					$options['admin_payment_receipt'] = 'website_admin';
				}
			}
			if ( ! array_key_exists( 'lock_email_field_for_logged_in_users', $options ) ) {
				$options['lock_email_field_for_logged_in_users'] = '1';
			}
			if ( ! array_key_exists( 'webhook_token', $options ) ) {
				$options['webhook_token'] = $this->create_webhook_token();
			}
			if ( ! array_key_exists( 'edd_license_key', $options ) ) {
				/** @noinspection PhpUndefinedClassInspection */
				$options['edd_license_key'] = MM_WPFS_License::KEY;
			}
			if ( ! array_key_exists( 'edd_license_status', $options ) ) {
				$options['edd_license_status'] = 'unknown';
			}
			if ( ! array_key_exists( 'custom_input_field_max_count', $options ) ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			} elseif ( $options['custom_input_field_max_count'] != MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT ) {
				$options['custom_input_field_max_count'] = MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
				$options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] = '0';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY';
			}
			if ( ! array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] = 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY';
			}
			update_option( 'fullstripe_options', $options );
		}
	}

	private function activate_license() {
		$options = get_option( 'fullstripe_options' );
		$license = $options['edd_license_key'];

		$apiParameters = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( WPFS_EDD_SL_ITEM_NAME ),
			'url'        => home_url()
		);

		$response = wp_remote_post( WPFS_EDD_SL_STORE_URL, array(
			'timeout'   => 1,
			'sslverify' => false,
			'body'      => $apiParameters
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$licenseData = json_decode( wp_remote_retrieve_body( $response ) );

		$options['edd_license_status'] = $licenseData->license;
		update_option( 'fullstripe_options', $options );

		return true;
	}

	function fullstripe_set_api_key_and_version( $key ) {
		if ( $key != '' && $key != 'YOUR_TEST_SECRET_KEY' && $key != 'YOUR_LIVE_SECRET_KEY' ) {
			try {
				\Stripe\Stripe::setApiKey( $key );
				\Stripe\Stripe::setApiVersion( MM_WPFS_Stripe::DESIRED_STRIPE_API_VERSION );
			} catch ( Exception $e ) {
				MM_WPFS_Utils::logException( $e, $this );
			}
		}
	}

	function hooks() {

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		// add_filter( MM_WPFS::FILTER_NAME_GET_VAT_PERCENT, array( $this, 'determine_custom_vat_percent' ), 10, 4 );

		add_shortcode( self::SHORTCODE_FULLSTRIPE_PAYMENT, array( $this, 'fullstripe_payment_form' ) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_SUBSCRIPTION, array( $this, 'fullstripe_subscription_form' ) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_CHECKOUT, array( $this, 'fullstripe_checkout_form' ) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_SUBSCRIPTION_CHECKOUT, array(
			$this,
			'fullstripe_checkout_subscription_form'
		) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_FORM, array( $this, 'fullstripe_form' ) );

		add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU, array( $this, 'fullstripe_thankyou' ) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU_SUCCESS, array( $this, 'fullstripe_thankyou_success' ) );
		add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU_DEFAULT, array( $this, 'fullstripe_thankyou_default' ) );

		add_action( 'wp_head', array( $this, 'fullstripe_wp_head' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fullstripe_enqueue_scripts_and_styles' ) );

		do_action( 'fullstripe_main_hooks_action' );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new MM_WPFS();
		}

		return self::$instance;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function esc_html_id_attr( $value ) {
		return preg_replace( '/[^a-z0-9\-_:\.]|^[^a-z]+/i', '', $value );
	}

	public static function get_credit_card_image_for( $currency ) {
		$creditCardImage = 'creditcards.png';

		if ( $currency === MM_WPFS::CURRENCY_USD ) {
			$creditCardImage = 'creditcards-us.png';
		}

		return $creditCardImage;
	}

	/**
	 * Creates an array of supported locales/languages on popup and popup subscription forms. These forms use Stripe
	 * Checkout.
	 *
	 * @return array list of locales/languages
	 */
	public static function get_available_checkout_languages() {
		return array(
			array(
				'value' => 'da',
				'name'  => 'Danish'
			),
			array(
				'value' => 'de',
				'name'  => 'German'
			),
			array(
				'value' => 'en',
				'name'  => 'English'
			),
			array(
				'value' => 'es',
				'name'  => 'Spanish'
			),
			array(
				'value' => 'fi',
				'name'  => 'Finnish'
			),
			array(
				'value' => 'fr',
				'name'  => 'French'
			),
			array(
				'value' => 'it',
				'name'  => 'Italian'
			),
			array(
				'value' => 'ja',
				'name'  => 'Japanese'
			),
			array(
				'value' => 'nl',
				'name'  => 'Dutch'
			),
			array(
				'value' => 'no',
				'name'  => 'Norwegian'
			),
			array(
				'value' => 'sv',
				'name'  => 'Swedish'
			),
			array(
				'value' => 'zh',
				'name'  => 'Simplified Chinese'
			)
		);
	}

	public static function get_custom_input_field_max_count() {
		$options = get_option( 'fullstripe_options' );
		if ( is_array( $options ) && array_key_exists( 'custom_input_field_max_count', $options ) ) {
			$customInputFieldMaxCount = $options['custom_input_field_max_count'];
			if ( is_numeric( $customInputFieldMaxCount ) ) {
				return $customInputFieldMaxCount;
			}
		}

		return self::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
	}

	/**
	 * Sustain compatibility with WP Full Stripe Members
	 *
	 * @param $currency
	 *
	 * @return mixed|null
	 */
	public static function get_currency_symbol_for( $currency ) {
		return MM_WPFS_Currencies::get_currency_symbol_for( $currency );
	}

	/**
	 * Sustain compatibility with WP Full Stripe Members
	 *
	 * @param $currency
	 *
	 * @return mixed|null
	 */
	public static function get_currency_for( $currency ) {
		return MM_WPFS_Currencies::get_currency_for( $currency );
	}

	public function plugin_action_links( $links, $file ) {
		static $currentPlugin;

		if ( ! $currentPlugin ) {
			$currentPlugin = plugin_basename( 'wp-full-stripe/wp-full-stripe.php' );
		}

		if ( $file == $currentPlugin ) {
			$settingsLink = '<a href="' . menu_page_url( 'fullstripe-settings', false ) . '">' . esc_html( __( 'Settings', 'fullstripe-settings' ) ) . '</a>';
			array_unshift( $links, $settingsLink );
		}

		return $links;
	}

	/**
	 * This is a sample implementation for custom VAT percent calculation
	 *
	 * @param $initialValue
	 * @param $fromCountry
	 * @param $toCountry
	 * @param $additionalArguments
	 *
	 * @return float
	 */
	public function determine_custom_vat_percent( $initialValue, $fromCountry, $toCountry, $additionalArguments ) {
		MM_WPFS_Utils::log( 'determine_custom_vat_percent(): initialValue=' . $initialValue . ', fromCountry=' . $fromCountry . ', toCountry=' . $toCountry . ', additionalArguments=' . print_r( $additionalArguments, true ) );
		// tnagy sample implementation to use the appropriate VAT percent by destination country
		if ( $toCountry == 'GB' ) {
			$vatPercent = 20.0;
		} elseif ( $toCountry == 'DE' ) {
			$vatPercent = 19.0;
		} elseif ( $toCountry == 'CZ' ) {
			$vatPercent = 21.0;
		} elseif ( $toCountry == 'HU' ) {
			$vatPercent = 27.0;
		} elseif ( $toCountry == 'ES' ) {
			$vatPercent = 21.0;
		} else {
			$vatPercent = $initialValue;
		}

		MM_WPFS_Utils::log( 'determine_custom_vat_percent(): vatPercent=' . $vatPercent );

		return $vatPercent;
	}

	/**
	 * Support for old shortcode format
	 *
	 * @param $attributes
	 *
	 * @return mixed|void
	 */
	function fullstripe_payment_form( $attributes ) {

		$curentAttributes = array(
			'type' => self::FORM_TYPE_INLINE_PAYMENT
		);

		if ( array_key_exists( 'form', $attributes ) ) {
			$curentAttributes['name'] = $attributes['form'];
		}

		$content = $this->fullstripe_form( $curentAttributes );

		return apply_filters( 'fullstripe_payment_form_output', $content );
	}

	/**
	 * Generalized function to handle the new shortcode format
	 *
	 * @param $atts
	 *
	 * @return mixed|void
	 */
	function fullstripe_form( $atts ) {

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'fullstripe_form(): CALLED' );
		}

		$form_type = self::FORM_TYPE_PAYMENT;
		$form_name = 'default';
		if ( array_key_exists( 'type', $atts ) ) {
			$form_type = $atts['type'];
		}
		if ( array_key_exists( 'name', $atts ) ) {
			$form_name = $atts['name'];
		}

		$form = null;
		if ( self::FORM_TYPE_INLINE_PAYMENT === $form_type || self::FORM_TYPE_PAYMENT === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_payment_form_by_name( $form_name );
		} elseif ( self::FORM_TYPE_INLINE_SUBSCRIPTION === $form_type || self::FORM_TYPE_SUBSCRIPTION === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_subscription_form_by_name( $form_name );
			/** @noinspection PhpUnusedLocalVariableInspection */
			$stripe_plans = $this->get_plans();
		} elseif ( self::FORM_TYPE_INLINE_SAVE_CARD === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_payment_form_by_name( $form_name );
		} elseif ( self::FORM_TYPE_POPUP_PAYMENT === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_checkout_form_by_name( $form_name );
		} elseif ( self::FORM_TYPE_POPUP_SUBSCRIPTION === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_checkout_subscription_form_by_name( $form_name );
			/** @noinspection PhpUnusedLocalVariableInspection */
			$stripe_plans = $this->get_plans();
		} elseif ( self::FORM_TYPE_POPUP_SAVE_CARD === $form_type ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$form = $this->database->get_checkout_form_by_name( $form_name );
		}

		if ( false === $this->loadScriptsAndStylesWithActionHook ) {
			$renderedForms = self::get_rendered_forms()->render_later( $form_type );
			if ( $renderedForms->get_total() == 1 ) {
				$this->fullstripe_load_css();
				$this->fullstripe_load_js();
				$this->fullstripe_set_common_js_variables();
			}
		}

		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( 'forms/fullstripe_form.php' );
		// todo tnagy handle $form == null case with a template
		$content = ob_get_clean();

		return apply_filters( 'fullstripe_form_output', $content );
	}

	/**
	 * @return array|mixed|void
	 */
	public function get_plans() {
		return $this->stripe != null ? apply_filters( 'fullstripe_subscription_plans_filter', $this->stripe->get_plans() ) : array();
	}

	/**
	 * @return WPFS_RenderedFormData
	 */
	public static function get_rendered_forms() {
		if ( ! array_key_exists( self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS, $_REQUEST ) ) {
			$_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ] = new WPFS_RenderedFormData();
		}

		return $_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ];
	}

	/**
	 * Register and enqueue WPFS styles
	 */
	public function fullstripe_load_css() {
		$options = get_option( 'fullstripe_options' );
		if ( $options['includeStyles'] === '1' ) {

			wp_register_style( self::HANDLE_STYLE_WPFS_VARIABLES, MM_WPFS_Assets::css( 'wpfs-variables.css' ), null, MM_WPFS::VERSION );
			wp_register_style( self::HANDLE_STYLE_WPFS_FORMS, MM_WPFS_Assets::css( 'wpfs-forms.css' ), array( self::HANDLE_STYLE_WPFS_VARIABLES ), MM_WPFS::VERSION );

			wp_enqueue_style( self::HANDLE_STYLE_WPFS_FORMS );
		}

		do_action( 'fullstripe_load_css_action' );
	}

	/**
	 * Register and enqueue WPFS scripts
	 */
	public function fullstripe_load_js() {
		$source = add_query_arg(
			array(
				'render' => 'explicit'
			),
			self::SOURCE_GOOGLE_RECAPTCHA_V2_API_JS
		);
		wp_register_script( self::HANDLE_GOOGLE_RECAPTCHA_V_2, $source, null, MM_WPFS::VERSION, true /* in footer */ );
		wp_register_script( self::HANDLE_SPRINTF_JS, MM_WPFS_Assets::scripts( 'sprintf.min.js' ), null, MM_WPFS::VERSION );
		wp_register_script( self::HANDLE_STRIPE_CHECKOUT_JS, 'https://checkout.stripe.com/checkout.js', array( 'jquery' ) );
		wp_register_script( self::HANDLE_STRIPE_JS_V_3, 'https://js.stripe.com/v3/', array( 'jquery' ) );
		wp_register_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS, MM_WPFS_Assets::scripts( 'wpfs-utils.js' ), null, MM_WPFS::VERSION );

		wp_enqueue_script( self::HANDLE_SPRINTF_JS );
		wp_enqueue_script( self::HANDLE_STRIPE_CHECKOUT_JS );
		wp_enqueue_script( self::HANDLE_STRIPE_JS_V_3 );
		wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS );
		if ( MM_WPFS_Utils::get_secure_inline_forms_with_google_recaptcha() ) {
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-selectmenu',
				'jquery-ui-autocomplete',
				'jquery-ui-tooltip',
				self::HANDLE_SPRINTF_JS,
				self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
				self::HANDLE_STRIPE_JS_V_3,
				self::HANDLE_STRIPE_CHECKOUT_JS,
				self::HANDLE_GOOGLE_RECAPTCHA_V_2
			);
		} else {
			$dependencies = array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-selectmenu',
				'jquery-ui-autocomplete',
				'jquery-ui-tooltip',
				self::HANDLE_SPRINTF_JS,
				self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
				self::HANDLE_STRIPE_JS_V_3,
				self::HANDLE_STRIPE_CHECKOUT_JS
			);
		}
		wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_JS, MM_WPFS_Assets::scripts( 'wpfs.js' ), $dependencies, MM_WPFS::VERSION );

		do_action( 'fullstripe_load_js_action' );
	}

	function fullstripe_set_common_js_variables() {
		wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_AJAX_URL, admin_url( 'admin-ajax.php' ) );
		$options = get_option( 'fullstripe_options' );
		if ( $options['apiMode'] === 'test' ) {
			wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_STRIPE_KEY, $options['publishKey_test'] );
		} else {
			wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_STRIPE_KEY, $options['publishKey_live'] );
		}
		wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY, MM_WPFS_Utils::get_google_recaptcha_site_key() );
		wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_L10N, array(
				'validation_errors'                      => array(
					'internal_error'                         => __( 'An internal error occurred.', 'wp-full-stripe' ),
					'internal_error_title'                   => __( 'Internal Error', 'wp-full-stripe' ),
					/* translators: 1: custom input field label */
					'mandatory_field_is_empty'               => __( 'Please enter a value for "%s"', 'wp-full-stripe' ),
					'custom_payment_amount_value_is_invalid' => __( 'Payment amount is invalid', 'wp-full-stripe' ),
					'invalid_payment_amount'                 => __( 'Cannot determine payment amount', 'wp-full-stripe' ),
					'invalid_payment_amount_title'           => __( 'Invalid payment amount', 'wp-full-stripe' )
				),
				'stripe_errors'                          => array(
					MM_WPFS_Stripe::INVALID_NUMBER_ERROR               => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_NUMBER_ERROR ),
					MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH     => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH ),
					MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR      => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR ),
					MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR         => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR ),
					MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR          => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR ),
					MM_WPFS_Stripe::INVALID_CVC_ERROR                  => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INVALID_CVC_ERROR ),
					MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR             => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR ),
					MM_WPFS_Stripe::EXPIRED_CARD_ERROR                 => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::EXPIRED_CARD_ERROR ),
					MM_WPFS_Stripe::INCORRECT_CVC_ERROR                => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_CVC_ERROR ),
					MM_WPFS_Stripe::INCORRECT_ZIP_ERROR                => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::INCORRECT_ZIP_ERROR ),
					MM_WPFS_Stripe::CARD_DECLINED_ERROR                => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::CARD_DECLINED_ERROR ),
					MM_WPFS_Stripe::MISSING_ERROR                      => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::MISSING_ERROR ),
					MM_WPFS_Stripe::PROCESSING_ERROR                   => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::PROCESSING_ERROR ),
					MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION        => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION ),
					MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION => $this->stripe->resolve_error_message_by_code( MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION )
				),
				'subscription_charge_interval_templates' => array(
					'weekly'           => __( 'Subscription will be charged every week.', 'wp-full-stripe' ),
					'monthly'          => __( 'Subscription will be charged every month.', 'wp-full-stripe' ),
					'yearly'           => __( 'Subscription will be charged every year.', 'wp-full-stripe' ),
					'y_weeks'          => __( 'Subscription will be charged every %d weeks.', 'wp-full-stripe' ),
					'y_months'         => __( 'Subscription will be charged every %d months.', 'wp-full-stripe' ),
					'y_years'          => __( 'Subscription will be charged every %d years.', 'wp-full-stripe' ),
					'x_times_weekly'   => __( 'Subscription will be charged every week, for %d occasions.', 'wp-full-stripe' ),
					'x_times_monthly'  => __( 'Subscription will be charged every month, for %d occasions.', 'wp-full-stripe' ),
					'x_times_yearly'   => __( 'Subscription will be charged every year, for %d occasions.', 'wp-full-stripe' ),
					'x_times_y_weeks'  => __( 'Subscription will be charged every %d weeks, for %d occasions.', 'wp-full-stripe' ),
					'x_times_y_months' => __( 'Subscription will be charged every %d months, for %d occasions.', 'wp-full-stripe' ),
					'x_times_y_years'  => __( 'Subscription will be charged every %d years, for %d occasions.', 'wp-full-stripe' ),
				)
			)
		);
		wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_FORM_FIELDS, array(
				'inlinePayment'      => MM_WPFS_InlinePaymentFormView::getFields(),
				'inlineCardCapture'  => MM_WPFS_InlineCardCaptureFormView::getFields(),
				'inlineSubscription' => MM_WPFS_InlineSubscriptionFormView::getFields(),
				'popupPayment'       => MM_WPFS_PopupPaymentFormView::getFields(),
				'popupCardCapture'   => MM_WPFS_PopupCardCaptureFormView::getFields(),
				'popupSubscription'  => MM_WPFS_PopupSubscriptionFormView::getFields()
			)
		);
	}

	/**
	 * Support for old shortcode format
	 *
	 * @param $attributes
	 *
	 * @return mixed|void
	 */
	function fullstripe_subscription_form( $attributes ) {

		$currentAttributes = array(
			'type' => self::FORM_TYPE_INLINE_SUBSCRIPTION
		);

		if ( array_key_exists( 'form', $attributes ) ) {
			$currentAttributes['name'] = $attributes['form'];
		}

		$content = $this->fullstripe_form( $currentAttributes );

		return apply_filters( 'fullstripe_subscription_form_output', $content );
	}

	/**
	 * Support for old shortcode format
	 *
	 * @param $attributes
	 *
	 * @return mixed|void
	 */
	function fullstripe_checkout_form( $attributes ) {

		$currentAttributes = array(
			'type' => self::FORM_TYPE_POPUP_PAYMENT
		);

		if ( array_key_exists( 'form', $attributes ) ) {
			$currentAttributes['name'] = $attributes['form'];
		}

		$content = $this->fullstripe_form( $currentAttributes );

		return apply_filters( 'fullstripe_checkout_form_output', $content );
	}

	/**
	 * Support for old shortcode format
	 *
	 * @param $attributes
	 *
	 * @return mixed|void
	 */
	function fullstripe_checkout_subscription_form( $attributes ) {

		$currentAttributes = array(
			'type' => self::FORM_TYPE_POPUP_SUBSCRIPTION
		);

		if ( array_key_exists( 'form', $attributes ) ) {
			$currentAttributes['name'] = $attributes['form'];
		}

		$content = $this->fullstripe_form( $currentAttributes );

		return apply_filters( 'fullstripe_checkout_subscription_form_output', $content );
	}

	function fullstripe_thankyou( $attributes, $content = null ) {
		$transactionDataKey = isset( $_REQUEST[ MM_WPFS_Customer::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY ] ) ? $_REQUEST[ MM_WPFS_Customer::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY ] : null;
		$transactionData    = $this->transactionDataService->retrieve( $transactionDataKey );

		if ( $transactionData !== false ) {
			$_REQUEST['transaction_data'] = $transactionData;
		}

		return do_shortcode( $content );
	}

	function fullstripe_thankyou_default( $attributes, $content = null ) {
		if ( isset( $_REQUEST['transaction_data'] ) ) {
			return '';
		} else {
			return $content;
		}
	}

	function fullstripe_thankyou_success( $attributes, $content = null ) {
		if ( isset( $_REQUEST['transaction_data'] ) ) {
			$transactionData = $_REQUEST['transaction_data'];
		} else {
			$transactionData = null;
		}

		if ( ! is_null( $transactionData ) && $transactionData instanceof MM_WPFS_TransactionData ) {

			if ( $transactionData instanceof MM_WPFS_SubscriptionTransactionData ) {
				/** @var $transactionData MM_WPFS_SubscriptionTransactionData */
				$search  = MM_WPFS_Utils::get_subscription_macros();
				$replace = MM_WPFS_Utils::getSubscriptionMacroValues( $transactionData );

				$result = str_replace(
					$search,
					$replace,
					$content
				);

				$result = MM_WPFS_Utils::replace_custom_fields( $result, $transactionData->getCustomInputValues() );

			} else {
				/** @var $transactionData MM_WPFS_PaymentTransactionData */
				$search  = MM_WPFS_Utils::get_payment_macros();
				$replace = MM_WPFS_Utils::getPaymentMacroValues( $transactionData );

				$result = str_replace(
					$search,
					$replace,
					$content
				);

				$result = MM_WPFS_Utils::replace_custom_fields( $result, $transactionData->getCustomInputValues() );

			}

			return $result;
		} else {
			return '';
		}
	}

	function fullstripe_wp_head() {
		//output the custom css
		$options = get_option( 'fullstripe_options' );
		echo '<style type="text/css" media="screen">' . $options['form_css'] . '</style>';
	}

	/**
	 * Register and enqueue styles and scripts to load for this addon
	 */
	public function fullstripe_enqueue_scripts_and_styles() {
		if ( $this->loadScriptsAndStylesWithActionHook ) {
			global $wp;
			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'fullstripe_enqueue_scripts_and_styles(): CALLED, wp=' . print_r( $wp, true ) );
			}
			if ( ! is_null( $wp ) && isset( $wp->request ) ) {
				$pageByPath = get_page_by_path( $wp->request );
				if ( ! is_null( $pageByPath ) && isset( $pageByPath->post_content ) ) {
					if (
						has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_FORM )
						|| has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_CHECKOUT )
						|| has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_PAYMENT )
						|| has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_SUBSCRIPTION )
						|| has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_SUBSCRIPTION_CHECKOUT )
					) {
						$this->fullstripe_load_css();
						$this->fullstripe_load_js();
						$this->fullstripe_set_common_js_variables();
					}
				}
			}
		}
	}

	public function get_recipients() {
		return $this->stripe != null ? apply_filters( 'fullstripe_transfer_receipients_filter', $this->stripe->get_recipients() ) : array();
	}

	public function get_subscription( $customerID, $subscriptionID ) {
		return $this->stripe != null ? apply_filters( 'fullstripe_customer_subscription_filter', $this->stripe->retrieve_subscription( $customerID, $subscriptionID ) ) : array();
	}

	/**
	 * @return MM_WPFS_Admin_Menu
	 */
	public function getAdminMenu() {
		return $this->adminMenu;
	}

	/**
	 * Create a list of email receipt template objects to render on the Settings page.
	 */
	public function get_email_receipt_templates() {

		$emailReceiptTemplates = array();

		$paymentMade                              = new stdClass();
		$paymentMade->id                          = 'paymentMade';
		$paymentMade->caption                     = __( 'Payment receipt', 'wp-full-stripe' );
		$cardCaptured                             = new stdClass();
		$cardCaptured->id                         = 'cardCaptured';
		$cardCaptured->caption                    = __( 'Card saved', 'wp-full-stripe' );
		$subscriptionStarted                      = new stdClass();
		$subscriptionStarted->id                  = 'subscriptionStarted';
		$subscriptionStarted->caption             = __( 'Subscription receipt', 'wp-full-stripe' );
		$subscriptionFinished                     = new stdClass();
		$subscriptionFinished->id                 = 'subscriptionFinished';
		$subscriptionFinished->caption            = __( 'Subscription ended', 'wp-full-stripe' );
		$cardUpdateConfirmationRequested          = new stdClass();
		$cardUpdateConfirmationRequested->id      = 'cardUpdateConfirmationRequest';
		$cardUpdateConfirmationRequested->caption = __( 'Subscription update security code', 'wp-full-stripe' );

		array_push( $emailReceiptTemplates, $paymentMade );
		array_push( $emailReceiptTemplates, $cardCaptured );
		array_push( $emailReceiptTemplates, $subscriptionStarted );
		array_push( $emailReceiptTemplates, $subscriptionFinished );
		array_push( $emailReceiptTemplates, $cardUpdateConfirmationRequested );

		return apply_filters( 'fullstripe_settings_email_receipt_templates', $emailReceiptTemplates );
	}

	public function get_form_validation_data() {
		return new WPFS_FormValidationData();
	}

	public function get_plan( $plan_id ) {
		return $this->stripe != null ? apply_filters( 'fullstripe_subscription_plan_filter', $this->stripe->retrieve_plan( $plan_id ) ) : null;
	}

}

class WPFS_FormValidationData {

	const NAME_LENGTH = 100;
	const FORM_TITLE_LENGTH = 100;
	const BUTTON_TITLE_LENGTH = 100;
	const REDIRECT_URL_LENGTH = 1024;
	const COMPANY_NAME_LENGTH = 100;
	const PRODUCT_DESCRIPTION_LENGTH = 100;
	const OPEN_BUTTON_TITLE_LENGTH = 100;
	const PAYMENT_AMOUNT_LENGTH = 8;
	const PAYMENT_AMOUNT_DESCRIPTION_LENGTH = 128;
	const IMAGE_LENGTH = 500;

}

class WPFS_PlanValidationData {
	const STATEMENT_DESCRIPTOR_LENGTH = 22;
}

class WPFS_RenderedFormData {

	private $payments = 0;
	private $subscriptions = 0;
	private $checkouts = 0;
	private $checkoutSubscriptions = 0;

	public function render_later( $type ) {
		if ( MM_WPFS::FORM_TYPE_PAYMENT === $type ) {
			// todo tnagy remove later
			$this->payments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_SUBSCRIPTION === $type ) {
			// todo tnagy remove later
			$this->subscriptions += 1;
		} elseif ( MM_WPFS::FORM_TYPE_CHECKOUT === $type ) {
			// todo tnagy remove later
			$this->checkouts += 1;
		} elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $type ) {
			// todo tnagy remove later
			$this->checkoutSubscriptions += 1;
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_PAYMENT === $type ) {
			$this->payments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD === $type ) {
			$this->payments += 1;
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION === $type ) {
			$this->subscriptions += 1;
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_PAYMENT === $type ) {
			$this->checkouts += 1;
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD === $type ) {
			$this->checkouts += 1;
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION === $type ) {
			$this->checkoutSubscriptions += 1;
		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function get_payments() {
		return $this->payments;
	}

	/**
	 * @return int
	 */
	public function get_subscriptions() {
		return $this->subscriptions;
	}

	/**
	 * @return int
	 */
	public function get_checkouts() {
		return $this->checkouts;
	}

	/**
	 * @return int
	 */
	public function get_checkout_subscriptions() {
		return $this->checkoutSubscriptions;
	}

	/**
	 * @return int
	 */
	public function get_total() {
		return $this->payments + $this->subscriptions + $this->checkouts + $this->checkoutSubscriptions;
	}

}

class MM_WPFS_Utils {

	const ADDITIONAL_DATA_KEY_ACTION_NAME = 'action_name';
	const ADDITIONAL_DATA_KEY_CUSTOMER = 'customer';
	const ADDITIONAL_DATA_KEY_MACROS = 'macros';
	const ADDITIONAL_DATA_KEY_MACRO_VALUES = 'macroValues';
	const WPFS_LOG_MESSAGE_PREFIX = "WPFS: ";
	const STRIPE_METADATA_KEY_MAX_LENGTH = 40;
	const STRIPE_METADATA_VALUE_MAX_LENGTH = 500;
	const STRIPE_METADATA_KEY_MAX_COUNT = 20;
	const ELEMENT_PART_SEPARATOR = '--';
	const SHORTCODE_PATTERN = '[fullstripe_form name="%s" type="%s"]';

	const ESCAPE_TYPE_NONE = 'none';
	const ESCAPE_TYPE_HTML = 'esc_html';
	const ESCAPE_TYPE_ATTR = 'esc_attr';

	/**
	 * @param $form
	 *
	 * @return bool|string
	 */
	public static function createShortCodeString( $form ) {
		$formType = MM_WPFS_Utils::getFormType( $form );
		if ( MM_WPFS::FORM_TYPE_INLINE_PAYMENT === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_PAYMENT );
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD );
		} elseif ( MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION );
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_PAYMENT === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_POPUP_PAYMENT );
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD );
		} elseif ( MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION === $formType ) {
			return sprintf( self::SHORTCODE_PATTERN, $form->name, MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION );
		}

		return false;
	}

	/**
	 * @param $form
	 *
	 * @return null|string
	 */
	public static function getFormType( $form ) {
		if ( is_null( $form ) ) {
			return null;
		}
		if ( isset( $form->paymentFormID ) ) {
			if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $form->customAmount ) {
				return MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;
			} else {
				return MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
			}
		}
		if ( isset( $form->subscriptionFormID ) ) {
			return MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
		}
		if ( isset( $form->checkoutFormID ) ) {
			if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $form->customAmount ) {
				return MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD;
			} else {
				return MM_WPFS::FORM_TYPE_POPUP_PAYMENT;
			}
		}
		if ( isset( $form->checkoutSubscriptionFormID ) ) {
			return MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION;
		}

		return null;
	}

	public static function generate_form_element_id( $element_id, $form_hash, $index = null ) {
		if ( is_null( $element_id ) ) {
			return null;
		}

		$generated_id = $element_id . MM_WPFS_Utils::ELEMENT_PART_SEPARATOR . $form_hash;
		if ( ! is_null( $index ) ) {
			$generated_id .= MM_WPFS_Utils::ELEMENT_PART_SEPARATOR . $index;
		}

		return esc_attr( $generated_id );
	}

	public static function generate_form_hash( $form_type, $form_id, $form_name ) {
		$data = $form_type . '|' . $form_id . '|' . $form_name;

		return substr( base64_encode( hash( 'sha256', $data ) ), 0, 7 );
	}

	public static function sanitize_text( $value ) {
		return self::stripslashes_deep( sanitize_text_field( $value ) );
	}

	public static function stripslashes_deep( $value ) {
		$value = is_array( $value ) ?
			array_map( 'MM_WPFS_Utils::stripslashes_deep', $value ) :
			stripslashes( $value );

		return $value;
	}

	public static function add_http_prefix( $url ) {
		if ( ! isset( $url ) ) {
			return null;
		}
		if ( substr( $url, 0, 7 ) != 'http://' && substr( $url, 0, 8 ) != 'https://' ) {
			return 'http://' . $url;
		}

		return sanitize_text_field( $url );
	}

	public static function get_card_update_confirmation_request_macros() {
		return array(
			'%CUSTOMERNAME%',
			'%CUSTOMER_EMAIL%',
			'%CARD_UPDATE_SECURITY_CODE%',
			'%CARD_UPDATE_SESSION_HASH%',
			'%NAME%',
			'%DATE%'
		);
	}

	/**
	 * @param $customerName
	 * @param $customerEmail
	 * @param $cardUpdateSessionHash
	 * @param $securityCode
	 *
	 * @return array
	 */
	public static function get_card_update_confirmation_request_macro_values( $customerName, $customerEmail, $cardUpdateSessionHash, $securityCode ) {
		$siteTitle  = get_bloginfo( 'name' );
		$dateFormat = get_option( 'date_format' );

		return array(
			esc_attr( $customerName ),
			esc_attr( $customerEmail ),
			esc_attr( $securityCode ),
			esc_attr( $cardUpdateSessionHash ),
			esc_attr( $siteTitle ),
			esc_attr( date( $dateFormat ) )
		);
	}

	/**
	 * @param $googleReCAPTCHAResponse
	 *
	 * @return array|bool|mixed|object|WP_Error
	 */
	public static function verifyReCAPTCHA( $googleReCAPTCHAResponse ) {
		$googleReCAPTCHASecretKey = MM_WPFS_Utils::get_google_recaptcha_secret_key();

		if ( ! is_null( $googleReCAPTCHASecretKey ) && ! is_null( $googleReCAPTCHAResponse ) ) {
			$inputArray = array(
				'secret'   => $googleReCAPTCHASecretKey,
				'response' => $googleReCAPTCHAResponse,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			);
			$request    = wp_remote_post(
				MM_WPFS::URL_RECAPTCHA_API_SITEVERIFY,
				array(
					'timeout'   => 10,
					'sslverify' => true,
					'body'      => $inputArray
				)
			);
			if ( ! is_wp_error( $request ) ) {
				$request = json_decode( wp_remote_retrieve_body( $request ) );

				return $request;
			} else {
				return false;
			}
		}

		return false;
	}

	public static function get_google_recaptcha_secret_key() {
		$googleReCAPTCHASecretKey = null;
		$options                  = get_option( 'fullstripe_options' );
		if ( array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
			$googleReCAPTCHASecretKey = $options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ];
		}

		return $googleReCAPTCHASecretKey;
	}

	/**
	 * @return array
	 */
	public static function get_payment_macros() {
		return array(
			'%AMOUNT%',
			'%NAME%',
			'%CUSTOMERNAME%',
			'%CUSTOMER_EMAIL%',
			'%ADDRESS1%',
			'%ADDRESS2%',
			'%CITY%',
			'%STATE%',
			'%COUNTRY%',
			'%COUNTRY_CODE%',
			'%ZIP%',
			'%SHIPPING_NAME%',
			'%SHIPPING_ADDRESS1%',
			'%SHIPPING_ADDRESS2%',
			'%SHIPPING_CITY%',
			'%SHIPPING_STATE%',
			'%SHIPPING_COUNTRY%',
			'%SHIPPING_COUNTRY_CODE%',
			'%SHIPPING_ZIP%',
			'%PRODUCT_NAME%',
			'%DATE%',
			'%FORM_NAME%'
		);
	}

	/**
	 * @return array
	 */
	public static function get_subscription_macros() {
		return array(
			'%SETUP_FEE%',
			'%SETUP_FEE_NET%',
			'%SETUP_FEE_GROSS%',
			'%SETUP_FEE_VAT%',
			'%SETUP_FEE_VAT_RATE%',
			'%PLAN_NAME%',
			'%PLAN_AMOUNT%',
			'%PLAN_AMOUNT_NET%',
			'%PLAN_AMOUNT_GROSS%',
			'%PLAN_AMOUNT_VAT%',
			'%PLAN_AMOUNT_VAT_RATE%',
			'%AMOUNT%',
			'%NAME%',
			'%CUSTOMERNAME%',
			'%CUSTOMER_EMAIL%',
			'%ADDRESS1%',
			'%ADDRESS2%',
			'%CITY%',
			'%STATE%',
			'%COUNTRY%',
			'%COUNTRY_CODE%',
			'%ZIP%',
			'%SHIPPING_NAME%',
			'%SHIPPING_ADDRESS1%',
			'%SHIPPING_ADDRESS2%',
			'%SHIPPING_CITY%',
			'%SHIPPING_STATE%',
			'%SHIPPING_COUNTRY%',
			'%SHIPPING_COUNTRY_CODE%',
			'%SHIPPING_ZIP%',
			'%PRODUCT_NAME%',
			'%DATE%'
		);
	}

	/**
	 * @param $content
	 * @param $custom_input_values
	 * @param string $escapeTypes
	 *
	 * @return mixed
	 */
	public static function replace_custom_fields( $content, $custom_input_values, $escapeTypes = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		$custom_field_macros       = self::get_custom_field_macros();
		$custom_field_macro_values = self::get_custom_field_macro_values( count( $custom_field_macros ), $custom_input_values, $escapeTypes );
		$content                   = str_replace(
			$custom_field_macros,
			$custom_field_macro_values,
			$content
		);

		return $content;
	}

	/**
	 * @return array
	 */
	public static function get_custom_field_macros() {
		$customInputFieldMaxCount = MM_WPFS::get_custom_input_field_max_count();

		$customFieldMacros = array();

		for ( $i = 1; $i <= $customInputFieldMaxCount; $i ++ ) {
			array_push( $customFieldMacros, "%CUSTOMFIELD$i%" );
		}

		return $customFieldMacros;
	}

	/**
	 * @param $customFieldCount
	 * @param $customInputValues
	 * @param string $escapeType
	 *
	 * @return array
	 */
	public static function get_custom_field_macro_values( $customFieldCount, $customInputValues, $escapeType = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		$macroValues = array();
		if ( isset( $customInputValues ) && is_array( $customInputValues ) ) {
			$customInputValueCount = count( $customInputValues );
			for ( $index = 0; $index < $customFieldCount; $index ++ ) {
				if ( $index < $customInputValueCount ) {
					$value = $customInputValues[ $index ];
				} else {
					$value = '';
				}
				array_push( $macroValues, self::escape( $value, $escapeType ) );
			}
		}

		return $macroValues;
	}

	/**
	 * @param $value
	 * @param $escapeType
	 *
	 * @return string|void
	 */
	public static function escape( $value, $escapeType ) {
		if ( is_null( $value ) ) {
			return $value;
		}
		if ( self::ESCAPE_TYPE_HTML === $escapeType ) {
			return esc_html( $value );
		} elseif ( self::ESCAPE_TYPE_ATTR === $escapeType ) {
			return esc_attr( $value );
		} else {
			return $value;
		}
	}

	/**
	 * @param $line1
	 * @param $line2
	 * @param $city
	 * @param $state
	 * @param $countryName
	 * @param $countryCode
	 * @param $zip
	 *
	 * @return array
	 */
	public static function prepare_address_data( $line1, $line2, $city, $state, $countryName, $countryCode, $zip ) {
		$addressData = array(
			'line1'        => is_null( $line1 ) ? '' : $line1,
			'line2'        => is_null( $line2 ) ? '' : $line2,
			'city'         => is_null( $city ) ? '' : $city,
			'state'        => is_null( $state ) ? '' : $state,
			'country'      => is_null( $countryName ) ? '' : $countryName,
			'country_code' => is_null( $countryCode ) ? '' : $countryCode,
			'zip'          => is_null( $zip ) ? '' : $zip
		);

		return $addressData;
	}

	/**
	 * This function creates a Stripe shipping address hash
	 *
	 * @param $shipping_name
	 * @param $shipping_phone
	 * @param $address_array array previously created with prepare_address_data()
	 *
	 * @return array
	 */
	public static function prepare_stripe_shipping_hash_from_array( $shipping_name, $shipping_phone, $address_array ) {
		return self::prepare_stripe_shipping_hash(
			$shipping_name,
			$shipping_phone,
			$address_array['line1'],
			$address_array['line2'],
			$address_array['city'],
			$address_array['state'],
			$address_array['country_code'],
			$address_array['zip']
		);
	}

	/**
	 * This function creates a Stripe shipping address hash
	 *
	 * @param $shipping_name string Customer name
	 * @param $shipping_phone string Customer phone (including extension)
	 * @param $line1 string Address line 1 (Street address/PO Box/Company name)
	 * @param $line2 string Address line 2 (Apartment/Suite/Unit/Building)
	 * @param $city string City/District/Suburb/Town/Village
	 * @param $state string State/County/Province/Region
	 * @param $country_code string 2-letter country code
	 * @param $postal_code string ZIP or postal code
	 *
	 * @return array
	 */
	public static function prepare_stripe_shipping_hash( $shipping_name, $shipping_phone, $line1, $line2, $city, $state, $country_code, $postal_code ) {
		$shipping_hash = array();

		//-- The 'name' property is required. It must contain a non-empty value or be null
		$shipping_hash['name'] = ! empty( $shipping_name ) ? $shipping_name : null;

		if ( ! empty( $shipping_phone ) ) {
			$shipping_hash['phone'] = $shipping_phone;
		}
		$address_hash             = self::prepare_stripe_address_hash( $line1, $line2, $city, $state, $country_code, $postal_code );
		$shipping_hash['address'] = $address_hash;

		return $shipping_hash;
	}

	/**
	 * This function creates a Stripe address hash
	 *
	 * @param $line1 string Address line 1 (Street address/PO Box/Company name)
	 * @param $line2 string Address line 2 (Apartment/Suite/Unit/Building)
	 * @param $city string City/District/Suburb/Town/Village
	 * @param $state string State/County/Province/Region
	 * @param $country_code string 2-letter country code
	 * @param $postal_code string ZIP or postal code
	 *
	 * @return array
	 */
	public static function prepare_stripe_address_hash( $line1, $line2, $city, $state, $country_code, $postal_code ) {
		$address_hash = array();

		//-- The 'line1' property is required
		if ( empty( $line1 ) ) {
			throw new InvalidArgumentException( __FUNCTION__ . '(): address line1 is required.' );
		} else {
			$address_hash['line1'] = $line1;
		}
		if ( ! empty( $line2 ) ) {
			$address_hash['line2'] = $line2;
		}
		if ( ! empty( $city ) ) {
			$address_hash['city'] = $city;
		}
		if ( ! empty( $state ) ) {
			$address_hash['state'] = $state;
		}
		if ( ! empty( $country_code ) ) {
			$address_hash['country'] = $country_code;
		}
		if ( ! empty( $postal_code ) ) {
			$address_hash['postal_code'] = $postal_code;
		}

		return $address_hash;
	}

	/**
	 * This function creates a Stripe address hash from an array created previously created with prepare_address_data()
	 *
	 * @param array $address_array
	 *
	 * @return array
	 */
	public static function prepare_stripe_address_hash_from_array( $address_array ) {
		return self::prepare_stripe_address_hash(
			$address_array['line1'],
			$address_array['line2'],
			$address_array['city'],
			$address_array['state'],
			$address_array['country_code'],
			$address_array['zip']
		);
	}

	/**
	 * @param $action_name
	 * @param $customer
	 *
	 * @param $macros
	 * @param $macro_values
	 *
	 * @return array
	 */
	public static function prepare_additional_data_for_subscription_charge( $action_name, $customer, $macros, $macro_values ) {
		$additionalData = array(
			self::ADDITIONAL_DATA_KEY_ACTION_NAME  => $action_name,
			self::ADDITIONAL_DATA_KEY_CUSTOMER     => $customer,
			self::ADDITIONAL_DATA_KEY_MACROS       => $macros,
			self::ADDITIONAL_DATA_KEY_MACRO_VALUES => $macro_values
		);

		return $additionalData;
	}

	/**
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 * @param string $escapeType
	 *
	 * @return array
	 */
	public static function getSubscriptionMacroValues( $transactionData, $escapeType = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		return self::get_subscription_macro_values(
			$transactionData->getCustomerName(),
			$transactionData->getCustomerEmail(),
			$transactionData->getBillingAddress(),
			$transactionData->getShippingName(),
			$transactionData->getShippingAddress(),
			$transactionData->getPlanName(),
			$transactionData->getPlanCurrency(),
			$transactionData->getPlanNetSetupFee(),
			$transactionData->getPlanGrossSetupFee(),
			$transactionData->getPlanSetupFeeVAT(),
			$transactionData->getPlanSetupFeeVATRate(),
			$transactionData->getPlanNetAmount(),
			$transactionData->getPlanGrossAmount(),
			$transactionData->getPlanAmountVAT(),
			$transactionData->getPlanAmountVATRate(),
			$transactionData->getPlanGrossAmountAndGrossSetupFee(),
			$transactionData->getProductName(),
			$escapeType
		);
	}

	/**
	 *
	 * @param $customerName
	 * @param $customerEmail
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $stripePlanName
	 * @param $stripePlanCurrency
	 * @param $stripePlanNetSetupFee
	 * @param $stripePlanGrossSetupFee
	 * @param $stripePlanSetupFeeVAT
	 * @param $stripePlanSetupFeeVATRate
	 * @param $stripePlanNetAmount
	 * @param $stripePlanGrossAmount
	 * @param $stripePlanAmountVAT
	 * @param $stripePlanAmountVATRate
	 * @param $grossAmountAndGrossSetupFee
	 * @param $productName
	 * @param $escapeType
	 *
	 * @return array
	 */
	public static function get_subscription_macro_values( $customerName, $customerEmail, $billingAddress, $shippingName, $shippingAddress, $stripePlanName, $stripePlanCurrency, $stripePlanNetSetupFee, $stripePlanGrossSetupFee, $stripePlanSetupFeeVAT, $stripePlanSetupFeeVATRate, $stripePlanNetAmount, $stripePlanGrossAmount, $stripePlanAmountVAT, $stripePlanAmountVATRate, $grossAmountAndGrossSetupFee, $productName, $escapeType = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		$siteTitle  = get_bloginfo( 'name' );
		$dateFormat = get_option( 'date_format' );

		return array(
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanGrossSetupFee ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanNetSetupFee ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanGrossSetupFee ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanSetupFeeVAT ),
			self::escape( round( $stripePlanSetupFeeVATRate, 4 ) . '%', $escapeType ),
			self::escape( $stripePlanName, $escapeType ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanGrossAmount ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanNetAmount ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanGrossAmount ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $stripePlanAmountVAT ),
			self::escape( round( $stripePlanAmountVATRate, 4 ) . '%', $escapeType ),
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $stripePlanCurrency, $grossAmountAndGrossSetupFee ),
			self::escape( $siteTitle, $escapeType ),
			self::escape( $customerName, $escapeType ),
			self::escape( $customerEmail, $escapeType ),
			self::escape( $billingAddress['line1'], $escapeType ),
			self::escape( $billingAddress['line2'], $escapeType ),
			self::escape( $billingAddress['city'], $escapeType ),
			self::escape( $billingAddress['state'], $escapeType ),
			self::escape( $billingAddress['country'], $escapeType ),
			self::escape( $billingAddress['country_code'], $escapeType ),
			self::escape( $billingAddress['zip'], $escapeType ),
			self::escape( $shippingName, $escapeType ),
			self::escape( $shippingAddress['line1'], $escapeType ),
			self::escape( $shippingAddress['line2'], $escapeType ),
			self::escape( $shippingAddress['city'], $escapeType ),
			self::escape( $shippingAddress['state'], $escapeType ),
			self::escape( $shippingAddress['country'], $escapeType ),
			self::escape( $shippingAddress['country_code'], $escapeType ),
			self::escape( $shippingAddress['zip'], $escapeType ),
			self::escape( $productName, $escapeType ),
			self::escape( date( $dateFormat ), $escapeType )
		);
	}

	/**
	 * @param $netValue
	 * @param $taxPercent
	 *
	 * @return mixed
	 */
	public static function calculateGrossFromNet( $netValue, $taxPercent ) {
		if ( ! is_numeric( $netValue ) ) {
			throw new InvalidArgumentException( sprintf( 'Parameter %s=%s is not numeric.', 'netValue', $netValue ) );
		}
		if ( ! is_numeric( $taxPercent ) ) {
			throw new InvalidArgumentException( sprintf( 'Parameter %s=%s is not numeric.', 'taxPercent', $taxPercent ) );
		}

		if ( $taxPercent == 0.0 ) {
			$grossValue = $netValue;
			$taxValue   = 0;
		} else {
			$grossValue = round( $netValue * ( 1.0 + round( $taxPercent, 4 ) / 100.0 ) );
			$taxValue   = $grossValue - $netValue;
		}

		$result = array(
			'net'        => $netValue,
			'taxPercent' => $taxPercent,
			'taxValue'   => $taxValue,
			'gross'      => $grossValue
		);

		return $result;
	}

	public static function get_vat_rate_type_values() {
		return array(
			MM_WPFS::VAT_RATE_TYPE_NO_VAT     => __( 'No VAT', 'wp-full-stripe' ),
			MM_WPFS::VAT_RATE_TYPE_FIXED_VAT  => __( 'Fixed rate', 'wp-full-stripe' ),
			MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT => __( 'Custom rate', 'wp-full-stripe' )
		);
	}

	/**
	 * @param $formId
	 * @param $formType
	 * @param $stripePlan
	 * @param $billingAddress
	 * @param null $customInputs
	 *
	 * @return array
	 */
	public static function prepare_vat_filter_arguments( $formId, $formType, $stripePlan, $billingAddress, $customInputs = null ) {
		$planSetupFee = MM_WPFS_Utils::get_setup_fee_for_plan( $stripePlan );

		$vatFilterArguments = array(
			'form_id'         => $formId,
			'form_type'       => $formType,
			'plan_id'         => isset( $stripePlan ) ? $stripePlan->id : '',
			'plan_currency'   => isset( $stripePlan ) ? $stripePlan->currency : '',
			'plan_amount'     => isset( $stripePlan ) ? $stripePlan->amount : '',
			'plan_setup_fee'  => $planSetupFee,
			'billing_address' => $billingAddress
		);

		if ( is_array( $customInputs ) && sizeof( $customInputs ) > 0 ) {
			$vatFilterArguments['custom_inputs'] = $customInputs;
		}

		return $vatFilterArguments;
	}

	/**
	 * @param \Stripe\StripeObject $stripePlan
	 *
	 * @return int
	 */
	public static function get_setup_fee_for_plan( $stripePlan ) {
		$planSetupFee = 0;
		if ( isset( $stripePlan ) && isset( $stripePlan->metadata ) && isset( $stripePlan->metadata->setup_fee ) ) {
			$planSetupFee = $stripePlan->metadata->setup_fee;
		}

		return $planSetupFee;
	}

	/**
	 * @param $customInputLabels
	 * @param $customInputValues
	 *
	 * @return array
	 */
	public static function prepare_custom_input_data( $customInputLabels, $customInputValues ) {
		$customInputs = array();
		if ( ! is_null( $customInputLabels ) && ! is_null( $customInputValues ) ) {
			foreach ( $customInputLabels as $i => $label ) {
				$customInputs[ $label ] = $customInputValues[ $i ];
			}
		}

		return $customInputs;
	}

	/**
	 * @param $stripePlans
	 * @param $formPlans
	 *
	 * @return array
	 */
	public static function get_sorted_form_plans( $stripePlans, $formPlans ) {
		$plans       = array();
		$formPlanIDs = json_decode( $formPlans );
		foreach ( $stripePlans as $stripePlan ) {
			$item = array_search( $stripePlan->id, $formPlanIDs );
			if ( $item !== false ) {
				$plans[ $item ] = $stripePlan;
			}
		}
		ksort( $plans );

		return $plans;
	}

	public static function format_amount( $currency, $amount ) {
		$formattedAmount = null;
		$currencyArray   = MM_WPFS_Currencies::get_currency_for( $currency );
		if ( is_array( $currencyArray ) ) {
			if ( $currencyArray['zeroDecimalSupport'] == true ) {
				$pattern   = '%d';
				$theAmount = is_numeric( $amount ) ? $amount : 0;
			} else {
				$pattern   = '%0.2f';
				$theAmount = is_numeric( $amount ) ? ( $amount / 100.0 ) : 0;
			}

			$formattedAmount = esc_attr( sprintf( $pattern, $theAmount ) );
		}

		return $formattedAmount;
	}

	/**
	 * Constructs an array with the default email receipt templates.
	 *
	 * @return array
	 */
	public static function create_default_email_receipts() {
		$emailReceipts = array();

		$paymentMade                   = self::create_default_payment_made_email_receipt();
		$cardCaptured                  = self::create_default_card_captured_email_receipt();
		$subscriptionStarted           = self::create_default_subscription_started_email_receipt();
		$subscriptionFinished          = self::create_default_subscription_finished_email_receipt();
		$cardUpdateConfirmationRequest = self::create_default_card_update_confirmation_request_email_receipt();

		$emailReceipts['paymentMade']                   = $paymentMade;
		$emailReceipts['cardCaptured']                  = $cardCaptured;
		$emailReceipts['subscriptionStarted']           = $subscriptionStarted;
		$emailReceipts['subscriptionFinished']          = $subscriptionFinished;
		$emailReceipts['cardUpdateConfirmationRequest'] = $cardUpdateConfirmationRequest;

		return $emailReceipts;
	}

	/**
	 * @return stdClass
	 */
	public static function create_default_payment_made_email_receipt() {
		$paymentMade          = new stdClass();
		$paymentMade->subject = 'Payment Receipt';
		$paymentMade->html    = "<html><body><p>Hi,</p><p>Here's your receipt for your payment of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";

		return $paymentMade;
	}

	/**
	 * @return stdClass
	 */
	public static function create_default_card_captured_email_receipt() {
		$cardCaptured          = new stdClass();
		$cardCaptured->subject = 'Card Information Saved';
		$cardCaptured->html    = '<html><body><p>Hi,</p><p>Your payment information has been saved.</p><p>Thanks</p><br/>%NAME%</body></html>';

		return $cardCaptured;
	}

	/**
	 * @return stdClass
	 */
	public static function create_default_subscription_started_email_receipt() {
		$subscriptionStarted          = new stdClass();
		$subscriptionStarted->subject = 'Subscription Receipt';
		$subscriptionStarted->html    = "<html><body><p>Hi,</p><p>Here's your receipt for your subscription of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";

		return $subscriptionStarted;
	}

	/**
	 * @return stdClass
	 */
	public static function create_default_subscription_finished_email_receipt() {
		$subscriptionFinished          = new stdClass();
		$subscriptionFinished->subject = 'Subscription Ended';
		$subscriptionFinished->html    = '<html><body><p>Hi,</p><p>Your subscription has ended.</p><p>Thanks</p><br/>%NAME%</body></html>';

		return $subscriptionFinished;
	}

	public static function create_default_card_update_confirmation_request_email_receipt() {
		$homeUrl                                = home_url();
		$cardUpdateConfirmationRequest          = new stdClass();
		$cardUpdateConfirmationRequest->subject = 'Security code for updating subscription';
		$cardUpdateConfirmationRequest->html    = '<html>
<body>
<p>Dear %CUSTOMER_EMAIL%,</p>

<p>You are receiving this email because you requested access to the page where you can manage your subscription(s).</p>

<br/>
<table>
    <tr>
        <td><b>Subscription management page:</b></td>
        <td><a href="https://www.example.com/manage-subscription">https://www.example.com/manage-subscription</a></td>
    </tr>
    <tr>
        <td><b>Your security code:</b></td>
        <td>%CARD_UPDATE_SECURITY_CODE%</td>
    </tr>
</table>

<br/>
<p>
    Thanks,<br/>
    %NAME%
</p>
</body>
</html>';

		return $cardUpdateConfirmationRequest;
	}

	/**
	 * Parse amount as smallest common currency unit with the given currency if the amount is a number.
	 *
	 * @param $currency
	 * @param $amount
	 *
	 * @return int|string the parsed value if the amount is a valid number, the amount itself otherwise
	 */
	public static function parse_amount( $currency, $amount ) {
		if ( ! is_numeric( $amount ) ) {
			return $amount;
		}
		$currencyArray = MM_WPFS_Currencies::get_currency_for( $currency );
		if ( is_array( $currencyArray ) ) {
			if ( $currencyArray['zeroDecimalSupport'] == true ) {
				$theAmount = $amount;
			} else {
				$theAmount = $amount * 100.0;
			}

			return $theAmount;
		}

		return $amount;
	}

	/**
	 * @deprecated
	 * Insert the inputs into the metadata array
	 *
	 * @param $metadata
	 * @param $customInputs
	 * @param $customInputValues
	 *
	 * @return mixed
	 */
	public static function add_custom_inputs( $metadata, $customInputs, $customInputValues ) {

		// MM_WPFS_Utils::log( 'add_custom_inputs(): CALLED, params: metadata=' . print_r( $metadata, true ) . ', customInputs=' . print_r( $customInputs, true ) . ', customInputValues=' . print_r( $customInputValues, true ) );

		if ( $customInputs == null ) {
			$customInputValueString = is_array( $customInputValues ) ? implode( ",", $customInputValues ) : printf( $customInputValues );
			if ( ! empty( $customInputValueString ) ) {
				$metadata['custom_inputs'] = $customInputValueString;
			}
		} else {
			$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $customInputs );
			foreach ( $customInputLabels as $i => $label ) {
				$key = $label;
				if ( array_key_exists( $key, $metadata ) ) {
					$key = $label . $i;
				}
				if ( ! empty( $customInputValues[ $i ] ) ) {
					$metadata[ $key ] = $customInputValues[ $i ];
				}
			}
		}

		return $metadata;
	}

	/**
	 * @deprecated
	 *
	 * @param $encodedCustomInputs
	 *
	 * @return array
	 */
	public static function decode_custom_input_labels( $encodedCustomInputs ) {
		$customInputLabels = array();
		if ( ! is_null( $encodedCustomInputs ) ) {
			$customInputLabels = explode( '{{', $encodedCustomInputs );
		}

		return $customInputLabels;
	}

	/**
	 * @param $metadata
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function add_metadata_entry( $metadata, $key, $value ) {
		if ( is_string( $value ) && strlen( $value ) > self::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
			$parts = str_split( $value, self::STRIPE_METADATA_VALUE_MAX_LENGTH );
			foreach ( $parts as $i => $part ) {
				$indexPostfix                    = '_' . ( $i + 1 );
				$metadataIndexedKey              = substr( $key, 0, self::STRIPE_METADATA_KEY_MAX_LENGTH - sizeof( $indexPostfix ) ) . $indexPostfix;
				$metadata[ $metadataIndexedKey ] = $parts[ $i ];
			}
		} else {
			if ( sizeof( $metadata ) < self::STRIPE_METADATA_KEY_MAX_COUNT ) {
				$metadataKey              = substr( $key, 0, self::STRIPE_METADATA_KEY_MAX_LENGTH );
				$metadata[ $metadataKey ] = substr( $value, 0, self::STRIPE_METADATA_VALUE_MAX_LENGTH );
			}
		}

		return $metadata;
	}

	/**
	 * @deprecated
	 *
	 * @param $customerEmail
	 * @param $customerName
	 * @param $formName
	 * @param $billingName
	 * @param $billingAddressLine1
	 * @param $billingAddressLine2
	 * @param $billingAddressZip
	 * @param $billingAddressCity
	 * @param $billingAddressState
	 * @param $billingAddressCountry
	 * @param $billingAddressCountryCode
	 * @param $shippingName
	 * @param $shippingAddressLine1
	 * @param $shippingAddressLine2
	 * @param $shippingAddressZip
	 * @param $shippingAddressCity
	 * @param $shippingAddressState
	 * @param $shippingAddressCountry
	 * @param $shippingAddressCountryCode
	 *
	 * @return array
	 */
	public static function create_metadata( $customerEmail, $customerName, $formName, $billingName = null, $billingAddressLine1 = null, $billingAddressLine2 = null, $billingAddressZip = null, $billingAddressCity = null, $billingAddressState = null, $billingAddressCountry = null, $billingAddressCountryCode = null, $shippingName = null, $shippingAddressLine1 = null, $shippingAddressLine2 = null, $shippingAddressZip = null, $shippingAddressCity = null, $shippingAddressState = null, $shippingAddressCountry = null, $shippingAddressCountryCode = null ) {
		$metadata = array();

		if ( ! empty( $customerEmail ) ) {
			$metadata['customer_email'] = $customerEmail;
		}
		if ( ! empty( $customerName ) ) {
			$metadata['customer_name'] = $customerName;
		}
		if ( ! empty( $formName ) ) {
			$metadata['form_name'] = $formName;
		}

		if ( ! empty( $billingName ) ) {
			$metadata['billing_name'] = $billingName;
		}
		if ( ! empty( $billingAddressLine1 ) || ! empty( $billingAddressZip ) || ! empty( $billingAddressCity ) || ! empty( $billingAddressCountry ) ) {
			$metadata['billing_address'] = implode( '|', array(
				$billingAddressLine1,
				$billingAddressLine2,
				$billingAddressZip,
				$billingAddressCity,
				$billingAddressState,
				$billingAddressCountry,
				$billingAddressCountryCode
			) );
		}
		if ( ! empty( $shippingName ) ) {
			$metadata['shipping_name'] = $shippingName;
		}
		if ( ! empty( $shippingAddressLine1 ) || ! empty( $shippingAddressZip ) || ! empty( $shippingAddressCity ) || ! empty( $shippingAddressCountry ) ) {
			$metadata['shipping_address'] = implode( '|', array(
				$shippingAddressLine1,
				$shippingAddressLine2,
				$shippingAddressZip,
				$shippingAddressCity,
				$shippingAddressState,
				$shippingAddressCountry,
				$shippingAddressCountryCode
			) );
		}

		return $metadata;
	}

	/**
	 * @deprecated
	 *
	 * @param $showCustomInput
	 * @param $customInputLabels
	 * @param $customInputTitle
	 * @param $customInputValues
	 * @param $customInputRequired
	 *
	 * @return ValidationResult
	 */
	public static function validate_custom_input_values( $showCustomInput, $customInputLabels, $customInputTitle, $customInputValues, $customInputRequired ) {
		$result = new ValidationResult();

		if ( $showCustomInput == 0 ) {
			return $result;
		}

		if ( $customInputRequired == 1 ) {
			if ( $customInputLabels == null ) {
				if ( is_null( $customInputValues ) || ( trim( $customInputValues ) == false ) ) {
					$result->setValid( false );
					$result->setMessage( sprintf( __( 'Please enter a value for "%s".', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $customInputTitle ) ) );
				}
			} else {
				$customInputLabelArray = MM_WPFS_Utils::decode_custom_input_labels( $customInputLabels );
				foreach ( $customInputLabelArray as $i => $label ) {
					if ( $result->isValid() && ( is_null( $customInputValues[ $i ] ) || ( trim( $customInputValues[ $i ] ) == false ) ) ) {
						$result->setValid( false );
						$result->setMessage( sprintf( __( 'Please enter a value for "%s".', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $label ) ) );
					}
				}
			}
		}

		if ( $result->isValid() ) {
			if ( $customInputLabels == null ) {
				if ( is_string( $customInputValues ) && strlen( $customInputValues ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
					$result->setValid( false );
					$result->setMessage( sprintf( __( 'The value for "%s" is too long.', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $customInputTitle ) ) );
				}
			} else {
				$customInputLabelArray = MM_WPFS_Utils::decode_custom_input_labels( $customInputLabels );
				foreach ( $customInputLabelArray as $i => $label ) {
					if ( $result->isValid() && ( is_string( $customInputValues[ $i ] ) && strlen( $customInputValues[ $i ] ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) ) {
						$result->setValid( false );
						$result->setMessage( sprintf( __( 'The value for "%s" is too long.', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $label ) ) );
					}
				}
			}
		}

		return $result;
	}

	public static function translate_label( $label ) {
		if ( empty( $label ) ) {
			return '';
		}

		return esc_attr( __( $label, 'wp-full-stripe' ) );
	}

	/**
	 * @deprecated
	 *
	 * @param \Stripe\StripeObject $stripeCustomer
	 *
	 * @return string Stripe Customer's name or null
	 */
	public static function retrieve_customer_name( $stripeCustomer ) {
		$customerName = null;
		if ( isset( $stripeCustomer ) && isset( $stripeCustomer->metadata ) && isset( $stripeCustomer->metadata->customer_name ) ) {
			$customerName = $stripeCustomer->metadata->customer_name;
		}
		if ( is_null( $customerName ) ) {
			if ( isset( $stripeCustomer->subscriptions ) ) {
				foreach ( $stripeCustomer->subscriptions as $subscription ) {
					if ( is_null( $customerName ) ) {
						if ( isset( $subscription->metadata ) && isset( $subscription->metadata->customer_name ) ) {
							$customerName = $subscription->metadata->customer_name;
						}
					}
				}
			}
		}

		return $customerName;
	}

	/**
	 * @param MM_WPFS_Database $databaseService
	 * @param MM_WPFS_Payment_API $stripeService
	 * @param string $stripeCustomerEmail
	 *
	 * @param bool $returnStripeCustomerObject
	 *
	 * @return \Stripe\Customer
	 */
	public static function find_existing_stripe_customer_by_email( $databaseService, $stripeService, $stripeCustomerEmail, $returnStripeCustomerObject = false ) {

		$options  = get_option( 'fullstripe_options' );
		$liveMode = $options['apiMode'] === 'live';

		$customers = $databaseService->get_existing_stripe_customers_by_email( $stripeCustomerEmail, $liveMode );

		$result = null;
		foreach ( $customers as $customer ) {
			$stripeCustomer = null;
			try {
				$stripeCustomer = $stripeService->retrieve_customer( $customer['stripeCustomerID'] );
			} catch ( Exception $e ) {
				MM_WPFS_Utils::logException( $e );
			}

			if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
				if ( $returnStripeCustomerObject ) {
					$result = $stripeCustomer;
				} else {
					$result = $customer;
				}
				break;
			}
		}

		return $result;
	}

	public static function logException( Exception $e, $object = null ) {
		if ( isset( $e ) ) {
			if ( is_null( $object ) ) {
				$message = sprintf( 'Message=%s, Stack=%s ', $e->getMessage(), $e->getTraceAsString() );
			} else {
				$message = sprintf( 'Class=%s, Message=%s, Stack=%s ', get_class( $object ), $e->getMessage(), $e->getTraceAsString() );
			}
			MM_WPFS_Utils::log( $message );
		}
	}

	public static function log( $message ) {
		error_log( self::WPFS_LOG_MESSAGE_PREFIX . $message );
	}

	public static function format_interval_label( $interval, $intervalCount ) {

		$intervalLabel = __( 'No interval', 'wp-full-stripe' );
		if ( $interval === "year" ) {
			$intervalLabel = sprintf( _n( 'year', '%d years', $intervalCount, 'wp-full-stripe' ), number_format_i18n( $intervalCount ) );
		} elseif ( $interval === "month" ) {
			$intervalLabel = sprintf( _n( 'month', '%d months', $intervalCount, 'wp-full-stripe' ), number_format_i18n( $intervalCount ) );
		} elseif ( $interval === "week" ) {
			$intervalLabel = sprintf( _n( 'week', '%d weeks', $intervalCount, 'wp-full-stripe' ), number_format_i18n( $intervalCount ) );
		}

		return $intervalLabel;
	}

	public static function get_translated_interval_label( $interval, $count ) {
		$label = null;
		if ( $interval == 'week' ) {
			$label = _n( 'week', 'weeks', $count, 'wp-full-stripe' );
		} elseif ( $interval == 'month' ) {
			$label = _n( 'month', 'months', $count, 'wp-full-stripe' );
		} elseif ( $interval == 'year' ) {
			$label = _n( 'year', 'years', $count, 'wp-full-stripe' );
		} else {
			$label = $interval;
		}

		return $label;
	}

	public static function echo_translated_label( $label ) {
		echo self::translate_label( $label );
	}

	public static function get_google_recaptcha_site_key() {
		$googleReCAPTCHASiteKey = null;
		$options                = get_option( 'fullstripe_options' );
		if ( array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
			$googleReCAPTCHASiteKey = $options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ];
		}

		return $googleReCAPTCHASiteKey;
	}

	public static function get_secure_inline_forms_with_google_recaptcha() {
		$options = get_option( 'fullstripe_options' );
		if ( array_key_exists( MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
			return $options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] == '1';
		}

		return false;
	}

	public static function get_secure_subscription_update_with_google_recaptcha() {
		$options = get_option( 'fullstripe_options' );
		if ( array_key_exists( MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA, $options ) ) {
			return $options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] == '1';
		}

		return false;
	}

	public static function create_default_payment_stripe_description() {
		return __( 'Payment on form %FORM_NAME%', 'wp-full-stripe' );
	}

	/**
	 * @param MM_WPFS_PaymentTransactionData $transactionData
	 * @param $escapeType
	 *
	 * @return array
	 */
	public static function getPaymentMacroValues( $transactionData, $escapeType = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		return MM_WPFS_Utils::get_payment_macro_values(
			$transactionData->getCustomerEmail(),
			$transactionData->getCurrency(),
			$transactionData->getAmount(),
			$transactionData->getBillingName(),
			$transactionData->getBillingAddress(),
			$transactionData->getShippingName(),
			$transactionData->getShippingAddress(),
			$transactionData->getProductName(),
			$transactionData->getFormName(),
			$escapeType
		);
	}

	/**
	 * @param $email
	 * @param $currency
	 * @param $amount
	 * @param $billingName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $productName
	 * @param $formName
	 * @param string $escapeType
	 *
	 * @return array
	 */
	public static function get_payment_macro_values( $email, $currency, $amount, $billingName, $billingAddress, $shippingName, $shippingAddress, $productName, $formName, $escapeType = MM_WPFS_Utils::ESCAPE_TYPE_ATTR ) {
		$siteTitle  = get_bloginfo( 'name' );
		$dateFormat = get_option( 'date_format' );

		return array(
			MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $currency, $amount ),
			self::escape( $siteTitle, $escapeType ),
			self::escape( $billingName, $escapeType ),
			self::escape( $email, $escapeType ),
			self::escape( $billingAddress['line1'], $escapeType ),
			self::escape( $billingAddress['line2'], $escapeType ),
			self::escape( $billingAddress['city'], $escapeType ),
			self::escape( $billingAddress['state'], $escapeType ),
			self::escape( $billingAddress['country'], $escapeType ),
			self::escape( $billingAddress['country_code'], $escapeType ),
			self::escape( $billingAddress['zip'], $escapeType ),
			self::escape( $shippingName, $escapeType ),
			self::escape( $shippingAddress['line1'], $escapeType ),
			self::escape( $shippingAddress['line2'], $escapeType ),
			self::escape( $shippingAddress['city'], $escapeType ),
			self::escape( $shippingAddress['state'], $escapeType ),
			self::escape( $shippingAddress['country'], $escapeType ),
			self::escape( $shippingAddress['country_code'], $escapeType ),
			self::escape( $shippingAddress['zip'], $escapeType ),
			self::escape( $productName, $escapeType ),
			self::escape( date( $dateFormat ), $escapeType ),
			self::escape( $formName, $escapeType )
		);
	}

	/**
	 * @deprecated
	 *
	 * @param $data
	 *
	 * @return array|string
	 */
	public static function html_escape_value( $data ) {
		if ( ! is_array( $data ) ) {
			return htmlspecialchars( $data, ENT_QUOTES, 'UTF-8', false );
		}

		$escapedData = array();

		foreach ( $data as $value ) {
			array_push( $escapedData, self::html_escape_value( $value ) );
		}

		return $escapedData;
	}

	public static function get_default_terms_of_use_label() {
		$defaultTermsOfUseURL     = home_url( '/terms-of-use' );
		$defaultTermsOfUseCaption = __( 'Terms of Use', 'wp-full-stripe' );
		$defaultTermsOfUseHTML    = '<a href="%s" target="_blank">%s</a>';

		return sprintf( __( 'I accept the %s', 'wp-full-stripe' ), sprintf( $defaultTermsOfUseHTML, $defaultTermsOfUseURL, $defaultTermsOfUseCaption ) );
	}

	public static function get_default_terms_of_use_not_checked_error_message() {
		return __( 'Please accept the Terms of Use', 'wp-full-stripe' );
	}

	public static function get_payment_statuses() {
		return array(
			MM_WPFS::PAYMENT_STATUS_FAILED,
			MM_WPFS::PAYMENT_STATUS_RELEASED,
			MM_WPFS::PAYMENT_STATUS_REFUNDED,
			MM_WPFS::PAYMENT_STATUS_EXPIRED,
			MM_WPFS::PAYMENT_STATUS_PAID,
			MM_WPFS::PAYMENT_STATUS_AUTHORIZED,
			MM_WPFS::PAYMENT_STATUS_PENDING
		);
	}

	public static function get_payment_status( $payment ) {
		if ( is_null( $payment ) ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
		} elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_FAILED === $payment->last_charge_status ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_FAILED;
		} elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_PENDING === $payment->last_charge_status ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_PENDING;
		} elseif ( 1 == $payment->expired ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_EXPIRED;
		} elseif ( 1 == $payment->refunded ) {
			if ( 1 == $payment->captured ) {
				$payment_status = MM_WPFS::PAYMENT_STATUS_REFUNDED;
			} else {
				$payment_status = MM_WPFS::PAYMENT_STATUS_RELEASED;
			}
		} elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $payment->last_charge_status && 1 == $payment->paid && 1 == $payment->captured ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_PAID;
		} elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $payment->last_charge_status && 1 == $payment->paid && 0 == $payment->captured ) {
			$payment_status = MM_WPFS::PAYMENT_STATUS_AUTHORIZED;
		} else {
			$payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
		}

		return $payment_status;
	}

	public static function get_payment_status_label( $payment_status ) {

		if ( MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $payment_status ) {
			$label = __( 'Authorized', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_PAID === $payment_status ) {
			$label = __( 'Paid', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_EXPIRED === $payment_status ) {
			$label = __( 'Expired', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_RELEASED === $payment_status ) {
			$label = __( 'Released', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_REFUNDED === $payment_status ) {
			$label = __( 'Refunded', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_FAILED === $payment_status ) {
			$label = __( 'Failed', 'wp-full-stripe' );
		} elseif ( MM_WPFS::PAYMENT_STATUS_PENDING === $payment_status ) {
			$label = __( 'Pending', 'wp-full-stripe' );
		} else {
			$label = __( 'Unknown', 'wp-full-stripe' );
		}

		return $label;
	}

	public static function get_cancellation_count_for_plan( $plan ) {
		$cancellation_count = 0;
		if ( isset( $plan ) && isset( $plan->metadata ) ) {
			if ( isset( $plan->metadata->cancellation_count ) ) {
				if ( is_numeric( $plan->metadata->cancellation_count ) ) {
					$cancellation_count = intval( $plan->metadata->cancellation_count );
				}
			}
		}

		return $cancellation_count;
	}

	/**
	 * @param $form
	 *
	 * @return null|string
	 */
	public static function getFormId( $form ) {
		if ( is_null( $form ) ) {
			return null;
		}
		if ( isset( $form->paymentFormID ) ) {
			return $form->paymentFormID;
		}
		if ( isset( $form->subscriptionFormID ) ) {
			return $form->subscriptionFormID;
		}
		if ( isset( $form->checkoutFormID ) ) {
			return $form->checkoutFormID;
		}
		if ( isset( $form->checkoutSubscriptionFormID ) ) {
			return $form->checkoutSubscriptionFormID;
		}

		return null;
	}

}

/**
 * @deprecated
 * Class ValidationResult
 */
class ValidationResult {
	/** @var bool */
	protected $valid = true;
	/** @var  string */
	protected $message;

	/**
	 * @return boolean
	 */
	public function isValid() {
		return $this->valid;
	}

	/**
	 * @param boolean $valid
	 */
	public function setValid( $valid ) {
		$this->valid = $valid;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

}

MM_WPFS::getInstance();
