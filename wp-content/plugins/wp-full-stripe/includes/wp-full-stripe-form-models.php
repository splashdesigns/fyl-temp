<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.05.31.
 * Time: 16:55
 */
trait MM_WPFS_Model {

	/**
	 * @var MM_WPFS_Validator
	 */
	protected $__validator;

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 * @param string $sanitationType
	 *
	 * @return string
	 */
	public function getSanitizedPostParam( $parameterName, $defaultValue = null, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		$parameterValue = $this->getPostParam( $parameterName, $defaultValue );

		return $this->sanitizeValue( $parameterValue, $sanitationType );
	}

	/**
	 * This function retrieves the value saved on the specific key from the $_POST array.
	 * The function strips slashes from the returned value.
	 *
	 * @see wp_unslash()
	 *
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return null
	 */
	public function getPostParam( $parameterName, $defaultValue = null ) {
		if ( array_key_exists( $parameterName, $_POST ) && isset( $_POST[ $parameterName ] ) ) {
			$value = wp_unslash( $_POST[ $parameterName ] );
		} else {
			$value = wp_unslash( $defaultValue );
		}

		return $value;
	}

	/**
	 * @param $value
	 * @param $sanitationType
	 *
	 * @return array|string
	 */
	public function sanitizeValue( $value, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		if ( is_array( $value ) ) {
			if ( MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD === $sanitationType ) {
				$functionName = 'sanitize_text_field';
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL === $sanitationType ) {
				$functionName = 'sanitize_email';
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_KEY === $sanitationType ) {
				$functionName = 'sanitize_key';
			} else {
				$functionName = 'sanitize_text_field';
			}

			array_walk_recursive( $value, $functionName );

			return $value;
		} else {
			if ( MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD === $sanitationType ) {
				return sanitize_text_field( $value );
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL === $sanitationType ) {
				return sanitize_email( $value );
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_KEY === $sanitationType ) {
				return sanitize_key( $value );
			} else {
				return sanitize_text_field( $value );
			}
		}
	}

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return null
	 */
	public function getNumericPostParam( $parameterName, $defaultValue = null ) {
		if ( isset( $_POST[ $parameterName ] ) && is_numeric( $_POST[ $parameterName ] ) ) {
			$value = wp_unslash( $_POST[ $parameterName ] );
		} else {
			$value = wp_unslash( $defaultValue );
		}

		return $value;
	}

	/**
	 * @deprecated
	 *
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return string
	 */
	public function getStrippedPostParam( $parameterName, $defaultValue = null ) {
		return stripslashes( $this->getPostParam( $parameterName, $defaultValue ) );
	}

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return string
	 */
	public function getHTMLDecodedPostParam( $parameterName, $defaultValue = null ) {
		return html_entity_decode( $this->getPostParam( $parameterName, $defaultValue ) );
	}

	/**
	 * @param $parameterName
	 *
	 * @return array|mixed|object
	 */
	public function getJSONDecodedPostParam( $parameterName ) {
		return json_decode( rawurldecode( stripslashes( $_POST[ $parameterName ] ) ) );
	}

	/**
	 * @param $parameterName
	 * @param string $sanitationType
	 *
	 * @return string
	 */
	public function getURLDecodedPostParam( $parameterName, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		return $this->sanitizeValue( urldecode( $this->getPostParam( $parameterName ) ), $sanitationType );
	}

}

trait MM_WPFS_Admin_InlineFormModel {

	use MM_WPFS_Model;

	protected $title;
	protected $showAddress;

	protected function bindInlineParams() {

		// tnagy WPFS-740: remove form title
		// $this->title       = $this->getSanitizedPostParam( MM_WPFS_Admin_InlineForm::PARAM_FORM_TITLE );
		$this->title       = '';
		$this->showAddress = $this->getSanitizedPostParam( MM_WPFS_Admin_InlineForm::PARAM_FORM_SHOW_ADDRESS_INPUT, 0 );

	}

	protected function getInlineDataArray() {

		$data = array(
			'formTitle'   => $this->title,
			'showAddress' => $this->showAddress
		);

		return $data;
	}

}

trait MM_WPFS_Admin_PopupFormModel {

	use MM_WPFS_Model;

	protected $companyName;
	protected $productDescription;
	protected $openButtonText;
	protected $showBillingAddress;
	protected $showShippingAddress;
	protected $showRememberMe;
	protected $image;
	protected $disableStyling;
	protected $preferredLanguage;

	protected function bindPopup() {

		$this->companyName         = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_COMPANY_NAME );
		$this->productDescription  = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_PROD_DESC );
		$this->openButtonText      = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_OPEN_FORM_BUTTON_TEXT );
		$this->showBillingAddress  = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_SHOW_ADDRESS_INPUT, 0 );
		$this->showShippingAddress = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_SHOW_SHIPPING_ADDRESS_INPUT, 0 );
		$this->showRememberMe      = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_SHOW_REMEMBER_ME );
		$this->image               = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_CHECKOUT_IMAGE, '' );
		$this->disableStyling      = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_DISABLE_STYLING );
		$this->preferredLanguage   = $this->getSanitizedPostParam( MM_WPFS_Admin_PopupForm::PARAM_FORM_PREFERRED_LANGUAGE, MM_WPFS_Admin_PopupForm::DEFAULT_PREFERRED_LANGUAGE );

	}

	protected function getPopupData() {

		$data = array(
			'companyName'         => $this->companyName,
			'productDesc'         => $this->productDescription,
			'openButtonTitle'     => $this->openButtonText,
			'showBillingAddress'  => $this->showBillingAddress,
			'showShippingAddress' => $this->showShippingAddress,
			'showRememberMe'      => $this->showRememberMe,
			'image'               => $this->image,
			'disableStyling'      => $this->disableStyling,
			'preferredLanguage'   => $this->preferredLanguage
		);

		return $data;
	}

}

interface MM_WPFS_ModelConstants {

	const SANITATION_TYPE_TEXT_FIELD = 'text_field';
	const SANITATION_TYPE_KEY = 'key';
	const SANITATION_TYPE_EMAIL = 'email';

}

interface MM_WPFS_Binder {

	const EMPTY_STR = '';

	/**
	 * @return MM_WPFS_BindingResult
	 */
	public function bind();

	public function getData();

}

interface MM_WPFS_Admin_PopupForm {

	const PARAM_COMPANY_NAME = 'company_name';
	const PARAM_PROD_DESC = 'prod_desc';
	const PARAM_OPEN_FORM_BUTTON_TEXT = 'open_form_button_text';
	const PARAM_FORM_SHOW_ADDRESS_INPUT = 'form_show_address_input';
	const PARAM_FORM_SHOW_SHIPPING_ADDRESS_INPUT = 'form_show_shipping_address_input';
	const PARAM_FORM_SHOW_REMEMBER_ME = 'form_show_remember_me';
	const PARAM_FORM_CHECKOUT_IMAGE = 'form_checkout_image';
	const PARAM_FORM_DISABLE_STYLING = 'form_disable_styling';
	const PARAM_FORM_PREFERRED_LANGUAGE = 'form_preferred_language';
	const DEFAULT_PREFERRED_LANGUAGE = 'auto';

}

interface MM_WPFS_Admin_InlineForm {

	const PARAM_FORM_TITLE = 'form_title';
	const PARAM_FORM_SHOW_ADDRESS_INPUT = 'form_show_address_input';

}

interface MM_WPFS_Public_PopupForm {

}

interface MM_WPFS_Public_InlineForm {

}

class MM_WPFS_BindingResult {

	protected $globalErrors = array();
	protected $fieldErrors = array();

	/**
	 * MM_WPFS_BindingResult constructor.
	 *
	 * @param $formHash
	 */
	public function __construct( $formHash = null ) {
		$this->formHash = $formHash;
	}

	public function hasErrors() {
		return ! empty( $this->globalErrors ) || ! empty( $this->fieldErrors );
	}

	public function hasFieldErrors( $field = null ) {
		if ( is_null( $field ) ) {
			return ! empty( $this->fieldErrors );
		} else {
			return array_key_exists( $field, $this->fieldErrors );
		}
	}

	public function addFieldError( $fieldName, $fieldId, $error ) {
		if ( is_null( $fieldName ) ) {
			return;
		}
		if ( ! array_key_exists( $fieldName, $this->fieldErrors ) ) {
			$this->fieldErrors[ $fieldName ] = array();
		}
		array_push(
			$this->fieldErrors[ $fieldName ],
			array(
				'id'      => $fieldId,
				'name'    => $fieldName,
				'message' => $error
			)
		);
	}

	public function getFieldErrors( $field = null ) {
		if ( is_null( $field ) ) {
			$fieldErrors = array();
			foreach ( array_values( $this->fieldErrors ) as $errors ) {
				$fieldErrors = array_merge( $fieldErrors, $errors );
			}

			return $fieldErrors;
		}
		if ( array_key_exists( $field, $this->fieldErrors ) ) {
			return $this->fieldErrors[ $field ];
		} else {
			return array();
		}
	}

	public function getGlobalErrors() {
		return $this->globalErrors;
	}

	public function hasGlobalErrors() {
		return ! empty( $this->globalErrors );
	}

	public function addGlobalError( $error ) {
		array_push( $this->globalErrors, $error );
	}

}

abstract class MM_WPFS_Admin_FormModel implements MM_WPFS_Binder {

	use MM_WPFS_Model;

	const PARAM_FORM_ID = 'formID';
	const PARAM_FORM_NAME = 'form_name';
	const PARAM_FORM_BUTTON_TEXT = 'form_button_text';
	const PARAM_FORM_INCLUDE_CUSTOM_INPUT = 'form_include_custom_input';
	const PARAM_FORM_CUSTOM_INPUT_REQUIRED = 'form_custom_input_required';
	const PARAM_CUSTOM_INPUTS = 'customInputs';
	const PARAM_FORM_DO_REDIRECT = 'form_do_redirect';
	const PARAM_FORM_REDIRECT_TO = 'form_redirect_to';
	const PARAM_FORM_REDIRECT_PAGE_OR_POST_ID = 'form_redirect_page_or_post_id';
	const PARAM_FORM_REDIRECT_URL = 'form_redirect_url';
	const PARAM_SHOW_DETAILED_SUCCESS_PAGE = 'showDetailedSuccessPage';
	const PARAM_STRIPE_DESCRIPTION = 'stripe_description';
	const PARAM_SHOW_TERMS_OF_USE = 'show_terms_of_use';
	const PARAM_TERMS_OF_USE_LABEL = 'terms_of_use_label';
	const PARAM_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE = 'terms_of_use_not_checked_error_message';
	const PARAM_FORM_SEND_EMAIL_RECEIPT = 'form_send_email_receipt';

	const REDIRECT_TO_PAGE_OR_POST = 'page_or_post';
	const REDIRECT_TO_URL = 'url';

	protected $id;
	protected $name;
	protected $buttonTitle;
	protected $showCustomInput;
	protected $customInputRequired;
	protected $customInputs;
	protected $doRedirect;
	/**
	 * @transient
	 */
	protected $redirectTo;
	protected $redirectPostID;
	protected $redirectUrl;
	protected $redirectToPageOrPost;
	protected $showDetailedSuccessPage;
	protected $stripeDescription;
	protected $showTermsOfUse;
	protected $termsOfUseLabel;
	protected $termsOfUseNotCheckedErrorMessage;
	protected $sendEmailReceipt;

	public function bind() {

		$bindingResult = new MM_WPFS_BindingResult();

		$this->id                  = $this->getSanitizedPostParam( self::PARAM_FORM_ID );
		$this->name                = $this->getSanitizedPostParam( self::PARAM_FORM_NAME );
		$this->buttonTitle         = $this->getSanitizedPostParam( self::PARAM_FORM_BUTTON_TEXT );
		$this->showCustomInput     = $this->getSanitizedPostParam( self::PARAM_FORM_INCLUDE_CUSTOM_INPUT );
		$this->customInputRequired = $this->getSanitizedPostParam( self::PARAM_FORM_CUSTOM_INPUT_REQUIRED, 0 );
		if ( $this->showCustomInput == 0 ) {
			$this->customInputRequired = 0;
		}
		$this->customInputs         = $this->getSanitizedPostParam( self::PARAM_CUSTOM_INPUTS );
		$this->doRedirect           = $this->getSanitizedPostParam( self::PARAM_FORM_DO_REDIRECT );
		$this->redirectTo           = $this->getSanitizedPostParam( self::PARAM_FORM_REDIRECT_TO );
		$this->redirectPostID       = $this->getSanitizedPostParam( self::PARAM_FORM_REDIRECT_PAGE_OR_POST_ID, 0 );
		$this->redirectUrl          = $this->getSanitizedPostParam( self::PARAM_FORM_REDIRECT_URL );
		$this->redirectUrl          = MM_WPFS_Utils::add_http_prefix( $this->redirectUrl );
		$this->redirectToPageOrPost = 1;
		if ( self::REDIRECT_TO_PAGE_OR_POST === $this->redirectTo ) {
			$this->redirectToPageOrPost = 1;
		} else if ( self::REDIRECT_TO_URL === $this->redirectTo ) {
			$this->redirectToPageOrPost = 0;
		}
		$this->showDetailedSuccessPage          = $this->getSanitizedPostParam( self::PARAM_SHOW_DETAILED_SUCCESS_PAGE, 0 );
		$this->stripeDescription                = $this->getSanitizedPostParam( self::PARAM_STRIPE_DESCRIPTION );
		$this->showTermsOfUse                   = $this->getSanitizedPostParam( self::PARAM_SHOW_TERMS_OF_USE, 0 );
		$this->termsOfUseLabel                  = $this->getPostParam( self::PARAM_TERMS_OF_USE_LABEL );
		$this->termsOfUseNotCheckedErrorMessage = $this->getSanitizedPostParam( self::PARAM_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE );
		$this->sendEmailReceipt                 = $this->getSanitizedPostParam( self::PARAM_FORM_SEND_EMAIL_RECEIPT, 0 );

		return $bindingResult;
	}

	public function getData() {

		$data = array(
			'name'                             => $this->name,
			'buttonTitle'                      => $this->buttonTitle,
			'showCustomInput'                  => $this->showCustomInput,
			'customInputRequired'              => $this->customInputRequired,
			'customInputs'                     => $this->customInputs,
			'redirectOnSuccess'                => $this->doRedirect,
			'redirectPostID'                   => $this->redirectPostID,
			'redirectUrl'                      => $this->redirectUrl,
			'redirectToPageOrPost'             => $this->redirectToPageOrPost,
			'showDetailedSuccessPage'          => $this->showDetailedSuccessPage,
			'stripeDescription'                => $this->stripeDescription,
			'showTermsOfUse'                   => $this->showTermsOfUse,
			'termsOfUseLabel'                  => $this->termsOfUseLabel,
			'termsOfUseNotCheckedErrorMessage' => $this->termsOfUseNotCheckedErrorMessage,
			'sendEmailReceipt'                 => $this->sendEmailReceipt
		);

		return $data;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

}

abstract class MM_WPFS_Admin_PaymentFormModel extends MM_WPFS_Admin_FormModel {

	const PARAM_FORM_CURRENCY = 'form_currency';
	const PARAM_FORM_AMOUNT = 'form_amount';
	const PARAM_FORM_CUSTOM = 'form_custom';
	const PARAM_PAYMENT_AMOUNT_VALUES = 'payment_amount_values';
	const PARAM_PAYMENT_AMOUNT_DESCRIPTIONS = 'payment_amount_descriptions';
	const PARAM_ALLOW_CUSTOM_PAYMENT_AMOUNT = 'allow_custom_payment_amount';
	const PARAM_FORM_CHARGE_TYPE = 'form_charge_type';
	const PARAM_FORM_BUTTON_AMOUNT = 'form_button_amount';
	const PARAM_AMOUNT_SELECTOR_STYLE = 'amount_selector_style';

	protected $currency;
	protected $amount;
	protected $custom;
	protected $listOfAmounts;
	protected $allowListOfAmountCustom;
	protected $chargeType;
	protected $showButtonAmount;
	protected $amountSelectorStyle;

	public function bind() {

		$bindingResult = parent::bind();

		$this->currency                = $this->getSanitizedPostParam( self::PARAM_FORM_CURRENCY );
		$this->amount                  = $this->getSanitizedPostParam( self::PARAM_FORM_AMOUNT, 0 );
		$this->custom                  = $this->getSanitizedPostParam( self::PARAM_FORM_CUSTOM );
		$this->listOfAmounts           = null;
		$this->allowListOfAmountCustom = 0;
		if ( $this->custom == MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS ) {
			$listOfAmounts             = array();
			$paymentAmountValues       = explode( ',', $this->getSanitizedPostParam( self::PARAM_PAYMENT_AMOUNT_VALUES ) );
			$paymentAmountDescriptions = explode( ',', $this->getURLDecodedPostParam( self::PARAM_PAYMENT_AMOUNT_DESCRIPTIONS ) );
			for ( $i = 0; $i < count( $paymentAmountValues ); $i ++ ) {
				$listElement = array( $paymentAmountValues[ $i ], $paymentAmountDescriptions[ $i ] );
				array_push( $listOfAmounts, $listElement );
			}
			$this->listOfAmounts           = json_encode( $listOfAmounts );
			$this->allowListOfAmountCustom = $this->getSanitizedPostParam( self::PARAM_ALLOW_CUSTOM_PAYMENT_AMOUNT, 0 );
		}
		$this->chargeType          = $this->getSanitizedPostParam( self::PARAM_FORM_CHARGE_TYPE, MM_WPFS::CHARGE_TYPE_IMMEDIATE );
		$this->showButtonAmount    = $this->getSanitizedPostParam( self::PARAM_FORM_BUTTON_AMOUNT );
		$this->amountSelectorStyle = $this->getSanitizedPostParam( self::PARAM_AMOUNT_SELECTOR_STYLE, MM_WPFS::AMOUNT_SELECTOR_STYLE_DROPDOWN );

		return $bindingResult;
	}

	public function getData() {

		$parentData = parent::getData();

		$data = array(
			'currency'                 => $this->currency,
			'amount'                   => $this->amount,
			'customAmount'             => $this->custom,
			'listOfAmounts'            => $this->listOfAmounts,
			'allowListOfAmountsCustom' => $this->allowListOfAmountCustom,
			'chargeType'               => $this->chargeType,
			'showButtonAmount'         => $this->showButtonAmount,
			'amountSelectorStyle'      => $this->amountSelectorStyle
		);

		$data = array_merge( $data, $parentData );

		return $data;
	}


	/**
	 * Updates properties to act as a saved card form
	 */
	public function convertToCardCaptureForm() {
		$this->currency                = MM_WPFS::CURRENCY_USD;
		$this->amount                  = 0;
		$this->custom                  = MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE;
		$this->listOfAmounts           = null;
		$this->allowListOfAmountCustom = 0;
		$this->stripeDescription       = null;
		$this->showButtonAmount        = 0;
		$this->amountSelectorStyle     = MM_WPFS::AMOUNT_SELECTOR_STYLE_DROPDOWN;
	}

}

abstract class MM_WPFS_Admin_SubscriptionFormModel extends MM_WPFS_Admin_FormModel {

	const PARAM_FORM_INCLUDE_COUPON_INPUT = 'form_include_coupon_input';
	const PARAM_PLAN_ORDER = 'plan_order';
	const PARAM_SELECTED_PLANS = 'selected_plans';
	const PARAM_FORM_VAT_RATE_TYPE = 'form_vat_rate_type';
	const PARAM_FORM_VAT_PERCENT = 'form_vat_percent';
	const PARAM_FORM_DEFAULT_BILLING_COUNTRY = 'form_default_billing_country';
	const PARAM_PLAN_SELECTOR_STYLE = 'plan_selector_style';

	protected $includeCouponInput;
	protected $plans;
	protected $vatRateType;
	protected $vatPercent;
	protected $defaultBillingCountry;
	protected $planSelectorStyle;

	public function bind() {

		$bindingResult = parent::bind();

		$this->includeCouponInput = $this->getSanitizedPostParam( self::PARAM_FORM_INCLUDE_COUPON_INPUT );
		$planOrder                = $this->getJSONDecodedPostParam( self::PARAM_PLAN_ORDER );
		$selectedPlans            = $this->getJSONDecodedPostParam( self::PARAM_SELECTED_PLANS );
		$this->plans              = json_encode( $this->orderPlans( $selectedPlans, $planOrder ) );
		$this->vatRateType        = $this->getSanitizedPostParam( self::PARAM_FORM_VAT_RATE_TYPE );
		$this->vatPercent         = $this->getSanitizedPostParam( self::PARAM_FORM_VAT_PERCENT, 0.0 );
		if ( $this->vatRateType != MM_WPFS::VAT_RATE_TYPE_FIXED_VAT ) {
			$this->vatPercent = 0.0;
		}
		$this->defaultBillingCountry = $this->getSanitizedPostParam( self::PARAM_FORM_DEFAULT_BILLING_COUNTRY );
		$this->planSelectorStyle     = $this->getSanitizedPostParam( self::PARAM_PLAN_SELECTOR_STYLE );

		return $bindingResult;
	}

	protected function orderPlans( $selectedPlansArray, $planOrderArray ) {
		$orderedPlans = array();
		if ( count( $selectedPlansArray ) > 0 ) {
			foreach ( $planOrderArray as $plan ) {
				if ( in_array( $plan, $selectedPlansArray ) ) {
					$orderedPlans[] = $plan;
				}
			}
		}

		return $orderedPlans;
	}

	public function getData() {
		$parentData = parent::getData();

		$data = array(
			'showCouponInput'       => $this->includeCouponInput,
			'plans'                 => $this->plans,
			'vatRateType'           => $this->vatRateType,
			'vatPercent'            => $this->vatPercent,
			'defaultBillingCountry' => $this->defaultBillingCountry,
			'planSelectorStyle'     => $this->planSelectorStyle
		);

		$data = array_merge( $data, $parentData );

		return $data;
	}

}

class MM_WPFS_Admin_InlinePaymentFormModel extends MM_WPFS_Admin_PaymentFormModel implements MM_WPFS_Admin_InlineForm {

	use MM_WPFS_Admin_InlineFormModel;

	const PARAM_FORM_SHOW_EMAIL_INPUT = 'form_show_email_input';
	const PARAM_FORM_STYLE = 'form_style';

	protected $showEmailInput;
	protected $formStyle;

	public function bind() {

		$bindingResult = parent::bind();

		$this->bindInlineParams();

		$this->showEmailInput = $this->getSanitizedPostParam( self::PARAM_FORM_SHOW_EMAIL_INPUT, 1 );
		$this->formStyle      = $this->getSanitizedPostParam( self::PARAM_FORM_STYLE );

		return $bindingResult;
	}

	public function getData() {

		$parentData = parent::getData();

		$inlineData = $this->getInlineDataArray();

		$data = array(
			'showEmailInput' => $this->showEmailInput,
			'formStyle'      => $this->formStyle
		);

		$data = array_merge( $data, $inlineData, $parentData );

		return $data;
	}

}

class MM_WPFS_Admin_PopupPaymentFormModel extends MM_WPFS_Admin_PaymentFormModel {

	use MM_WPFS_Admin_PopupFormModel;

	const PARAM_FORM_USE_BITCOIN = 'form_use_bitcoin';
	const PARAM_FORM_USE_ALIPAY = 'form_use_alipay';

	protected $useBitcoin;
	protected $useAlipay;

	public function bind() {

		$bindingResult = parent::bind();

		$this->bindPopup();

		$this->useBitcoin = 0; // $this->getNumericPostParam( MM_WPFS_PopupForm::PARAM_FORM_USE_BITCOIN, 0 );
		$this->useAlipay  = 0; // $this->getNumericPostParam( MM_WPFS_PopupForm::PARAM_FORM_USE_ALIPAY, 0 );

		if ( MM_WPFS::CURRENCY_USD !== $this->currency ) {
			$this->useAlipay  = 0;
			$this->useBitcoin = 0;
		}

		return $bindingResult;
	}

	public function getData() {

		$parentData = parent::getData();

		$popupData = $this->getPopupData();

		$data = array(
			'useBitcoin' => $this->useBitcoin,
			'useAlipay'  => $this->useAlipay
		);

		$data = array_merge( $data, $popupData, $parentData );

		return $data;
	}
}

class MM_WPFS_Admin_InlineSubscriptionFormModel extends MM_WPFS_Admin_SubscriptionFormModel implements MM_WPFS_Admin_InlineForm {

	use MM_WPFS_Admin_InlineFormModel;

	public function bind() {

		$bindingResult = parent::bind();

		$this->bindInlineParams();

		if ( 0 == $this->showAddress ) {
			$this->defaultBillingCountry = null;
		}

		return $bindingResult;
	}

	public function getData() {

		$parentData = parent::getData();

		$inlineData = $this->getInlineDataArray();

		$data = array();

		$data = array_merge( $data, $inlineData, $parentData );

		return $data;
	}
}

class MM_WPFS_Admin_PopupSubscriptionFormModel extends MM_WPFS_Admin_SubscriptionFormModel implements MM_WPFS_Admin_PopupForm {

	use MM_WPFS_Admin_PopupFormModel;

	const PARAM_FORM_SIMPLE_BUTTON_LAYOUT = 'form_simple_button_layout';

	protected $simpleButtonLayout;

	public function bind() {

		$bindingResult = parent::bind();

		$this->bindPopup();

		if ( $this->showBillingAddress == 0 ) {
			$this->defaultBillingCountry = null;
		}
		$this->simpleButtonLayout = $this->getSanitizedPostParam( self::PARAM_FORM_SIMPLE_BUTTON_LAYOUT, 0 );

		return $bindingResult;
	}

	public function getData() {

		$parentData = parent::getData();

		$popupData = $this->getPopupData();

		$data = array(
			'simpleButtonLayout' => $this->simpleButtonLayout
		);

		$data = array_merge( $data, $popupData, $parentData );

		return $data;
	}

}

abstract class MM_WPFS_Public_FormModel implements MM_WPFS_Binder {

	use MM_WPFS_Model;

	const ARRAY_KEY_ADDRESS_LINE_1 = 'line1';
	const ARRAY_KEY_ADDRESS_LINE_2 = 'line2';
	const ARRAY_KEY_ADDRESS_CITY = 'city';
	const ARRAY_KEY_ADDRESS_STATE = 'state';
	const ARRAY_KEY_ADDRESS_COUNTRY = 'country';
	const ARRAY_KEY_ADDRESS_COUNTRY_CODE = 'country_code';
	const ARRAY_KEY_ADDRESS_ZIP = 'zip';

	const PARAM_WPFS_FORM_NAME = 'wpfs-form-name';
	const PARAM_WPFS_FORM_ACTION = 'action';
	const PARAM_WPFS_STRIPE_TOKEN = 'wpfs-stripe-token';
	const PARAM_WPFS_CARD_HOLDER_NAME = 'wpfs-card-holder-name';
	const PARAM_WPFS_CARD_HOLDER_EMAIL = 'wpfs-card-holder-email';
	const PARAM_WPFS_CUSTOM_INPUT = 'wpfs-custom-input';
	const PARAM_WPFS_BILLING_NAME = 'wpfs-billing-name';
	const PARAM_WPFS_BILLING_ADDRESS_LINE_1 = 'wpfs-billing-address-line-1';
	const PARAM_WPFS_BILLING_ADDRESS_LINE_2 = 'wpfs-billing-address-line-2';
	const PARAM_WPFS_BILLING_ADDRESS_CITY = 'wpfs-billing-address-city';
	const PARAM_WPFS_BILLING_ADDRESS_STATE = 'wpfs-billing-address-state';
	const PARAM_WPFS_BILLING_ADDRESS_ZIP = 'wpfs-billing-address-zip';
	const PARAM_WPFS_BILLING_ADDRESS_COUNTRY = 'wpfs-billing-address-country';
	const PARAM_WPFS_SHIPPING_NAME = 'wpfs-shipping-name';
	const PARAM_WPFS_SHIPPING_ADDRESS_LINE_1 = 'wpfs-shipping-address-line-1';
	const PARAM_WPFS_SHIPPING_ADDRESS_LINE_2 = 'wpfs-shipping-address-line-2';
	const PARAM_WPFS_SHIPPING_ADDRESS_CITY = 'wpfs-shipping-address-city';
	const PARAM_WPFS_SHIPPING_ADDRESS_STATE = 'wpfs-shipping-address-state';
	const PARAM_WPFS_SHIPPING_ADDRESS_ZIP = 'wpfs-shipping-address-zip';
	const PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY = 'wpfs-shipping-address-country';
	const PARAM_WPFS_TERMS_OF_USE_ACCEPTED = 'wpfs-terms-of-use-accepted';
	const PARAM_GOOGLE_RECAPTCHA_RESPONSE = 'g-recaptcha-response';

	protected $action;
	protected $formName;
	protected $stripeToken;
	protected $cardHolderName;
	protected $cardHolderEmail;
	protected $cardHolderPhone;
	protected $customInputValues;
	protected $billingName;
	protected $billingAddressLine1;
	protected $billingAddressLine2;
	protected $billingAddressCity;
	protected $billingAddressState;
	protected $billingAddressZip;
	protected $billingAddressCountry;
	protected $shippingName;
	protected $shippingAddressLine1;
	protected $shippingAddressLine2;
	protected $shippingAddressCity;
	protected $shippingAddressState;
	protected $shippingAddressZip;
	protected $shippingAddressCountry;
	protected $termsOfUseAccepted;
	protected $googleReCaptchaResponse;

	protected $__form;
	protected $__formHash;
	protected $__billingAddressCountryComposite;
	protected $__billingAddressCountryName;
	protected $__billingAddressCountryCode;
	protected $__shippingAddressCountryComposite;
	protected $__shippingAddressCountryName;
	protected $__shippingAddressCountryCode;
	protected $__stripeCustomer;
	protected $__productName;

	protected $__dao;

	/**
	 * MM_WPFS_Public_FormModel constructor.
	 */
	public function __construct() {
		$this->__dao = new MM_WPFS_Database();
	}

	public function bind() {

		$bindingResult = new MM_WPFS_BindingResult();

		$this->action                  = $this->getSanitizedPostParam( self::PARAM_WPFS_FORM_ACTION );
		$this->formName                = $this->getSanitizedPostParam( self::PARAM_WPFS_FORM_NAME );
		$this->stripeToken             = $this->getSanitizedPostParam( self::PARAM_WPFS_STRIPE_TOKEN );
		$this->cardHolderName          = $this->getSanitizedPostParam( self::PARAM_WPFS_CARD_HOLDER_NAME );
		$this->cardHolderEmail         = $this->getSanitizedPostParam( self::PARAM_WPFS_CARD_HOLDER_EMAIL, null, MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL );
		$this->cardHolderPhone         = null;
		$this->customInputValues       = $this->getSanitizedPostParam( self::PARAM_WPFS_CUSTOM_INPUT );
		$this->billingName             = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_NAME );
		$this->billingAddressLine1     = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_LINE_1, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressLine2     = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_LINE_2, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressCity      = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_CITY, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressState     = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_STATE, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressZip       = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_ZIP, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressCountry   = $this->getSanitizedPostParam( self::PARAM_WPFS_BILLING_ADDRESS_COUNTRY, MM_WPFS_Binder::EMPTY_STR );
		$this->shippingName            = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_NAME );
		$this->shippingAddressLine1    = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_1 );
		$this->shippingAddressLine2    = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_2 );
		$this->shippingAddressCity     = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_CITY );
		$this->shippingAddressState    = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_STATE );
		$this->shippingAddressZip      = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_ZIP );
		$this->shippingAddressCountry  = $this->getSanitizedPostParam( self::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY );
		$this->termsOfUseAccepted      = $this->getSanitizedPostParam( self::PARAM_WPFS_TERMS_OF_USE_ACCEPTED, 0 );
		$this->googleReCaptchaResponse = $this->getSanitizedPostParam( self::PARAM_GOOGLE_RECAPTCHA_RESPONSE );

		if ( isset( $this->cardHolderName ) && ! empty( $this->cardHolderName ) ) {
			if ( ! isset( $this->billingName ) || empty( $this->billingName ) ) {
				$this->billingName = $this->cardHolderName;
			}
			if ( ! isset( $this->shippingName ) || empty( $this->shippingName ) ) {
				$this->shippingName = $this->cardHolderName;
			}
		} else {
			if ( isset( $this->billingName ) && ! empty( $this->billingName ) ) {
				$this->cardHolderName = $this->billingName;
			} elseif ( isset( $this->shippingName ) && ! empty( $this->shippingName ) ) {
				$this->cardHolderName = $this->shippingName;
			}
		}

		if ( isset( $this->billingAddressCountry ) ) {
			$this->__billingAddressCountryComposite = MM_WPFS_Countries::get_country_by_code( $this->billingAddressCountry );
			if ( isset( $this->__billingAddressCountryComposite ) ) {
				$this->__billingAddressCountryName = $this->__billingAddressCountryComposite['name'];
				$this->__billingAddressCountryCode = $this->__billingAddressCountryComposite['alpha-2'];
			}
		}
		if ( isset( $this->shippingAddressCountry ) ) {
			$this->__shippingAddressCountryComposite = MM_WPFS_Countries::get_country_by_code( $this->shippingAddressCountry );
			if ( isset( $this->__shippingAddressCountryComposite ) ) {
				$this->__shippingAddressCountryName = $this->__shippingAddressCountryComposite['name'];
				$this->__shippingAddressCountryCode = $this->__shippingAddressCountryComposite['alpha-2'];
			}
		}

		return $bindingResult;
	}

	/**
	 * @return MM_WPFS_Database
	 */
	public function getDAO() {
		return $this->__dao;
	}

	public function getData() {
		$data = array();

		return $data;
	}

	/**
	 * @return mixed
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @return mixed
	 */
	public function getFormName() {
		return $this->formName;
	}

	/**
	 * @return mixed
	 */
	public function getStripeToken() {
		return $this->stripeToken;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderName() {
		return $this->cardHolderName;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderEmail() {
		return $this->cardHolderEmail;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderPhone() {
		return $this->cardHolderPhone;
	}

	/**
	 * @return mixed
	 */
	public function getCustomInputvalues() {
		return $this->customInputValues;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressLine1() {
		return $this->billingAddressLine1;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressLine2() {
		return $this->billingAddressLine2;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCity() {
		return $this->billingAddressCity;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressState() {
		return $this->billingAddressState;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressZip() {
		return $this->billingAddressZip;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountry() {
		return $this->billingAddressCountry;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressLine1() {
		return $this->shippingAddressLine1;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressLine2() {
		return $this->shippingAddressLine2;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCity() {
		return $this->shippingAddressCity;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressState() {
		return $this->shippingAddressState;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressZip() {
		return $this->shippingAddressZip;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountry() {
		return $this->shippingAddressCountry;
	}

	/**
	 * @return mixed
	 */
	public function getTermsOfUseAccepted() {
		return $this->termsOfUseAccepted;
	}

	/**
	 * @return mixed
	 */
	public function getGoogleReCaptchaResponse() {
		return $this->googleReCaptchaResponse;
	}

	/**
	 * @return mixed
	 */
	public function getForm() {
		return $this->__form;
	}

	/**
	 * @param mixed $form
	 */
	public function setForm( $form ) {
		$this->__form = $form;
		$this->prepareFormHash();
	}

	protected function prepareFormHash() {
		$formType = MM_WPFS_Utils::getFormType( $this->__form );
		$formId   = MM_WPFS_Utils::getFormId( $this->__form );
		$formName = $this->__form->name;
		$this->setFormHash(
			esc_attr(
				MM_WPFS_Utils::generate_form_hash(
					$formType,
					$formId,
					$formName
				)
			)
		);
	}

	/**
	 * @return mixed
	 */
	public function getFormHash() {
		return $this->__formHash;
	}

	/**
	 * @param mixed $formHash
	 */
	public function setFormHash( $formHash ) {
		$this->__formHash = $formHash;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountryComposite() {
		return $this->__billingAddressCountryComposite;
	}

	/**
	 * @param mixed $billing_address_country_composite
	 */
	public function setBillingAddressCountryComposite( $billing_address_country_composite ) {
		$this->__billingAddressCountryComposite = $billing_address_country_composite;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountryName() {
		return $this->__billingAddressCountryName;
	}

	/**
	 * @param mixed $billing_address_country_name
	 */
	public function setBillingAddressCountryName( $billing_address_country_name ) {
		$this->__billingAddressCountryName = $billing_address_country_name;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountryComposite() {
		return $this->__shippingAddressCountryComposite;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountryName() {
		return $this->__shippingAddressCountryName;
	}

	/**
	 * @return array
	 */
	public function getMetadata() {
		$metadata = array();

		if ( isset( $this->cardHolderEmail ) ) {
			$metadata['customer_email'] = $this->cardHolderEmail;
		}
		if ( isset( $this->cardHolderName ) ) {
			$metadata['customer_name'] = $this->cardHolderName;
		}
		if ( isset( $this->formName ) ) {
			$metadata['form_name'] = $this->formName;
		}
		if (
			( isset( $this->__form->showAddress ) && 1 == $this->__form->showAddress )
			|| ( isset( $this->__form->showBillingAddress ) && 1 == $this->__form->showBillingAddress )
		) {
			if ( isset( $this->billingName ) ) {
				$metadata['billing_name'] = $this->billingName;
			}
			if ( isset( $this->billingAddressLine1 ) || isset( $this->billingAddressZip ) || isset( $this->billingAddressCity ) || isset( $this->billingAddressCountry ) ) {
				$metadata['billing_address'] = implode( '|', array(
					$this->billingAddressLine1,
					$this->billingAddressLine2,
					$this->billingAddressZip,
					$this->billingAddressCity,
					$this->billingAddressState,
					$this->__billingAddressCountryName,
					$this->__billingAddressCountryCode
				) );
			}
		}
		if ( isset( $this->__form->showShippingAddress ) && 1 == $this->__form->showShippingAddress ) {
			if ( isset( $this->shippingName ) ) {
				$metadata['shipping_name'] = $this->shippingName;
			}
			if ( isset( $this->shippingAddressLine1 ) || isset( $this->shippingAddressZip ) || isset( $this->shippingAddressCity ) || isset( $this->shippingAddressCountry ) ) {
				$metadata['shipping_address'] = implode( '|', array(
					$this->shippingAddressLine1,
					$this->shippingAddressLine2,
					$this->shippingAddressZip,
					$this->shippingAddressCity,
					$this->shippingAddressState,
					$this->__shippingAddressCountryName,
					$this->__shippingAddressCountryCode
				) );
			}
		}
		if ( is_null( $this->__form->customInputs ) ) {
			$customInputValueString = is_array( $this->customInputValues ) ? implode( ",", $this->customInputValues ) : printf( $this->customInputValues );
			if ( ! empty( $customInputValueString ) ) {
				$metadata['custom_inputs'] = $customInputValueString;
			}
		} else {
			$customInputLabels = $this->getDecodedCustomInputLabels();
			foreach ( $customInputLabels as $i => $label ) {
				$key = $label;
				if ( array_key_exists( $key, $metadata ) ) {
					$key = $label . $i;
				}
				if ( ! empty( $this->customInputValues[ $i ] ) ) {
					$metadata[ $key ] = $this->customInputValues[ $i ];
				}
			}
		}

		return $metadata;
	}

	/**
	 * @return array
	 */
	public function getDecodedCustomInputLabels() {
		$customInputLabels = array();
		if ( isset( $this->__form->customInputs ) ) {
			$customInputLabels = explode( '{{', $this->__form->customInputs );
		}

		return $customInputLabels;
	}

	/**
	 * @param mixed $stripeCustomer
	 */
	public function setStripeCustomer( $stripeCustomer ) {
		$this->__stripeCustomer = $stripeCustomer;
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
	 * @param bool $mayReturnNull
	 *
	 * @return array
	 */
	public function getBillingAddress( $mayReturnNull = true ) {
		return $this->getAddressArray(
			$mayReturnNull,
			$this->billingAddressLine1,
			$this->billingAddressLine2,
			$this->billingAddressCity,
			$this->billingAddressState,
			$this->__billingAddressCountryName,
			$this->__billingAddressCountryCode,
			$this->billingAddressZip
		);
	}

	/**
	 * @param $mayReturnNull
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
	protected function getAddressArray( $mayReturnNull, $line1, $line2, $city, $state, $countryName, $countryCode, $zip ) {
		$addressData = array(
			self::ARRAY_KEY_ADDRESS_LINE_1       => is_null( $line1 ) ? '' : $line1,
			self::ARRAY_KEY_ADDRESS_LINE_2       => is_null( $line2 ) ? '' : $line2,
			self::ARRAY_KEY_ADDRESS_CITY         => is_null( $city ) ? '' : $city,
			self::ARRAY_KEY_ADDRESS_STATE        => is_null( $state ) ? '' : $state,
			self::ARRAY_KEY_ADDRESS_COUNTRY      => is_null( $countryName ) ? '' : $countryName,
			self::ARRAY_KEY_ADDRESS_COUNTRY_CODE => is_null( $countryCode ) ? '' : $countryCode,
			self::ARRAY_KEY_ADDRESS_ZIP          => is_null( $zip ) ? '' : $zip
		);
		if ( $mayReturnNull ) {
			$hasNotEmptyValue = false;
			foreach ( $addressData as $key => $value ) {
				if ( $value !== '' ) {
					$hasNotEmptyValue = true;
				}
			}
			if ( $hasNotEmptyValue ) {
				return $addressData;
			} else {
				return null;
			}
		}

		return $addressData;
	}

	/**
	 * @param bool $mayReturnNull
	 *
	 * @return array
	 */
	public function getShippingAddress( $mayReturnNull = true ) {
		return $this->getAddressArray(
			$mayReturnNull,
			$this->shippingAddressLine1,
			$this->shippingAddressLine2,
			$this->shippingAddressCity,
			$this->shippingAddressState,
			$this->__shippingAddressCountryName,
			$this->__shippingAddressCountryCode,
			$this->shippingAddressZip
		);
	}

	/**
	 * @return mixed
	 */
	public function getProductName() {
		return $this->__productName;
	}

	/**
	 * @param mixed $productName
	 */
	public function setProductName( $productName ) {
		$this->__productName = $productName;
	}

	/**
	 * @deprecated
	 *
	 * @param $stripeCustomer
	 *
	 * @return null
	 */
	protected function retrieveStripeCustomerName( $stripeCustomer ) {
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

}

abstract class MM_WPFS_Public_PaymentFormModel extends MM_WPFS_Public_FormModel {

	const PARAM_WPFS_CUSTOM_AMOUNT_INDEX = 'wpfs-custom-amount-index';
	const PARAM_WPFS_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';
	const CUSTOM_AMOUNT_LABEL_MACRO_AMOUNT = '{amount}';
	const INITIAL_CUSTOM_AMOUNT_INDEX = - 1;
	protected $customAmountIndex;
	protected $customAmountValue;
	protected $customAmountUniqueValue;
	protected $__amount;

	/**
	 * MM_WPFS_Public_PaymentFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function bind() {
		$bindingResult                 = parent::bind();
		$this->customAmountIndex       = $this->getSanitizedPostParam( self::PARAM_WPFS_CUSTOM_AMOUNT_INDEX, self::INITIAL_CUSTOM_AMOUNT_INDEX );
		$this->customAmountValue       = $this->getSanitizedPostParam( self::PARAM_WPFS_CUSTOM_AMOUNT );
		$this->customAmountUniqueValue = $this->getSanitizedPostParam( self::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE );

		if ( isset( $this->__validator ) ) {
			$this->__validator->validate( $bindingResult, $this );
		}

		return $bindingResult;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountIndex() {
		return $this->customAmountIndex;
	}

	/**
	 * @param mixed $customAmountIndex
	 */
	public function setCustomAmountIndex( $customAmountIndex ) {
		$this->customAmountIndex = $customAmountIndex;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountValue() {
		return $this->customAmountValue;
	}

	/**
	 * @param mixed $customAmountValue
	 */
	public function setCustomAmountValue( $customAmountValue ) {
		$this->customAmountValue = $customAmountValue;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountUniqueValue() {
		return $this->customAmountUniqueValue;
	}

	/**
	 * @param mixed $customAmountUniqueValue
	 */
	public function setCustomAmountUniqueValue( $customAmountUniqueValue ) {
		$this->customAmountUniqueValue = $customAmountUniqueValue;
	}

	/**
	 * @return mixed
	 */
	public function getAmount() {
		return $this->__amount;
	}

	/**
	 * @param mixed $amount
	 */
	public function setAmount( $amount ) {
		$this->__amount = $amount;
	}

	public function setForm( $form ) {
		parent::setForm( $form );
		$this->prepareAmountAndProductName();
	}

	protected function prepareAmountAndProductName() {
		$this->__amount = null;
		if ( isset( $this->__form->productDesc ) ) {
			$this->__productName = esc_attr( $this->__form->productDesc );
		} else {
			$this->__productName = '';
		}
		if ( MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT === $this->__form->customAmount ) {
			$this->__amount = $this->__form->amount;
		} elseif ( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS === $this->__form->customAmount ) {
			if ( 1 == $this->__form->allowListOfAmountsCustom && 'other' === $this->customAmountValue ) {
				$this->__amount = MM_WPFS_Utils::parse_amount( $this->__form->currency, $this->customAmountUniqueValue );
			} else {
				$listOfAmounts = json_decode( $this->__form->listOfAmounts );
				if ( isset( $this->customAmountIndex ) && $this->customAmountIndex > self::INITIAL_CUSTOM_AMOUNT_INDEX && count( $listOfAmounts ) > $this->customAmountIndex ) {
					$customAmountElement                 = $listOfAmounts[ $this->customAmountIndex ];
					$customAmountAmount                  = $customAmountElement[0];
					$customAmountElementDescription      = $customAmountElement[1];
					$customAmountElementAmountLabel      = MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $this->__form->currency, $customAmountAmount );
					$customAmountElementDescriptionLabel = MM_WPFS_Utils::translate_label( $customAmountElementDescription );
					if ( strpos( $customAmountElementDescription, self::CUSTOM_AMOUNT_LABEL_MACRO_AMOUNT ) !== false ) {
						$customAmountElementDescriptionLabel = str_replace( self::CUSTOM_AMOUNT_LABEL_MACRO_AMOUNT, $customAmountElementAmountLabel, $customAmountElementDescriptionLabel );
					}
					$this->__amount      = $customAmountAmount;
					$this->__productName = $customAmountElementDescriptionLabel;
				}
			}
		} elseif ( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $this->__form->customAmount ) {
			$this->__amount = MM_WPFS_Utils::parse_amount( $this->__form->currency, $this->customAmountUniqueValue );
		}
	}

}

abstract class MM_WPFS_Public_SubscriptionFormModel extends MM_WPFS_Public_FormModel {

	const PARAM_WPFS_STRIPE_PLAN = 'wpfs-plan';
	const PARAM_WPFS_COUPON = 'wpfs-coupon';
	const PARAM_WPFS_AMOUNT_WITH_COUPON_APPLIED = 'wpfs-amount-with-coupon-applied';

	protected $__stripe;

	protected $stripePlanId;
	protected $couponCode;
	protected $amountWithCouponApplied;
	/**
	 * @var \Stripe\Plan
	 */
	protected $__stripePlan;
	protected $__stripePlanSetupFee;
	protected $__stripePlanAmount;
	/**
	 * @var \Stripe\Coupon
	 */
	protected $__stripeCoupon;
	protected $__stripeCouponDiscount = 0;

	/**
	 * MM_WPFS_Public_SubscriptionFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->__stripe = new MM_WPFS_Stripe();
	}

	public function bind() {
		$bindingResult                 = parent::bind();
		$this->stripePlanId            = $this->getHTMLDecodedPostParam( self::PARAM_WPFS_STRIPE_PLAN );
		$this->couponCode              = $this->getSanitizedPostParam( self::PARAM_WPFS_COUPON );
		$this->amountWithCouponApplied = $this->getNumericPostParam( self::PARAM_WPFS_AMOUNT_WITH_COUPON_APPLIED );
		$this->__productName           = '';

		$this->prepareStripeCouponAndPlan();

		if ( isset( $this->__validator ) ) {
			$this->__validator->validate( $bindingResult, $this );
		}

		return $bindingResult;
	}

	private function prepareStripeCouponAndPlan() {
		$this->prepareStripeCoupon();
		if ( isset( $this->stripePlanId ) ) {
			$this->__stripePlan         = $this->__stripe->retrieve_plan( $this->stripePlanId );
			$this->__productName        = $this->__stripePlan->name;
			$this->__stripePlanSetupFee = MM_WPFS_Utils::get_setup_fee_for_plan( $this->__stripePlan );
			if ( isset( $this->__stripeCoupon ) ) {
				if ( isset( $this->__stripeCoupon->percent_off ) ) {
					$percentOff                   = intval( $this->__stripeCoupon->percent_off ) / 100;
					$this->__stripeCouponDiscount = round( $this->__stripePlan->amount * $percentOff );
				} elseif ( isset( $this->__stripeCoupon->amount_off ) ) {
					if ( $this->__stripePlan->currency === $this->__stripeCoupon->currency ) {
						$this->__stripeCouponDiscount = intval( $this->__stripeCoupon->amount_off );
					}
				}
			}
			$this->__stripePlanAmount = $this->__stripePlan->amount - $this->__stripeCouponDiscount;
		}
	}

	private function prepareStripeCoupon() {
		if ( isset( $this->getForm()->showCouponInput ) && 1 == $this->getForm()->showCouponInput ) {
			if ( isset( $this->couponCode ) && ! empty( $this->couponCode ) ) {
				$this->__stripeCoupon = $this->__stripe->get_coupon( $this->couponCode );
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getStripePlanId() {
		return $this->stripePlanId;
	}

	/**
	 * @return mixed
	 */
	public function getCouponCode() {
		return $this->couponCode;
	}

	/**
	 * @return mixed
	 */
	public function getAmountWithCouponApplied() {
		return $this->amountWithCouponApplied;
	}

	/**
	 * @return mixed
	 */
	public function getStripePlan() {
		return $this->__stripePlan;
	}

	/**
	 * @return mixed
	 */
	public function getStripePlanSetupFee() {
		return $this->__stripePlanSetupFee;
	}

	/**
	 * @return mixed
	 */
	public function getStripePlanAmount() {
		return $this->__stripePlanAmount;
	}

	/**
	 * @return int
	 */
	public function getStripeCouponDiscount() {
		return $this->__stripeCouponDiscount;
	}

	/**
	 * @return mixed
	 */
	public function getStripeCoupon() {
		return $this->__stripeCoupon;
	}

}

class MM_WPFS_Public_InlinePaymentFormModel extends MM_WPFS_Public_PaymentFormModel implements MM_WPFS_Public_InlineForm {

	/**
	 * MM_WPFS_Public_InlinePaymentFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->__validator = new MM_WPFS_InlinePaymentFormValidator();
	}

}

class MM_WPFS_Public_InlineSubscriptionFormModel extends MM_WPFS_Public_SubscriptionFormModel implements MM_WPFS_Public_InlineForm {

	/**
	 * MM_WPFS_Public_InlineSubscriptionFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->__validator = new MM_WPFS_InlineSubscriptionFormValidator();
	}

}

class MM_WPFS_Public_PopupPaymentFormModel extends MM_WPFS_Public_PaymentFormModel implements MM_WPFS_Public_PopupForm {

	/**
	 * MM_WPFS_Public_PopupPaymentFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->__validator = new MM_WPFS_PopupPaymentFormValidator();
	}

}

class MM_WPFS_Public_PopupSubscriptionFormModel extends MM_WPFS_Public_SubscriptionFormModel implements MM_WPFS_Public_PopupForm {

	/**
	 * MM_WPFS_Public_PopupSubscriptionFormModel constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->__validator = new MM_WPFS_PopupSubscriptionFormValidator();
	}

}