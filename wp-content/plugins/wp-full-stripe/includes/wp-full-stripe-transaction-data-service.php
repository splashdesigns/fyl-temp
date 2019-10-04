<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.11.29.
 * Time: 16:38
 */
class MM_WPFS_TransactionDataService {

	const KEY_PREFIX = 'wpfs_td_';

	/**
	 * @param $formName
	 * @param $stripeToken
	 * @param $stripeCustomerId
	 * @param $customerEmail
	 * @param $customerPhone
	 * @param $currency
	 * @param $amount
	 * @param $productName
	 * @param $billingName
	 * @param $billingAddress
	 * @param $shippingName
	 * @param $shippingAddress
	 * @param null $customInputValues
	 *
	 * @return MM_WPFS_PaymentTransactionData
	 */
	public static function createPaymentData( $formName, $stripeToken, $stripeCustomerId, $customerEmail, $customerPhone, $currency, $amount, $productName, $billingName, $billingAddress, $shippingName, $shippingAddress, $customInputValues = null ) {
		$transactionData = new MM_WPFS_PaymentTransactionData();

		$transactionData->setFormName( $formName );
		$transactionData->setStripeToken( $stripeToken );
		$transactionData->setStripeCustomerId( $stripeCustomerId );
		$transactionData->setCustomerEmail( $customerEmail );
		$transactionData->setCustomerPhone( $customerPhone );
		$transactionData->setCustomerName( $billingName );
		$transactionData->setCurrency( $currency );
		$transactionData->setAmount( $amount );
		$transactionData->setProductName( $productName );
		$transactionData->setBillingName( $billingName );
		$transactionData->setBillingAddress( $billingAddress );
		$transactionData->setShippingName( $shippingName );
		$transactionData->setShippingAddress( $shippingAddress );
		$transactionData->setCustomInputValues( $customInputValues );

		return $transactionData;
	}

	/**
	 * @param $formName
	 * @param $stripeToken
	 * @param $stripeCustomerId
	 * @param $customerEmail
	 * @param $customerPhone
	 * @param $stripePlanId
	 * @param $stripePlanName
	 * @param $stripePlanCurrency
	 * @param $stripePlanAmount
	 * @param $stripePlanSetupFee
	 * @param $productName
	 * @param $billingName
	 * @param array $billingAddress
	 * @param $shippingName
	 * @param array $shippingAddress
	 * @param $customInputValues
	 * @param $vatPercent
	 * @param $subscriptionDescription
	 * @param $couponCode
	 * @param array $metadata
	 *
	 * @return MM_WPFS_SubscriptionTransactionData
	 */
	public static function createSubscriptionData( $formName, $stripeToken, $stripeCustomerId, $customerEmail, $customerPhone, $stripePlanId, $stripePlanName, $stripePlanCurrency, $stripePlanAmount, $stripePlanSetupFee, $productName, $billingName, $billingAddress, $shippingName, $shippingAddress, $customInputValues, $vatPercent, $subscriptionDescription, $couponCode, $metadata ) {

		$planAmountGrossComposite   = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanAmount, $vatPercent );
		$planSetupFeeGrossComposite = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanSetupFee, $vatPercent );

		$transactionData = new MM_WPFS_SubscriptionTransactionData();

		$transactionData->setFormName( $formName );
		$transactionData->setStripeToken( $stripeToken );
		$transactionData->setStripeCustomerId( $stripeCustomerId );
		$transactionData->setCustomerEmail( $customerEmail );
		$transactionData->setCustomerPhone( $customerPhone );
		$transactionData->setCustomerName( $billingName );
		$transactionData->setPlanId( $stripePlanId );
		$transactionData->setPlanName( $stripePlanName );
		$transactionData->setPlanCurrency( $stripePlanCurrency );
		$transactionData->setPlanAmountVATRate( $vatPercent );
		$transactionData->setPlanNetAmount( $stripePlanAmount );
		$transactionData->setPlanGrossAmount( $planAmountGrossComposite['gross'] );
		$transactionData->setPlanAmountVAT( $transactionData->getPlanGrossAmount() - $transactionData->getPlanNetAmount() );
		$transactionData->setPlanSetupFeeVATRate( $vatPercent );
		$transactionData->setPlanNetSetupFee( $stripePlanSetupFee );
		$transactionData->setPlanGrossSetupFee( $planSetupFeeGrossComposite['gross'] );
		$transactionData->setPlanSetupFeeVAT( $transactionData->getPlanGrossSetupFee() - $transactionData->getPlanNetSetupFee() );
		$transactionData->setProductName( $productName );
		$transactionData->setBillingName( $billingName );
		$transactionData->setBillingAddress( $billingAddress );
		$transactionData->setShippingName( $shippingName );
		$transactionData->setShippingAddress( $shippingAddress );
		$transactionData->setCustomInputValues( $customInputValues );
		$transactionData->setSubscriptionDescription( $subscriptionDescription );
		$transactionData->setCouponCode( $couponCode );
		$transactionData->setMetadata( $metadata );

		return $transactionData;
	}

	/**
	 * Store transaction data as a transient.
	 *
	 * @param $data MM_WPFS_TransactionData
	 *
	 * @return null|string
	 */
	public function store( $data ) {
		$key = $this->generateKey();
		set_transient( $key, $data );

		return rawurlencode( $key );
	}

	/**
	 * Generates a random key currently not in use as a transient key.
	 */
	private function generateKey() {
		$key = null;
		do {
			$key = self::KEY_PREFIX . crypt( strval( round( microtime( true ) * 1000 ) ), strval( rand() ) );
		} while ( get_transient( $key ) !== false );

		return $key;
	}

	/**
	 * @param $data_key
	 *
	 * @return bool|MM_WPFS_TransactionData
	 */
	public function retrieve( $data_key ) {
		if ( is_null( $data_key ) ) {
			return false;
		}
		$prefix_position = strpos( $data_key, self::KEY_PREFIX );
		if ( $prefix_position === false ) {
			return false;
		}
		if ( $prefix_position == 0 ) {
			$data = get_transient( $data_key );

			if ( $data !== false ) {
				delete_transient( $data_key );
			}

			return $data;
		} else {
			return false;
		}
	}

}

abstract class MM_WPFS_TransactionData {

	protected $formName;
	protected $stripeCustomerId;
	protected $customerName;
	protected $customerEmail;
	protected $customerPhone;
	protected $billingName;
	/**
	 * @var array|null
	 */
	protected $billingAddress;
	protected $shippingName;
	/**
	 * @var array|null
	 */
	protected $shippingAddress;
	protected $productName;
	protected $customInputValues;
	protected $couponCode;
	protected $stripeToken;
	/**
	 * @var array|null
	 */
	protected $metadata;

	/**
	 * @return mixed
	 */
	public function getFormName() {
		return $this->formName;
	}

	/**
	 * @param mixed $formName
	 */
	public function setFormName( $formName ) {
		$this->formName = $formName;
	}

	/**
	 * @return mixed
	 */
	public function getStripeCustomerId() {
		return $this->stripeCustomerId;
	}

	/**
	 * @param mixed $stripeCustomerId
	 */
	public function setStripeCustomerId( $stripeCustomerId ) {
		$this->stripeCustomerId = $stripeCustomerId;
	}

	/**
	 * @return mixed
	 */
	public function getCustomerName() {
		return $this->customerName;
	}

	/**
	 * @param mixed $customerName
	 */
	public function setCustomerName( $customerName ) {
		$this->customerName = $customerName;
	}

	/**
	 * @return mixed
	 */
	public function getCustomerEmail() {
		return $this->customerEmail;
	}

	/**
	 * @param mixed $customerEmail
	 */
	public function setCustomerEmail( $customerEmail ) {
		$this->customerEmail = $customerEmail;
	}

	/**
	 * @return mixed
	 */
	public function getCustomerPhone() {
		return $this->customerPhone;
	}

	/**
	 * @param mixed $customerPhone
	 */
	public function setCustomerPhone( $customerPhone ) {
		$this->customerPhone = $customerPhone;
	}

	/**
	 * @return mixed
	 */
	public function getBillingName() {
		return $this->billingName;
	}

	/**
	 * @param mixed $billingName
	 */
	public function setBillingName( $billingName ) {
		$this->billingName = $billingName;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddress() {
		return $this->billingAddress;
	}

	/**
	 * @param array|null $billingAddress
	 */
	public function setBillingAddress( $billingAddress ) {
		$this->billingAddress = $billingAddress;
	}

	/**
	 * @return mixed
	 */
	public function getShippingName() {
		return $this->shippingName;
	}

	/**
	 * @param mixed $shippingName
	 */
	public function setShippingName( $shippingName ) {
		$this->shippingName = $shippingName;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddress() {
		return $this->shippingAddress;
	}

	/**
	 * @param mixed $shippingAddress
	 */
	public function setShippingAddress( $shippingAddress ) {
		$this->shippingAddress = $shippingAddress;
	}

	/**
	 * @return mixed
	 */
	public function getProductName() {
		return $this->productName;
	}

	/**
	 * @param mixed $productName
	 */
	public function setProductName( $productName ) {
		$this->productName = $productName;
	}

	/**
	 * @return mixed
	 */
	public function getCustomInputValues() {
		return $this->customInputValues;
	}

	/**
	 * @param mixed $customInputValues
	 */
	public function setCustomInputValues( $customInputValues ) {
		$this->customInputValues = $customInputValues;
	}

	/**
	 * @return mixed
	 */
	public function getCouponCode() {
		return $this->couponCode;
	}

	/**
	 * @param mixed $couponCode
	 */
	public function setCouponCode( $couponCode ) {
		$this->couponCode = $couponCode;
	}

	/**
	 * @return mixed
	 */
	public function getStripeToken() {
		return $this->stripeToken;
	}

	/**
	 * @param mixed $stripeToken
	 */
	public function setStripeToken( $stripeToken ) {
		$this->stripeToken = $stripeToken;
	}

	/**
	 * @return mixed
	 */
	public function getMetadata() {
		return $this->metadata;
	}

	/**
	 * @param mixed $metadata
	 */
	public function setMetadata( $metadata ) {
		$this->metadata = $metadata;
	}

}

class MM_WPFS_PaymentTransactionData extends MM_WPFS_TransactionData {

	protected $currency;
	protected $amount;
	protected $vatPercent;

	/**
	 * @return mixed
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * @param mixed $currency
	 */
	public function setCurrency( $currency ) {
		$this->currency = $currency;
	}

	/**
	 * @return mixed
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param mixed $amount
	 */
	public function setAmount( $amount ) {
		$this->amount = $amount;
	}

	/**
	 * @return mixed
	 */
	public function getVatPercent() {
		return $this->vatPercent;
	}

	/**
	 * @param mixed $vatPercent
	 */
	public function setVatPercent( $vatPercent ) {
		$this->vatPercent = $vatPercent;
	}

}


class MM_WPFS_SubscriptionTransactionData extends MM_WPFS_TransactionData {

	protected $planId;
	protected $planName;
	protected $planCurrency;
	protected $planNetAmount;
	protected $planGrossAmount;
	protected $planAmountVAT;
	protected $planAmountVATRate;
	protected $planNetSetupFee;
	protected $planGrossSetupFee;
	protected $planSetupFeeVAT;
	protected $planSetupFeeVATRate;
	protected $subscriptionDescription;

	public function getPlanNetAmountAndNetSetupFee() {
		return $this->planNetAmount + $this->planNetSetupFee;
	}

	public function getPlanGrossAmountAndGrossSetupFee() {
		return $this->planGrossAmount + $this->planGrossSetupFee;
	}

	/**
	 * @return mixed
	 */
	public function getPlanId() {
		return $this->planId;
	}

	/**
	 * @param mixed $planId
	 */
	public function setPlanId( $planId ) {
		$this->planId = $planId;
	}

	/**
	 * @return mixed
	 */
	public function getPlanName() {
		return $this->planName;
	}

	/**
	 * @param mixed $planName
	 */
	public function setPlanName( $planName ) {
		$this->planName = $planName;
	}

	/**
	 * @return mixed
	 */
	public function getPlanCurrency() {
		return $this->planCurrency;
	}

	/**
	 * @param mixed $planCurrency
	 */
	public function setPlanCurrency( $planCurrency ) {
		$this->planCurrency = $planCurrency;
	}

	/**
	 * @return mixed
	 */
	public function getPlanNetAmount() {
		return $this->planNetAmount;
	}

	/**
	 * @param mixed $planNetAmount
	 */
	public function setPlanNetAmount( $planNetAmount ) {
		$this->planNetAmount = $planNetAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanGrossAmount() {
		return $this->planGrossAmount;
	}

	/**
	 * @param mixed $planGrossAmount
	 */
	public function setPlanGrossAmount( $planGrossAmount ) {
		$this->planGrossAmount = $planGrossAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanAmountVAT() {
		return $this->planAmountVAT;
	}

	/**
	 * @param mixed $planAmountVAT
	 */
	public function setPlanAmountVAT( $planAmountVAT ) {
		$this->planAmountVAT = $planAmountVAT;
	}

	/**
	 * @return mixed
	 */
	public function getPlanAmountVATRate() {
		return $this->planAmountVATRate;
	}

	/**
	 * @param mixed $planAmountVATRate
	 */
	public function setPlanAmountVATRate( $planAmountVATRate ) {
		$this->planAmountVATRate = $planAmountVATRate;
	}

	/**
	 * @return mixed
	 */
	public function getPlanNetSetupFee() {
		return $this->planNetSetupFee;
	}

	/**
	 * @param mixed $planNetSetupFee
	 */
	public function setPlanNetSetupFee( $planNetSetupFee ) {
		$this->planNetSetupFee = $planNetSetupFee;
	}

	/**
	 * @return mixed
	 */
	public function getPlanGrossSetupFee() {
		return $this->planGrossSetupFee;
	}

	/**
	 * @param mixed $planGrossSetupFee
	 */
	public function setPlanGrossSetupFee( $planGrossSetupFee ) {
		$this->planGrossSetupFee = $planGrossSetupFee;
	}

	/**
	 * @return mixed
	 */
	public function getPlanSetupFeeVAT() {
		return $this->planSetupFeeVAT;
	}

	/**
	 * @param mixed $planSetupFeeVAT
	 */
	public function setPlanSetupFeeVAT( $planSetupFeeVAT ) {
		$this->planSetupFeeVAT = $planSetupFeeVAT;
	}

	/**
	 * @return mixed
	 */
	public function getPlanSetupFeeVATRate() {
		return $this->planSetupFeeVATRate;
	}

	/**
	 * @param mixed $planSetupFeeVATRate
	 */
	public function setPlanSetupFeeVATRate( $planSetupFeeVATRate ) {
		$this->planSetupFeeVATRate = $planSetupFeeVATRate;
	}

	/**
	 * @return mixed
	 */
	public function getSubscriptionDescription() {
		return $this->subscriptionDescription;
	}

	/**
	 * @param mixed $subscriptionDescription
	 */
	public function setSubscriptionDescription( $subscriptionDescription ) {
		$this->subscriptionDescription = $subscriptionDescription;
	}

}