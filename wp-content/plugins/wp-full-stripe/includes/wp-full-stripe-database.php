<?php

class MM_WPFS_Database {

	/**
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function fullstripe_setup_db() {
		// require for dbDelta()
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . 'fullstripe_payments';

		$sql = "CREATE TABLE " . $table . " (
        paymentID INT NOT NULL AUTO_INCREMENT,
        eventID VARCHAR(100) NOT NULL,
        description VARCHAR(255) NOT NULL,
        payment_method VARCHAR(100),
        paid TINYINT(1),
        captured TINYINT(1),
        refunded TINYINT(1),
        expired TINYINT(1),
        failure_code VARCHAR(100),
        failure_message VARCHAR(512),
        livemode TINYINT(1),
        last_charge_status VARCHAR(100),
        currency VARCHAR(3) NOT NULL,
        amount INT NOT NULL,
        fee INT NOT NULL,
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500) NOT NULL,
        shippingAddressLine2 VARCHAR(500) NOT NULL,
        shippingAddressCity VARCHAR(500) NOT NULL,
        shippingAddressState VARCHAR(255) NOT NULL,
        shippingAddressZip VARCHAR(100) NOT NULL,
        shippingAddressCountry VARCHAR(100) NOT NULL,
        shippingAddressCountryCode VARCHAR(2) NOT NULL,
        created DATETIME NOT NULL,
        stripeCustomerID VARCHAR(100),
        name VARCHAR(100),
        email VARCHAR(255) NOT NULL,
        formId INT,
        formType VARCHAR(30),
        formName VARCHAR(100),
        UNIQUE KEY paymentID (paymentID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_payment_forms';

		$sql = "CREATE TABLE " . $table . " (
        paymentFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        formTitle VARCHAR(100) NOT NULL,
        chargeType VARCHAR(100) NOT NULL,
        amount INT NOT NULL,
        currency VARCHAR(3) NOT NULL,
        customAmount VARCHAR(32) NOT NULL,
        listOfAmounts VARCHAR(1024) DEFAULT NULL,
        allowListOfAmountsCustom TINYINT(1) DEFAULT '0',
        amountSelectorStyle VARCHAR(100) NOT NULL,
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Make Payment',
        showButtonAmount TINYINT(1) DEFAULT '1',
        showEmailInput TINYINT(1) DEFAULT '1',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        showAddress TINYINT(1) DEFAULT '0',
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        formStyle INT(5) DEFAULT 0,
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(256) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        UNIQUE KEY paymentFormID (paymentFormID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$paymentType = MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT;
		// tnagy migrate old values
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE $table SET customAmount = %s WHERE customAmount = %s", $paymentType, '0' ) );
		self::handleDbError( $queryResult, __( 'Migration of fullstripe_payment_forms/customAmount failed!', 'wp-full-stripe' ) );

		$paymentType = MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE $table SET customAmount = %s WHERE customAmount = %s", $paymentType, '1' ) );
		self::handleDbError( $queryResult, __( 'Migration of fullstripe_payment_forms/customAmount failed!', 'wp-full-stripe' ) );

		$table = $wpdb->prefix . 'fullstripe_subscription_forms';

		$sql = "CREATE TABLE " . $table . " (
        subscriptionFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        formTitle VARCHAR(100) NOT NULL,
        plans VARCHAR(2048) NOT NULL,
        showCouponInput TINYINT(1) DEFAULT '0',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        showAddress TINYINT(1) DEFAULT '0',
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        formStyle INT(5) DEFAULT 0,
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Subscribe',
        setupFee INT NOT NULL DEFAULT '0',
        vatRateType VARCHAR(32) NOT NULL,
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        defaultBillingCountry VARCHAR(100),
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(256) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        planSelectorStyle VARCHAR(32) NOT NULL,
        UNIQUE KEY subscriptionFormID (subscriptionFormID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_subscribers';

		$sql = "CREATE TABLE " . $table . " (
        subscriberID INT NOT NULL AUTO_INCREMENT,
        stripeCustomerID VARCHAR(100) NOT NULL,
        stripeSubscriptionID VARCHAR(100) NOT NULL,
		chargeMaximumCount INT(5) NOT NULL,
		chargeCurrentCount INT(5) NOT NULL,
		status VARCHAR(32) NOT NULL,
		cancelled DATETIME DEFAULT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        planID VARCHAR(100) NOT NULL,
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500),
        shippingAddressLine2 VARCHAR(500),
        shippingAddressCity VARCHAR(500),
        shippingAddressState VARCHAR(255),
        shippingAddressZip VARCHAR(100),
        shippingAddressCountry VARCHAR(100),
        shippingAddressCountryCode VARCHAR(2),
        created DATETIME NOT NULL,
        livemode TINYINT(1),
        formId INT,
        formName VARCHAR(100),
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        UNIQUE KEY subscriberID (subscriberID),
		KEY stripeSubscriptionID (stripeSubscriptionID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_checkout_forms';

		$sql = "CREATE TABLE " . $table . " (
        checkoutFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        companyName VARCHAR(100) NOT NULL,
        productDesc VARCHAR(100) NOT NULL,
        chargeType VARCHAR(100) NOT NULL,
        amount INT NOT NULL,
        currency VARCHAR(3) NOT NULL,
        customAmount VARCHAR(32) NOT NULL,
        listOfAmounts VARCHAR(1024) DEFAULT NULL,
        allowListOfAmountsCustom TINYINT(1) DEFAULT '0',
        amountSelectorStyle VARCHAR(100) NOT NULL,
        openButtonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay With Card',
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay {{amount}}',
        showButtonAmount TINYINT(1) DEFAULT '1',
        showBillingAddress TINYINT(1) DEFAULT '0',
        showShippingAddress TINYINT(1) DEFAULT '0',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        showRememberMe TINYINT(1) DEFAULT '0',
        image VARCHAR(500) NOT NULL,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        disableStyling TINYINT(1) DEFAULT 0,
        useBitcoin TINYINT(1) DEFAULT '0',
        useAlipay TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(256) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        UNIQUE KEY checkoutFormID (checkoutFormID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		//default form
		$defaultPaymentForm = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payment_forms" . " WHERE name='default';" );
		if ( $defaultPaymentForm === null ) {
			$data         = array(
				'name'         => 'default',
				'formTitle'    => 'Payment',
				'amount'       => 1000, //$10.00
				'currency'     => MM_WPFS::CURRENCY_USD,
				'customAmount' => MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT
			);
			$formats      = array( '%s', '%s', '%d', '%s', '%s' );
			$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_payment_forms', $data, $formats );
			self::handleDbError( $insertResult, __( 'Cannot insert default form!', 'wp-full-stripe' ) );
		}


		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_patch_info (
		id INT NOT NULL AUTO_INCREMENT,
		patch_id VARCHAR(255) NOT NULL,
		plugin_version VARCHAR(255) NOT NULL,
		applied_at DATETIME NOT NULL,
		description VARCHAR(500),
		UNIQUE KEY id (id),
		KEY patch_id (patch_id)
		) $charset_collate;";

		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_checkout_subscription_forms';

		$sql = "CREATE TABLE " . $table . " (
		checkoutSubscriptionFormID INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		companyName VARCHAR(100) NOT NULL,
		productDesc VARCHAR(100) NOT NULL,
		image VARCHAR(500) NOT NULL,
		plans VARCHAR(2048) NOT NULL,
		showCouponInput TINYINT(1) DEFAULT '0',
		showCustomInput TINYINT(1) DEFAULT '0',
		customInputRequired TINYINT(1) DEFAULT '0',
		customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
		customInputs TEXT,
		redirectOnSuccess TINYINT(1) DEFAULT '0',
		redirectPostID INT(5) DEFAULT 0,
		redirectUrl VARCHAR(1024) DEFAULT NULL,
		redirectToPageOrPost TINYINT(1) DEFAULT '1',
		showDetailedSuccessPage TINYINT(1) DEFAULT '0',
		showBillingAddress TINYINT(1) DEFAULT '0',
		showShippingAddress TINYINT(1) DEFAULT '0',
		sendEmailReceipt TINYINT(1) DEFAULT '0',
		disableStyling TINYINT(1) DEFAULT 0,
        openButtonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay With Card',
		buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Subscribe',
		showRememberMe TINYINT(1) DEFAULT '0',
        vatRateType VARCHAR(32) NOT NULL,
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        defaultBillingCountry VARCHAR(100),
        simpleButtonLayout TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(256) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        planSelectorStyle VARCHAR(32) NOT NULL,
        UNIQUE KEY checkoutSubscriptionFormID (checkoutSubscriptionFormID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_card_captures';

		$sql = "CREATE TABLE $table (
        captureID INT NOT NULL AUTO_INCREMENT,
        livemode TINYINT(1),
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500) NOT NULL,
        shippingAddressLine2 VARCHAR(500) NOT NULL,
        shippingAddressCity VARCHAR(500) NOT NULL,
        shippingAddressState VARCHAR(255) NOT NULL,
        shippingAddressZip VARCHAR(100) NOT NULL,
        shippingAddressCountry VARCHAR(100) NOT NULL,
        shippingAddressCountryCode VARCHAR(2) NOT NULL,
        created DATETIME NOT NULL,
        stripeCustomerID VARCHAR(100),
        name VARCHAR(100),
        email VARCHAR(255) NOT NULL,
        formId INT,
        formType VARCHAR(30),
        formName VARCHAR(100),
        UNIQUE KEY captureID (captureID)
        ) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_card_update_session (
		id INT NOT NULL AUTO_INCREMENT,
		hash VARCHAR(512) NOT NULL,
		email VARCHAR(255) NOT NULL,
		liveMode TINYINT(1),
		stripeCustomerId VARCHAR(100) NOT NULL,
		securityCodeRequest INT DEFAULT 0,
		securityCodeInput INT DEFAULT 0,
		created DATETIME NOT NULL,
		status VARCHAR(32) NOT NULL,
		UNIQUE KEY id (id),
		KEY hash (hash),
		KEY email (email),
		KEY stripeCustomerId (stripeCustomerId),
		KEY status (status),
		KEY created (created)
		) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_security_code (
		id INT NOT NULL AUTO_INCREMENT,
		sessionId INT NOT NULL,
		securityCode VARCHAR(512) NOT NULL,
		created DATETIME NOT NULL,
		sent DATETIME,
		consumed DATETIME,
		status VARCHAR(32) NOT NULL,
		UNIQUE KEY id (id),
		KEY sessionId (sessionId),
		KEY securityCode (securityCode),
		KEY status (status)
		) $charset_collate;";

		//database write/update
		dbDelta( $sql );

		do_action( 'fullstripe_setup_db' );

		return true;
	}

	/**
	 *
	 * @param $result
	 *
	 * @param $message
	 *
	 * @throws Exception
	 */
	private static function handleDbError( $result, $message ) {
		if ( $result === false ) {
			global $wpdb;
			error_log( sprintf( "%s: Raised exception with message=%s", 'WP Full Stripe/Database', $message ) );
			error_log( sprintf( "%s: SQL last error=%s", 'WP Full Stripe/Database', $wpdb->last_error ) );
			throw new Exception( $message );
		}
	}

	/**
	 * @return array|null|object|void
	 */
	public static function get_site_ids() {
		global $wpdb;

		return $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid};" );
	}

	/**
	 *
	 * @param $stripe_charge
	 * @param $billing_address
	 * @param $shipping_name
	 * @param $shipping_address
	 * @param $stripe_customer_id
	 * @param $customer_name
	 * @param $customer_email
	 * @param $form_id
	 * @param $form_type
	 * @param $form_name
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function fullstripe_insert_payment( $stripe_charge, $billing_address, $shipping_name, $shipping_address, $stripe_customer_id, $customer_name, $customer_email, $form_id, $form_type, $form_name ) {
		global $wpdb;

		$data = array(
			'eventID'                    => $stripe_charge->id,
			'description'                => $stripe_charge->description,
			'payment_method'             => MM_WPFS::PAYMENT_METHOD_CARD,
			'paid'                       => $stripe_charge->paid,
			'captured'                   => $stripe_charge->captured,
			'refunded'                   => $stripe_charge->refunded,
			'expired'                    => false,
			'failure_code'               => $stripe_charge->failure_code,
			'failure_message'            => $stripe_charge->failure_message,
			'livemode'                   => $stripe_charge->livemode,
			'last_charge_status'         => $stripe_charge->status,
			'currency'                   => $stripe_charge->currency,
			'amount'                     => $stripe_charge->amount,
			'fee'                        => ( isset( $stripe_charge->fee ) && ! is_null( $stripe_charge->fee ) ) ? $stripe_charge->fee : 0,
			'addressLine1'               => $billing_address['line1'],
			'addressLine2'               => $billing_address['line2'],
			'addressCity'                => $billing_address['city'],
			'addressState'               => $billing_address['state'],
			'addressCountry'             => $billing_address['country'],
			'addressCountryCode'         => $billing_address['country_code'],
			'addressZip'                 => $billing_address['zip'],
			'shippingName'               => $shipping_name,
			'shippingAddressLine1'       => $shipping_address['line1'],
			'shippingAddressLine2'       => $shipping_address['line2'],
			'shippingAddressCity'        => $shipping_address['city'],
			'shippingAddressState'       => $shipping_address['state'],
			'shippingAddressCountry'     => $shipping_address['country'],
			'shippingAddressCountryCode' => $shipping_address['country_code'],
			'shippingAddressZip'         => $shipping_address['zip'],
			'created'                    => date( 'Y-m-d H:i:s', $stripe_charge->created ),
			'stripeCustomerID'           => $stripe_customer_id,
			'name'                       => $customer_name,
			'email'                      => $customer_email,
			'formId'                     => $form_id,
			'formType'                   => $form_type,
			'formName'                   => $form_name
		);

		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_payments', apply_filters( 'fullstripe_insert_payment_data', $data ) );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert payment' ) );

		return $insertResult;
	}

	/**
	 *
	 * @param $stripeCustomer
	 * @param $customerName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $formId
	 * @param $formName
	 * @param $vatPercent
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function fullstripe_insert_subscriber( $stripeCustomer, $customerName, $billingAddress, $shippingName, $shippingAddress, $formId, $formName, $vatPercent ) {
		$debugLog = false;
		if ( $debugLog ) {
			MM_WPFS_Utils::log( "fullstripe_insert_subscriber(): CALLED, params: stripeCustomer={$stripeCustomer}, customerName={$customerName}, address={$billingAddress}, formId={$formId}, vatPercent={$vatPercent}" );
			MM_WPFS_Utils::log( 'fullstripe_insert_subscriber(): stripeCustomer->subscriptions->data=' . print_r( $stripeCustomer->subscriptions->data, true ) );
		}
		/** @var \Stripe\Subscription $latestSubscription */
		$latestSubscription = null;
		if ( isset( $stripeCustomer ) && isset( $stripeCustomer->subscriptions ) ) {
			$latestSubscription = $stripeCustomer->subscriptions->data[0];
		}
		if ( is_null( $latestSubscription ) ) {
			return false;
		}
		$maximumCharge = 0;
		if ( isset( $latestSubscription->plan->metadata ) && isset( $latestSubscription->plan->metadata->cancellation_count ) ) {
			$maximumCharge = intval( $latestSubscription->plan->metadata->cancellation_count );
		}
		$data = array(
			'stripeCustomerID'           => $stripeCustomer->id,
			'stripeSubscriptionID'       => $latestSubscription->id,
			'chargeMaximumCount'         => $maximumCharge,
			'chargeCurrentCount'         => 0,
			'status'                     => 'running',
			'name'                       => $customerName,
			'email'                      => $stripeCustomer->email,
			'planID'                     => $latestSubscription->plan->id,
			'addressLine1'               => $billingAddress['line1'],
			'addressLine2'               => $billingAddress['line2'],
			'addressCity'                => $billingAddress['city'],
			'addressState'               => $billingAddress['state'],
			'addressCountry'             => $billingAddress['country'],
			'addressCountryCode'         => $billingAddress['country_code'],
			'addressZip'                 => $billingAddress['zip'],
			'shippingName'               => $shippingName,
			'shippingAddressLine1'       => $shippingAddress['line1'],
			'shippingAddressLine2'       => $shippingAddress['line2'],
			'shippingAddressCity'        => $shippingAddress['city'],
			'shippingAddressState'       => $shippingAddress['state'],
			'shippingAddressCountry'     => $shippingAddress['country'],
			'shippingAddressCountryCode' => $shippingAddress['country_code'],
			'shippingAddressZip'         => $shippingAddress['zip'],
			'created'                    => date( 'Y-m-d H:i:s' ),
			'livemode'                   => $stripeCustomer->livemode,
			'formId'                     => $formId,
			'formName'                   => $formName,
			'vatPercent'                 => $vatPercent
		);

		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_subscribers', apply_filters( 'fullstripe_insert_subscriber_data', $data ) );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert subscriber' ) );

		return $insertResult;
	}

	/**
	 * @deprecated unused
	 *
	 * @param $stripeCustomerID
	 *
	 * @return mixed
	 */
	function get_subscriber_by_stripeID( $stripeCustomerID ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscribers" . " WHERE stripeCustomerID='" . $stripeCustomerID . "';" );
	}

	/**
	 *
	 * @param $email
	 * @param bool $livemode
	 *
	 * @return mixed
	 */
	function get_subscriber_by_email( $email, $livemode = true ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscribers" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";" );
	}

	/**
	 * @deprecated
	 *
	 * @param $id
	 * @param $subscriber
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function update_subscriber( $id, $subscriber ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_subscribers', $subscriber, array( 'subscriberID' => $id ) );
		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update subscriber' ) );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function insert_subscription_form( $form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_subscription_forms', $form );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert subscription form' ) );

		return $insertResult;
	}

	/**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function update_subscription_form( $id, $form ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_subscription_forms', $form, array( 'subscriptionFormID' => $id ) );
		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update subscription form' ) );

		return $updateResult;
	}

	/**
	 * @param $form
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function insert_checkout_subscription_form( $form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_subscription_forms', $form );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert checkout subscription form' ) );

		return $insertResult;
	}

	public function update_checkout_subscription_form( $id, $form ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_checkout_subscription_forms', $form, array( 'checkoutSubscriptionFormID' => $id ) );
		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update checkout subscription form' ) );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function insert_payment_form( $form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_payment_forms', $form );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert.', 'wp-full-stripe' ), 'Insert payment form' ) );

		return $insertResult;
	}

	/**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function update_payment_form( $id, $form ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_payment_forms', $form, array( 'paymentFormID' => $id ) );
		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update payment form' ) );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function insert_checkout_form( $form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_forms', $form );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert checkout form' ) );

		return $insertResult;
	}

	/**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function update_checkout_form( $id, $form ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_checkout_forms', $form, array( 'checkoutFormID' => $id ) );
		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update checkout form' ) );

		return $updateResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function delete_payment_form( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_payment_forms' . " WHERE paymentFormID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete payment form' ) );

		return $queryResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function delete_subscription_form( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_subscription_forms' . " WHERE subscriptionFormID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete subscription form' ) );

		return $queryResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function delete_checkout_form( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_checkout_forms' . " WHERE checkoutFormID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete checkout form' ) );

		return $queryResult;
	}

	/**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function delete_checkout_subscription_form( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_checkout_subscription_forms' . " WHERE checkoutSubscriptionFormID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete checkout subscription form' ) );

		return $queryResult;
	}

	/**
	 * @deprecated
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function delete_subscriber( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_subscribers' . " WHERE subscriberID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete subscriber' ) );

		return $queryResult;
	}

	/**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function cancel_subscription( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s WHERE subscriberID=%d", 'cancelled', $id ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Cancel subscription' ) );

		return $queryResult;
	}

	/**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function delete_subscription_by_id( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_subscribers WHERE subscriberID=%d", $id ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete subscription record' ) );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function cancel_subscription_by_stripe_subscription_id( $stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s,cancelled=NOW() WHERE stripeSubscriptionID=%s", 'cancelled', $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Cancel subscription by Stripe Subscription ID' ) );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function complete_subscription_by_stripe_subscription_id( $stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s,cancelled=NOW() WHERE stripeSubscriptionID=%s", 'ended', $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Complete subscription by Stripe Subscription ID' ) );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function update_subscription_with_payment( $stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET chargeCurrentCount=chargeCurrentCount + 1 WHERE stripeSubscriptionID=%s", $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update subscription charge count by Stripe Subscription ID' ) );

		return $queryResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function delete_payment( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_payments' . " WHERE paymentID='" . $id . "';" );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete payment' ) );

		return $queryResult;
	}

	////////////////////////////////////////////////////////////////////////////////////////////

	function delete_card_capture( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_card_captures WHERE captureID=%d", $id ) );
		self::handleDbError( $queryResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete payment' ) );

		return $queryResult;
	}

	/**
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function get_payment_form_by_name( $name ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payment_forms" . " WHERE name='" . $name . "';" );
	}

	/**
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function get_subscription_form_by_name( $name ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscription_forms" . " WHERE name='" . $name . "';" );
	}

	/**
	 * @param $formId
	 *
	 * @return array|null|object|void
	 */
	public function get_subscription_form_by_id( $formId ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscription_forms WHERE subscriptionFormID=%d", $formId ) );
	}

	/**
	 * @param $formId
	 *
	 * @return array|null|object|void
	 */
	public function get_checkout_subscription_form_by_id( $formId ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE checkoutSubscriptionFormID=%d", $formId ) );
	}

	/**
	 * @param $formName
	 *
	 * @return mixed
	 */
	public function get_checkout_form_by_name( $formName ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE name=%s", $formName ) );
	}

	/**
	 * @param $formName
	 *
	 * @return array|null|object|void
	 */
	public function get_checkout_subscription_form_by_name( $formName ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE name=%s", $formName ) );
	}

	/**
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function get_customer_id_from_payments( $email, $livemode ) {
		global $wpdb;
		$id      = null;
		$payment = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payments" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";" );
		if ( $payment ) {
			// if no ID set, will be set to null.
			$id = $payment->stripeCustomerID;
		}

		return $id;
	}

	/**
	 *
	 * search payments and subscribers table for existing customer
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function find_existing_stripe_customer_by_email( $email, $livemode ) {
		global $wpdb;
		$subscriber = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscribers" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";", ARRAY_A );
		if ( $subscriber ) {
			$subscriber['is_subscriber'] = true;

			return $subscriber;
		} else {
			$payment = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payments" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";", ARRAY_A );
			if ( $payment ) {
				$subscriber['is_subscriber'] = false;

				return $payment;
			}
		}

		return null;
	}

	/**
	 *
	 * return customers from the payment and subscriber tables where the email address and the mode match
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function get_existing_stripe_customers_by_email( $email, $livemode ) {
		global $wpdb;

		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE email=%s AND livemode=%s GROUP BY StripeCustomerID;", $email, $livemode ? '1' : '0' ), ARRAY_A );
		$payees      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payments WHERE email=%s AND livemode=%s GROUP BY StripeCustomerID;", $email, $livemode ? '1' : '0' ), ARRAY_A );
		$cards       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_captures WHERE email=%s AND livemode=%s GROUP BY StripeCustomerID;", $email, $livemode ? '1' : '0' ), ARRAY_A );

		$result = array_merge( $subscribers, $payees, $cards );

		return $result;
	}

	/**
	 * @param $id
	 *
	 * @return array|null|object|void
	 */
	public function find_subscriber_by_id( $id ) {
		global $wpdb;
		$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE subscriberID=%d", $id ) );

		return $subscription;
	}

	/**
	 * @param $stripe_subscription_id
	 *
	 * @return array|null|object|void
	 */
	public function find_subscription_by_stripe_subscription_id( $stripe_subscription_id ) {
		global $wpdb;
		$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE stripeSubscriptionID=%s", $stripe_subscription_id ) );

		return $subscription;
	}

	public function fullstripe_insert_card_capture( $stripeCustomer, $customerName, $billingAddress, $shippingName, $shippingAddress, $formId, $formType, $formName ) {
		global $wpdb;

		$data = array(
			'livemode'                   => $stripeCustomer->livemode,
			'addressLine1'               => $billingAddress['line1'],
			'addressLine2'               => $billingAddress['line2'],
			'addressCity'                => $billingAddress['city'],
			'addressState'               => $billingAddress['state'],
			'addressCountry'             => $billingAddress['country'],
			'addressCountryCode'         => $billingAddress['country_code'],
			'addressZip'                 => $billingAddress['zip'],
			'shippingName'               => $shippingName,
			'shippingAddressLine1'       => $shippingAddress['line1'],
			'shippingAddressLine2'       => $shippingAddress['line2'],
			'shippingAddressCity'        => $shippingAddress['city'],
			'shippingAddressState'       => $shippingAddress['state'],
			'shippingAddressCountry'     => $shippingAddress['country'],
			'shippingAddressCountryCode' => $shippingAddress['country_code'],
			'shippingAddressZip'         => $shippingAddress['zip'],
			'created'                    => date( 'Y-m-d H:i:s', $stripeCustomer->created ),
			'stripeCustomerID'           => $stripeCustomer->id,
			'name'                       => $customerName,
			'email'                      => $stripeCustomer->email,
			'formId'                     => $formId,
			'formType'                   => $formType,
			'formName'                   => $formName
		);

		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_card_captures', apply_filters( 'fullstripe_insert_card_data', $data ) );
		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert card capture' ) );

		return $insertResult;
	}

	/**
	 * Insert card update session
	 *
	 * @param $email
	 * @param $liveMode
	 * @param $stripeCustomerId
	 * @param $cardUpdateSessionHash
	 *
	 * @return int -1 when insert failed, the inserted record id otherwise
	 * @throws Exception
	 */
	public function insert_card_update_session( $email, $liveMode, $stripeCustomerId, $cardUpdateSessionHash ) {
		global $wpdb;

		$insertResult = $wpdb->insert( "{$wpdb->prefix}fullstripe_card_update_session", array(
			'hash'             => $cardUpdateSessionHash,
			'email'            => $email,
			'liveMode'         => $liveMode,
			'stripeCustomerId' => $stripeCustomerId,
			'created'          => current_time( 'mysql' ),
			'status'           => MM_WPFS_CardUpdateService::SESSION_STATUS_WAITING_FOR_CONFIRMATION
		) );

		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert card update session' ) );

		if ( $insertResult === false ) {
			return - 1;
		}

		return $wpdb->insert_id;
	}

	public function update_card_update_session( $cardUpdateSessionId, $data ) {

		global $wpdb;

		$updateResult = $wpdb->update( "{$wpdb->prefix}fullstripe_card_update_session", $data, array( 'id' => $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update card update session' ) );

		return $updateResult;
	}

	public function find_card_update_session_by_hash( $cardUpdateSessionHash ) {
		global $wpdb;

		$cardUpdateSession = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_update_session WHERE hash=%s", $cardUpdateSessionHash ) );

		return $cardUpdateSession;
	}

	public function find_card_update_sessions_by_email_and_customer( $email, $stripeCustomerId ) {
		global $wpdb;

		$cardUpdateSession = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_update_session WHERE email=%s AND stripeCustomerId=%s", $email, $stripeCustomerId ) );

		return $cardUpdateSession;
	}

	public function insert_security_code( $cardUpdateSessionId, $securityCode ) {
		global $wpdb;

		$insertResult = $wpdb->insert( "{$wpdb->prefix}fullstripe_security_code", array(
			'sessionId'    => $cardUpdateSessionId,
			'securityCode' => $securityCode,
			'created'      => current_time( 'mysql' ),
			'status'       => MM_WPFS_CardUpdateService::SECURITY_CODE_STATUS_PENDING
		) );

		self::handleDbError( $insertResult, sprintf( __( '%s: an error occurred during insert!', 'wp-full-stripe' ), 'Insert security code' ) );

		if ( $insertResult === false ) {
			return - 1;
		}

		return $wpdb->insert_id;

	}

	public function find_security_codes_by_session( $sessionId ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_security_code WHERE sessionId=%d", $sessionId ) );

	}

	public function find_security_code_by_session_and_code( $sessionId, $securityCode ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_security_code WHERE sessionId=%d AND securityCode=%s", $sessionId, $securityCode ) );

	}

	public function update_security_code( $securityCodeId, $data ) {

		global $wpdb;

		$updateResult = $wpdb->update( "{$wpdb->prefix}fullstripe_security_code", $data, array( 'id' => $securityCodeId ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update security code' ) );

		return $updateResult;
	}

	public function increment_security_code_input( $cardUpdateSessionId ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET securityCodeInput=securityCodeInput+1 WHERE id=%d", $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update card update session, increment securityCodeInput' ) );

		return $updateResult;
	}

	public function increment_security_code_request( $cardUpdateSessionId ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET securityCodeRequest=securityCodeRequest+1 WHERE id=%d", $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update card update session, increment securityCodeRequest' ) );

		return $updateResult;
	}

	public function invalidate_expired_card_update_sessions( $validUntilHour ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE created < DATE_SUB(NOW(), INTERVAL %d HOUR)", MM_WPFS_CardUpdateService::SESSION_STATUS_INVALIDATED, $validUntilHour ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Invalidate expired card update session' ) );

		return $updateResult;
	}

	public function invalidate_card_update_sessions_by_security_code_request_limit( $securityCodeRequestLimit ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE securityCodeRequest >= %d", MM_WPFS_CardUpdateService::SESSION_STATUS_INVALIDATED, $securityCodeRequestLimit ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Invalidate card update session by security code request limit' ) );

		return $updateResult;
	}

	public function invalidate_card_update_sessions_by_security_code_input_limit( $securityCodeInputLimit ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE securityCodeInput >= %d", MM_WPFS_CardUpdateService::SESSION_STATUS_INVALIDATED, $securityCodeInputLimit ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Invalidate card update session by security code input limit' ) );

		return $updateResult;
	}

	public function find_invalidated_session_ids() {
		global $wpdb;

		$cardUpdateSessionIds = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}fullstripe_card_update_session WHERE status=%s", MM_WPFS_CardUpdateService::SESSION_STATUS_INVALIDATED ) );

		return $cardUpdateSessionIds;
	}

	public function delete_security_codes_by_sessions( $invalidatedSessionIds ) {
		global $wpdb;

		$whereStatement = ' WHERE sessionId IN (' . implode( ', ', array_fill( 0, sizeof( $invalidatedSessionIds ), '%s' ) ) . ')';

		$updateResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_security_code" . $whereStatement, $invalidatedSessionIds ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete security codes by sessionIds' ) );

		return $updateResult;
	}

	public function delete_invalidated_card_update_sessions( $invalidatedSessionIds ) {
		global $wpdb;

		$whereStatement = ' WHERE id IN (' . implode( ', ', array_fill( 0, sizeof( $invalidatedSessionIds ), '%s' ) ) . ')';

		$updateResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_card_update_session" . $whereStatement, $invalidatedSessionIds ) );

		self::handleDbError( $updateResult, sprintf( __( '%s: an error occurred during delete!', 'wp-full-stripe' ), 'Delete card update sessions by ids' ) );

		return $updateResult;
	}

	public function get_payment( $id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payments WHERE paymentID=%d", $id ) );
	}

	public function update_payment_by_charge( $event_id, $data ) {
		global $wpdb;

		$update_result = $wpdb->update( "{$wpdb->prefix}fullstripe_payments", $data, array( 'eventID' => $event_id ) );

		self::handleDbError( $update_result, sprintf( __( '%s: an error occurred during update!', 'wp-full-stripe' ), 'Update payment by charge id' ) );

		return $update_result;
	}
}

?>