<?php

interface MM_WPFS_Payment_API {
	function charge( $currency, $amount, $card, $description, $metadata = null, $stripeEmail = null );

	function create_plan( $id, $name, $currency, $amount, $setup_fee, $interval, $trial_days, $interval_count, $cancellation_count );

	function get_plans();

	function get_recipients();

	function create_recipient( $recipient );

	function create_transfer( $transfer );

	function get_coupon( $code );

	function create_customer_with_card( $card, $email, $metadata );

	/**
	 * @param $token
	 * @param $name
	 * @param $email
	 * @param $metadata
	 *
	 * @return mixed
	 */
	function create_customer_with_source( $token, $name, $email, $metadata );

	function charge_customer( $customerId, $currency, $amount, $capture, $description, $metadata = null, $stripeEmail = null );

	function retrieve_customer( $customerID );

	function update_customer_card( $customerID, $card );

	function add_customer_source( $customerID, $token );

	function update_customer_shipping_address( $stripe_customer, $shipping_name, $shipping_phone, $shipping_address );

	/**
	 * Add subscription to new customer
	 *
	 * @param string $stripeCustomerEmail
	 * @param string $stripeToken
	 * @param $stripePlanId
	 * @param $taxPercent
	 * @param string $couponCode
	 * @param string $billingName
	 * @param array $billingAddress
	 * @param string $customerPhone
	 * @param string $shippingName
	 * @param array $shippingAddress
	 * @param null|array $metadata
	 *
	 * @return \Stripe\Customer
	 */
	function subscribe( $stripeCustomerEmail, $stripeToken, $stripePlanId, $taxPercent, $couponCode, $billingName, $billingAddress, $customerPhone, $shippingName, $shippingAddress, $metadata = null );

	function subscribe_existing( $stripeCustomerId, $stripeTokenOrSource, $stripeStripePlanId, $taxPercent, $couponCode, $billingName, $customerPhone, $billingAddressAsCustomerShippingAddress, $metadata = null );

	function alternativeSubscribe( $transactionData );

	function alternativeSubscribeExisting( $transactionData );

	function retrieve_subscription( $customerID, $subscriptionID );

	function update_plan( $plan_id, $plan_data );

	function delete_plan( $plan_id );

	/**
	 * @param bool|null $associativeArray
	 * @param array|null $productIds
	 *
	 * @return mixed
	 */
	function get_products( $associativeArray = false, $productIds = null );

	/**
	 * @param $charge_id
	 *
	 * @return mixed
	 */
	function capture_charge( $charge_id );

	/**
	 * @param $charge_id
	 *
	 * @return mixed
	 */
	function refund_charge( $charge_id );

}

/**
 * Class MM_WPFS_Stripe
 *
 * deals with calls to Stripe API
 *
 */
class MM_WPFS_Stripe implements MM_WPFS_Payment_API {

	const DESIRED_STRIPE_API_VERSION = '2018-01-23';

	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR = 'invalid_number';
	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR_EXP_MONTH = 'invalid_number_exp_month';
	/**
	 * @var string
	 */
	const INVALID_NUMBER_ERROR_EXP_YEAR = 'invalid_number_exp_year';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_MONTH_ERROR = 'invalid_expiry_month';
	/**
	 * @var string
	 */
	const INVALID_EXPIRY_YEAR_ERROR = 'invalid_expiry_year';
	/**
	 * @var string
	 */
	const INVALID_CVC_ERROR = 'invalid_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_NUMBER_ERROR = 'incorrect_number';
	/**
	 * @var string
	 */
	const EXPIRED_CARD_ERROR = 'expired_card';
	/**
	 * @var string
	 */
	const INCORRECT_CVC_ERROR = 'incorrect_cvc';
	/**
	 * @var string
	 */
	const INCORRECT_ZIP_ERROR = 'incorrect_zip';
	/**
	 * @var string
	 */
	const CARD_DECLINED_ERROR = 'card_declined';
	/**
	 * @var string
	 */
	const MISSING_ERROR = 'missing';
	/**
	 * @var string
	 */
	const PROCESSING_ERROR = 'processing_error';
	/**
	 * @var string
	 */
	const MISSING_PAYMENT_INFORMATION = 'missing_payment_information';
	/**
	 * @var string
	 */
	const COULD_NOT_FIND_PAYMENT_INFORMATION = 'Could not find payment information';

	private $debugLog = false;

	public function __construct() {
	}

	function get_error_codes() {
		return array(
			self::INVALID_NUMBER_ERROR,
			self::INVALID_NUMBER_ERROR_EXP_MONTH,
			self::INVALID_NUMBER_ERROR_EXP_YEAR,
			self::INVALID_EXPIRY_MONTH_ERROR,
			self::INVALID_EXPIRY_YEAR_ERROR,
			self::INVALID_CVC_ERROR,
			self::INCORRECT_NUMBER_ERROR,
			self::EXPIRED_CARD_ERROR,
			self::INCORRECT_CVC_ERROR,
			self::INCORRECT_ZIP_ERROR,
			self::CARD_DECLINED_ERROR,
			self::MISSING_ERROR,
			self::PROCESSING_ERROR,
			self::MISSING_PAYMENT_INFORMATION
		);
	}

	/**
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 *
	 * @return \Stripe\Customer
	 */
	public function alternativeSubscribe( $transactionData ) {
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'alternativeSubscribe(): transactionData=' . print_r( $transactionData, true ) );
		}

		return $this->subscribe(
			$transactionData->getCustomerEmail(),
			$transactionData->getStripeToken(),
			$transactionData->getPlanId(),
			$transactionData->getPlanAmountVATRate(),
			$transactionData->getCouponCode(),
			$transactionData->getBillingName(),
			$transactionData->getBillingAddress(),
			$transactionData->getCustomerPhone(),
			$transactionData->getShippingName(),
			$transactionData->getShippingAddress(),
			$transactionData->getMetadata()
		);
	}

	public function subscribe( $stripeCustomerEmail, $stripeToken, $stripePlanId, $taxPercent, $couponCode, $billingName, $billingAddress, $stripeCustomerPhone, $shippingName, $shippingAddress, $metadata = null ) {

		if ( $this->debugLog ) {
			$billingAddressString = print_r( $billingAddress, true );
			$metadataString       = print_r( $metadata, true );

			MM_WPFS_Utils::log( "subscribe(): CALLED, params: stripeCustomerEmail={$stripeCustomerEmail}, planID={$stripePlanId}, taxPercent={$taxPercent}, stripeToken={$stripeToken}, couponCode={$couponCode}, billingAddress={$billingAddressString}, metadata={$metadataString}" );
		}

		$this->validate_token_cvc( $stripeToken );

		$params = array(
			'email'  => $stripeCustomerEmail,
			'source' => $stripeToken
		);
		if ( ! is_null( $billingName ) && ! empty( $billingName ) ) {
			$params['name'] = $billingName;
		}
		$stripeCustomer = \Stripe\Customer::create( $params );
		if ( ! is_null( $billingAddress ) ) {
			$this->update_customer_billing_address( $stripeCustomer, $billingName, $billingAddress );
		}
		if ( ! is_null( $shippingAddress ) ) {
			$this->update_customer_shipping_address( $stripeCustomer, $shippingName, $stripeCustomerPhone, $shippingAddress );
		}

		$this->createSubscriptionForCustomer( $stripeCustomer, $stripePlanId, $taxPercent, $couponCode, $metadata );

		return $this->retrieve_customer( $stripeCustomer->id );
	}

	public function validate_token_cvc( $token_id ) {

		/* @var $token \Stripe\Token */
		$token = \Stripe\Token::retrieve( $token_id );

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'validate_token_cvc(): token=' . print_r( $token, true ) );
		}

		if ( is_null( $token->card->cvc_check ) ) {
			throw new Exception( __( 'Please enter a CVC code', 'wp-full-stripe' ) );
		}
	}

	/**
	 * Updates the \Stripe\Customer object's address property with an appropriate address array.
	 *
	 * @param $stripe_customer \Stripe\Customer
	 * @param $billing_name
	 * @param $billing_address array
	 *
	 * @return \Stripe\Customer
	 */
	public function update_customer_billing_address( $stripe_customer, $billing_name, $billing_address ) {
		$stripe_array_hash = MM_WPFS_Utils::prepare_stripe_address_hash_from_array( $billing_address );
		if ( isset( $stripe_array_hash ) ) {
			$stripe_customer->address = $stripe_array_hash;
		}
		if ( ! empty( $billing_name ) ) {
			$stripe_customer->name = $billing_name;
		}
		$stripe_customer->save();

		return $stripe_customer;
	}

	/**
	 * Updates the \Stripe\Customer object's shipping property with an appropriate address array.
	 *
	 * @param $stripe_customer \Stripe\Customer
	 * @param $shipping_name
	 * @param $shipping_phone
	 * @param $shipping_address array
	 *
	 * @return \Stripe\Customer
	 */
	public function update_customer_shipping_address( $stripe_customer, $shipping_name, $shipping_phone, $shipping_address ) {
		$stripe_shipping_hash = MM_WPFS_Utils::prepare_stripe_shipping_hash_from_array( $shipping_name, $shipping_phone, $shipping_address );
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'update_customer_shipping_address(): stripe_shipping_hash=' . print_r( $stripe_shipping_hash, true ) );
		}
		$stripe_customer->shipping = $stripe_shipping_hash;
		$stripe_customer->save();

		return $stripe_customer;
	}

	/**
	 * @param $stripeCustomer
	 * @param $stripePlanId
	 * @param $taxPercent
	 * @param $couponCode
	 * @param $metadata
	 * @param $stripeTokenOrSource
	 *
	 * @return \Stripe\ApiResource
	 * @throws Exception
	 */
	private function createSubscriptionForCustomer( $stripeCustomer, $stripePlanId, $taxPercent, $couponCode, $metadata, $stripeTokenOrSource = null ) {

		// tnagy check if plan exists
		$stripePlan = \Stripe\Plan::retrieve( $stripePlanId );
		if ( ! isset( $stripePlan ) ) {
			throw new Exception( "Stripe plan with id '{$stripePlanId}' doesn't exist." );
		}

		// tnagy get setup fee 
		$setupFee = MM_WPFS_Utils::get_setup_fee_for_plan( $stripePlan );

		if ( $setupFee > 0 ) {
			// tnagy add setup fee as invoice item
			\Stripe\InvoiceItem::create( array(
					'amount'      => $setupFee,
					'currency'    => $stripePlan->currency,
					'customer'    => $stripeCustomer->id,
					'description' => sprintf( __( 'One-time setup fee (plan: %s)', 'wp-full-stripe' ), $stripePlan->id )
				)
			);
		}

		// tnagy create subscription
		$subscriptionData = array(
			'customer'        => $stripeCustomer->id,
			'trial_from_plan' => true,
			'items'           => array(
				array(
					'plan' => $stripePlan->id
				)
			)
		);
		if ( ! is_null( $stripeTokenOrSource ) ) {
			$subscriptionData['source'] = $stripeTokenOrSource;
		}
		if ( $couponCode != '' ) {
			$subscriptionData['coupon'] = $couponCode;
		}
		if ( $taxPercent != 0.0 ) {
			$subscriptionData['tax_percent'] = $taxPercent;
		}
		if ( ! is_null( $metadata ) ) {
			$subscriptionData['metadata'] = $metadata;
		}
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'createSubscriptionForCustomer(): subscriptionData=' . print_r( $subscriptionData, true ) );
		}
		$stripeSubscription = \Stripe\Subscription::create( $subscriptionData );
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'createSubscriptionForCustomer(): created subscription=' . print_r( $stripeSubscription, true ) );
		}

		return $stripeSubscription;
	}

	function retrieve_customer( $customerID ) {
		return \Stripe\Customer::retrieve( $customerID );
	}

	/**
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 *
	 * @return \Stripe\Customer
	 * @throws Exception
	 */
	public function alternativeSubscribeExisting( $transactionData ) {
		return $this->subscribe_existing( $transactionData->getStripeCustomerId(), $transactionData->getStripeToken(), $transactionData->getPlanId(), $transactionData->getPlanAmountVATRate(), $transactionData->getCouponCode(), $transactionData->getBillingName(), $transactionData->getBillingAddress(), $transactionData->getCustomerPhone(), $transactionData->getMetadata() );
	}

	/**
	 * Add subscription to existing customer
	 *
	 * @param $stripeCustomerId
	 * @param $stripeTokenOrSource
	 * @param $stripePlanId
	 * @param $taxPercent
	 * @param $couponCode
	 * @param $billingName
	 * @param null $customerPhone
	 * @param $billingAddressAsCustomerShippingAddress
	 * @param null $metadata
	 *
	 * @return \Stripe\Customer
	 * @throws Exception when the plan or customer do not exist
	 */
	public function subscribe_existing( $stripeCustomerId, $stripeTokenOrSource, $stripePlanId, $taxPercent, $couponCode, $billingName, $billingAddressAsCustomerShippingAddress, $customerPhone, $metadata = null ) {

		if ( $this->debugLog ) {
			$metadataString = print_r( $metadata, true );
			MM_WPFS_Utils::log( "subscribe_existing(): CALLED, params: stripeCustomerID={$stripeCustomerId}, planID={$stripePlanId}, taxPercent={$taxPercent}, stripeTokenOrSource={$stripeTokenOrSource}, couponCode={$couponCode}, billingAddressAsCustomerShippingAddress={$billingAddressAsCustomerShippingAddress}, metadata={$metadataString}" );
		}

		$this->validate_token_cvc( $stripeTokenOrSource );

		$stripeCustomer = \Stripe\Customer::retrieve( $stripeCustomerId );
		if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
			$this->createSubscriptionForCustomer( $stripeCustomer, $stripePlanId, $taxPercent, $couponCode, $metadata, $stripeTokenOrSource );
		} else {
			throw new Exception( "Stripe customer with id '{$stripeCustomerId}' doesn't exist." );
		}
		if ( ! is_null( $billingAddressAsCustomerShippingAddress ) ) {
			$this->update_customer_shipping_address( $stripeCustomer, $billingName, $customerPhone, $billingAddressAsCustomerShippingAddress );
		}

		return $this->retrieve_customer( $stripeCustomer->id );
	}

	function resolve_error_message_by_code( $code ) {
		if ( $code === self::INVALID_NUMBER_ERROR ) {
			$resolved_message =  /* translators: message for Stripe error code 'invalid_number' */
				__( 'Your card number is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_EXPIRY_MONTH_ERROR || $code === self::INVALID_NUMBER_ERROR_EXP_MONTH ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_month' */
				__( 'Your card\'s expiration month is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_EXPIRY_YEAR_ERROR || $code === self::INVALID_NUMBER_ERROR_EXP_YEAR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_expiry_year' */
				__( 'Your card\'s expiration year is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INVALID_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'invalid_cvc' */
				__( 'Your card\'s security code is invalid.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_NUMBER_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_number' */
				__( 'Your card number is incorrect.', 'wp-full-stripe' );
		} elseif ( $code === self::EXPIRED_CARD_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'expired_card' */
				__( 'Your card has expired.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_CVC_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_cvc' */
				__( 'Your card\'s security code is incorrect.', 'wp-full-stripe' );
		} elseif ( $code === self::INCORRECT_ZIP_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'incorrect_zip' */
				__( 'Your card\'s zip code failed validation.', 'wp-full-stripe' );
		} elseif ( $code === self::CARD_DECLINED_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'card_declined' */
				__( 'Your card was declined.', 'wp-full-stripe' );
		} elseif ( $code === self::MISSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'missing' */
				__( 'There is no card on a customer that is being charged.', 'wp-full-stripe' );
		} elseif ( $code === self::PROCESSING_ERROR ) {
			$resolved_message = /* translators: message for Stripe error code 'processing_error' */
				__( 'An error occurred while processing your card.', 'wp-full-stripe' );
		} elseif ( $code === self::MISSING_PAYMENT_INFORMATION ) {
			$resolved_message = /* translators: Stripe error message 'Missing payment information' */
				__( 'Missing payment information', 'wp-full-stripe' );
		} elseif ( $code === self::COULD_NOT_FIND_PAYMENT_INFORMATION ) {
			$resolved_message = /* translators: Stripe error message 'Could not find payment information' */
				__( 'Could not find payment information', 'wp-full-stripe' );
		} else {
			$resolved_message = null;
		}

		return $resolved_message;
	}

	function charge( $currency, $amount, $card, $description, $metadata = null, $stripeEmail = null ) {
		$charge = array(
			'card'        => $card,
			'amount'      => $amount,
			'currency'    => $currency,
			'description' => $description
		);
		if ( isset( $stripeEmail ) ) {
			$charge['receipt_email'] = $stripeEmail;
		}
		if ( isset( $metadata ) ) {
			$charge['metadata'] = $metadata;
		}

		$result = \Stripe\Charge::create( $charge );

		return $result;
	}

	function create_plan( $id, $name, $currency, $amount, $setup_fee, $interval, $trial_days, $interval_count, $cancellation_count ) {

		try {
			$plan_data = array(
				"amount"         => $amount,
				"interval"       => $interval,
				"nickname"       => $id,
				"product"        => array(
					"name" => $name,
				),
				"currency"       => $currency,
				"interval_count" => $interval_count,
				"id"             => $id,
				"metadata"       => array(
					"cancellation_count" => $cancellation_count,
					"setup_fee"          => $setup_fee
				)
			);

			if ( $trial_days != 0 ) {
				$plan_data['trial_period_days'] = $trial_days;
			}

			do_action( 'fullstripe_before_create_plan', $plan_data );
			\Stripe\Plan::create( $plan_data );
			do_action( 'fullstripe_after_create_plan' );

			$return = array( 'success' => true, 'msg' => __( 'Subscription plan created ', 'wp-full-stripe' ) );
		} catch ( Exception $e ) {

			MM_WPFS_Utils::logException( $e, $this );

			//show notification of error
			$return = array(
				'success' => false,
				'msg'     => __( 'There was an error creating the plan: ', 'wp-full-stripe' ) . $e->getMessage()
			);
		}

		return $return;
	}

	/**
	 * @param $plan_id
	 *
	 * @return null|\Stripe\Plan
	 */
	function retrieve_plan( $plan_id ) {
		try {
			$plan = \Stripe\Plan::retrieve( array( "id" => $plan_id, "expand" => array( "product" ) ) );
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$plan = null;
		}

		return $plan;
	}

	/**
	 * @return array|\Stripe\Collection
	 */
	public function get_plans() {
		$plans = array();
		try {
			do {
				$params    = array( 'limit' => 100, 'include[]' => 'total_count' );
				$last_plan = end( $plans );
				if ( $last_plan ) {
					$params['starting_after'] = $last_plan['id'];
				}
				$plan_collection = \Stripe\Plan::all( $params );
				$plans           = array_merge( $plans, $plan_collection['data'] );
			} while ( $plan_collection['has_more'] );
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$plans = array();
		}

		return $plans;
	}

	function get_recipients() {
		try {
			$recipients = \Stripe\Recipient::all();
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$recipients = array();
		}

		return $recipients;
	}

	function create_recipient( $recipient ) {
		return \Stripe\Recipient::create( $recipient );
	}

	function create_transfer( $transfer ) {
		return \Stripe\Transfer::create( $transfer );
	}

	/**
	 * @param $code
	 *
	 * @return \Stripe\Coupon
	 */
	function get_coupon( $code ) {
		return \Stripe\Coupon::retrieve( $code );
	}

	/**
	 * @deprecated
	 *
	 * @param $card
	 * @param $email
	 * @param $metadata
	 *
	 * @return \Stripe\Customer
	 */
	function create_customer_with_card( $card, $email, $metadata ) {
		$customer = array(
			"card"     => $card,
			"email"    => $email,
			"metadata" => $metadata
		);

		return \Stripe\Customer::create( $customer );
	}

	function create_customer_with_source( $token, $name, $email, $metadata ) {
		$customer = array(
			"source" => $token,
			"email"  => $email
		);

		if ( ! is_null( $name ) ) {
			$customer['name'] = $name;
		}

		if ( ! is_null( $metadata ) ) {
			$customer['metadata'] = $metadata;
		}

		return \Stripe\Customer::create( $customer );
	}

	/**
	 * @param $customerId
	 * @param $currency
	 * @param $amount
	 * @param $capture
	 * @param null $description
	 * @param null $metadata
	 * @param null $stripeEmail
	 *
	 * @return \Stripe\ApiResource
	 */
	function charge_customer( $customerId, $currency, $amount, $capture, $description, $metadata = null, $stripeEmail = null ) {
		$charge_parameters = array(
			'customer'    => $customerId,
			'amount'      => $amount,
			'currency'    => $currency,
			'description' => $description,
			'capture'     => $capture
		);
		if ( isset( $stripeEmail ) ) {
			$charge_parameters['receipt_email'] = $stripeEmail;
		}
		if ( isset( $metadata ) ) {
			$charge_parameters['metadata'] = $metadata;
		}

		$charge = \Stripe\Charge::create( $charge_parameters );

		return $charge;
	}

	/**
	 * @deprecated
	 *
	 * @param $customerID
	 * @param $card
	 *
	 * @return \Stripe\Customer
	 */
	function update_customer_card( $customerID, $card ) {
		$cu       = \Stripe\Customer::retrieve( $customerID );
		$cu->card = $card;
		$cu->save();

		return \Stripe\Customer::retrieve( $customerID );
	}

	function add_customer_source( $customerID, $token, $setToDefault = false ) {
		$stripeCustomer         = \Stripe\Customer::retrieve( $customerID );
		$stripeCustomer->source = $token;
		if ( $setToDefault ) {
			$stripeCustomer->default_source = $token;
		}
		$stripeCustomer->save();

		return \Stripe\Customer::retrieve( $customerID );
	}

	function update_plan( $plan_id, $plan_data ) {
		if ( isset( $plan_id ) ) {
			$plan = \Stripe\Plan::retrieve( array( "id" => $plan_id, "expand" => array( "product" ) ) );
			if ( isset( $plan_data ) ) {
				if ( array_key_exists( 'name', $plan_data ) && ! empty( $plan_data['name'] ) ) {
					$plan->name          = $plan_data['name'];
					$plan->product->name = $plan_data['name'];
				}
				if ( array_key_exists( 'statement_descriptor', $plan_data ) && ! empty( $plan_data['statement_descriptor'] ) ) {
					$plan->statement_descriptor          = $plan_data['statement_descriptor'];
					$plan->product->statement_descriptor = $plan_data['statement_descriptor'];
				} else {
					$plan->statement_descriptor          = null;
					$plan->product->statement_descriptor = null;
				}
				if ( array_key_exists( 'setup_fee', $plan_data ) && ! empty( $plan_data['setup_fee'] ) ) {
					$plan->metadata->setup_fee = $plan_data['setup_fee'];
				} else {
					$plan->metadata->setup_fee = 0;
				}

				return $plan->save();
			}
		}

		return null;
	}

	public function delete_plan( $plan_id ) {
		if ( isset( $plan_id ) ) {
			$plan = \Stripe\Plan::retrieve( $plan_id );

			return $plan->delete();
		}

		return null;
	}

	public function cancel_subscription( $stripeCustomerID, $stripeSubscriptionID, $atPeriodEnd = false ) {
		if ( isset( $stripeCustomerID ) && isset( $stripeSubscriptionID ) ) {
			if ( ! empty( $stripeCustomerID ) && ! empty( $stripeSubscriptionID ) ) {
				$subscription = $this->retrieve_subscription( $stripeCustomerID, $stripeSubscriptionID );
				if ( $subscription ) {
					$cancellation_result = $subscription->cancel( array( "at_period_end" => $atPeriodEnd ) );
				}
			}
		}
	}

	function retrieve_subscription( $customerID, $subscriptionID ) {
		$cu = \Stripe\Customer::retrieve( $customerID );

		return $cu->subscriptions->retrieve( $subscriptionID );
	}

	function get_products( $associativeArray = false, $productIds = null ) {
		$products = array();
		try {

			$params = array(
				'limit'     => 100,
				'include[]' => 'total_count'
			);
			if ( ! is_null( $productIds ) && count( $productIds ) > 0 ) {
				$params['ids'] = $productIds;
			}
			$params            = array( 'active' => 'false', 'limit' => 100 );
			$productCollection = \Stripe\Product::all( $params );
			foreach ( $productCollection->autoPagingIterator() as $product ) {
				if ( $associativeArray ) {
					$products[ $product->id ] = $product;
				} else {
					array_push( $products, $product );
				}
			}

			// MM_WPFS_Utils::log( 'params=' . print_r( $params, true ) );
			// MM_WPFS_Utils::log( 'productCollection=' . print_r( $productCollection, true ) );

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$products = array();
		}

		return $products;
	}

	function capture_charge( $charge_id ) {
		$charge = \Stripe\Charge::retrieve( $charge_id );
		if ( $charge instanceof \Stripe\Charge ) {
			return $charge->capture();
		}

		return $charge;
	}

	function refund_charge( $charge_id ) {
		$refund = \Stripe\Refund::create( [
			'charge' => $charge_id
		] );

		return $refund;
	}

}