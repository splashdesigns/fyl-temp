<?php

/**
 * Class MM_WPFS_Customer deals with customer front-end input i.e. payment forms submission
 */
class MM_WPFS_Customer {

	const REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY = 'wpfs_td_key';
	const ACTION_NAME_FULLSTRIPE_BEFORE_SUBSCRIPTION_CHARGE = 'fullstripe_before_subscription_charge';
	const ACTION_NAME_FULLSTRIPE_AFTER_SUBSCRIPTION_CHARGE = 'fullstripe_after_subscription_charge';

	/* @var $stripe MM_WPFS_Stripe */
	private $stripe = null;

	/* @var $db MM_WPFS_Database */
	private $db = null;

	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	/* @var MM_WPFS_TransactionDataService */
	private $transaction_data_service = null;

	private $debugLog = false;

	public function __construct() {
		$this->db                       = new MM_WPFS_Database();
		$this->mailer                   = new MM_WPFS_Mailer();
		$this->stripe                   = new MM_WPFS_Stripe();
		$this->transaction_data_service = new MM_WPFS_TransactionDataService();
		$this->hooks();
	}

	private function hooks() {
		add_action( 'wp_ajax_wp_full_stripe_subscription_charge', array( $this, 'fullstripe_subscription_charge' ) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_subscription_charge', array(
			$this,
			'fullstripe_subscription_charge'
		) );
		add_action( 'wp_ajax_wp_full_stripe_check_coupon', array( $this, 'fullstripe_check_coupon' ) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_check_coupon', array( $this, 'fullstripe_check_coupon' ) );
		add_action( 'wp_ajax_wp_full_stripe_calculate_plan_amounts_and_setup_fees', array(
			$this,
			'calculate_plan_amounts_and_setup_fees'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_calculate_plan_amounts_and_setup_fees', array(
			$this,
			'calculate_plan_amounts_and_setup_fees'
		) );
		add_action( 'wp_ajax_wp_full_stripe_inline_payment_charge', array(
			$this,
			'fullstripe_inline_payment_charge'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_inline_payment_charge', array(
			$this,
			'fullstripe_inline_payment_charge'
		) );
		add_action( 'wp_ajax_wp_full_stripe_inline_subscription_charge', array(
			$this,
			'fullstripe_inline_subscription_charge'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_inline_subscription_charge', array(
			$this,
			'fullstripe_inline_subscription_charge'
		) );
		add_action( 'wp_ajax_wp_full_stripe_popup_payment_charge', array(
			$this,
			'fullstripe_popup_payment_charge'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_popup_payment_charge', array(
			$this,
			'fullstripe_popup_payment_charge'
		) );
		add_action( 'wp_ajax_wp_full_stripe_popup_subscription_charge', array(
			$this,
			'fullstripe_popup_subscription_charge'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_popup_subscription_charge', array(
			$this,
			'fullstripe_popup_subscription_charge'
		) );
	}

	function fullstripe_inline_payment_charge() {

		try {

			$paymentFormModel = new MM_WPFS_Public_InlinePaymentFormModel();
			$bindingResult    = $paymentFormModel->bind();

			if ( $bindingResult->hasErrors() ) {
				$return = self::generate_return_value_from_bindings( $bindingResult );
			} else {
				$chargeResult = $this->processCharge( $paymentFormModel );
				$return       = self::generate_return_value_from_transaction_result( $chargeResult );
			}

		} catch ( \Stripe\Error\Card $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$messageTitle = __( 'Stripe Error', 'wp-full-stripe' );
			$message      = $this->stripe->resolve_error_message_by_code( $e->getCode() );
			if ( is_null( $message ) ) {
				$message = MM_WPFS_Utils::translate_label( $e->getMessage() );
			}
			$return = array(
				'success'          => false,
				'messageTitle'     => $messageTitle,
				'message'          => $message,
				'exceptionMessage' => $e->getMessage()
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return = array(
				'success'          => false,
				'messageTitle'     => __( 'Internal Error', 'wp-full-stripe' ),
				'message'          => MM_WPFS_Utils::translate_label( $e->getMessage() ),
				'exceptionMessage' => $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_inline_payment_charge_return_message', $return ) );
		exit;

	}

	/**
	 * @param $bindingResult MM_WPFS_BindingResult
	 *
	 * @return array
	 */
	static function generate_return_value_from_bindings( $bindingResult ) {
		// todo tnagy change global error messages title
		return array(
			'success'       => false,
			'bindingResult' => array(
				'fieldErrors'  => array(
					'title'  => __( 'Form errors', 'wp-full-stripe' ),
					'errors' => $bindingResult->getFieldErrors()
				),
				'globalErrors' => array(
					'title'  => __( 'Global errors', 'wp-full-stripe' ),
					'errors' => $bindingResult->getGlobalErrors()
				)
			)
		);
	}

	/**
	 * @param MM_WPFS_Public_PaymentFormModel $paymentFormModel
	 *
	 * @return MM_WPFS_ChargeResult
	 */
	private function processCharge( $paymentFormModel ) {

		$chargeResult = new MM_WPFS_ChargeResult();

		$sendPluginEmail = $this->sendPluginEmail( $paymentFormModel );

		$billingName = ! is_null( $paymentFormModel->getCardHolderName() ) ? $paymentFormModel->getCardHolderName() : $paymentFormModel->getBillingName();
		if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $paymentFormModel->getForm()->customAmount ) {
			$stripeCustomer = $this->create_or_get_customer(
				$paymentFormModel->getStripeToken(),
				$paymentFormModel->getCardHolderName(),
				$paymentFormModel->getCardHolderEmail(),
				$paymentFormModel->getCardHolderPhone(),
				$billingName,
				$paymentFormModel->getBillingAddress(),
				$paymentFormModel->getShippingName(),
				$paymentFormModel->getShippingAddress(),
				$paymentFormModel->getMetadata()
			);
		} else {
			$stripeCustomer = $this->create_or_get_customer(
				$paymentFormModel->getStripeToken(),
				$paymentFormModel->getCardHolderName(),
				$paymentFormModel->getCardHolderEmail(),
				$paymentFormModel->getCardHolderPhone(),
				$billingName,
				$paymentFormModel->getBillingAddress(),
				$paymentFormModel->getShippingName(),
				$paymentFormModel->getShippingAddress(),
				null
			);
		}
		$paymentFormModel->setStripeCustomer( $stripeCustomer );

		$transactionData = MM_WPFS_TransactionDataService::createPaymentData(
			$paymentFormModel->getFormName(),
			$paymentFormModel->getStripeToken(),
			$stripeCustomer->id,
			$paymentFormModel->getCardHolderEmail(),
			$paymentFormModel->getCardHolderPhone(),
			$paymentFormModel->getForm()->currency,
			$paymentFormModel->getAmount(),
			$paymentFormModel->getProductName(),
			$billingName,
			$paymentFormModel->getBillingAddress(),
			$paymentFormModel->getShippingName(),
			$paymentFormModel->getShippingAddress(),
			$paymentFormModel->getCustomInputvalues()
		);

		$translateLabel          = MM_WPFS_Utils::translate_label( $paymentFormModel->getForm()->stripeDescription );
		$macros                  = MM_WPFS_Utils::get_payment_macros();
		$macroValues             = MM_WPFS_Utils::getPaymentMacroValues( $transactionData, MM_WPFS_Utils::ESCAPE_TYPE_NONE );
		$stripeChargeDescription = str_replace(
			$macros,
			$macroValues,
			$translateLabel
		);
		$stripeChargeDescription = MM_WPFS_Utils::replace_custom_fields( $stripeChargeDescription, $paymentFormModel->getCustomInputvalues(), MM_WPFS_Utils::ESCAPE_TYPE_NONE );

		$formId = null;
		if ( $paymentFormModel instanceof MM_WPFS_Public_InlinePaymentFormModel ) {
			$formId = $paymentFormModel->getForm()->paymentFormID;
		}
		if ( $paymentFormModel instanceof MM_WPFS_Public_PopupPaymentFormModel ) {
			$formId = $paymentFormModel->getForm()->checkoutFormID;
		}

		$formType = MM_WPFS::FORM_TYPE_PAYMENT;
		if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $paymentFormModel->getForm()->customAmount ) {
			$chargeResult->setPaymentType( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE );
			do_action( 'fullstripe_before_card_capture', $paymentFormModel->getFormName() );
			$this->db->fullstripe_insert_card_capture(
				$stripeCustomer,
				$paymentFormModel->getCardHolderName(),
				$paymentFormModel->getBillingAddress( false ),
				$paymentFormModel->getShippingName(),
				$paymentFormModel->getShippingAddress( false ),
				$formId,
				$formType,
				$paymentFormModel->getFormName()
			);
			do_action( 'fullstripe_after_card_capture', $stripeCustomer );
			$chargeResult->setSuccess( true );
			$chargeResult->setMessageTitle( __( 'Success', 'wp-full-stripe' ) );
			$chargeResult->setMessage( __( 'Card saved successfully!', 'wp-full-stripe' ) );
		} else {
			do_action( 'fullstripe_before_payment_charge', $paymentFormModel->getAmount() );
			if ( MM_WPFS::CHARGE_TYPE_IMMEDIATE === $paymentFormModel->getForm()->chargeType ) {
				$capture = true;
			} elseif ( MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE === $paymentFormModel->getForm()->chargeType ) {
				$capture = false;
			} else {
				$capture = true;
			}
			$charge              = $this->stripe->charge_customer(
				$stripeCustomer->id,
				$paymentFormModel->getForm()->currency,
				$paymentFormModel->getAmount(),
				$capture,
				$stripeChargeDescription,
				$paymentFormModel->getMetadata(),
				( $sendPluginEmail == false && $paymentFormModel->getForm()->sendEmailReceipt == true ? $paymentFormModel->getCardHolderEmail() : null )
			);
			$charge['wpfs_form'] = $paymentFormModel->getFormName();
			do_action( 'fullstripe_after_payment_charge', $charge );
			$this->db->fullstripe_insert_payment(
				$charge,
				$paymentFormModel->getBillingAddress( false ),
				$paymentFormModel->getShippingName(),
				$paymentFormModel->getShippingAddress( false ),
				$stripeCustomer->id,
				$paymentFormModel->getCardHolderName(),
				$paymentFormModel->getCardHolderEmail(),
				$formId,
				$formType,
				$paymentFormModel->getFormName()
			);
			if ( isset( $charge->source ) && isset( $charge->source->name ) && ! empty( $charge->source->name ) ) {
				$paymentFormModel->setBillingName( $charge->source->name );
			} else {
				$paymentFormModel->setBillingName( $paymentFormModel->getCardHolderName() );
			}
			$chargeResult->setSuccess( true );
			$chargeResult->setMessageTitle( __( 'Success', 'wp-full-stripe' ) );
			$chargeResult->setMessage( __( 'Payment Successful!', 'wp-full-stripe' ) );
		}

		$this->handleRedirect( $paymentFormModel, $transactionData, $chargeResult );

		if ( $sendPluginEmail && 1 == $paymentFormModel->getForm()->sendEmailReceipt ) {
			if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $paymentFormModel->getForm()->customAmount ) {
				$this->mailer->send_card_captured_email_receipt(
					$paymentFormModel->getCardHolderEmail(),
					$paymentFormModel->getBillingName(),
					$paymentFormModel->getBillingAddress(),
					$paymentFormModel->getShippingName(),
					$paymentFormModel->getShippingAddress(),
					$paymentFormModel->getProductName(),
					$paymentFormModel->getCustomInputvalues(),
					$paymentFormModel->getFormName()
				);
			} else {
				$this->mailer->send_payment_email_receipt(
					$paymentFormModel->getCardHolderEmail(),
					$paymentFormModel->getForm()->currency,
					$paymentFormModel->getAmount(),
					$paymentFormModel->getBillingName(),
					$paymentFormModel->getBillingAddress(),
					$paymentFormModel->getShippingName(),
					$paymentFormModel->getShippingAddress(),
					$paymentFormModel->getProductName(),
					$paymentFormModel->getCustomInputvalues(),
					$paymentFormModel->getFormName()
				);
			}
		}

		return $chargeResult;
	}

	/**
	 * @param MM_WPFS_Public_FormModel $formModel
	 *
	 * @return bool
	 */
	private function sendPluginEmail( $formModel ) {
		$sendPluginEmail = true;
		$options         = get_option( 'fullstripe_options' );
		if ( 'stripe' == $options['receiptEmailType'] && 1 == $formModel->getForm()->sendEmailReceipt ) {
			$sendPluginEmail = false;

			return $sendPluginEmail;
		}

		return $sendPluginEmail;
	}

	/**
	 * @param $token
	 * @param $cardHolderName
	 * @param $cardHolderEmail
	 * @param $cardHolderPhone
	 * @param $billingName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $metadata
	 *
	 * @return \Stripe\ApiResource|\Stripe\StripeObject
	 * @throws Exception
	 */
	private function create_or_get_customer( $token, $cardHolderName, $cardHolderEmail, $cardHolderPhone, $billingName, $billingAddress, $shippingName, $shippingAddress, $metadata ) {

		$this->stripe->validate_token_cvc( $token );

		$customer = MM_WPFS_Utils::find_existing_stripe_customer_by_email( $this->db, $this->stripe, $cardHolderEmail );

		if ( ! isset( $customer ) ) {
			$customerName   = ! empty( $cardHolderName ) ? $cardHolderName : $billingName;
			$customerEmail  = $cardHolderEmail;
			$stripeCustomer = $this->stripe->create_customer_with_source( $token, $customerName, $customerEmail, $metadata );
		} else {
			// update existing customer to charge
			$stripeCustomer = $this->stripe->add_customer_source( $customer['stripeCustomerID'], $token );
		}
		if ( ! is_null( $billingAddress ) ) {
			$this->stripe->update_customer_billing_address( $stripeCustomer, $billingName, $billingAddress );
			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'create_or_get_customer(): customer\'s billing address updated with=' . print_r( $billingAddress, true ) );
			}
		}
		if ( ! is_null( $shippingAddress ) ) {
			$this->stripe->update_customer_shipping_address( $stripeCustomer, $shippingName, $cardHolderPhone, $shippingAddress );
			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'create_or_get_customer(): customer\'s shipping address updated with=' . print_r( $shippingAddress, true ) );
			}
		}

		return $stripeCustomer;
	}

	/**
	 * @param MM_WPFS_Public_FormModel $formModel
	 * @param MM_WPFS_TransactionData $transactionData
	 * @param MM_WPFS_TransactionResult $transactionResult
	 */
	private function handleRedirect( $formModel, $transactionData, $transactionResult ) {
		if ( 1 == $formModel->getForm()->redirectOnSuccess ) {
			if ( 1 == $formModel->getForm()->redirectToPageOrPost ) {
				if ( 0 != $formModel->getForm()->redirectPostID ) {
					$pageOrPostUrl = get_page_link( $formModel->getForm()->redirectPostID );
					if ( 1 == $formModel->getForm()->showDetailedSuccessPage ) {
						$transactionDataKey = $this->transaction_data_service->store( $transactionData );
						$pageOrPostUrl      = add_query_arg(
							array(
								self::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY => $transactionDataKey
							),
							$pageOrPostUrl
						);
					}
					$transactionResult->setRedirect( true );
					$transactionResult->setRedirectURL( $pageOrPostUrl );
				} else {
					MM_WPFS_Utils::log( "handleRedirect(): Inconsistent form data: formName={$formModel->getFormName()}, doRedirect={$formModel->getForm()->redirectOnSuccess}, redirectPostID={$formModel->getForm()->redirectPostID}" );
				}
			} else {
				$transactionResult->setRedirect( true );
				$transactionResult->setRedirectURL( $formModel->getForm()->redirectUrl );
			}
		}
	}

	/**
	 * @param $chargeResult MM_WPFS_TransactionResult
	 *
	 * @return array
	 */
	static function generate_return_value_from_transaction_result( $chargeResult ) {
		return array(
			'success'      => $chargeResult->isSuccess(),
			'messageTitle' => $chargeResult->getMessageTitle(),
			'message'      => $chargeResult->getMessage(),
			'redirect'     => $chargeResult->isRedirect(),
			'redirectURL'  => $chargeResult->getRedirectURL()
		);
	}

	function fullstripe_inline_subscription_charge() {

		try {

			$subscriptionFormModel = new MM_WPFS_Public_InlineSubscriptionFormModel();
			$bindingResult         = $subscriptionFormModel->bind();

			if ( $bindingResult->hasErrors() ) {
				$return = self::generate_return_value_from_bindings( $bindingResult );
			} else {
				$subscriptionResult = $this->processSubscription( $subscriptionFormModel );
				$return             = self::generate_return_value_from_transaction_result( $subscriptionResult );
			}

		} catch ( \Stripe\Error\Card $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$messageTitle = __( 'Stripe Error', 'wp-full-stripe' );
			$message      = $this->stripe->resolve_error_message_by_code( $e->getCode() );
			if ( is_null( $message ) ) {
				$message = MM_WPFS_Utils::translate_label( $e->getMessage() );
			}
			$return = array(
				'success'          => false,
				'messageTitle'     => $messageTitle,
				'message'          => $message,
				'exceptionMessage' => $e->getMessage()
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return = array(
				'success'          => false,
				'messageTitle'     => __( 'Internal Error', 'wp-full-stripe' ),
				'message'          => MM_WPFS_Utils::translate_label( $e->getMessage() ),
				'exceptionMessage' => $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_inline_subscription_charge_return_message', $return ) );
		exit;

	}

	/**
	 * @param MM_WPFS_Public_SubscriptionFormModel $subscriptionFormModel
	 *
	 * @return MM_WPFS_SubscriptionResult
	 */
	private function processSubscription( $subscriptionFormModel ) {

		$subscriptionResult = new MM_WPFS_SubscriptionResult();

		$sendPluginEmail = $this->sendPluginEmail( $subscriptionFormModel );

		$stripeCustomer = MM_WPFS_Utils::find_existing_stripe_customer_by_email( $this->db, $this->stripe, $subscriptionFormModel->getCardHolderEmail() );
		$subscriptionFormModel->setStripeCustomer( $stripeCustomer );

		do_action( self::ACTION_NAME_FULLSTRIPE_BEFORE_SUBSCRIPTION_CHARGE, $subscriptionFormModel->getStripePlan()->id );
		$formId              = null;
		$formType            = null;
		$showBillingAddress  = false;
		$showShippingAddress = false;
		if ( $subscriptionFormModel instanceof MM_WPFS_Public_InlineSubscriptionFormModel ) {
			$formId              = $subscriptionFormModel->getForm()->subscriptionFormID;
			$formType            = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
			$showBillingAddress  = 1 == $subscriptionFormModel->getForm()->showAddress;
			$showShippingAddress = 1 == $subscriptionFormModel->getForm()->showAddress;
		}
		if ( $subscriptionFormModel instanceof MM_WPFS_Public_PopupSubscriptionFormModel ) {
			$formId              = $subscriptionFormModel->getForm()->checkoutSubscriptionFormID;
			$formType            = MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION;
			$showBillingAddress  = 1 == $subscriptionFormModel->getForm()->showBillingAddress;
			$showShippingAddress = 1 == $subscriptionFormModel->getForm()->showShippingAddress;
		}

		$vatPercent = $this->get_vat_percent(
			$formId,
			$formType,
			$subscriptionFormModel->getStripePlan(),
			$subscriptionFormModel->getBillingAddress(),
			$subscriptionFormModel->getDecodedCustomInputLabels(),
			$subscriptionFormModel->getCustomInputvalues()
		);

		$subscriptionDescription = sprintf( __( 'Subscriber: %s', 'wp-full-stripe' ), $subscriptionFormModel->getCardHolderName() );
		if ( $stripeCustomer && $stripeCustomer['stripeCustomerID'] ) {
			$stripeCustomer  = $this->stripe->retrieve_customer( $stripeCustomer['stripeCustomerID'] );
			$transactionData = MM_WPFS_TransactionDataService::createSubscriptionData(
				$subscriptionFormModel->getFormName(),
				$subscriptionFormModel->getStripeToken(),
				$stripeCustomer->id,
				$subscriptionFormModel->getCardHolderEmail(),
				$subscriptionFormModel->getCardHolderPhone(),
				$subscriptionFormModel->getStripePlan()->id,
				$subscriptionFormModel->getStripePlan()->name,
				$subscriptionFormModel->getStripePlan()->currency,
				$subscriptionFormModel->getStripePlanAmount(),
				$subscriptionFormModel->getStripePlanSetupFee(),
				$subscriptionFormModel->getProductName(),
				$subscriptionFormModel->getBillingName(),
				$showBillingAddress ? $subscriptionFormModel->getBillingAddress() : null,
				$subscriptionFormModel->getShippingName(),
				$showShippingAddress ? $subscriptionFormModel->getShippingAddress() : null,
				$subscriptionFormModel->getCustomInputvalues(),
				$vatPercent,
				$subscriptionDescription,
				$subscriptionFormModel->getCouponCode(),
				$subscriptionFormModel->getMetadata()
			);
			/** @noinspection PhpUnusedLocalVariableInspection */
			$stripeCustomer = $this->stripe->alternativeSubscribeExisting( $transactionData );
			$subscriptionFormModel->setStripeCustomer( $stripeCustomer );
		} else {
			$billingName     = ! is_null( $subscriptionFormModel->getCardHolderName() ) ? $subscriptionFormModel->getCardHolderName() : $subscriptionFormModel->getBillingName();
			$transactionData = MM_WPFS_TransactionDataService::createSubscriptionData(
				$subscriptionFormModel->getFormName(),
				$subscriptionFormModel->getStripeToken(),
				null,
				$subscriptionFormModel->getCardHolderEmail(),
				$subscriptionFormModel->getCardHolderPhone(),
				$subscriptionFormModel->getStripePlan()->id,
				$subscriptionFormModel->getStripePlan()->name,
				$subscriptionFormModel->getStripePlan()->currency,
				$subscriptionFormModel->getStripePlanAmount(),
				$subscriptionFormModel->getStripePlanSetupFee(),
				$subscriptionFormModel->getProductName(),
				$billingName,
				$showBillingAddress ? $subscriptionFormModel->getBillingAddress() : null,
				$subscriptionFormModel->getShippingName(),
				$showShippingAddress ? $subscriptionFormModel->getShippingAddress() : null,
				$subscriptionFormModel->getCustomInputvalues(),
				$vatPercent,
				$subscriptionDescription,
				$subscriptionFormModel->getCouponCode(),
				$subscriptionFormModel->getMetadata()
			);
			$stripeCustomer  = $this->stripe->alternativeSubscribe( $transactionData );
			$subscriptionFormModel->setStripeCustomer( $stripeCustomer );
			$transactionData->setStripeCustomerId( $stripeCustomer->id );
		}
		$this->db->fullstripe_insert_subscriber(
			$stripeCustomer,
			$subscriptionFormModel->getCardHolderName(),
			$subscriptionFormModel->getBillingAddress( false ),
			$subscriptionFormModel->getShippingName(),
			$subscriptionFormModel->getShippingAddress( false ),
			$formId,
			$subscriptionFormModel->getForm()->name,
			$vatPercent
		);

		$macros      = MM_WPFS_Utils::get_subscription_macros();
		$macroValues = MM_WPFS_Utils::getSubscriptionMacroValues( $transactionData, MM_WPFS_Utils::ESCAPE_TYPE_NONE );
		if ( ! is_null( $transactionData->getCustomInputValues() ) && is_array( $transactionData->getCustomInputValues() ) ) {
			$customFieldMacros      = MM_WPFS_Utils::get_custom_field_macros();
			$customFieldMacroValues = MM_WPFS_Utils::get_custom_field_macro_values( count( $customFieldMacros ), $transactionData->getCustomInputValues() );
			$macros                 = array_merge( $macros, $customFieldMacros );
			$macroValues            = array_merge( $macroValues, $customFieldMacroValues );
		}
		$additionalData = MM_WPFS_Utils::prepare_additional_data_for_subscription_charge( self::ACTION_NAME_FULLSTRIPE_AFTER_SUBSCRIPTION_CHARGE, $stripeCustomer, $macros, $macroValues );
		do_action( self::ACTION_NAME_FULLSTRIPE_AFTER_SUBSCRIPTION_CHARGE, $stripeCustomer, $additionalData );

		$subscriptionResult->setSuccess( true );
		$subscriptionResult->setMessageTitle( __( 'Success', 'wp-full-stripe' ) );
		$subscriptionResult->setMessage( __( 'Payment Successful!', 'wp-full-stripe' ) );

		$this->handleRedirect( $subscriptionFormModel, $transactionData, $subscriptionResult );

		if ( $sendPluginEmail && 1 == $subscriptionFormModel->getForm()->sendEmailReceipt ) {
			$this->mailer->sendSubscriptionStartedEmailReceipt( $transactionData );
		}

		return $subscriptionResult;
	}

	/**
	 * @param $formId
	 * @param $formType
	 * @param $stripePlan
	 * @param $billingAddress
	 * @param $customInputLabels
	 * @param $customInputValues
	 *
	 * @return float
	 * @throws Exception
	 */
	private function get_vat_percent( $formId, $formType, $stripePlan, $billingAddress, $customInputLabels, $customInputValues ) {

		// MM_WPFS_Utils::log( "get_vat_percent(): formId=$formId, formType=$formType" );

		$form = $this->get_subscription_form_by_id( $formId, $formType );

		if ( isset( $form ) ) {
			if ( MM_WPFS::VAT_RATE_TYPE_NO_VAT === $form->vatRateType ) {
				$vatPercent = MM_WPFS::NO_VAT_PERCENT;
			} elseif ( MM_WPFS::VAT_RATE_TYPE_FIXED_VAT === $form->vatRateType ) {
				$vatPercent = $form->vatPercent;
			} elseif ( MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT === $form->vatRateType ) {
				$fromCountry = $form->defaultBillingCountry;
				$toCountry   = $billingAddress['country_code'];
				$vatPercent  = apply_filters(
					MM_WPFS::FILTER_NAME_GET_VAT_PERCENT,
					MM_WPFS::NO_VAT_PERCENT,
					$fromCountry,
					$toCountry,
					MM_WPFS_Utils::prepare_vat_filter_arguments(
						$formId,
						$formType,
						$stripePlan,
						$billingAddress,
						MM_WPFS_Utils::prepare_custom_input_data(
							$customInputLabels,
							$customInputValues
						)
					)
				);
			} else {
				throw new Exception( sprintf( __( 'Unknown VAT Rate Type: %s', 'wp-full-stripe' ), $form->vatRateType ) );
			}
		} else {
			throw new Exception( sprintf( __( 'Cannot find \'%s\' form with id=%s', 'wp-full-stripe' ), $formType, $formId ) );
		}

		return $vatPercent;
	}

	/**
	 * @param $formId
	 * @param $formType
	 *
	 * @return array|null|object|void
	 * @throws Exception
	 */
	private function get_subscription_form_by_id( $formId, $formType ) {
		switch ( $formType ) {
			case MM_WPFS::FORM_TYPE_SUBSCRIPTION:
			case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
				$form = $this->db->get_subscription_form_by_id( $formId );
				break;
			case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
			case MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION:
				$form = $this->db->get_checkout_subscription_form_by_id( $formId );
				break;
			default:
				throw new Exception( sprintf( __( 'Unknown form type: %s', 'wp-full-stripe' ), $formType ) );
		}

		return $form;
	}

	function fullstripe_popup_payment_charge() {
		try {

			$paymentFormModel = new MM_WPFS_Public_PopupPaymentFormModel();
			$bindingResult    = $paymentFormModel->bind();
			if ( $bindingResult->hasErrors() ) {
				$return = self::generate_return_value_from_bindings( $bindingResult );
			} else {
				$chargeResult = $this->processCharge( $paymentFormModel );
				$return       = self::generate_return_value_from_transaction_result( $chargeResult );
			}

		} catch ( \Stripe\Error\Card $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$messageTitle = __( 'Stripe Error', 'wp-full-stripe' );
			$message      = $this->stripe->resolve_error_message_by_code( $e->getCode() );
			if ( is_null( $message ) ) {
				$message = MM_WPFS_Utils::translate_label( $e->getMessage() );
			}
			$return = array(
				'success'          => false,
				'messageTitle'     => $messageTitle,
				'message'          => $message,
				'exceptionMessage' => $e->getMessage()
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return = array(
				'success'          => false,
				'messageTitle'     => __( 'Internal Error', 'wp-full-stripe' ),
				'message'          => MM_WPFS_Utils::translate_label( $e->getMessage() ),
				'exceptionMessage' => $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_popup_payment_charge_return_message', $return ) );
		exit;

	}

	function fullstripe_popup_subscription_charge() {

		try {

			$subscriptionFormModel = new MM_WPFS_Public_PopupSubscriptionFormModel();
			$bindingResult         = $subscriptionFormModel->bind();

			if ( $bindingResult->hasErrors() ) {
				$return = self::generate_return_value_from_bindings( $bindingResult );
			} else {
				$subscriptionResult = $this->processSubscription( $subscriptionFormModel );
				$return             = self::generate_return_value_from_transaction_result( $subscriptionResult );
			}

		} catch ( \Stripe\Error\Card $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$messageTitle = __( 'Stripe Error', 'wp-full-stripe' );
			$message      = $this->stripe->resolve_error_message_by_code( $e->getCode() );
			if ( is_null( $message ) ) {
				$message = MM_WPFS_Utils::translate_label( $e->getMessage() );
			}
			$return = array(
				'success'          => false,
				'messageTitle'     => $messageTitle,
				'message'          => $message,
				'exceptionMessage' => $e->getMessage()
			);
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return = array(
				'success'          => false,
				'messageTitle'     => __( 'Internal Error', 'wp-full-stripe' ),
				'message'          => MM_WPFS_Utils::translate_label( $e->getMessage() ),
				'exceptionMessage' => $e->getMessage()
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( apply_filters( 'fullstripe_popup_subscription_charge_return_message', $return ) );
		exit;

	}

	function fullstripe_check_coupon() {
		$code = $_POST['code'];

		try {
			$coupon = $this->stripe->get_coupon( $code );
			if ( false == $coupon->valid ) {
				$return = array(
					'msg_title' => __( 'Coupon redemption', 'wp-full-stripe' ),
					'msg'       => __( 'This coupon has expired.', 'wp-full-stripe' ),
					'valid'     => false
				);
			} else {
				if ( $this->debugLog ) {
					MM_WPFS_Utils::log( 'coupon=' . print_r( $coupon, true ) );
				}
				$return = array(
					'msg_title' => __( 'Coupon redemption', 'wp-full-stripe' ),
					'msg'       => __( 'The coupon has been applied successfully.', 'wp-full-stripe' ),
					'coupon'    => array(
						'name'        => $coupon->id,
						'currency'    => $coupon->currency,
						'percent_off' => $coupon->percent_off,
						'amount_off'  => $coupon->amount_off
					),
					'valid'     => true
				);
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::log( sprintf( 'Message=%s, Stack=%s ', $e->getMessage(), $e->getTraceAsString() ) );
			$return = array(
				'msg'   => __( 'Entered coupon code is not valid anymore. Try with another one.', 'wp-full-stripe' ),
				'valid' => false
			);
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	function calculate_plan_amounts_and_setup_fees() {
		$formNameAsIdentifier = sanitize_text_field( $_POST['formId'] );
		$formType             = sanitize_text_field( $_POST['formType'] );
		$toCountry            = sanitize_text_field( $_POST['selectedCountry'] );
		$customInputValues    = isset( $_POST['customInputValues'] ) ? $_POST['customInputValues'] : null;

		$response = array(
			'formId'   => $formNameAsIdentifier,
			'formType' => $formType
		);

		try {
			$form = $this->get_subscription_form_by_name( $formNameAsIdentifier, $formType );
			if ( isset( $form ) && $form->vatRateType == MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT ) {
				$fromCountry  = $form->defaultBillingCountry;
				$customInputs = null;
				if ( $form->showCustomInput == 1 ) {
					$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $form->customInputs );
					$customInputs      = MM_WPFS_Utils::prepare_custom_input_data( $customInputLabels, $customInputValues );
				}
				$vatPercent             = apply_filters( MM_WPFS::FILTER_NAME_GET_VAT_PERCENT, MM_WPFS::NO_VAT_PERCENT, $fromCountry, $toCountry, MM_WPFS_Utils::prepare_vat_filter_arguments( $formNameAsIdentifier, $formType, null, array( 'country' => $toCountry ), $customInputs ) );
				$response['vatPercent'] = $vatPercent;
				$stripePlans            = MM_WPFS::getInstance()->get_plans();
				$formPlans              = MM_WPFS_Utils::get_sorted_form_plans( $stripePlans, $form->plans );
				$plans                  = array();
				foreach ( $formPlans as $plan ) {
					$planSetupFee = MM_WPFS_Utils::get_setup_fee_for_plan( $plan );
					$planAmount   = $plan->amount;
					$aPlan        = array(
						'id'                                   => esc_attr( $plan->id ),
						'name'                                 => $plan->name,
						'planAmount'                           => MM_WPFS_Utils::format_amount( $plan->currency, $planAmount ),
						'planAmountInSmallestCommonCurrency'   => $planAmount,
						'planSetupFee'                         => MM_WPFS_Utils::format_amount( $plan->currency, $planSetupFee ),
						'planSetupFeeInSmallestCommonCurrency' => $planSetupFee,
						'vatPercent'                           => $vatPercent
					);
					array_push( $plans, $aPlan );
				}
				$response['plans']   = $plans;
				$response['success'] = true;
			} else {
				$response['success'] = false;
				$response['error']   = __( 'Form not found or form do not use custom VAT rates!', 'wp-full-stripe' );
			}
		} catch ( Exception $e ) {
			$response['success'] = false;
			$response['error']   = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $response );
		exit;
	}

	/**
	 * @param $formName
	 * @param $formType
	 *
	 * @return array|mixed|null|object|void
	 * @throws Exception
	 */
	private function get_subscription_form_by_name( $formName, $formType ) {
		switch ( $formType ) {
			case MM_WPFS::FORM_TYPE_SUBSCRIPTION:
			case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
				$form = $this->db->get_subscription_form_by_name( $formName );
				break;
			case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
			case MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION:
				$form = $this->db->get_checkout_subscription_form_by_name( $formName );
				break;
			default:
				throw new Exception( sprintf( __( 'Unknown form type: %s', 'wp-full-stripe' ), $formType ) );
		}

		return $form;
	}

}

class MM_WPFS_TransactionResult {

	/**
	 * @var boolean
	 */
	protected $success = false;
	/**
	 * @var string
	 */
	protected $messageTitle;
	/**
	 * @var string
	 */
	protected $message;
	/**
	 * @var boolean
	 */
	protected $redirect = false;
	/**
	 * @var string
	 */
	protected $redirectURL;

	/**
	 * @return boolean
	 */
	public function isSuccess() {
		return $this->success;
	}

	/**
	 * @param boolean $success
	 */
	public function setSuccess( $success ) {
		$this->success = $success;
	}

	/**
	 * @return string
	 */
	public function getMessageTitle() {
		return $this->messageTitle;
	}

	/**
	 * @param string $messageTitle
	 */
	public function setMessageTitle( $messageTitle ) {
		$this->messageTitle = $messageTitle;
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

	/**
	 * @return boolean
	 */
	public function isRedirect() {
		return $this->redirect;
	}

	/**
	 * @param boolean $redirect
	 */
	public function setRedirect( $redirect ) {
		$this->redirect = $redirect;
	}

	/**
	 * @return string
	 */
	public function getRedirectURL() {
		return $this->redirectURL;
	}

	/**
	 * @param string $redirectURL
	 */
	public function setRedirectURL( $redirectURL ) {
		$this->redirectURL = $redirectURL;
	}

}

class MM_WPFS_ChargeResult extends MM_WPFS_TransactionResult {

	/**
	 * @var string
	 */
	protected $paymentType;

	/**
	 * @return string
	 */
	public function getPaymentType() {
		return $this->paymentType;
	}

	/**
	 * @param string $paymentType
	 */
	public function setPaymentType( $paymentType ) {
		$this->paymentType = $paymentType;
	}

}

class MM_WPFS_SubscriptionResult extends MM_WPFS_TransactionResult {

}
