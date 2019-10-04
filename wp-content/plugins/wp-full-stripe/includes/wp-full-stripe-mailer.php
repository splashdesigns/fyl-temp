<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.02.26.
 * Time: 14:16
 */
class MM_WPFS_Mailer {

	/**
	 * @param $email
	 * @param $currency
	 * @param $amount
	 * @param $billingName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $productName
	 * @param $customInputValues
	 * @param $formName
	 */
	public function send_payment_email_receipt( $email, $currency, $amount, $billingName, $billingAddress, $shippingName, $shippingAddress, $productName, $customInputValues, $formName ) {

		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options       = get_option( 'fullstripe_options' );
		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->paymentMade->subject;
		$message       = stripslashes( $emailReceipts->paymentMade->html );

		$search  = MM_WPFS_Utils::get_payment_macros();
		$replace = MM_WPFS_Utils::get_payment_macro_values(
			$email,
			$currency,
			$amount,
			$billingName,
			$billingAddress,
			$shippingName,
			$shippingAddress,
			$productName,
			$formName
		);
		$message = str_replace(
			$search,
			$replace,
			$message );

		$message = MM_WPFS_Utils::replace_custom_fields( $message, $customInputValues );

		$this->send_email( $email, $subject, $message );
	}

	public function send_email( $email, $subject, $message ) {
		$options = get_option( 'fullstripe_options' );

		$name = html_entity_decode( get_bloginfo( 'name' ) );

		$admin_email  = get_bloginfo( 'admin_email' );
		$sender_email = $admin_email;
		if ( isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
			$sender_email = $options['email_receipt_sender_address'];
		}
		$headers[] = "From: $name <$sender_email>";

		$headers[] = "Content-type: text/html";

		wp_mail( $email,
			apply_filters( 'fullstripe_email_subject_filter', $subject ),
			apply_filters( 'fullstripe_email_message_filter', $message ),
			apply_filters( 'fullstripe_email_headers_filter', $headers ) );

		if ( $options['admin_payment_receipt'] == 'website_admin' || $options['admin_payment_receipt'] == 'sender_address' ) {
			$receipt_to = $admin_email;
			if ( $options['admin_payment_receipt'] == 'sender_address' && isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
				$receipt_to = $options['email_receipt_sender_address'];
			}
			wp_mail( $receipt_to,
				"COPY: " . apply_filters( 'fullstripe_email_subject_filter', $subject ),
				apply_filters( 'fullstripe_email_message_filter', $message ),
				apply_filters( 'fullstripe_email_headers_filter', $headers ) );
		}
	}

	/**
	 * @param $email
	 * @param $billingName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $productName
	 * @param $custom_input_values
	 * @param $form_name
	 */
	public function send_card_captured_email_receipt( $email, $billingName, $billingAddress, $shippingName, $shippingAddress, $productName, $custom_input_values, $form_name ) {

		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options       = get_option( 'fullstripe_options' );
		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->cardCaptured->subject;
		$message       = stripslashes( $emailReceipts->cardCaptured->html );

		$search  = MM_WPFS_Utils::get_payment_macros();
		$replace = MM_WPFS_Utils::get_payment_macro_values(
			$email,
			null,
			0,
			$billingName,
			$billingAddress,
			$shippingName,
			$shippingAddress,
			$productName,
			$form_name
		);
		$message = str_replace(
			$search,
			$replace,
			$message );

		$message = MM_WPFS_Utils::replace_custom_fields( $message, $custom_input_values );

		$this->send_email( $email, $subject, $message );
	}

	/**
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 */
	public function sendSubscriptionStartedEmailReceipt( $transactionData ) {
		$this->send_subscription_started_email_receipt(
			$transactionData->getCustomerEmail(),
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
			$transactionData->getCustomerName(),
			$transactionData->getBillingAddress(),
			$transactionData->getShippingName(),
			$transactionData->getShippingAddress(),
			$transactionData->getProductName(),
			$transactionData->getCustomInputValues()
		);
	}

	/**
	 * @deprecated
	 *
	 * @param $customerEmail
	 * @param $planName
	 * @param $planCurrency
	 * @param $planNetSetupFee
	 * @param $planGrossSetupFee
	 * @param $planSetupFeeVAT
	 * @param $planSetupFeeVATRate
	 * @param $planNetAmount
	 * @param $planGrossAmount
	 * @param $planAmountVAT
	 * @param $planAmountVATRate
	 * @param $grossAmountAndGrossSetupFee
	 * @param $cardholderName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $productName
	 * @param null $customInputValues
	 */
	public function send_subscription_started_email_receipt( $customerEmail, $planName, $planCurrency, $planNetSetupFee, $planGrossSetupFee, $planSetupFeeVAT, $planSetupFeeVATRate, $planNetAmount, $planGrossAmount, $planAmountVAT, $planAmountVATRate, $grossAmountAndGrossSetupFee, $cardholderName, $billingAddress, $shippingName, $shippingAddress, $productName, $customInputValues = null ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options       = get_option( 'fullstripe_options' );
		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->subscriptionStarted->subject;
		$message       = stripslashes( $emailReceipts->subscriptionStarted->html );

		$search  = MM_WPFS_Utils::get_subscription_macros();
		$replace = MM_WPFS_Utils::get_subscription_macro_values(
			$cardholderName,
			$customerEmail,
			$billingAddress,
			$shippingName,
			$shippingAddress,
			$planName,
			$planCurrency,
			$planNetSetupFee,
			$planGrossSetupFee,
			$planSetupFeeVAT,
			$planSetupFeeVATRate,
			$planNetAmount,
			$planGrossAmount,
			$planAmountVAT,
			$planAmountVATRate,
			$grossAmountAndGrossSetupFee,
			$productName
		);
		$message = str_replace(
			$search,
			$replace,
			$message
		);

		$message = MM_WPFS_Utils::replace_custom_fields( $message, $customInputValues );

		$this->send_email( $customerEmail, $subject, $message );
	}

	/**
	 * @param $customerEmail
	 * @param $planName
	 * @param $planCurrency
	 * @param $planNetSetupFee
	 * @param $planGrossSetupFee
	 * @param $planSetupFeeVAT
	 * @param $planSetupFeeVATRate
	 * @param $planNetAmount
	 * @param $planGrossAmount
	 * @param $planAmountVAT
	 * @param $planAmountVATRate
	 * @param $grossAmountAndGrossSetupFee
	 * @param $cardholderName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param $productName
	 */
	public function send_subscription_finished_email_receipt( $customerEmail, $planName, $planCurrency, $planNetSetupFee, $planGrossSetupFee, $planSetupFeeVAT, $planSetupFeeVATRate, $planNetAmount, $planGrossAmount, $planAmountVAT, $planAmountVATRate, $grossAmountAndGrossSetupFee, $cardholderName, $billingAddress, $shippingName, $shippingAddress, $productName ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options       = get_option( 'fullstripe_options' );
		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->subscriptionFinished->subject;
		$message       = stripslashes( $emailReceipts->subscriptionFinished->html );

		$search  = MM_WPFS_Utils::get_subscription_macros();
		$replace = MM_WPFS_Utils::get_subscription_macro_values(
			$cardholderName,
			$customerEmail,
			$billingAddress,
			$shippingName,
			$shippingAddress,
			$planName,
			$planCurrency,
			$planNetSetupFee,
			$planGrossSetupFee,
			$planSetupFeeVAT,
			$planSetupFeeVATRate,
			$planNetAmount,
			$planGrossAmount,
			$planAmountVAT,
			$planAmountVATRate,
			$grossAmountAndGrossSetupFee,
			$productName
		);
		$message = str_replace(
			$search,
			$replace,
			$message
		);

		$this->send_email( $customerEmail, $subject, $message );
	}

	public function send_card_update_confirmation_request( $customerName, $customerEmail, $cardUpdateSessionHash, $securityCode ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$options       = get_option( 'fullstripe_options' );
		$emailReceipts = json_decode( $options['email_receipts'] );
		$subject       = $emailReceipts->cardUpdateConfirmationRequest->subject;
		$message       = stripslashes( $emailReceipts->cardUpdateConfirmationRequest->html );

		$search  = MM_WPFS_Utils::get_card_update_confirmation_request_macros();
		$replace = MM_WPFS_Utils::get_card_update_confirmation_request_macro_values( $customerName, $customerEmail, $cardUpdateSessionHash, $securityCode );

		$message = str_replace(
			$search,
			$replace,
			$message
		);

		$this->send_email( $customerEmail, $subject, $message );
	}

}