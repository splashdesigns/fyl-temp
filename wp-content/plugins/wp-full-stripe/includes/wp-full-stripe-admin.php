<?php

/**
 * Class MM_WPFS_Admin deals with admin back-end input i.e. create plans, transfers
 */
class MM_WPFS_Admin {

	/* @var boolean */
	private $debug_log = false;

	/* @var $stripe MM_WPFS_Stripe */
	private $stripe = null;

	/* @var $db MM_WPFS_Database */
	private $db = null;

	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	/* @var $webHookEventHandler MM_WPFS_WebHookEventHandler */
	private $webHookEventHandler = null;

	public function __construct() {
		$this->stripe              = new MM_WPFS_Stripe();
		$this->db                  = new MM_WPFS_Database();
		$this->mailer              = new MM_WPFS_Mailer();
		$this->webHookEventHandler = new MM_WPFS_WebHookEventHandler( $this->db, $this->stripe, $this->mailer );
		$this->hooks();
	}

	private function hooks() {

		// tnagy actions for settings
		add_action( 'wp_ajax_wp_full_stripe_update_settings', array( $this, 'fullstripe_update_settings_post' ) );

		// tnagy actions for subscription plans
		add_action( 'wp_ajax_wp_full_stripe_create_plan', array( $this, 'fullstripe_create_plan_post' ) );
		add_action( 'wp_ajax_wp_full_stripe_edit_subscription_plan', array(
			$this,
			'fullstripe_edit_subscription_plan_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_delete_subscription_plan', array(
			$this,
			'fullstripe_delete_subscription_plan'
		) );

		// tnagy actions for subscription forms
		add_action( 'wp_ajax_wp_full_stripe_create_subscripton_form', array(
			$this,
			'fullstripe_create_subscription_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_subscription_form', array(
			$this,
			'fullstripe_edit_subscription_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_delete_subscription_form', array(
			$this,
			'fullstripe_delete_subscription_form'
		) );

		// tnagy actions for checkout subscriptions
		add_action( 'wp_ajax_wp_full_stripe_create_checkout_subscription_form', array(
			$this,
			'fullstripe_create_checkout_subscription_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_checkout_subscription_form', array(
			$this,
			'fullstripe_edit_checkout_subscription_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_delete_checkout_subscription_form', array(
			$this,
			'fullstripe_delete_checkout_subscription_form'
		) );

		// tnagy actions for subscriptions
		add_action( 'wp_ajax_wp_full_stripe_cancel_subscription', array( $this, 'fullstripe_cancel_subscription' ) );
		add_action( 'wp_ajax_wp_full_stripe_delete_subscription_record', array(
			$this,
			'fullstripe_delete_subscription_record'
		) );

		// tnagy actions for payment forms
		add_action( 'wp_ajax_wp_full_stripe_create_payment_form', array(
			$this,
			'fullstripe_create_payment_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_payment_form', array( $this, 'fullstripe_edit_payment_form_post' ) );
		add_action( 'wp_ajax_wp_full_stripe_delete_payment_form', array( $this, 'fullstripe_delete_payment_form' ) );

		// tnagy actions for checkouts
		add_action( 'wp_ajax_wp_full_stripe_create_checkout_form', array(
			$this,
			'fullstripe_create_checkout_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_checkout_form', array( $this, 'fullstripe_edit_checkout_form_post' ) );
		add_action( 'wp_ajax_wp_full_stripe_delete_checkout_form', array( $this, 'fullstripe_delete_checkout_form' ) );

		// tnagy actions for payments
		add_action( 'wp_ajax_wp_full_stripe_delete_payment', array( $this, 'fullstripe_delete_payment_local' ) );
		add_action( 'wp_ajax_wp_full_stripe_capture_payment', array( $this, 'fullstripe_capture_payment' ) );
		add_action( 'wp_ajax_wp_full_stripe_refund_payment', array( $this, 'fullstripe_refund_payment' ) );

		// tnagy actions for saved cards
		add_action( 'wp_ajax_wp_full_stripe_create_inline_card_capture_form', array(
			$this,
			'fullstripe_create_inline_card_capture_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_create_popup_card_capture_form', array(
			$this,
			'fullstripe_create_popup_card_capture_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_inline_card_capture_form', array(
			$this,
			'fullstripe_edit_inline_card_capture_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_edit_popup_card_capture_form', array(
			$this,
			'fullstripe_edit_popup_card_capture_form_post'
		) );
		add_action( 'wp_ajax_wp_full_stripe_delete_inline_card_capture_form', array(
			$this,
			'fullstripe_delete_inline_card_capture_form'
		) );
		add_action( 'wp_ajax_wp_full_stripe_delete_popup_card_capture_form', array(
			$this,
			'fullstripe_delete_popup_card_capture_form'
		) );

		// tnagy actions for recipients
		add_action( 'wp_ajax_wp_full_stripe_create_recipient', array( $this, 'fullstripe_create_recipient' ) );
		add_action( 'wp_ajax_wp_full_stripe_create_recipient_card', array(
			$this,
			'fullstripe_create_recipient_card'
		) );

		// tnagy actions for transfers
		add_action( 'wp_ajax_wp_full_stripe_create_transfer', array( $this, 'fullstripe_create_transfer' ) );

		// tnagy handle stripe webhook events
		add_action( 'admin_post_nopriv_handle_wpfs_event', array(
			$this,
			'fullstripe_handle_wpfs_event'
		) );
	}

	function fullstripe_create_plan_post() {

		$validation_result = array();
		if ( ! $this->is_valid_plan( $validation_result ) ) {
			header( "Content-Type: application/json" );
			echo json_encode( array( 'success' => false, 'validation_result' => $validation_result ) );
			exit;
		}

		$id                = stripslashes( $_POST['sub_id'] );
		$name              = $_POST['sub_name'];
		$currency          = $_POST['sub_currency'];
		$amount            = $_POST['sub_amount'];
		$setup_fee         = $_POST['sub_setup_fee'];
		$interval          = $_POST['sub_interval'];
		$intervalCount     = $_POST['sub_interval_count'];
		$cancellationCount = $_POST['sub_cancellation_count'];
		$trial             = $_POST['sub_trial'];

		$return                = $this->stripe->create_plan( $id, $name, $currency, $amount, $setup_fee, $interval, $trial, $intervalCount, $cancellationCount );
		$return['redirectURL'] = admin_url( 'admin.php?page=fullstripe-subscriptions&tab=plans' );

		do_action( 'fullstripe_admin_create_plan_action', $return );

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	private function is_valid_plan( &$validation_result ) {
		if ( isset( $_POST['sub_cancellation_count'] ) ) {
			if ( ! is_numeric( $_POST['sub_cancellation_count'] ) ) {
				$validation_result['sub_cancellation_count'] = __( 'Field value is invalid.', 'wp-full-stripe' );
			} else if ( intval( $_POST['sub_cancellation_count'] ) < 0 ) {
				$validation_result['sub_cancellation_count'] = __( 'Field value is invalid.', 'wp-full-stripe' );
			}
		} else {
			$validation_result['sub_cancellation_count'] = __( 'Required field.', 'wp-full-stripe' );
		}

		return empty( $validation_result );
	}

	function fullstripe_create_subscription_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-subscriptions',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$subscriptionFormModel = new MM_WPFS_Admin_InlineSubscriptionFormModel();
			$subscriptionFormModel->bind();
			$data = $subscriptionFormModel->getData();

			$this->db->insert_subscription_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_subscription_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-subscriptions',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);
		try {

			$inlineSubscriptionFormModel = new MM_WPFS_Admin_InlineSubscriptionFormModel();
			$inlineSubscriptionFormModel->bind();
			$data = $inlineSubscriptionFormModel->getData();

			$this->db->update_subscription_form( $inlineSubscriptionFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_payment_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-payments',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$inlinePaymentFormModel = new MM_WPFS_Admin_InlinePaymentFormModel();
			$inlinePaymentFormModel->bind();
			$data = $inlinePaymentFormModel->getData();

			$this->db->insert_payment_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_inline_card_capture_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-saved-cards',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$inlinePaymentFormModel = new MM_WPFS_Admin_InlinePaymentFormModel();
			$inlinePaymentFormModel->bind();
			$inlinePaymentFormModel->convertToCardCaptureForm();
			$data = $inlinePaymentFormModel->getData();

			$this->db->insert_payment_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_payment_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-payments',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$inlinePaymentFormModel = new MM_WPFS_Admin_InlinePaymentFormModel();
			$inlinePaymentFormModel->bind();
			$data = $inlinePaymentFormModel->getData();

			$this->db->update_payment_form( $inlinePaymentFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_inline_card_capture_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-saved-cards',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);
		try {

			$inlinePaymentFormModel = new MM_WPFS_Admin_InlinePaymentFormModel();
			$inlinePaymentFormModel->bind();
			$inlinePaymentFormModel->convertToCardCaptureForm();
			$data = $inlinePaymentFormModel->getData();

			$this->db->update_payment_form( $inlinePaymentFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_popup_card_capture_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-saved-cards',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$popupPaymentFormModel = new MM_WPFS_Admin_PopupPaymentFormModel();
			$popupPaymentFormModel->bind();
			$popupPaymentFormModel->convertToCardCaptureForm();
			$data = $popupPaymentFormModel->getData();

			$this->db->insert_checkout_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_checkout_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-payments',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$popupPaymentFormModel = new MM_WPFS_Admin_PopupPaymentFormModel();
			$popupPaymentFormModel->bind();
			$data = $popupPaymentFormModel->getData();

			$this->db->insert_checkout_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_checkout_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-payments',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);
		try {

			$popupPaymentFormModel = new MM_WPFS_Admin_PopupPaymentFormModel();
			$popupPaymentFormModel->bind();
			$data = $popupPaymentFormModel->getData();

			$this->db->update_checkout_form( $popupPaymentFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_popup_card_capture_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-saved-cards',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$popupPaymentFormModel = new MM_WPFS_Admin_PopupPaymentFormModel();
			$popupPaymentFormModel->bind();
			$popupPaymentFormModel->convertToCardCaptureForm();
			$data = $popupPaymentFormModel->getData();

			$this->db->update_checkout_form( $popupPaymentFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_checkout_subscription_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-subscriptions',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$popupSubscriptionFormModel = new MM_WPFS_Admin_PopupSubscriptionFormModel();
			$popupSubscriptionFormModel->bind();
			$data = $popupSubscriptionFormModel->getData();

			$this->db->insert_checkout_subscription_form( $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_checkout_subscription_form_post() {

		$redirectURL = add_query_arg(
			array(
				'page' => 'fullstripe-subscriptions',
				'tab'  => 'forms'
			),
			admin_url( 'admin.php' )
		);

		try {

			$popupSubscriptionFormModel = new MM_WPFS_Admin_PopupSubscriptionFormModel();
			$popupSubscriptionFormModel->bind();
			$data = $popupSubscriptionFormModel->getData();

			$this->db->update_checkout_subscription_form( $popupSubscriptionFormModel->getId(), $data );

			$return = array(
				'success'     => true,
				'redirectURL' => $redirectURL
			);

		} catch ( Exception $e ) {
			error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'success'     => false,
				'redirectURL' => $redirectURL,
				'ex_msg'      => $e->getMessage(),
				'ex_stack'    => $e->getTraceAsString()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_update_settings_post() {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			header( "Content-Type: application/json" );
			echo json_encode( array(
				'success'     => true,
				'redirectURL' => admin_url( 'admin.php?page=fullstripe-settings' )
			) );
			exit;
		}

		$validation_result = array();
		if ( ! $this->is_valid_options( $validation_result ) ) {
			header( "Content-Type: application/json" );
			echo json_encode( array( 'success' => false, 'validation_result' => $validation_result ) );
			exit;
		}

		// Save the posted value in the database
		$options = get_option( 'fullstripe_options' );

		$tab = null;
		if ( isset( $_POST['tab'] ) ) {
			$tab = sanitize_text_field( $_POST['tab'] );
		}
		if ( isset( $_POST['publishKey_test'] ) ) {
			$options['publishKey_test'] = sanitize_text_field( $_POST['publishKey_test'] );
		}
		if ( isset( $_POST['secretKey_test'] ) ) {
			$options['secretKey_test'] = sanitize_text_field( $_POST['secretKey_test'] );
		}
		if ( isset( $_POST['publishKey_live'] ) ) {
			$options['publishKey_live'] = sanitize_text_field( $_POST['publishKey_live'] );
		}
		if ( isset( $_POST['secretKey_live'] ) ) {
			$options['secretKey_live'] = sanitize_text_field( $_POST['secretKey_live'] );
		}
		if ( isset( $_POST['apiMode'] ) ) {
			$options['apiMode'] = sanitize_text_field( $_POST['apiMode'] );
		}
		if ( isset( $_POST['form_css'] ) ) {
			$options['form_css'] = stripslashes( $_POST['form_css'] );
		}
		if ( isset( $_POST['includeStyles'] ) ) {
			$options['includeStyles'] = sanitize_text_field( $_POST['includeStyles'] );
		}
		if ( isset( $_POST['receiptEmailType'] ) ) {
			$options['receiptEmailType'] = sanitize_text_field( $_POST['receiptEmailType'] );
		}
		if ( isset( $_POST['email_receipts'] ) ) {
			$options['email_receipts'] = json_encode( json_decode( rawurldecode( stripslashes( $_POST['email_receipts'] ) ) ) );
		}
		if ( isset( $_POST['email_receipt_sender_address'] ) ) {
			$options['email_receipt_sender_address'] = sanitize_email( $_POST['email_receipt_sender_address'] );
		}
		if ( isset( $_POST['admin_payment_receipt'] ) ) {
			$options['admin_payment_receipt'] = sanitize_text_field( $_POST['admin_payment_receipt'] );
		}
		if ( isset( $_POST['lock_email_field_for_logged_in_users'] ) ) {
			$options['lock_email_field_for_logged_in_users'] = sanitize_text_field( $_POST['lock_email_field_for_logged_in_users'] );
		}
		$secureInlineFormsWithGoogleReCAPTCHA = '0';
		if ( isset( $_POST[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] ) ) {
			$secureInlineFormsWithGoogleReCAPTCHA                                  = sanitize_text_field( $_POST[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] );
			$options[ MM_WPFS::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA ] = $secureInlineFormsWithGoogleReCAPTCHA;
		}
		$secureSubscriptionUpdateWithGoogleReCAPTCHA = '0';
		if ( isset( $_POST[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] ) ) {
			$secureSubscriptionUpdateWithGoogleReCAPTCHA                                  = sanitize_text_field( $_POST[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] );
			$options[ MM_WPFS::OPTION_SECURE_SUBSCRIPTION_UPDATE_WITH_GOOGLE_RE_CAPTCHA ] = $secureSubscriptionUpdateWithGoogleReCAPTCHA;
		}
		if ( '1' == $secureInlineFormsWithGoogleReCAPTCHA || '1' == $secureSubscriptionUpdateWithGoogleReCAPTCHA ) {
			if ( isset( $_POST[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] = sanitize_text_field( $_POST[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ] );
			}
			if ( isset( $_POST[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] ) ) {
				$options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] = sanitize_text_field( $_POST[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ] );
			}
		}

		$activeTab = null;
		if ( $tab === 'stripe' ) {
			$activeTab = $tab;
		} else if ( $tab === 'appearance' ) {
			$activeTab = $tab;
		} else if ( $tab === 'email-receipts' ) {
			$activeTab = $tab;
		} else if ( $tab === 'users' ) {
			$activeTab = $tab;
		}
		update_option( 'fullstripe_options', $options );
		do_action( 'fullstripe_admin_update_options_action', $options );

		header( "Content-Type: application/json" );
		echo json_encode( array(
			'success'     => true,
			'redirectURL' => admin_url( 'admin.php?page=fullstripe-settings' . ( isset( $activeTab ) ? "&tab=$activeTab" : "" ) )
		) );
		exit;
	}

	private function is_valid_options( &$validation_result ) {
		if ( ! $this->is_not_set_or_empty( 'email_receipt_sender_address' ) ) {
			if ( ! filter_var( sanitize_email( $_POST['email_receipt_sender_address'] ), FILTER_VALIDATE_EMAIL ) ) {
				$validation_result['email_receipt_sender_address'] = __( 'Please enter a valid email address or leave the field empty', 'wp-full-stripe' );
			}
		}

		return empty( $validation_result );
	}

	private function is_not_set_or_empty( $key ) {
		return ! isset( $_POST[ $key ] ) || empty( $_POST[ $key ] );
	}

	function fullstripe_delete_payment_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_payment_form_action', $id );

			try {
				$this->db->delete_payment_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);

			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_inline_card_capture_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_inline_card_capture_form_action', $id );

			try {
				$this->db->delete_payment_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);

			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_subscription_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_subscription_form_action', $id );

			try {
				$this->db->delete_subscription_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}

		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_checkout_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_checkout_form_action', $id );

			try {

				$this->db->delete_checkout_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => true,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_popup_card_capture_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_popup_card_capture_form_action', $id );

			try {

				$this->db->delete_checkout_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => true,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_checkout_subscription_form() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_checkout_subscription_form_action', $id );

			try {
				$this->db->delete_checkout_subscription_form( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}

		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_cancel_subscription() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];

			do_action( 'fullstripe_admin_delete_subscriber_action', $id );

			try {

				$subscriber = $this->db->find_subscriber_by_id( $id );

				if ( $subscriber ) {
					$this->db->cancel_subscription( $id );
					$this->stripe->cancel_subscription( $subscriber->stripeCustomerID, $subscriber->stripeSubscriptionID );
					$return = array(
						'success'     => true,
						'remove'      => false,
						'redirectURL' => admin_url( 'admin.php?page=fullstripe-subscriptions' )
					);
				} else {
					$return = array( 'success' => false );
				}
			} catch ( \Stripe\Error\InvalidRequest $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				// tnagy log exception but return success to continue on client side
				$return = array(
					'success'     => true,
					'with_errors' => true,
					'remove'      => false,
					'redirectURL' => admin_url( 'admin.php?page=fullstripe-subscriptions' ),
					'ex_msg'      => $e->getMessage(),
					'ex_stack'    => $e->getTraceAsString()
				);
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}

		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	public function fullstripe_delete_subscription_record() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];

			do_action( 'fullstripe_admin_delete_subscription_record_action', $id );

			try {

				$this->db->delete_subscription_by_id( $id );

				$return = array(
					'success'     => true,
					'remove'      => false,
					'redirectURL' => admin_url( 'admin.php?page=fullstripe-subscriptions' )
				);
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}

		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * @deprecated
	 */
	function fullstripe_delete_subscriber_local() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_subscriber_action', $id );

			try {
				$this->db->delete_subscriber( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}

		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_payment_local() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_payment_action', $id );

			try {
				$this->db->delete_payment( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_refund_payment() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_refund_payment_action', $id );

			try {

				$success = $this->refund( $id );

				$return = array(
					'success'     => $success,
					'redirectURL' => add_query_arg(
						array( 'page' => 'fullstripe-payments', 'tab' => 'payments' ),
						admin_url( 'admin.php' )
					)
				);
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * @param $id
	 *
	 * @return mixed|null|\Stripe\ApiResource
	 */
	private function refund( $id ) {
		$payment = null;

		if ( ! is_null( $id ) ) {
			$payment = $this->db->get_payment( $id );
		}

		if ( isset( $payment ) ) {
			$payment_status = MM_WPFS_Utils::get_payment_status( $payment );
			if ( MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $payment_status || MM_WPFS::PAYMENT_STATUS_PAID === $payment_status ) {
				$refund = $this->stripe->refund_charge( $payment->eventID );

				if ( $refund instanceof \Stripe\Refund && MM_WPFS::REFUND_STATUS_SUCCEEDED === $refund->status ) {
					$this->db->update_payment_by_charge(
						$refund->charge,
						array(
							'refunded' => true
						)
					);

					return true;
				}
			}
		}

		return false;
	}

	function fullstripe_capture_payment() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_capture_payment_action', $id );

			try {

				$success = $this->capture( $id );

				$return = array(
					'success'     => $success,
					'redirectURL' => add_query_arg(
						array( 'page' => 'fullstripe-payments', 'tab' => 'payments' ),
						admin_url( 'admin.php' )
					)
				);
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * @param $payment_id
	 *
	 * @return bool
	 *
	 */
	private function capture( $payment_id ) {
		$payment = $this->db->get_payment( $payment_id );
		if ( isset( $payment ) ) {
			$payment_status = MM_WPFS_Utils::get_payment_status( $payment );
			if ( MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $payment_status ) {
				$charge = $this->stripe->capture_charge( $payment->eventID );
				if ( $charge instanceof \Stripe\Charge && true === $charge->captured && MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $charge->status ) {
					$this->db->update_payment_by_charge(
						$charge->id,
						array(
							'paid'               => $charge->paid,
							'captured'           => $charge->captured,
							'refunded'           => $charge->refunded,
							'last_charge_status' => $charge->status,
							'failure_code'       => $charge->failure_code,
							'failure_message'    => $charge->failure_message
						)
					);

					return true;
				}
			}
		}

		return false;
	}

	function fullstripe_delete_card_capture_local() {
		if ( ! defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			$id = $_POST['id'];
			do_action( 'fullstripe_admin_delete_card_capture_action', $id );

			try {
				$this->db->delete_card_capture( $id );

				$return = array( 'success' => true );
			} catch ( Exception $e ) {
				error_log( sprintf( 'Message=%s, Stack=%s', $e->getMessage(), $e->getTraceAsString() ) );
				$return = array(
					'success'  => false,
					'ex_msg'   => $e->getMessage(),
					'ex_stack' => $e->getTraceAsString()
				);
			}
		} else {
			$return = array( 'success' => true );
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * @deprecated
	 */
	function fullstripe_create_recipient() {
		$token = $_POST['stripeToken'];
		$name  = $_POST['recipient_name'];
		$type  = $_POST['recipient_type'];
		$taxID = isset( $_POST['recipient_tax_id'] ) ? $_POST['recipient_tax_id'] : '';
		$email = isset( $_POST['recipient_email'] ) ? $_POST['recipient_email'] : '';

		$data = array(
			'name'         => $name,
			'type'         => $type,
			'bank_account' => $token
		);
		//optional fields
		if ( $taxID !== '' ) {
			$data['tax_id'] = $taxID;
		}
		if ( $email !== '' ) {
			$data['email'] = $email;
		}

		try {
			$recipient = $this->stripe->create_recipient( $data );

			do_action( 'fullstripe_admin_create_recipient_action', $recipient );

			$return = array( 'success' => true, 'msg' => 'Recipient created' );

		} catch ( Exception $e ) {
			//show notification of error
			$return = array(
				'success' => false,
				'msg'     => sprintf( __( 'There was an error creating the recipient: %s', 'wp-full-stripe' ), $e->getMessage() )
			);
		}

		//correct way to return JS results in wordpress
		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * @deprecated
	 */
	function fullstripe_create_recipient_card() {
		$token = $_POST['stripeToken'];
		$name  = $_POST['recipient_name_card'];
		$type  = $_POST['recipient_type_card'];
		$taxID = isset( $_POST['recipient_tax_id_card'] ) ? $_POST['recipient_tax_id_card'] : '';
		$email = isset( $_POST['recipient_email_card'] ) ? $_POST['recipient_email_card'] : '';

		$data = array(
			'name' => $name,
			'type' => $type,
			'card' => $token
		);
		//optional fields
		if ( $taxID !== '' ) {
			$data['tax_id'] = $taxID;
		}
		if ( $email !== '' ) {
			$data['email'] = $email;
		}

		try {
			$recipient = $this->stripe->create_recipient( $data );

			do_action( 'fullstripe_admin_create_recipient_action', $recipient );

			$return = array( 'success' => true, 'msg' => 'Recipient created' );

		} catch ( Exception $e ) {
			//show notification of error
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error creating the recipient: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		//correct way to return JS results in wordpress
		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_create_transfer() {
		$amount    = $_POST['transfer_amount'];
		$desc      = $_POST['transfer_desc'];
		$recipient = $_POST['transfer_recipient'];

		try {
			$transfer = $this->stripe->create_transfer( array(
				"amount"                => $amount,
				"currency"              => MM_WPFS::CURRENCY_USD,
				"recipient"             => $recipient,
				"statement_description" => $desc
			) );

			do_action( 'fullstripe_admin_create_transfer_action', $transfer );

			$return = array( 'success' => true );
		} catch ( Exception $e ) {
			//show notification of error
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error creating the transfer: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_edit_subscription_plan_post() {

		$plan_id              = stripslashes( $_POST['plan'] );
		$display_name         = $_POST['plan_display_name'];
		$statement_descriptor = isset( $_POST['plan_statement_descriptor'] ) ? $_POST['plan_statement_descriptor'] : null;
		$setup_fee            = isset( $_POST['plan_setup_fee'] ) ? $_POST['plan_setup_fee'] : null;

		try {

			$this->stripe->update_plan( $plan_id, array(
				'setup_fee'            => $setup_fee,
				'name'                 => $display_name,
				'statement_descriptor' => $statement_descriptor
			) );

			$return = array(
				'success'     => true,
				'msg'         => 'Subscription plan updated',
				'redirectURL' => admin_url( 'admin.php?page=fullstripe-subscriptions&tab=plans' )
			);
		} catch ( Exception $e ) {
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error updating the subscription plan: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function fullstripe_delete_subscription_plan() {

		$plan_id = stripslashes( $_POST['id'] );

		try {

			$this->stripe->delete_plan( $plan_id );

			$return = array(
				'success'     => true,
				'msg'         => 'Subscription plan deleted',
				'redirectURL' => admin_url( 'admin.php?page=fullstripe-subscriptions&tab=plans' )
			);
		} catch ( Exception $e ) {
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error deleting the subscription plan: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	/**
	 * Stripe Web hook handler
	 */
	function fullstripe_handle_wpfs_event() {

		if ( $this->debug_log ) {
			MM_WPFS_Utils::log( 'fullstripe_handle_wpfs_event(): ' . 'CALLED' );
		}

		$auth_token     = empty( $_REQUEST['auth_token'] ) ? '' : $_REQUEST['auth_token'];
		$web_hook_token = self::get_webhook_token();

		if ( $web_hook_token !== $auth_token ) {
			if ( $this->debug_log ) {
				MM_WPFS_Utils::log( 'fullstripe_handle_wpfs_event(): ' . 'Authentication failed, abort.' );
			}
			// return HTTP Unathorized
			status_header( 401 );
			header( 'Content-Type: application/json' );
			exit;
		}

		try {

			// Retrieve the request's body and parse it as JSON
			$input = @file_get_contents( "php://input" );
			// error_log( 'DEBUG: input=' . json_encode( $input ) );

			$event = json_decode( $input );

			// Do something with $event_json
			if ( $this->debug_log ) {
				MM_WPFS_Utils::log( 'fullstripe_handle_wpfs_event(): ' . 'event=' . json_encode( $event ) );
			}

			$event_processed = $this->webHookEventHandler->handle( $event );

			if ( $this->debug_log ) {
				MM_WPFS_Utils::log( 'fullstripe_handle_wpfs_event(): ' . 'event processed? ' . ( $event_processed === true ? 'true' : 'false' ) );
			}

			// return HTTP OK
			status_header( 200 );
		} catch ( Exception $e ) {
			error_log( 'ERROR: Message=' . $e->getMessage() . ', Trace=' . $e->getTraceAsString() );
			// return HTTP Internal Server Error
			status_header( 500 );
		}

		header( "Content-Type: application/json" );
		exit;
	}

	/**
	 * Generates the md5 hash by site_url and admin_email to create a unique ID for a WordPress installation.
	 * @return string
	 */
	public static function get_webhook_token() {
		$options = get_option( 'fullstripe_options' );

		return $options['webhook_token'];
	}

}