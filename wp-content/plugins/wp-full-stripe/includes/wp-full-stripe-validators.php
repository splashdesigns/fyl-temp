<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.05.31.
 * Time: 17:05
 */
abstract class MM_WPFS_Validator {

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	public abstract function validate( $bindingResult, $formModelObject );

}

class MM_WPFS_FormValidator extends MM_WPFS_Validator {

	/**
	 * @var array
	 */
	protected $fieldsToIgnore;

	/**
	 * MM_WPFS_FormValidator constructor.
	 */
	public function __construct() {
		$this->fieldsToIgnore = array();
	}

	public final function validate( $bindingResult, $formModelObject ) {
		$this->validateForm( $bindingResult, $formModelObject );
		if ( ! $bindingResult->hasErrors() ) {
			$this->validateFields( $bindingResult, $formModelObject );
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateForm( $bindingResult, $formModelObject ) {
		if ( is_null( $formModelObject->getFormName() ) ) {
			$error = __( 'Invalid form name', 'wp-full-stripe' );
			$bindingResult->addGlobalError( $error );
		} else {
			$formObject = null;
			if ( $formModelObject instanceof MM_WPFS_Public_InlinePaymentFormModel ) {
				$formObject = $formModelObject->getDAO()->get_payment_form_by_name( $formModelObject->getFormName() );
			}
			if ( $formModelObject instanceof MM_WPFS_Public_PopupPaymentFormModel ) {
				$formObject = $formModelObject->getDAO()->get_checkout_form_by_name( $formModelObject->getFormName() );
			}
			if ( $formModelObject instanceof MM_WPFS_Public_InlineSubscriptionFormModel ) {
				$formObject = $formModelObject->getDAO()->get_subscription_form_by_name( $formModelObject->getFormName() );
			}
			if ( $formModelObject instanceof MM_WPFS_Public_PopupSubscriptionFormModel ) {
				$formObject = $formModelObject->getDAO()->get_checkout_subscription_form_by_name( $formModelObject->getFormName() );
			}
			if ( is_null( $formObject ) ) {
				$bindingResult->addGlobalError( __( 'Invalid form name or form not found', 'wp-full-stripe' ) );
			} else {
				$formModelObject->setForm( $formObject );
			}
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param $formModelObject
	 */
	protected function validateFields( $bindingResult, $formModelObject ) {
		if ( $formModelObject instanceof MM_WPFS_Public_FormModel ) {
			if ( isset( $formModelObject->getForm()->showTermsOfUse ) && 1 == $formModelObject->getForm()->showTermsOfUse ) {
				if ( 0 == $formModelObject->getTermsOfUseAccepted() ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_TERMS_OF_USE_ACCEPTED;
					$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
					$error     = MM_WPFS_Utils::get_default_terms_of_use_not_checked_error_message();
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
			}
			if ( ! filter_var( $formModelObject->getCardHolderEmail(), FILTER_VALIDATE_EMAIL ) ) {
				$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CARD_HOLDER_EMAIL;
				$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
				$error     = __( 'Please enter a valid email address', 'wp-full-stripe' );
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			}
			$showBillingAddress = false;
			if ( isset( $formModelObject->getForm()->showAddress ) ) {
				$showBillingAddress = 1 == $formModelObject->getForm()->showAddress;
			} elseif ( isset( $formModelObject->getForm()->showBillingAddress ) ) {
				$showBillingAddress = 1 == $formModelObject->getForm()->showBillingAddress;
			}
			if ( $showBillingAddress ) {
				if ( empty( $formModelObject->getBillingAddressLine1() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_LINE_1;
					$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please enter a billing address', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( empty( $formModelObject->getBillingAddressCity() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_CITY;
					$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please enter a city', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( empty( $formModelObject->getBillingAddressCountry() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_COUNTRY;
					$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please select a country', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				// tnagy WPFS-886 fix: some countries do not have or do not use postcodes
				$validateBillingAddressZip = null;
				if ( empty( $formModelObject->getBillingAddressCountryComposite() ) ) {
					$validateBillingAddressZip = false;
					if ( ! $bindingResult->hasFieldErrors( $formModelObject::PARAM_WPFS_BILLING_ADDRESS_COUNTRY ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_COUNTRY;
						$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please select a country', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
				} else {
					$billingAddressCountryComposite = $formModelObject->getBillingAddressCountryComposite();
					if ( true === $billingAddressCountryComposite['usePostCode'] ) {
						$validateBillingAddressZip = true;
					} else {
						$validateBillingAddressZip = false;
					}
				}
				if ( $validateBillingAddressZip ) {
					if ( empty( $formModelObject->getBillingAddressZip() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_ZIP;
						$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please enter a zip/postal code', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
				}
			}
			if ( ! $this->isIgnored( MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT ) ) {
				if ( 1 == $formModelObject->getForm()->showCustomInput ) {
					if ( 1 == $formModelObject->getForm()->customInputRequired ) {
						if ( is_null( $formModelObject->getForm()->customInputs ) ) {
							if ( is_null( $formModelObject->getCustomInputvalues() ) || ( false == trim( $formModelObject->getCustomInputvalues() ) ) ) {
								$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
								$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
								$error     = sprintf( __( 'Please enter a value for "%s"', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $formModelObject->getForm()->customInputTitle ) );
								$bindingResult->addFieldError( $fieldName, $fieldId, $error );
							}
						} else {
							$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $formModelObject->getForm()->customInputs );
							foreach ( $customInputLabels as $index => $label ) {
								if ( is_null( $formModelObject->getCustomInputvalues()[ $index ] ) || ( false == trim( $formModelObject->getCustomInputvalues()[ $index ] ) ) ) {
									$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
									$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash(), $index );
									$error     = sprintf( __( 'Please enter a value for "%s"', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $label ) );
									$bindingResult->addFieldError( $fieldName, $fieldId, $error );
								}
							}
						}
					}
					if ( is_null( $formModelObject->getForm()->customInputs ) ) {
						if ( is_string( $formModelObject->getCustomInputvalues() ) && strlen( $formModelObject->getCustomInputvalues() ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
							$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
							$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
							$error     = sprintf( __( 'The value for "%s" is too long', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $formModelObject->getForm()->customInputTitle ) );
							$bindingResult->addFieldError( $fieldName, $fieldId, $error );
						}
					} else {
						$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $formModelObject->getForm()->customInputs );
						foreach ( $customInputLabels as $index => $label ) {
							if ( is_string( $formModelObject->getCustomInputvalues()[ $index ] ) && strlen( $formModelObject->getCustomInputvalues()[ $index ] ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
								$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
								$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash(), $index );
								$error     = sprintf( __( 'The value for "%s" is too long', 'wp-full-stripe' ), MM_WPFS_Utils::translate_label( $label ) );
								$bindingResult->addFieldError( $fieldName, $fieldId, $error );
							}
						}
					}
				}
			}
		}
	}

	protected function isIgnored( $fieldName ) {
		return array_key_exists( $fieldName, $this->fieldsToIgnore );
	}

	protected function ignore( $fieldName ) {
		if ( ! array_key_exists( $this->fieldsToIgnore, $fieldName ) ) {
			$this->fieldsToIgnore[ $fieldName ] = true;
		}
	}

	protected function unIgnore( $fieldName ) {
		if ( array_key_exists( $this->fieldsToIgnore, $fieldName ) ) {
			unset( $this->fieldsToIgnore[ $fieldName ] );
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateGoogleReCaptcha( $bindingResult, $formModelObject ) {
		if ( MM_WPFS_Utils::get_secure_inline_forms_with_google_recaptcha() ) {
			$fieldName = $formModelObject::PARAM_GOOGLE_RECAPTCHA_RESPONSE;
			$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
			$error     = __( 'Please prove that you are not a robot', 'wp-full-stripe' );
			if ( is_null( $formModelObject->getGoogleReCaptchaResponse() ) ) {
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			} else {
				$googleReCaptchaVerificationResult = MM_WPFS_Utils::verifyReCAPTCHA( $formModelObject->getGoogleReCaptchaResponse() );
				if ( $googleReCaptchaVerificationResult === false ) {
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				} elseif ( ! isset( $googleReCaptchaVerificationResult->success ) || $googleReCaptchaVerificationResult->success === false ) {
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
			}
		}
	}

}

class MM_WPFS_PaymentFormValidator extends MM_WPFS_FormValidator {

	protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_PaymentFormModel ) {

			if ( $this->validateCustomAmount( $formModelObject ) ) {
				$fieldName = $formModelObject::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE;
				$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
				if ( empty( $formModelObject->getAmount() ) ) {
					$error = __( 'Please enter an amount', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				} elseif ( ! is_numeric( trim( $formModelObject->getAmount() ) ) || $formModelObject->getAmount() <= 0 ) {
					$error = __( 'Please enter a valid amount, use only digits and a decimal point', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
			}
		}
	}

	/**
	 * @param MM_WPFS_Public_PaymentFormModel $formModelObject
	 *
	 * @return bool
	 */
	private function validateCustomAmount( $formModelObject ) {
		$validateCustomAmount = false;

		if ( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $formModelObject->getForm()->customAmount ) {
			$validateCustomAmount = true;
		} elseif (
			MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $formModelObject->getForm()->customAmount
			&& 1 == $formModelObject->getForm()->allowListOfAmountsCustom
			&& MM_WPFS_Public_PaymentFormModel::INITIAL_CUSTOM_AMOUNT_INDEX == $formModelObject->getCustomAmountIndex()
		) {
			$validateCustomAmount = true;
		}

		return $validateCustomAmount;
	}

}

class MM_WPFS_InlinePaymentFormValidator extends MM_WPFS_PaymentFormValidator {

	protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_InlinePaymentFormModel ) {
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}

}

class MM_WPFS_PopupPaymentFormValidator extends MM_WPFS_PaymentFormValidator {

}

class MM_WPFS_SubscriptionFormValidator extends MM_WPFS_FormValidator {

	protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_SubscriptionFormModel ) {

			if ( is_null( $formModelObject->getStripePlan() ) ) {
				$fieldName = $formModelObject::PARAM_WPFS_STRIPE_PLAN;
				$fieldId   = MM_WPFS_Utils::generate_form_element_id( $fieldName, $formModelObject->getFormHash() );
				$error     = __( 'Invalid plan selected, please contact the site administrator.', 'wp-full-stripe' );
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			}

		}
	}

}

class MM_WPFS_InlineSubscriptionFormValidator extends MM_WPFS_SubscriptionFormValidator {

	protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_InlineSubscriptionFormModel ) {
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}

}

class MM_WPFS_PopupSubscriptionFormValidator extends MM_WPFS_SubscriptionFormValidator {

	protected function validateFields( $bindingResult, $formModelObject ) {
		if ( $formModelObject instanceof MM_WPFS_Public_PopupSubscriptionFormModel ) {
			if ( 1 == $formModelObject->getForm()->simpleButtonLayout ) {
				$this->ignore( MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT );
			}
		}
		parent::validateFields( $bindingResult, $formModelObject );
	}

}
