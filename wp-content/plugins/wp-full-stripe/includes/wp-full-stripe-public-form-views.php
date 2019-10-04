<?php

trait MM_WPFS_InlineFormView {

	/** @var MM_WPFS_Control */
	protected $cardHolderName;
	/** @var MM_WPFS_Control */
	protected $cardHolderEmail;
	/** @var MM_WPFS_Control */
	protected $card;

	public static function getInlineFields() {
		$fields = array(
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME  => MM_WPFS_ControlUtils::input( MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME ),
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL => MM_WPFS_ControlUtils::input( MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL ),
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER       => MM_WPFS_ControlUtils::cardInput( MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER )
		);

		return $fields;
	}

	public function setCurrentEmailAddress( $email_address ) {
		$this->cardHolderEmail()->setValue( $email_address );
	}

	public function cardHolderEmail() {
		return $this->cardHolderEmail;
	}

	public function cardHolderName() {
		return $this->cardHolderName;
	}

	public function card() {
		return $this->card;
	}

	protected function initInlineFields( $formHash ) {
		$this->cardHolderName  = MM_WPFS_ControlUtils::createControl( $formHash, MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME, null, null, __( 'Card holder’s name', 'wp-full-stripe' ), null );
		$this->cardHolderEmail = MM_WPFS_ControlUtils::createControl( $formHash, MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL, null, null, __( 'E-mail address', 'wp-full-stripe' ), null );
		$this->card            = MM_WPFS_ControlUtils::createControl( $formHash, MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER, null, null, __( 'Card info', 'wp-full-stripe' ), null );
	}

	/**
	 * @return array
	 */
	protected function getInlineFormAttributes() {
		$attributes = array();

		return $attributes;
	}

}

trait MM_WPFS_PopupFormView {

	protected function getPopupFormAttributes( $form ) {
		$attributes = array();

		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_COMPANY_NAME ]        = $form->companyName;
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_PRODUCT_DESCRIPTION ] = MM_WPFS_Utils::translate_label( $form->productDesc );
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_BUTTON_TITLE ]        = MM_WPFS_Utils::translate_label( $form->buttonTitle );
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_SHOW_REMEMBER_ME ]    = $form->showRememberMe;
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_IMAGE ]               = $form->image;
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_USE_ALIPAY ]          = 0; // tnagy remove Alipay support temporarily $form->useAlipay
		$attributes[ MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_PREFERRED_LANGUAGE ]  = $form->preferredLanguage;

		return $attributes;
	}

}

interface MM_WPFS_FormViewConstants {

	const ATTR_ACTION = 'action';
	const ATTR_CLASS = 'class';
	const ATTR_METHOD = 'method';
	const ATTR_DATA_WPFS_FORM_ID = 'data-wpfs-form-id';
	const ATTR_DATA_WPFS_FORM_TYPE = 'data-wpfs-form-type';
	const ATTR_DATA_WPFS_FORM_HASH = 'data-wpfs-form-hash';
	const ATTR_DATA_WPFS_SHOW_ADDRESS = 'data-wpfs-show-address';
	const ATTR_DATA_WPFS_SHOW_BILLING_ADDRESS = 'data-wpfs-show-billing-address';
	const ATTR_DATA_WPFS_SHOW_SHIPPING_ADDRESS = 'data-wpfs-show-shipping-address';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_TITLE = 'data-wpfs-custom-input-title';
	const ATTR_DATA_WPFS_CUSTOM_INPUTS = 'data-wpfs-custom-inputs';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_REQUIRED = 'data-wpfs-custom-input-required';
	const ATTR_DATA_WPFS_SHOW_TERMS_OF_USE = 'data-wpfs-show-terms-of-use';
	const ATTR_DATA_WPFS_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE = 'data-wpfs-terms-of-use-not-checked-error-message';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_FIELD = 'data-wpfs-custom-input-field';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_LABEL = 'data-wpfs-custom-input-label';
	const ATTR_DATA_WPFS_VAT_RATE_TYPE = 'data-wpfs-vat-rate-type';
	const ATTR_DATA_WPFS_CURRENCY = 'data-wpfs-currency';
	const ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT = 'data-wpfs-zero-decimal-support';
	const ATTR_DATA_WPFS_CURRENCY_SYMBOL = 'data-wpfs-currency-symbol';
	const ATTR_DATA_WPFS_AMOUNT = 'data-wpfs-amount';
	const ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-amount-in-smallest-common-currency';
	const ATTR_DATA_WPFS_AMOUNT_INDEX = 'data-wpfs-amount-index';
	const ATTR_DATA_WPFS_AMOUNT_DESCRIPTION = 'data-wpfs-amount-description';
	const ATTR_DATA_WPFS_AMOUNT_TYPE = 'data-wpfs-amount-type';
	const ATTR_DATA_WPFS_ALLOW_LIST_OF_AMOUNTS_CUSTOM = 'data-wpfs-allow-list-of-amounts-custom';
	const ATTR_DATA_WPFS_AMOUNT_SELECTOR_STYLE = 'data-wpfs-amount-selector-style';
	const ATTR_DATA_WPFS_BUTTON_TITLE = 'data-wpfs-button-title';
	const ATTR_DATA_WPFS_SHOW_AMOUNT = 'data-wpfs-show-amount';

	const ATTR_CLASS_VALUE_WPFS_FORM_WPFS_W_60 = 'wpfs-form wpfs-w-60';
	const ATTR_CLASS_VALUE_WPFS_FORM_WPFS_FORM_INLINE = 'wpfs-form wpfs-form--inline';
	const ATTR_METHOD_VALUE_POST = 'post';

	const FIELD_ACTION = 'action';
	const FIELD_FORM_NAME = 'wpfs-form-name';
	const FIELD_BILLING_ADDRESS_LINE1 = 'wpfs-billing-address-line-1';
	const FIELD_BILLING_ADDRESS_LINE2 = 'wpfs-billing-address-line-2';
	const FIELD_BILLING_ADDRESS_ZIP = 'wpfs-billing-address-zip';
	const FIELD_BILLING_ADDRESS_STATE = 'wpfs-billing-address-state';
	const FIELD_BILLING_ADDRESS_CITY = 'wpfs-billing-address-city';
	const FIELD_BILLING_ADDRESS_COUNTRY = 'wpfs-billing-address-country';
	const FIELD_CUSTOM_INPUT = 'wpfs-custom-input';
	const FIELD_TERMS_OF_USE_ACCEPTED = 'wpfs-terms-of-use-accepted';
	const FIELD_GOOGLE_RECAPTCHA_RESPONSE = 'g-recaptcha-response';

	const MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT = '{{amount}}';

	const BUTTON_SUBMIT = 'submit';

}

interface MM_WPFS_PaymentFormViewConstants {

	const FIELD_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const FIELD_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';

	const FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE = 'wp_full_stripe_inline_payment_charge';
	const FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE = 'wp_full_stripe_popup_payment_charge';

}

interface MM_WPFS_SubscriptionFormViewConstants {

	const FIELD_PLAN = 'wpfs-plan';
	const FIELD_COUPON = 'wpfs-coupon';

	const ATTR_DATA_WPFS_VALUE = 'data-wpfs-value';
	const ATTR_DATA_WPFS_PLAN_AMOUNT = 'data-wpfs-plan-amount';
	const ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-plan-amount-in-smallest-common-currency';
	const ATTR_DATA_WPFS_INTERVAL = 'data-wpfs-interval';
	const ATTR_DATA_WPFS_INTERVAL_COUNT = 'data-wpfs-interval-count';
	const ATTR_DATA_WPFS_CANCELLATION_COUNT = 'data-wpfs-cancellation-count';
	const ATTR_DATA_WPFS_PLAN_SETUP_FEE = 'data-wpfs-plan-setup-fee';
	const ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-plan-setup-fee-in-smallest-common-currency';
	const ATTR_DATA_WPFS_VAT_PERCENT = 'data-wpfs-vat-percent';
	const ATTR_DATA_WPFS_SIMPLE_BUTTON_LAYOUT = 'data-wpfs-simple-button-layout';

	const FIELD_ACTION_VALUE_INLINE_SUBSCRIPTION_CHARGE = 'wp_full_stripe_inline_subscription_charge';
	const FIELD_ACTION_VALUE_POPUP_SUBSCRIPTION_CHARGE = 'wp_full_stripe_popup_subscription_charge';

	const FIELD_CUSTOM_VAT_BILLING_COUNTRY = 'wpfs-custom-vat-billing-country';

}

interface MM_WPFS_InlineFormViewConstants {

	const FIELD_CARD_HOLDER_NAME = 'wpfs-card-holder-name';
	const FIELD_CARD_HOLDER_EMAIL = 'wpfs-card-holder-email';
	/**
	 * This is the default field name that Stripe Elements uses for Cards. It should be changed only in correlation with
	 * the Stripe Elements configuration.
	 */
	const FIELD_CARD_NUMBER = 'cardnumber';

}

interface MM_WPFS_PopupFormViewConstants {

	const ATTR_DATA_WPFS_COMPANY_NAME = 'data-wpfs-company-name';
	const ATTR_DATA_WPFS_PRODUCT_DESCRIPTION = 'data-wpfs-product-description';
	const ATTR_DATA_WPFS_BUTTON_TITLE = 'data-wpfs-button-title';
	const ATTR_DATA_WPFS_SHOW_REMEMBER_ME = 'data-wpfs-show-remember-me';
	const ATTR_DATA_WPFS_IMAGE = 'data-wpfs-image';
	const ATTR_DATA_WPFS_USE_ALIPAY = 'data-wpfs-use-alipay';
	const ATTR_DATA_WPFS_PREFERRED_LANGUAGE = 'data-wpfs-preferred-language';

}

class MM_WPFS_ControlUtils {

	const FIELD_TYPE_INPUT = 'input';
	const FIELD_TYPE_INPUT_CUSTOM = 'input-custom';
	const FIELD_TYPE_INPUT_GROUP = 'input-group';
	const FIELD_TYPE_DROPDOWN = 'dropdown';
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_CARD = 'card';
	const FIELD_TYPE_CAPTCHA = 'captcha';

	public static final function customInput( $name ) {
		$class         = 'wpfs-form-control';
		$selector      = "#{fieldId}";
		$errorClass    = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor( self::FIELD_TYPE_INPUT, $name, $class, $selector, $errorClass, $errorSelector );
	}

	/**
	 * @param $type
	 * @param $name
	 * @param $class
	 * @param $selector
	 * @param $errorClass
	 * @param $errorSelector
	 * @param bool $hidden
	 *
	 * @return array
	 */
	public static final function descriptor( $type, $name, $class, $selector, $errorClass, $errorSelector, $hidden = false ) {
		return array(
			'type'          => $type,
			'name'          => $name,
			'class'         => $class,
			'selector'      => $selector,
			'errorClass'    => $errorClass,
			'errorSelector' => $errorSelector,
			'hidden'        => $hidden
		);
	}

	public static final function input( $name ) {
		$class         = 'wpfs-form-control';
		$selector      = ".{$class}[name='{$name}']";
		$errorClass    = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor( self::FIELD_TYPE_INPUT, $name, $class, $selector, $errorClass, $errorSelector );
	}

	public static final function inputHidden( $name ) {
		return self::descriptor( self::FIELD_TYPE_INPUT, $name, null, null, null, null, true );
	}

	public static final function selectMenu( $name ) {
		$class         = 'wpfs-form-control';
		$selector      = "select[name='{$name}']";
		$errorClass    = 'wpfs-form-control--error';
		$errorSelector = '#{fieldId}-button,#{fieldId}-menu';

		return self::descriptor( self::FIELD_TYPE_DROPDOWN, $name, $class, $selector, $errorClass, $errorSelector );
	}

	public static final function checkbox( $name ) {
		$class         = 'wpfs-form-check-input';
		$selector      = ".{$class}[name='{$name}']";
		$errorClass    = 'wpfs-form-check-input--error';
		$errorSelector = ".{$class}";

		return self::descriptor( self::FIELD_TYPE_CHECKBOX, $name, $class, $selector, $errorClass, $errorSelector );
	}

	public static final function captcha( $name ) {
		$class         = 'wpfs-inline-form-captcha';
		$selector      = ".{$class}[data-wpfs-field-name='{$name}']";
		$errorClass    = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor( self::FIELD_TYPE_CAPTCHA, $name, $class, $selector, $errorClass, $errorSelector );
	}

	public static final function inputGroup( $name ) {
		$class         = 'wpfs-input-group-form-control';
		$selector      = ".{$class}[name='{$name}']";
		$errorClass    = 'wpfs-input-group--error';
		$errorSelector = '.' . 'wpfs-input-group';

		return self::descriptor( self::FIELD_TYPE_INPUT_GROUP, $name, $class, $selector, $errorClass, $errorSelector );
	}

	public static final function cardInput( $name ) {
		$class         = 'wpfs-form-card';
		$selector      = "input[name='{$name}']";
		$errorClass    = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor( self::FIELD_TYPE_CARD, $name, $class, $selector, $errorClass, $errorSelector );
	}

	/**
	 * @param $formHash
	 * @param $name
	 * @param $placeholder
	 * @param $caption
	 * @param $label
	 * @param null $index
	 *
	 * @return MM_WPFS_Control
	 */
	public static final function createControl( $formHash, $name, $placeholder, $caption, $label, $index ) {
		$control = new MM_WPFS_Control(
			MM_WPFS_Utils::generate_form_element_id( $name, $formHash, $index ),
			$name,
			$placeholder
		);
		$control->setCaption( $caption );
		$control->setLabel( $label );
		$control->setIndex( $index );

		return $control;
	}

}

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.07.26.
 * Time: 14:38
 */
class MM_WPFS_Control {

	const ESCAPE_TYPE_NONE = 'none';
	const ESCAPE_TYPE_ATTRIBUTE = 'attribute';
	const ESCAPE_TYPE_HTML = 'html';
	const PHP_MULTI_VALUE_POST_PARAMETER_NAME_POSTFIX = '[]';

	protected $id;
	protected $name;
	protected $placeholder;
	protected $index;
	protected $caption;
	protected $value;
	protected $tooltip;
	protected $label;
	protected $labelEscape = self::ESCAPE_TYPE_HTML;
	protected $labelAttributes = array();
	protected $options = array();
	protected $attributes = array();
	/** @var  boolean $multiValue */
	protected $multiValue = false;

	/**
	 * MM_WPFS_ViewBlock constructor.
	 *
	 * @param $id
	 * @param $name
	 * @param $placeholder
	 */
	public function __construct( $id, $name, $placeholder ) {
		$this->id          = $id;
		$this->name        = $name;
		$this->placeholder = $placeholder;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @param mixed $placeholder
	 */
	public function setPlaceholder( $placeholder ) {
		$this->placeholder = $placeholder;
	}

	/**
	 * @param mixed $caption
	 */
	public function setCaption( $caption ) {
		$this->caption = $caption;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * @param mixed $tooltip
	 */
	public function setTooltip( $tooltip ) {
		$this->tooltip = $tooltip;
	}

	/**
	 * @param mixed $index
	 */
	public function setIndex( $index ) {
		$this->index = $index;
	}

	/**
	 * @param mixed $label
	 * @param string $labelEscape
	 */
	public function setLabel( $label, $labelEscape = self::ESCAPE_TYPE_HTML ) {
		$this->label = $label;
		if ( self::ESCAPE_TYPE_NONE === $labelEscape ) {
			$this->labelEscape = self::ESCAPE_TYPE_NONE;
		} else {
			$this->labelEscape = self::ESCAPE_TYPE_HTML;
		}
	}

	/**
	 * @param string $labelEscape
	 */
	public function setLabelEscape( $labelEscape ) {
		$this->labelEscape = $labelEscape;
	}

	/**
	 * @param array $labelAttributes
	 */
	public function setLabelAttributes( $labelAttributes ) {
		$this->labelAttributes = $labelAttributes;
	}

	/**
	 * @param array $options
	 */
	public function setOptions( $options ) {
		$this->options = $options;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes( $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * @param boolean $multiValue
	 */
	public function setMultiValue( $multiValue ) {
		$this->multiValue = $multiValue;
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function name( $echo = true ) {
		$name = $this->name;
		if ( $this->multiValue ) {
			$name .= self::PHP_MULTI_VALUE_POST_PARAMETER_NAME_POSTFIX;
		}

		return $this->echoOrReturnValue( $echo, $name );
	}

	/**
	 * @param $echo
	 * @param $fieldValue
	 * @param string $escapeType
	 *
	 * @return bool|null|string|void
	 */
	protected function echoOrReturnValue( $echo, $fieldValue, $escapeType = self::ESCAPE_TYPE_ATTRIBUTE ) {
		$escapedValue = null;
		if ( self::ESCAPE_TYPE_ATTRIBUTE === $escapeType ) {
			$escapedValue = esc_attr( $fieldValue );
		} elseif ( self::ESCAPE_TYPE_HTML === $escapeType ) {
			$escapedValue = esc_html( $fieldValue );
		} else {
			$escapedValue = $fieldValue;
		}
		if ( $echo ) {
			echo( $escapedValue );

			return true;
		} else {
			return $escapedValue;
		}
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function index( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->index );
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function id( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->id );
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function placeholder( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->placeholder );
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function caption( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->caption, self::ESCAPE_TYPE_HTML );
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function value( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->value );
	}

	/**
	 * @param bool $echo
	 *
	 * @return mixed
	 */
	public function tooltip( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->tooltip );
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function label( $echo = true ) {
		return $this->echoOrReturnValue( $echo, $this->label, $this->labelEscape );
	}

	/**
	 * @param bool $echo
	 *
	 * @return array
	 */
	public function labelAttributes( $echo = true ) {
		if ( $echo ) {

			$labelAttributesAsString = $this->attributesAsString( $this->labelAttributes );

			echo( $labelAttributesAsString );
		}

		return $this->labelAttributes;
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	public static function attributesAsString( array $attributes ) {
		$attributesAsString = implode(
			' ',
			array_map(
				function ( $value, $key ) {
					return $key . '=' . '"' . esc_attr( $value ) . '"';
				},
				$attributes,
				array_keys( $attributes )
			)
		);

		return $attributesAsString;
	}

	/**
	 * @return array
	 */
	public function options() {
		return $this->options;
	}

	/**
	 * @param bool $echo
	 *
	 * @return array
	 */
	public function attributes( $echo = true ) {
		if ( $echo ) {

			$attributesAsString = $this->attributesAsString( $this->attributes );

			echo( $attributesAsString );
		}

		return $this->attributes;
	}
}

class MM_WPFS_FieldDescriptor {

	/**
	 * @var string
	 */
	protected $type;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $class;
	/**
	 * @var string
	 */
	protected $selector;
	/**
	 * @var string
	 */
	protected $errorClass;
	/**
	 * @var string
	 */
	protected $errorSelector;

	/**
	 * MM_WPFS_FieldDescriptor constructor.
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $class
	 * @param string $selector
	 * @param string $errorClass
	 * @param string $errorSelector
	 */
	public function __construct( $type, $name, $class, $selector, $errorClass, $errorSelector ) {
		$this->type          = $type;
		$this->name          = $name;
		$this->class         = $class;
		$this->selector      = $selector;
		$this->errorClass    = $errorClass;
		$this->errorSelector = $errorSelector;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function getSelector() {
		return $this->selector;
	}

	/**
	 * @return string
	 */
	public function getErrorClass() {
		return $this->errorClass;
	}

	/**
	 * @return string
	 */
	public function getErrorSelector() {
		return $this->errorSelector;
	}

}

abstract class MM_WPFS_FormView implements MM_WPFS_FormViewConstants {

	/** @var MM_WPFS_Control */
	protected $action;
	/** @var MM_WPFS_Control */
	protected $formName;
	/** @var MM_WPFS_Control */
	protected $billingAddressLine1;
	/** @var MM_WPFS_Control */
	protected $billingAddressLine2;
	/** @var MM_WPFS_Control */
	protected $billingAddressCity;
	/** @var MM_WPFS_Control */
	protected $billingAddressState;
	/** @var MM_WPFS_Control */
	protected $billingAddressZip;
	/** @var MM_WPFS_Control */
	protected $billingAddressCountry;
	/** @var array */
	protected $customInputs;
	/** @var MM_WPFS_Control */
	protected $termsOfUseAccepted;
	/** @var MM_WPFS_Control */
	protected $submitButton;

	protected $form;
	protected $formHash;
	protected $attributes = array();

	/**
	 * MM_WPFS_FormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {

		$this->form                  = $form;
		$this->formHash              = $this->getFormHash();
		$this->attributes            = $this->getFormAttributes();
		$this->action                = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_ACTION, null, null, null, null );
		$this->formName              = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_NAME, null, null, null, null );
		$this->billingAddressLine1   = null;
		$this->billingAddressLine2   = null;
		$this->billingAddressZip     = null;
		$this->billingAddressState   = null;
		$this->billingAddressCity    = null;
		$this->billingAddressCountry = null;
		$this->customInputs          = array();
		$this->termsOfUseAccepted    = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_TERMS_OF_USE_ACCEPTED, null, null, $this->form->termsOfUseLabel, null );
		$this->submitButton          = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_FormViewConstants::BUTTON_SUBMIT, null, $this->getSubmitButtonCaptionForForm( $form ), null, null );

		$this->action->setAttributes(
			array(
				'type' => 'hidden'
			)
		);
		$this->formName->setAttributes(
			array(
				'type' => 'hidden'
			)
		);
		$this->formName->setValue( $this->form->name );
		$this->termsOfUseAccepted->setLabelEscape( MM_WPFS_Control::ESCAPE_TYPE_NONE );

		$this->prepareCustomInputs();
		$this->prepareAddresses();

	}

	public function getFormHash() {
		$formType = MM_WPFS_Utils::getFormType( $this->form );
		$formId   = MM_WPFS_Utils::getFormId( $this->form );

		return esc_attr( MM_WPFS_Utils::generate_form_hash(
			$formType,
			$formId,
			$this->form->name )
		);
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes() {
		$attributes = array();

		$attributes[ self::ATTR_ACTION ]              = '';
		$attributes[ self::ATTR_METHOD ]              = self::ATTR_METHOD_VALUE_POST;
		$attributes[ self::ATTR_CLASS ]               = self::ATTR_CLASS_VALUE_WPFS_FORM_WPFS_W_60;
		$attributes[ self::ATTR_DATA_WPFS_FORM_ID ]   = $this->form->name;
		$attributes[ self::ATTR_DATA_WPFS_FORM_HASH ] = $this->formHash;
		if ( 1 == $this->form->showCustomInput && $this->form->customInputs ) {
			$attributes[ self::ATTR_DATA_WPFS_CUSTOM_INPUT_TITLE ]    = $this->form->customInputTitle;
			$attributes[ self::ATTR_DATA_WPFS_CUSTOM_INPUTS ]         = $this->form->customInputs;
			$attributes[ self::ATTR_DATA_WPFS_CUSTOM_INPUT_REQUIRED ] = $this->form->customInputRequired;
		}
		if ( isset( $this->form->showAddress ) && 1 == $this->form->showAddress ) {
			$attributes[ self::ATTR_DATA_WPFS_SHOW_ADDRESS ] = $this->form->showAddress;
		}
		if ( isset( $this->form->showBillingAddress ) && 1 == $this->form->showBillingAddress ) {
			$attributes[ self::ATTR_DATA_WPFS_SHOW_BILLING_ADDRESS ] = $this->form->showBillingAddress;
		}
		if ( isset( $this->form->showShippingAddress ) && 1 == $this->form->showShippingAddress ) {
			$attributes[ self::ATTR_DATA_WPFS_SHOW_SHIPPING_ADDRESS ] = $this->form->showShippingAddress;
		}

		$attributes[ self::ATTR_DATA_WPFS_SHOW_TERMS_OF_USE ]                      = $this->form->showTermsOfUse;
		$attributes[ self::ATTR_DATA_WPFS_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE ] = $this->form->termsOfUseNotCheckedErrorMessage;

		return $attributes;
	}

	/**
	 * @param $form
	 *
	 * @return string
	 */
	protected function getSubmitButtonCaptionForForm( $form ) {
		$submitButtonCaption = null;
		if ( $this instanceof MM_WPFS_PopupPaymentFormView || $this instanceof MM_WPFS_PopupSubscriptionFormView || $this instanceof MM_WPFS_PopupCardCaptureFormView ) {
			$submitButtonCaption = MM_WPFS_Utils::translate_label( $this->form->openButtonTitle );
		} elseif ( $this instanceof MM_WPFS_InlinePaymentFormView || $this instanceof MM_WPFS_InlineSubscriptionFormView || $this instanceof MM_WPFS_InlineCardCaptureFormView ) {
            $submitButtonCaption = MM_WPFS_Utils::translate_label($form->buttonTitle);
        }

        if ( isset( $form->customAmount ) ) {
            if ( MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT == $form->customAmount ) {
                if ( isset( $form->showButtonAmount ) && 1 == $form->showButtonAmount ) {
                    $amountLabel         = MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $form->currency, $form->amount, false );
                    $submitButtonCaption = $this->insertAmountIntoLabel( $submitButtonCaption, $amountLabel );
                }
            } elseif ( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $form->customAmount ) {
                if ( isset( $form->showButtonAmount ) && 1 == $form->showButtonAmount ) {
                    $listOfAmounts = json_decode( $form->listOfAmounts );
                    if ( JSON_ERROR_NONE === json_last_error() ) {
                        $firstAmountElement  = reset( $listOfAmounts );
                        $amountLabel         = MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $form->currency, $firstAmountElement[0], false );
                        $submitButtonCaption = $this->insertAmountIntoLabel( $submitButtonCaption, $amountLabel );
                    }
                }
            } elseif ( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $form->customAmount ) {
                $submitButtonCaption = $this->insertAmountIntoLabel( $submitButtonCaption, '' );
            }
        }

		return $submitButtonCaption;
	}

	protected function insertAmountIntoLabel( $label, $amountLabel ) {
		if ( is_null( $label ) ) {
			return $label;
		}
		$labelWithAmount = $label;
		if ( false !== strpos( $labelWithAmount, MM_WPFS_FormViewConstants::MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT ) ) {
			$labelWithAmount = trim( str_replace( MM_WPFS_FormViewConstants::MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT, $amountLabel, $labelWithAmount ) );
		} else {
			$labelWithAmount .= ' ' . $amountLabel;
		}

		return $labelWithAmount;
	}

	protected function prepareCustomInputs() {
		if ( 1 == $this->form->showCustomInput ) {
			// tnagy legacy forms have only one custom input field
			if ( is_null( $this->form->customInputs ) ) {
				$input = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_INPUT, null, null, MM_WPFS_Utils::translate_label( $this->form->customInputTitle ), null );
				array_push( $this->customInputs, $input );
			} else {
				$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $this->form->customInputs );
				foreach ( $customInputLabels as $index => $label ) {
					$customInputLabel = MM_WPFS_Utils::translate_label( $label );
					$input            = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_INPUT, null, null, $customInputLabel, $index );
					$input->setMultiValue( true );
					$input->setAttributes( array(
						self::ATTR_DATA_WPFS_CUSTOM_INPUT_FIELD => 'input',
						self::ATTR_DATA_WPFS_CUSTOM_INPUT_LABEL => $customInputLabel
					) );
					array_push( $this->customInputs, $input );
				}
			}
		}
	}

	protected function prepareAddresses() {
		// todo tnagy handle showBillingAddress/showShippingAddress properties
		if ( isset( $this->form->showAddress ) && 1 == $this->form->showAddress ) {
			$this->billingAddressLine1   = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_LINE1, null, null, __( 'Billing address street', 'wp-full-stripe' ), null );
			$this->billingAddressLine2   = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_LINE2, null, null, __( 'Billing address line 2', 'wp-full-stripe' ), null );
			$this->billingAddressZip     = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_ZIP, null, null, __( 'Postal code', 'wp-full-stripe' ), null );
			$this->billingAddressState   = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_STATE, null, null, __( 'State', 'wp-full-stripe' ), null );
			$this->billingAddressCity    = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_CITY, null, null, __( 'City', 'wp-full-stripe' ), null );
			$this->billingAddressCountry = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_COUNTRY, null, null, __( 'Country', 'wp-full-stripe' ), null );
			$countryOptions              = array();
			$selectCountryOption         = MM_WPFS_ControlUtils::createControl( $this->formHash, null, null, __( 'Select country', 'wp-full-stripe' ), null, null );
			$selectCountryOption->setAttributes(
				array(
					'disabled' => 'disabled'
				)
			);
			array_push( $countryOptions, $selectCountryOption );
			$defaultBillingCountrySelected = false;
			foreach ( MM_WPFS_Countries::get_available_countries() as $countryKey => $country ) {
				$option = MM_WPFS_ControlUtils::createControl( $this->formHash, null, null, MM_WPFS_Utils::translate_label( $country['name'] ), null, null );
				if ( isset( $this->form->defaultBillingCountry ) && $this->form->defaultBillingCountry === $country['alpha-2'] ) {
					$defaultBillingCountrySelected = true;
					$option->setAttributes( array( 'selected' => 'selected' ) );
				}
				$option->setValue( $countryKey );
				array_push( $countryOptions, $option );
			}
			$this->billingAddressCountry->setOptions( $countryOptions );
			if ( false === $defaultBillingCountrySelected ) {
				$selectCountryOption->setAttributes(
					array(
						'disabled' => 'disabled',
						'selected' => 'selected'
					)
				);
			}
			if ( isset( $this->form->vatRateType ) ) {
				$this->billingAddressCountry->setAttributes( array( self::ATTR_DATA_WPFS_VAT_RATE_TYPE => $this->form->vatRateType ) );
			}
		}
	}

	/**
	 * Note: billing address fields must be added in child classes manually.
	 *
	 * @return array
	 */
	public static function getFields() {
		$fields = array(
			self::FIELD_ACTION                    => MM_WPFS_ControlUtils::inputHidden( self::FIELD_ACTION ),
			self::FIELD_FORM_NAME                 => MM_WPFS_ControlUtils::inputHidden( self::FIELD_FORM_NAME ),
			self::FIELD_CUSTOM_INPUT              => MM_WPFS_ControlUtils::customInput( self::FIELD_CUSTOM_INPUT ),
			self::FIELD_TERMS_OF_USE_ACCEPTED     => MM_WPFS_ControlUtils::checkbox( self::FIELD_TERMS_OF_USE_ACCEPTED ),
			self::FIELD_GOOGLE_RECAPTCHA_RESPONSE => MM_WPFS_ControlUtils::captcha( self:: FIELD_GOOGLE_RECAPTCHA_RESPONSE )
		);

		return $fields;
	}

	protected static function getPopupBillingAddressFields() {
		$fields = array(
			self::FIELD_BILLING_ADDRESS_LINE1   => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_LINE1 ),
			self::FIELD_BILLING_ADDRESS_LINE2   => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_LINE2 ),
			self::FIELD_BILLING_ADDRESS_ZIP     => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_ZIP ),
			self::FIELD_BILLING_ADDRESS_STATE   => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_STATE ),
			self::FIELD_BILLING_ADDRESS_CITY    => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_CITY ),
			self::FIELD_BILLING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::inputHidden( self::FIELD_BILLING_ADDRESS_COUNTRY ),
		);

		return $fields;
	}

	protected static function getInlineBillingAddressFields() {
		$fields = array(
			self::FIELD_BILLING_ADDRESS_LINE1   => MM_WPFS_ControlUtils::input( self::FIELD_BILLING_ADDRESS_LINE1 ),
			self::FIELD_BILLING_ADDRESS_LINE2   => MM_WPFS_ControlUtils::input( self::FIELD_BILLING_ADDRESS_LINE2 ),
			self::FIELD_BILLING_ADDRESS_ZIP     => MM_WPFS_ControlUtils::input( self::FIELD_BILLING_ADDRESS_ZIP ),
			self::FIELD_BILLING_ADDRESS_STATE   => MM_WPFS_ControlUtils::input( self::FIELD_BILLING_ADDRESS_STATE ),
			self::FIELD_BILLING_ADDRESS_CITY    => MM_WPFS_ControlUtils::input( self::FIELD_BILLING_ADDRESS_CITY ),
			self::FIELD_BILLING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::selectMenu( self::FIELD_BILLING_ADDRESS_COUNTRY ),
		);

		return $fields;
	}

	public function _formName() {
		echo( esc_attr( $this->form->name ) );
	}

	/**
	 * @return array
	 */
	public function customInputs() {
		return $this->customInputs;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function tOUAccepted() {
		return $this->termsOfUseAccepted;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function submitButton() {
		return $this->submitButton;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressLine1() {
		return $this->billingAddressLine1;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressLine2() {
		return $this->billingAddressLine2;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressCity() {
		return $this->billingAddressCity;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressState() {
		return $this->billingAddressState;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressZip() {
		return $this->billingAddressZip;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressCountry() {
		return $this->billingAddressCountry;
	}

	public function formAttributes() {
		$attributesAsString = MM_WPFS_Control::attributesAsString( $this->attributes );

		echo( $attributesAsString );
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function action() {
		return $this->action;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function formName() {
		return $this->formName;
	}

}

abstract class MM_WPFS_SubscriptionFormView extends MM_WPFS_FormView implements MM_WPFS_SubscriptionFormViewConstants {

	/** @var array $stripePlans */
	protected $stripePlans;
	/** @var array $selectedStripePlans */
	protected $selectedStripePlans;
	/** @var MM_WPFS_Control */
	protected $plans;
	/** @var MM_WPFS_Control */
	protected $coupon;

	protected $__currentVATPercent;

	/**
	 * MM_WPFS_SubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripePlans
	 */
	public function __construct( $form, $stripePlans ) {

		parent::__construct( $form );

		$this->stripePlans = $stripePlans;
		$this->plans       = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_PLAN, null, null, __( 'Choose subscription plan', 'wp-full-stripe' ), null );
		$this->coupon      = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_COUPON, __( 'Enter coupon code', 'wp-full-stripe' ), null, __( 'Coupon', 'wp-full-stripe' ), null );
		$this->plans->setAttributes(
			array(
				self::ATTR_DATA_WPFS_FORM_ID => $this->form->name
			)
		);
		// todo tnagy change coupon tooltip later
		$this->coupon->setTooltip( __( 'Apply a coupon for a discount to your subscription', 'wp-full-stripe' ) );

		$this->prepareCurrentVATPercent();
		$this->preparePlans();

	}

	protected function prepareCurrentVATPercent() {
		if ( MM_WPFS::VAT_RATE_TYPE_NO_VAT === $this->form->vatRateType ) {
			$this->__currentVATPercent = MM_WPFS::NO_VAT_PERCENT;
		} elseif ( MM_WPFS::VAT_RATE_TYPE_FIXED_VAT === $this->form->vatRateType ) {
			$this->__currentVATPercent = floatval( $this->form->vatPercent );
		} elseif ( MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT ) {
			$formType                  = MM_WPFS_Utils::getFormType( $this->form );
			$this->__currentVATPercent = floatval( apply_filters(
					MM_WPFS::FILTER_NAME_GET_VAT_PERCENT,
					MM_WPFS::NO_VAT_PERCENT,
					$this->form->defaultBillingCountry,
					$this->form->defaultBillingCountry,
					MM_WPFS_Utils::prepare_vat_filter_arguments(
						$this->formName(),
						$formType,
						null,
						array(
							'country' => $this->form->defaultBillingCountry
						),
						array()
					)
				)
			);
		} else {
			$this->__currentVATPercent = MM_WPFS::NO_VAT_PERCENT;
		}
	}

	protected function preparePlans() {
		if ( ! empty( $this->stripePlans ) ) {
			$planOptions               = array();
			$this->selectedStripePlans = MM_WPFS_Utils::get_sorted_form_plans( $this->stripePlans, $this->form->plans );
			foreach ( $this->selectedStripePlans as $index => $selectedStripePlan ) {
				$planCurrency          = $selectedStripePlan->currency;
				$planCancellationCount = MM_WPFS_Utils::get_cancellation_count_for_plan( $selectedStripePlan );
				$currencyArray         = MM_WPFS_Currencies::get_currency_for( $planCurrency );
				$planAmount            = $selectedStripePlan->amount;
				$planSetupFee          = MM_WPFS_Utils::get_setup_fee_for_plan( $selectedStripePlan );

				$controlLabel = MM_WPFS_Utils::translate_label( $selectedStripePlan->name );
				$amountLabel  = MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $planCurrency, $planAmount, false );
				$name         = null;
				if ( MM_WPFS::PLAN_SELECTOR_STYLE_LIST === $this->form->planSelectorStyle ) {
					$name = self::FIELD_PLAN;
				}
				$plan = MM_WPFS_ControlUtils::createControl( $this->formHash, $name, null, null, null, $index );
				$plan->setValue( $selectedStripePlan->id );
				$plan->setAttributes(
					array(
						self::ATTR_DATA_WPFS_VALUE                                      => $selectedStripePlan->id,
						self::ATTR_DATA_WPFS_PLAN_AMOUNT                                => MM_WPFS_Utils::format_amount( $planCurrency, $planAmount ),
						self::ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY    => $planAmount,
						self::ATTR_DATA_WPFS_PLAN_SETUP_FEE                             => MM_WPFS_Utils::format_amount( $planCurrency, $planSetupFee ),
						self::ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY => $planSetupFee,
						self::ATTR_DATA_WPFS_VAT_PERCENT                                => $this->__currentVATPercent,
						self::ATTR_DATA_WPFS_INTERVAL                                   => $selectedStripePlan->interval,
						self::ATTR_DATA_WPFS_INTERVAL_COUNT                             => $selectedStripePlan->interval_count,
						self::ATTR_DATA_WPFS_CANCELLATION_COUNT                         => $planCancellationCount,
						self::ATTR_DATA_WPFS_CURRENCY                                   => $planCurrency,
						self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT                       => $currencyArray['zeroDecimalSupport'] ? 'true' : 'false',
						self::ATTR_DATA_WPFS_CURRENCY_SYMBOL                            => MM_WPFS_Currencies::get_currency_symbol_for( $planCurrency )
					)
				);
				$plan->setLabel( sprintf( '%s - <strong>%s</strong>', $controlLabel, $amountLabel ), MM_WPFS_Control::ESCAPE_TYPE_NONE );
				array_push( $planOptions, $plan );
			}
			$this->plans->setOptions( $planOptions );
		}
	}

	/**
	 * @return array
	 */
	public static function getFields() {

		$fields = array(
			self::FIELD_PLAN   => MM_WPFS_ControlUtils::selectMenu( self::FIELD_PLAN ),
			self::FIELD_COUPON => MM_WPFS_ControlUtils::inputGroup( self::FIELD_COUPON )
		);

		return array_merge( $fields, parent::getFields() );
	}

	/**
	 * @return mixed
	 */
	public function getCurrentVATPercent() {
		return $this->__currentVATPercent;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function plans() {
		return $this->plans;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function coupon() {
		return $this->coupon;
	}

}

abstract class MM_WPFS_PaymentFormView extends MM_WPFS_FormView implements MM_WPFS_PaymentFormViewConstants {

	const AMOUNT_PATTERN = '{amount}';

	protected $currencyCode;
	protected $currencySymbol;
	protected $currencyName;
	protected $currencyZeroDecimalSupport;
	/** @var MM_WPFS_Control */
	protected $customAmount;
	/** @var MM_WPFS_Control */
	protected $customAmountOptions;

	/**
	 * MM_WPFS_PaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {

		parent::__construct( $form );

		$this->currencyCode               = null;
		$this->currencyName               = null;
		$this->currencySymbol             = null;
		$this->currencyZeroDecimalSupport = null;
		$this->customAmount               = null;
		$this->customAmountOptions        = null;

		$this->prepareCurrency();
		$this->prepareCustomAmountAndOptions();

		$this->attributes = $this->getFormAttributes();

	}


	protected function prepareCurrency() {
		$currency                         = MM_WPFS_Currencies::get_currency_for( $this->form->currency );
		$this->currencyCode               = $currency['code'];
		$this->currencyName               = $currency['name'];
		$this->currencySymbol             = $currency['symbol'];
		$this->currencyZeroDecimalSupport = $currency['zeroDecimalSupport'];
	}

	protected function prepareCustomAmountAndOptions() {
		$amountButtonTitle = $this->form->buttonTitle;
		if ( $this instanceof MM_WPFS_PopupPaymentFormView || $this instanceof MM_WPFS_PopupCardCaptureFormView ) {
			$amountButtonTitle = $this->form->openButtonTitle;
		}
		$attributes = array(
			self::ATTR_DATA_WPFS_BUTTON_TITLE          => MM_WPFS_Utils::translate_label( $amountButtonTitle ),
			self::ATTR_DATA_WPFS_SHOW_AMOUNT           => isset( $this->form->showButtonAmount ) ? $this->form->showButtonAmount : null,
			self::ATTR_DATA_WPFS_CURRENCY              => $this->currencyCode,
			self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT  => $this->currencyZeroDecimalSupport ? 'true' : 'false',
			self::ATTR_DATA_WPFS_CURRENCY_SYMBOL       => $this->currencySymbol,
			self::ATTR_DATA_WPFS_FORM_ID               => $this->formHash,
			self::ATTR_DATA_WPFS_AMOUNT_SELECTOR_STYLE => $this->form->amountSelectorStyle
		);
		if ( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $this->form->customAmount ) {
			$this->customAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_AMOUNT_UNIQUE, null, null, __( 'Amount', 'wp-full-stripe' ), null );
			$this->customAmount->setAttributes( $attributes );
			$this->customAmount->setLabelAttributes( array(
					'class' => 'wpfs-form-label'
				)
			);
		} elseif ( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $this->form->customAmount ) {
			$this->customAmountOptions = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_AMOUNT, null, null, __( 'Select amount', 'wp-full-stripe' ), null );
			$this->customAmountOptions->setAttributes( $attributes );
			$customAmountOptions = array();
			$listOfAmounts       = json_decode( $this->form->listOfAmounts );
			$lastIndex           = - 1;
			foreach ( $listOfAmounts as $index => $listElement ) {
				$lastIndex        = $index;
				$amount           = $listElement[0];
				$description      = $listElement[1];
				$amountLabel      = MM_WPFS_Currencies::format_amount_with_currency_html_escaped( $this->currencyCode, $amount, false );
				$descriptionLabel = MM_WPFS_Utils::translate_label( $description );
				if ( false !== strpos( $description, self::AMOUNT_PATTERN ) ) {
					$descriptionLabel = str_replace( self::AMOUNT_PATTERN, $amountLabel, $descriptionLabel );
				}
				$customAmountOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_AMOUNT, null, null, $descriptionLabel, $index );
				$formattedAmount    = MM_WPFS_Utils::format_amount( $this->currencyCode, $amount );
				$customAmountOption->setValue( $formattedAmount );
				$optionAttributes = array(
					self::ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY => $amount,
					self::ATTR_DATA_WPFS_AMOUNT_INDEX                       => $index,
					self::ATTR_DATA_WPFS_AMOUNT_DESCRIPTION                 => $descriptionLabel
				);
				$optionAttributes = array_merge( $attributes, $optionAttributes );
				$customAmountOption->setAttributes( $optionAttributes );
				if ( MM_WPFS::AMOUNT_SELECTOR_STYLE_DROPDOWN === $this->form->amountSelectorStyle ) {
					$customAmountOption->setLabel( $descriptionLabel );
				} elseif ( MM_WPFS::AMOUNT_SELECTOR_STYLE_BUTTON_GROUP === $this->form->amountSelectorStyle ) {
					$customAmountOption->setLabel( $amountLabel );
				} elseif ( MM_WPFS::AMOUNT_SELECTOR_STYLE_RADIO_BUTTONS === $this->form->amountSelectorStyle ) {
					$controlLabel = MM_WPFS_Utils::translate_label( $description );
					if ( false !== strpos( $description, self::AMOUNT_PATTERN ) ) {
						$controlLabel = str_replace( self::AMOUNT_PATTERN, $amountLabel, $controlLabel );
						$customAmountOption->setLabel( $controlLabel, MM_WPFS_Control::ESCAPE_TYPE_NONE );
					} else {
						$customAmountOption->setLabel( sprintf( '%s - <strong>%s</strong>', $controlLabel, $amountLabel ), MM_WPFS_Control::ESCAPE_TYPE_NONE );
					}
				} else {
					$customAmountOption->setLabel( $descriptionLabel );
				}
				array_push( $customAmountOptions, $customAmountOption );
			}
			if ( 1 == $this->form->allowListOfAmountsCustom ) {
				$this->customAmount     = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_AMOUNT_UNIQUE, null, null, __( 'Amount', 'wp-full-stripe' ), null );
				$customAmountAttributes = array_merge( $attributes, array( 'disabled' => true ) );
				$this->customAmount->setAttributes( $customAmountAttributes );
				$this->customAmount->setLabelAttributes( array(
						'class' => 'wpfs-sr-only'
					)
				);
				$customAmountOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_CUSTOM_AMOUNT, null, null, __( 'Other', 'wp-full-stripe' ), $lastIndex + 1 );
				$customAmountOption->setValue( 'other' );
				$customAmountOption->setAttributes( $attributes );
				array_push( $customAmountOptions, $customAmountOption );
			}

			$this->customAmountOptions->setOptions( $customAmountOptions );
		}
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes() {
		$attributes = array();

		$attributes[ self::ATTR_DATA_WPFS_AMOUNT_TYPE ] = $this->form->customAmount;
		if ( MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT === $this->form->customAmount || MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $this->form->customAmount ) {
			$attributes[ self::ATTR_DATA_WPFS_AMOUNT ]               = $this->form->amount;
			$attributes[ self::ATTR_DATA_WPFS_CURRENCY ]             = $this->currencyCode;
			$attributes[ self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT ] = $this->currencyZeroDecimalSupport ? 'true' : 'false';
			$attributes[ self::ATTR_DATA_WPFS_CURRENCY_SYMBOL ]      = $this->currencySymbol;
		} elseif ( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS === $this->form->customAmount ) {
			$attributes[ self::ATTR_DATA_WPFS_AMOUNT_SELECTOR_STYLE ]        = $this->form->amountSelectorStyle;
			$attributes[ self::ATTR_DATA_WPFS_ALLOW_LIST_OF_AMOUNTS_CUSTOM ] = $this->form->allowListOfAmountsCustom;
		}

		return array_merge( $attributes, parent::getFormAttributes() );
	}

	/**
	 * @return array
	 */
	public static function getFields() {
		$fields = array(
			self::FIELD_CUSTOM_AMOUNT_UNIQUE => MM_WPFS_ControlUtils::inputGroup( self::FIELD_CUSTOM_AMOUNT_UNIQUE )
		);

		return array_merge( $fields, parent::getFields() );
	}

	public function _currencySymbol() {
		echo( esc_attr( $this->currencySymbol ) );
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function customAmount() {
		return $this->customAmount;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function customAmountOptions() {
		return $this->customAmountOptions;
	}

}

class MM_WPFS_InlinePaymentFormView extends MM_WPFS_PaymentFormView {

	use MM_WPFS_InlineFormView;

	/**
	 * MM_WPFS_InlinePaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {
		parent::__construct( $form );
		$this->initInlineFields( $this->formHash );

		$this->action->setValue( self::FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE );

	}

	/**
	 * @return array
	 */
	public static function getFields() {
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge( $inlineFields, self::getInlineBillingAddressFields() );

		return array_merge( $inlineFields, parent::getFields() );
	}

	/**
	 * @return array
	 */
	public function getFormAttributes() {
		$attributes = array();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
		$attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

		$inlineFormAttributes = $this->getInlineFormAttributes();

		return array_merge( $attributes, $inlineFormAttributes );
	}

}

class MM_WPFS_InlineSubscriptionFormView extends MM_WPFS_SubscriptionFormView {

	use MM_WPFS_InlineFormView;

	/**
	 * MM_WPFS_InlineSubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripePlans
	 */
	public function __construct( $form, $stripePlans ) {
		parent::__construct( $form, $stripePlans );
		$this->initInlineFields( $this->formHash );

		$this->action->setValue( self::FIELD_ACTION_VALUE_INLINE_SUBSCRIPTION_CHARGE );

	}

	/**
	 * @return array
	 */
	public static function getFields() {
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge( $inlineFields, self::getInlineBillingAddressFields() );

		return array_merge( $inlineFields, parent::getFields() );
	}

	/**
	 * @return array
	 */
	public function getFormAttributes() {

		$attributes = array();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
		$attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

		$inlineFormAttributes = $this->getInlineFormAttributes();

		return array_merge( $attributes, $inlineFormAttributes );
	}

}

class MM_WPFS_InlineCardCaptureFormView extends MM_WPFS_InlinePaymentFormView {

	use MM_WPFS_InlineFormView;

	/**
	 * MM_WPFS_InlineCardCaptureFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {
		parent::__construct( $form );
	}

	public function getFormAttributes() {
		$attributes = parent::getFormAttributes();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;

		return $attributes;
	}

}

class MM_WPFS_PopupPaymentFormView extends MM_WPFS_PaymentFormView {

	use MM_WPFS_PopupFormView;

	/**
	 * MM_WPFS_PopupPaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {
		parent::__construct( $form );

		$this->action->setValue( self::FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE );
	}

	public static function getFields() {
		$fields = self::getPopupBillingAddressFields();

		return array_merge( $fields, parent::getFields() );
	}

	protected function getFormAttributes() {
		$attributes = array();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_POPUP_PAYMENT;
		$attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

		$popupFormAttributes = $this->getPopupFormAttributes( $this->form );

		return array_merge( $attributes, $popupFormAttributes );
	}

}

class MM_WPFS_PopupSubscriptionFormView extends MM_WPFS_SubscriptionFormView {

	use MM_WPFS_PopupFormView;

	/**
	 * @var MM_WPFS_Control
	 */
	protected $firstPlan;

	/**
	 * @var MM_WPFS_Control
	 */
	protected $customVATBillingCountry;

	/**
	 * MM_WPFS_PopupSubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripePlans
	 */
	public function __construct( $form, $stripePlans ) {
		parent::__construct( $form, $stripePlans );

		$this->action->setValue( self::FIELD_ACTION_VALUE_POPUP_SUBSCRIPTION_CHARGE );

		$firstStripePlan = null;
		if ( ! empty( $this->stripePlans ) ) {
			if ( sizeof( $this->selectedStripePlans ) > 0 ) {
				$firstStripePlan = $this->selectedStripePlans[0];
			}
		}
		if ( ! is_null( $firstStripePlan ) ) {

			$planCurrency          = $firstStripePlan->currency;
			$planCancellationCount = MM_WPFS_Utils::get_cancellation_count_for_plan( $firstStripePlan );
			$currencyArray         = MM_WPFS_Currencies::get_currency_for( $planCurrency );
			$planAmount            = $firstStripePlan->amount;
			$planSetupFee          = MM_WPFS_Utils::get_setup_fee_for_plan( $firstStripePlan );

			$this->firstPlan = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_PLAN, null, null, null, null );
			$this->firstPlan->setValue( $firstStripePlan->id );
			$this->firstPlan->setAttributes(
				array(
					'type'                                                          => 'hidden',
					self::ATTR_DATA_WPFS_VALUE                                      => $firstStripePlan->id,
					self::ATTR_DATA_WPFS_PLAN_AMOUNT                                => MM_WPFS_Utils::format_amount( $planCurrency, $planAmount ),
					self::ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY    => $planAmount,
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE                             => MM_WPFS_Utils::format_amount( $planCurrency, $planSetupFee ),
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY => $planSetupFee,
					self::ATTR_DATA_WPFS_VAT_PERCENT                                => $this->__currentVATPercent,
					self::ATTR_DATA_WPFS_INTERVAL                                   => $firstStripePlan->interval,
					self::ATTR_DATA_WPFS_INTERVAL_COUNT                             => $firstStripePlan->interval_count,
					self::ATTR_DATA_WPFS_CANCELLATION_COUNT                         => $planCancellationCount,
					self::ATTR_DATA_WPFS_CURRENCY                                   => $planCurrency,
					self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT                       => $currencyArray['zeroDecimalSupport'] ? 'true' : 'false',
					self::ATTR_DATA_WPFS_CURRENCY_SYMBOL                            => MM_WPFS_Currencies::get_currency_symbol_for( $planCurrency )
				)
			);
		}
		$this->customVATBillingCountry = null;
		if ( 0 == $this->form->simpleButtonLayout && MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT == $this->form->vatRateType && 1 == $this->form->showBillingAddress ) {
			$this->customVATBillingCountry = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_BILLING_ADDRESS_COUNTRY, null, null, __( 'Country', 'wp-full-stripe' ), null );
			$countryOptions                = array();
			$selectCountryOption           = MM_WPFS_ControlUtils::createControl( $this->formHash, null, null, __( 'Select country', 'wp-full-stripe' ), null, null );
			$selectCountryOption->setAttributes(
				array(
					'disabled' => 'disabled'
				)
			);
			array_push( $countryOptions, $selectCountryOption );
			$defaultCustomVATBillingCountrySelected = false;
			foreach ( MM_WPFS_Countries::get_available_countries() as $countryKey => $country ) {
				$option = MM_WPFS_ControlUtils::createControl( $this->formHash, null, null, MM_WPFS_Utils::translate_label( $country['name'] ), null, null );
				if ( isset( $this->form->defaultBillingCountry ) && $this->form->defaultBillingCountry === $country['alpha-2'] ) {
					$defaultCustomVATBillingCountrySelected = true;
					$option->setAttributes( array( 'selected' => 'selected' ) );
				}
				$option->setValue( $countryKey );
				array_push( $countryOptions, $option );
			}
			$this->customVATBillingCountry->setOptions( $countryOptions );
			if ( false === $defaultCustomVATBillingCountrySelected ) {
				$selectCountryOption->setAttributes(
					array(
						'disabled' => 'disabled',
						'selected' => 'selected'
					)
				);
			}
			if ( isset( $this->form->vatRateType ) ) {
				$this->customVATBillingCountry->setAttributes( array( self::ATTR_DATA_WPFS_VAT_RATE_TYPE => $this->form->vatRateType ) );
			}
		}

	}

	public static function getFields() {
		$fields = array(
			self::FIELD_BILLING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::selectMenu( self::FIELD_BILLING_ADDRESS_COUNTRY )
		);
		$fields = array_merge( $fields, self::getPopupBillingAddressFields() );

		return array_merge( $fields, parent::getFields() );
	}

	/**
	 * @return array
	 */
	public function getFormAttributes() {

		$attributes = array();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ]            = MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION;
		$attributes[ self::ATTR_DATA_WPFS_SIMPLE_BUTTON_LAYOUT ] = $this->form->simpleButtonLayout;
		$attributes                                              = array_merge( $attributes, parent::getFormAttributes() );

		$popupFormAttributes = $this->getPopupFormAttributes( $this->form );

		if ( 1 == $this->form->simpleButtonLayout ) {
			$attributes[ self::ATTR_CLASS ] = self::ATTR_CLASS_VALUE_WPFS_FORM_WPFS_FORM_INLINE;
		}

		return array_merge( $attributes, $popupFormAttributes );
	}

	public function firstPlan() {
		return $this->firstPlan;
	}

	public function customVATBillingCountry() {
		return $this->customVATBillingCountry;
	}

}

class MM_WPFS_PopupCardCaptureFormView extends MM_WPFS_PopupPaymentFormView {

	use MM_WPFS_PopupFormView;

	/**
	 * MM_WPFS_PopupCardCaptureFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct( $form ) {
		parent::__construct( $form );
	}

	public function getFormAttributes() {
		$attributes = parent::getFormAttributes();

		$attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD;

		return $attributes;
	}

}
