<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.04.10.
 * Time: 14:06
 */
class MM_WPFS_CardUpdateService {

	const PARAM_CARD_UPDATE_SESSION = 'wpfs-card-update-session';
	const PARAM_CARD_UPDATE_SECURITY_CODE = 'wpfs-security-code';
	const COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID = 'WPFS_CARD_UPDATE_SESSION_ID';
	const TEMPLATE_ENTER_EMAIL_ADDRESS = 'enter-email-address';
	const TEMPLATE_ENTER_SECURITY_CODE = 'enter-security-code';
	const TEMPLATE_INVALID_SESSION = 'invalid-session';
	const TEMPLATE_MANAGE_SUBSCRIPTIONS = 'manage-subscriptions';
	const SESSION_STATUS_WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';
	const SESSION_STATUS_CONFIRMED = 'confirmed';
	const SESSION_STATUS_INVALIDATED = 'invalidated';
	const SECURITY_CODE_STATUS_PENDING = 'pending';
	const SECURITY_CODE_STATUS_SENT = 'sent';
	const SECURITY_CODE_STATUS_CONSUMED = 'consumed';
	const SECURITY_CODE_REQUEST_LIMIT = 5;
	const SECURITY_CODE_INPUT_LIMIT = 5;
	const COOKIE_ACTION_SET = 'set';
	const COOKIE_ACTION_REMOVE = 'remove';
	const CARD_UPDATE_SESSION_VALID_UNTIL_HOURS = 1;
	const URL_RECAPTCHA_API_SITEVERIFY = 'https://www.google.com/recaptcha/api/siteverify';
	const SOURCE_GOOGLE_RECAPTCHA_V2_API_JS = 'https://www.google.com/recaptcha/api.js';
	const ASSET_DIR_CARDS_AND_SUBSCRIPTIONS = 'cards_and_subscriptions';
	const ASSET_ENTER_EMAIL_ADDRESS_PHP = 'enter-email-address.php';
	const ASSET_INVALID_SESSION_PHP = 'invalid-session.php';
	const ASSET_MANAGE_SUBSCRIPTIONS_PHP = 'manage-subscriptions.php';
	const ASSET_ENTER_SECURITY_CODE_PHP = 'enter-security-code.php';
	const ASSET_WPFS_MANAGE_SUBSCRIPTIONS_CSS = 'wpfs-manage-subscriptions.css';
	const ASSET_WPFS_MANAGE_SUBSCRIPTIONS_JS = 'wpfs-manage-subscriptions.js';
	const CARD_AMERICAN_EXPRESS = 'American Express';
	const CARD_DINERS_CLUB = 'Diners Club';
	const CARD_DISCOVER = 'Discover';
	const CARD_JCB = 'JCB';
	const CARD_MASTERCARD = 'MasterCard';
	const CARD_UNIONPAY = 'UnionPay';
	const CARD_VISA = 'Visa';
	const CARD_UNKNOWN = 'Unknown';
	const PARAM_WPFS_SUBSCRIPTION_ID = 'wpfs-subscription-id';
	const PARAM_EMAIL_ADDRESS = 'emailAddress';
	const PARAM_GOOGLE_RE_CAPTCHA_RESPONSE = 'googleReCAPTCHAResponse';
	const FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS = 'fullstripe_manage_subscriptions';
	const FULLSTRIPE_SHORTCODE_SUBSCRIPTION_UPDATE = 'fullstripe_subscription_update';

	const JS_VARIABLE_AJAX_URL = 'wpfsAjaxURL';
    const JS_VARIABLE_STRIPE_KEY = 'wpfsStripeKey';
    const JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY = 'wpfsGoogleReCAPTCHASiteKey';

    /**
	 * @deprecated
	 */
	const PARAM_SUBSCRIPTION_ID = 'subscriptionId';
	/**
	 * @deprecated
	 */
	const ASSET_FULLSTRIPE_CARD_UPDATE_CSS = 'fullstripe-card-update.css';
	/**
	 * @deprecated
	 */
	const ASSET_WP_FULL_STRIPE_CARD_UPDATE_JS = 'wp-full-stripe-card-update.js';
	/**
	 * @deprecated
	 */
	const ASSET_FULLSTRIPE_CARD_UPDATE_REQUEST_EMAIL_FORM_PHP = 'fullstripe_card_update_request_email_form.php';
	/**
	 * @deprecated
	 */
	const ASSET_FULLSTRIPE_INVALID_CARD_UPDATE_SESSION_PHP = 'fullstripe_invalid_card_update_session.php';
	/**
	 * @deprecated
	 */
	const ASSET_FULLSTRIPE_CARDS_AND_SUBSCRIPTIONS_PAGE_PHP = 'fullstripe_cards_and_subscriptions_page.php';
	/**
	 * @deprecated
	 */
	const ASSET_FULLSTRIPE_CARD_UPDATE_REQUEST_CODE_FORM_PHP = 'fullstripe_card_update_request_code_form.php';

	/**
	 * @deprecated
	 */
	const HANDLE_WP_FULL_STRIPE_CARD_UPDATE_JS = 'wp-full-stripe-card-update-js';
	/**
	 * @deprecated
	 */
	const HANDLE_CARD_UPDATE_CSS = 'fullstripe-card-update-css';
	/**
	 * @deprecated
	 */
	const PAGE_EMAIL_FORM = 'email_form';
	/**
	 * @deprecated
	 */
	const PAGE_SECURITY_CODE_FORM = 'security_code_form';
	/**
	 * @deprecated
	 */
	const PAGE_CARDS_AND_SUBSCRIPTIONS_TABLE = 'cards_and_subscriptions_table';

	const HANDLE_STRIPE_JS_V_3 = 'stripe-js-v3';
	const HANDLE_GOOGLE_RECAPTCHA_V_2 = 'google-recaptcha-v2';
	const HANDLE_MANAGE_SUBSCRIPTIONS_CSS = 'wpfs-manage-subscriptions-css';
	const HANDLE_MANAGE_SUBSCRIPTIONS_JS = 'wpfs-manage-subscriptions-js';

	/* @var bool */
	private $debugLog = false;

	/* @var $db MM_WPFS_Database */
	private $db = null;

	/* @var $stripe MM_WPFS_Payment_API */
	private $stripe = null;

	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	public function __construct() {
		$this->db     = new MM_WPFS_Database();
		$this->stripe = new MM_WPFS_Stripe();
		$this->mailer = new MM_WPFS_Mailer();
		$this->hooks();
	}

	private function hooks() {

		add_shortcode( self::FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS, array( $this, 'renderShortCode' ) );
		add_shortcode( self::FULLSTRIPE_SHORTCODE_SUBSCRIPTION_UPDATE, array( $this, 'renderShortCode' ) );

		add_action( 'fullstripe_check_card_update_sessions', array(
			$this,
			'checkCardUpdateSessionsAndCodes'
		) );

		add_action( 'wp_ajax_wp_full_stripe_create_card_update_session', array(
			$this,
			'handleCardUpdateSessionRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_create_card_update_session', array(
			$this,
			'handleCardUpdateSessionRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_reset_card_update_session', array(
			$this,
			'handleResetCardUpdateSessionRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_reset_card_update_session', array(
			$this,
			'handleResetCardUpdateSessionRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_validate_security_code', array(
			$this,
			'handleSecurityCodeValidationRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_validate_security_code', array(
			$this,
			'handleSecurityCodeValidationRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_update_card', array(
			$this,
			'handleCardUpdateRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_update_card', array(
			$this,
			'handleCardUpdateRequest'
		) );
		add_action( 'wp_ajax_wp_full_stripe_cancel_my_subscription', array(
			$this,
			'handleSubscriptionCancellationRequest'
		) );
		add_action( 'wp_ajax_nopriv_wp_full_stripe_cancel_my_subscription', array(
			$this,
			'handleSubscriptionCancellationRequest'
		) );

		// tnagy WPFS-861: prevent caching of pages generated by the shortcode
		add_action( 'send_headers', array( $this, 'addCacheControlHeader' ), 10, 1 );

		add_filter( 'script_loader_tag', array( $this, 'addAsyncDeferAttributes' ), 10, 2 );

	}

	public static function onActivation() {
		if ( ! wp_next_scheduled( 'fullstripe_check_card_update_sessions' ) ) {
			wp_schedule_event( time(), 'hourly', 'fullstripe_check_card_update_sessions' );
		}
	}

	public static function onDeactivation() {
		wp_clear_scheduled_hook( 'fullstripe_check_card_update_sessions' );
	}

	public function checkCardUpdateSessionsAndCodes() {
		try {

			// tnagy invalidate expired sessions
			$this->db->invalidate_expired_card_update_sessions( self::CARD_UPDATE_SESSION_VALID_UNTIL_HOURS );

			// tnagy invalidate sessions where security code request and security code input limits reached
			$this->db->invalidate_card_update_sessions_by_security_code_request_limit( self::SECURITY_CODE_REQUEST_LIMIT );
			$this->db->invalidate_card_update_sessions_by_security_code_input_limit( self::SECURITY_CODE_INPUT_LIMIT );

			// tnagy remove invalidated sessions
			$invalidatedSessionIdObjects = $this->db->find_invalidated_session_ids();
			$invalidatedSessionIds       = array_map( function ( $o ) {
				return $o->id;
			}, $invalidatedSessionIdObjects );
			if ( isset( $invalidatedSessionIds ) && sizeof( $invalidatedSessionIds ) > 0 ) {
				$this->db->delete_security_codes_by_sessions( $invalidatedSessionIds );
				$this->db->delete_invalidated_card_update_sessions( $invalidatedSessionIds );
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
		}
	}

	public function renderShortCode( $attributes ) {

		$cardUpdateSession = null;
		$cookieAction      = null;

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'renderShortCode(): COOKIE=' . print_r( $_COOKIE, true ) );
		}

		// tnagy pick up session by cookie
		$cardUpdateSessionHash = $this->findCardUpdateSessionCookieValue();
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'renderShortCode(): $cardUpdateSessionHash by cookie=' . $cardUpdateSessionHash );
		}
		if ( ! is_null( $cardUpdateSessionHash ) ) {
			$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
			if ( ! is_null( $cardUpdateSession ) ) {
				$isWaitingForConfirmation = $this->isWaitingForConfirmation( $cardUpdateSession );
				$isConfirmed              = $this->isConfirmed( $cardUpdateSession );
				if ( ! $isWaitingForConfirmation && ! $isConfirmed ) {
					$cardUpdateSession = null;
					$cookieAction      = self::COOKIE_ACTION_REMOVE;
				} elseif ( $isWaitingForConfirmation ) {
					$securityCode = $this->findSecurityCodeByRequest();
					if ( $this->debugLog ) {
						MM_WPFS_Utils::log( 'renderShortCode(): cardUpdateSessionHash=' . $cardUpdateSessionHash . ', securityCode=' . $securityCode );
					}
					if ( ! is_null( $securityCode ) ) {
						if ( ! is_null( $cardUpdateSession ) && $this->isWaitingForConfirmation( $cardUpdateSession ) && ! $this->securityCodeInputExhausted( $cardUpdateSession ) ) {
							$this->incrementSecurityCodeInput( $cardUpdateSession );
							$validationResult     = $this->validateSecurityCode( $cardUpdateSession, $securityCode );
							$valid                = $validationResult['valid'];
							$matchingSecurityCode = $validationResult['securityCode'];
							if ( $valid ) {
								$this->confirmCardUpdateSession( $cardUpdateSession, $matchingSecurityCode );
								// tnagy reload session after update
								$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
								$cookieAction      = self::COOKIE_ACTION_SET;
							} else {
								$cardUpdateSession = null;
								$cookieAction      = self::COOKIE_ACTION_REMOVE;
							}
						}
					}
				}
			}
		}

		// tnagy check request parameters to pick up existing card update session
		if ( is_null( $cardUpdateSession ) ) {
			$cardUpdateSessionHash = $this->findSessionHashByRequest();
			$securityCode          = $this->findSecurityCodeByRequest();

			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'renderShortCode(): cardUpdateSessionHash=' . $cardUpdateSessionHash . ', securityCode=' . $securityCode );
			}

			if ( ! is_null( $cardUpdateSessionHash ) && ! is_null( $securityCode ) ) {
				$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) && $this->isWaitingForConfirmation( $cardUpdateSession ) && ! $this->securityCodeInputExhausted( $cardUpdateSession ) ) {
					$this->incrementSecurityCodeInput( $cardUpdateSession );
					$validationResult     = $this->validateSecurityCode( $cardUpdateSession, $securityCode );
					$valid                = $validationResult['valid'];
					$matchingSecurityCode = $validationResult['securityCode'];
					if ( $valid ) {
						$this->confirmCardUpdateSession( $cardUpdateSession, $matchingSecurityCode );
						// tnagy reload session after update
						$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
						$cookieAction      = self::COOKIE_ACTION_SET;
					} else {
						$cardUpdateSession = null;
						$cookieAction      = self::COOKIE_ACTION_REMOVE;
					}
				}
			}
		}

		$model = new MM_WPFS_CardUpdateModel();

		if ( is_null( $cardUpdateSession ) ) {
			$templateToShow = self::TEMPLATE_ENTER_EMAIL_ADDRESS;
		} elseif ( $this->isWaitingForConfirmation( $cardUpdateSession ) ) {
			$templateToShow = self::TEMPLATE_ENTER_SECURITY_CODE;
		} elseif ( $this->isConfirmed( $cardUpdateSession ) ) {
			$templateToShow = self::TEMPLATE_MANAGE_SUBSCRIPTIONS;
		} else {
			$templateToShow = self::TEMPLATE_ENTER_EMAIL_ADDRESS;
		}

		$this->enqueueCardUpdateScript( $cookieAction, is_null( $cardUpdateSession ) ? null : $cardUpdateSession->hash );

		if ( self::TEMPLATE_ENTER_EMAIL_ADDRESS === $templateToShow ) {
			$content = $this->renderEmailForm( $attributes );
		} elseif ( self::TEMPLATE_ENTER_SECURITY_CODE === $templateToShow ) {
			$content = $this->renderSecurityCodeForm( $attributes );
		} elseif ( self::TEMPLATE_MANAGE_SUBSCRIPTIONS === $templateToShow ) {
			$stripeCustomer = MM_WPFS_Utils::find_existing_stripe_customer_by_email( $this->db, $this->stripe, $cardUpdateSession->email, true );
			$defaultSource  = null;
			if ( isset( $stripeCustomer ) ) {
				$model->setStripeCustomer( $stripeCustomer );
				if ( isset( $stripeCustomer->sources ) && isset( $stripeCustomer->sources->data ) ) {
					foreach ( $stripeCustomer->sources->data as $source ) {
						if ( is_null( $defaultSource ) ) {
							if ( $source->object == 'card' && $source->id == $stripeCustomer->default_source ) {
								$defaultSource = $source;
							}
						}
					}
				}
				if ( isset( $defaultSource ) ) {
					$model->setDefaultSource( $defaultSource );
					$model->setCardNumber( $defaultSource->last4 );
					if ( self::CARD_AMERICAN_EXPRESS === $defaultSource->brand ) {
						$model->setCardName( self::CARD_AMERICAN_EXPRESS );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'amex.png' ) );
					} elseif ( self::CARD_DINERS_CLUB === $defaultSource->brand ) {
						$model->setCardName( self::CARD_DINERS_CLUB );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'diners-club.png' ) );
					} elseif ( self::CARD_DISCOVER === $defaultSource->brand ) {
						$model->setCardName( self::CARD_DISCOVER );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'discover.png' ) );
					} elseif ( self::CARD_JCB === $defaultSource->brand ) {
						$model->setCardName( self::CARD_JCB );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'jcb.png' ) );
					} elseif ( self::CARD_MASTERCARD === $defaultSource->brand ) {
						$model->setCardName( self::CARD_MASTERCARD );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'mastercard.png' ) );
					} elseif ( self::CARD_UNIONPAY === $defaultSource->brand ) {
						$model->setCardName( self::CARD_UNIONPAY );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'unionpay.png' ) );
					} elseif ( self::CARD_VISA === $defaultSource->brand ) {
						$model->setCardName( self::CARD_VISA );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'visa.png' ) );
					} elseif ( self::CARD_UNKNOWN === $defaultSource->brand ) {
						$model->setCardName( self::CARD_UNKNOWN );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'generic.png' ) );
					} else {
						$model->setCardName( self::CARD_UNKNOWN );
						$model->setCardImageUrl( MM_WPFS_Assets::images( 'generic.png' ) );
					}
				}
			}
			$model->setSubscriptions( $this->prepareSubscriptions( $stripeCustomer ) );

			if ( $this->debugLog ) {
				MM_WPFS_Utils::log( 'renderShortCode(): model=' . print_r( $model, true ) );
			}

			$content = $this->renderCardsAndSubscriptionsTable( $attributes, $model );
		} else {
			$content = $this->renderInvalidCardUpdateSession( $attributes );
		}

		return $content;
	}

	/**
	 * @return null|string
	 */
	private function findCardUpdateSessionCookieValue() {
		return isset( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] ) ? sanitize_text_field( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] ) : null;
	}

	/**
	 * @param $cardUpdateSessionHash
	 *
	 * @return array|null|object|void
	 */
	private function findCardUpdateSessionByHash( $cardUpdateSessionHash ) {
		return $this->db->find_card_update_session_by_hash( $cardUpdateSessionHash );
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function isWaitingForConfirmation( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
			return self::SESSION_STATUS_WAITING_FOR_CONFIRMATION === $cardUpdateSession->status;
		} else {
			return false;
		}
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function isConfirmed( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
			return self::SESSION_STATUS_CONFIRMED === $cardUpdateSession->status;
		} else {
			return false;
		}
	}

	/**
	 * @return null|string
	 */
	private function findSecurityCodeByRequest() {
		return isset( $_REQUEST[ self::PARAM_CARD_UPDATE_SECURITY_CODE ] ) ? sanitize_text_field( $_REQUEST[ self::PARAM_CARD_UPDATE_SECURITY_CODE ] ) : null;
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function securityCodeInputExhausted( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->securityCodeInput ) ) {
			return $cardUpdateSession->securityCodeInput >= self::SECURITY_CODE_INPUT_LIMIT;
		}

		return true;
	}

	/**
	 * @param $cardUpdateSession
	 */
	private function incrementSecurityCodeInput( $cardUpdateSession ) {
		$this->db->increment_security_code_input( $cardUpdateSession->id );
	}

	public function validateSecurityCode( $cardUpdateSession, $securityCode ) {
		$valid                = false;
		$matchingSecurityCode = null;
		if ( isset( $cardUpdateSession ) && isset( $securityCode ) ) {
			$sanitizedSecurityCode = sanitize_text_field( $securityCode );
			$matchingSecurityCode  = $this->db->find_security_code_by_session_and_code( $cardUpdateSession->id, $sanitizedSecurityCode );
			if ( ! is_null( $matchingSecurityCode ) && $matchingSecurityCode->status !== self::SECURITY_CODE_STATUS_CONSUMED ) {
				$valid = true;
			}
		}

		if ( $this->isDemo() ) {
			$valid = true;
		}

		return array( 'valid' => $valid, 'securityCode' => $matchingSecurityCode );
	}

	/**
	 * @return bool
	 */
	private function isDemo() {
		return defined( 'WP_FULL_STRIPE_DEMO_MODE' );
	}

	/**
	 * @param $cardUpdateSession
	 * @param $matchingSecurityCode
	 */
	private function confirmCardUpdateSession( $cardUpdateSession, $matchingSecurityCode ) {
		$this->db->update_card_update_session( $cardUpdateSession->id, array( 'status' => self::SESSION_STATUS_CONFIRMED ) );
		$this->db->update_security_code( $matchingSecurityCode->id, array(
			'consumed' => current_time( 'mysql' ),
			'status'   => self::SECURITY_CODE_STATUS_CONSUMED
		) );
	}

	/**
	 * @return null|string
	 */
	private function findSessionHashByRequest() {
		return isset( $_REQUEST[ self::PARAM_CARD_UPDATE_SESSION ] ) ? sanitize_text_field( $_REQUEST[ self::PARAM_CARD_UPDATE_SESSION ] ) : null;
	}

	private function enqueueCardUpdateScript( $cookieAction, $cardUpdateSessionHash ) {

		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'enqueueCardUpdateScript() CALLED, cookieAction=' . $cookieAction . ', cardUpdateSessionHash=' . $cardUpdateSessionHash );
		}

		wp_register_style( MM_WPFS::HANDLE_STYLE_WPFS_VARIABLES, MM_WPFS_Assets::css( 'wpfs-variables.css' ), null, MM_WPFS::VERSION );
		wp_enqueue_style( self::HANDLE_MANAGE_SUBSCRIPTIONS_CSS, MM_WPFS_Assets::css( self::ASSET_WPFS_MANAGE_SUBSCRIPTIONS_CSS ), array( MM_WPFS::HANDLE_STYLE_WPFS_VARIABLES ), MM_WPFS::VERSION );

		wp_register_script( self::HANDLE_STRIPE_JS_V_3, 'https://js.stripe.com/v3/', array( 'jquery' ) );
		wp_register_script( MM_WPFS::HANDLE_SPRINTF_JS, MM_WPFS_Assets::scripts( 'sprintf.min.js' ), null, MM_WPFS::VERSION );

		if ( MM_WPFS_Utils::get_secure_subscription_update_with_google_recaptcha() ) {
			$source = add_query_arg(
				array(
					'render' => 'explicit'
				),
				MM_WPFS::SOURCE_GOOGLE_RECAPTCHA_V2_API_JS
			);
			wp_register_script( MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2, $source, null, MM_WPFS::VERSION, true /* in footer */ );
			$dependencies = array(
				'jquery',
				MM_WPFS::HANDLE_SPRINTF_JS,
				self::HANDLE_STRIPE_JS_V_3,
				MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2
			);
		} else {
			$dependencies = array(
				'jquery',
				MM_WPFS::HANDLE_SPRINTF_JS,
				self::HANDLE_STRIPE_JS_V_3
			);
		}

		wp_enqueue_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, MM_WPFS_Assets::scripts( self::ASSET_WPFS_MANAGE_SUBSCRIPTIONS_JS ), $dependencies, MM_WPFS::VERSION );

		wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, self::JS_VARIABLE_AJAX_URL, admin_url( 'admin-ajax.php' ) );
		$options = get_option( 'fullstripe_options' );
		if ( $options['apiMode'] === 'test' ) {
			wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, self::JS_VARIABLE_STRIPE_KEY, $options['publishKey_test'] );
		} else {
			wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, self::JS_VARIABLE_STRIPE_KEY, $options['publishKey_live'] );
		}

		$cardUpdateSessionData         = array();
		$cardUpdateSessionData['i18n'] = array(
			'confirmSubscriptionCancellationMessage'        => __( 'Are you sure you\'d like to cancel the selected subscriptions?', 'wp-full-stripe' ),
			'selectAtLeastOneSubscription'                  => __( 'Select at least one subscription!', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionDefault'  => __( 'Cancel subscription', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionSingular' => __( 'Cancel 1 subscription', 'wp-full-stripe' ),
			'cancelSubscriptionSubmitButtonCaptionPlural'   => __( 'Cancel %d subscriptions', 'wp-full-stripe' )
		);
		if ( self::COOKIE_ACTION_SET === $cookieAction ) {
			$cardUpdateSessionData['action']                = 'setCookie';
			$cardUpdateSessionData['cookieName']            = self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID;
			$cardUpdateSessionData['cookieValidUntilhours'] = self::CARD_UPDATE_SESSION_VALID_UNTIL_HOURS;
			$cardUpdateSessionData['cookiePath']            = COOKIEPATH;
			$cardUpdateSessionData['cookieDomain']          = COOKIE_DOMAIN;
		} elseif ( self::COOKIE_ACTION_REMOVE === $cookieAction ) {
			$cardUpdateSessionData['cookieName'] = self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID;
			$cardUpdateSessionData['action']     = 'removeCookie';
		}
		if ( ! is_null( $cardUpdateSessionHash ) ) {
			$cardUpdateSessionData['sessionId'] = $cardUpdateSessionHash;
		}
		wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, 'wpfsCardUpdateSessionData', $cardUpdateSessionData );
		wp_localize_script( self::HANDLE_MANAGE_SUBSCRIPTIONS_JS, self::JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY, MM_WPFS_Utils::get_google_recaptcha_site_key() );

	}

	public function renderEmailForm( $attributes ) {

		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CARDS_AND_SUBSCRIPTIONS . DIRECTORY_SEPARATOR . self::ASSET_ENTER_EMAIL_ADDRESS_PHP );
		$content = ob_get_clean();

		return $content;

	}

	public function renderSecurityCodeForm( $attributes ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CARDS_AND_SUBSCRIPTIONS . DIRECTORY_SEPARATOR . self::ASSET_ENTER_SECURITY_CODE_PHP );
		$content = ob_get_clean();

		return $content;
	}

	private function prepareSubscriptions( $stripeCustomer ) {
		$subscriptions = array();
		if ( isset( $stripeCustomer ) && isset( $stripeCustomer->subscriptions ) && isset( $stripeCustomer->subscriptions->data ) ) {
			$subscriptions = $stripeCustomer->subscriptions->data;
		}

		return $subscriptions;
	}

	/**
	 * @param $attributes
	 * @param MM_WPFS_CardUpdateModel $model
	 *
	 * @return string
	 */
	public function renderCardsAndSubscriptionsTable( $attributes, $model ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CARDS_AND_SUBSCRIPTIONS . DIRECTORY_SEPARATOR . self::ASSET_MANAGE_SUBSCRIPTIONS_PHP );
		$content = ob_get_clean();

		return $content;
	}

	public function renderInvalidCardUpdateSession( $attributes ) {
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include MM_WPFS_Assets::templates( self::ASSET_DIR_CARDS_AND_SUBSCRIPTIONS . DIRECTORY_SEPARATOR . self::ASSET_INVALID_SESSION_PHP );
		$content = ob_get_clean();

		return $content;
	}

	public function handleCardUpdateSessionRequest() {

		$return = array();

		try {

			$stripeCustomerEmail     = isset( $_POST[ self::PARAM_EMAIL_ADDRESS ] ) ? sanitize_email( $_POST[ self::PARAM_EMAIL_ADDRESS ] ) : null;
			$googleReCAPTCHAResponse = isset( $_POST[ self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE ] ) ? sanitize_text_field( $_POST[ self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE ] ) : null;

			$validRequest = true;
			if ( is_null( $stripeCustomerEmail ) || ! filter_var( $stripeCustomerEmail, FILTER_VALIDATE_EMAIL ) ) {
				$return['success']    = false;
				$return['message']    = __( 'The entered email address is invalid.', 'wp-full-stripe' );
				$return['fieldError'] = self::PARAM_EMAIL_ADDRESS;
				$validRequest         = false;
			}
			$verifyReCAPTCHA = MM_WPFS_Utils::get_secure_subscription_update_with_google_recaptcha();
			if ( $verifyReCAPTCHA && $validRequest ) {
				if ( is_null( $googleReCAPTCHAResponse ) ) {
					$return['success']    = false;
					$return['message']    = __( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
					$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
					$validRequest         = false;
				} else {
					$googleReCAPTCHVerificationResult = MM_WPFS_Utils::verifyReCAPTCHA( $googleReCAPTCHAResponse );
					// MM_WPFS_Utils::log( 'googleReCAPTCHVerificationResult=' . print_r( $googleReCAPTCHVerificationResult, true ) );
					if ( $googleReCAPTCHVerificationResult === false ) {
						$return['success']    = false;
						$return['message']    = __( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
						$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
						$validRequest         = false;
					} elseif ( ! isset( $googleReCAPTCHVerificationResult->success ) || $googleReCAPTCHVerificationResult->success === false ) {
						$return['success']    = false;
						$return['message']    = __( 'Please prove that you are not a robot. ', 'wp-full-stripe' );
						$return['fieldError'] = self::PARAM_GOOGLE_RE_CAPTCHA_RESPONSE;
						if ( $this->debugLog ) {
							MM_WPFS_Utils::log( 'handleCardUpdateSessionRequest(): reCAPTCHA error response=' . print_r( $googleReCAPTCHVerificationResult, true ) );
						}
						$validRequest = false;
					}
				}
			}

			$stripeCustomer = null;
			if ( $validRequest ) {
				$stripeCustomer = MM_WPFS_Utils::find_existing_stripe_customer_by_email( $this->db, $this->stripe, $stripeCustomerEmail, true );
				if ( is_null( $stripeCustomer ) ) {
					$return['success']    = false;
					$return['message']    = __( 'The entered email address is invalid.', 'wp-full-stripe' );
					$return['fieldError'] = self::PARAM_EMAIL_ADDRESS;
					$validRequest         = false;
				}
			}

			if ( $validRequest ) {

				$cardUpdateSession = $this->findValidCardUpdateSessionByEmailAndCustomer( $stripeCustomerEmail, $stripeCustomer->id );

				if ( ! is_null( $cardUpdateSession ) ) {
					$cardUpdateSessionCookieValue = $this->findCardUpdateSessionCookieValue();
					if ( $cardUpdateSession->hash !== $cardUpdateSessionCookieValue ) {
						$this->invalidate( $cardUpdateSession );
						$cardUpdateSession = null;
					}
				}

				if ( is_null( $cardUpdateSession ) || $this->isInvalidated( $cardUpdateSession ) ) {
					$options           = get_option( 'fullstripe_options' );
					$liveMode          = $options['apiMode'] === 'live';
					$cardUpdateSession = $this->createCardUpdateSession( $stripeCustomerEmail, $liveMode, $stripeCustomer->id );
				}

				$this->createCardUpdateSessionCookie( $cardUpdateSession );

				$this->createAndSendSecurityCodeAsEmail( $cardUpdateSession, $stripeCustomer );

				$return['success'] = true;
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	private function findValidCardUpdateSessionByEmailAndCustomer( $stripeCustomerEmail, $stripeCustomerId ) {
		$cardUpdateSessions = $this->db->find_card_update_sessions_by_email_and_customer( $stripeCustomerEmail, $stripeCustomerId );

		$validCardUpdateSession = null;
		if ( isset( $cardUpdateSessions ) ) {
			foreach ( $cardUpdateSessions as $cardUpdateSession ) {
				if ( is_null( $validCardUpdateSession ) && ! $this->isInvalidated( $cardUpdateSession ) ) {
					$validCardUpdateSession = $cardUpdateSession;
				}
			}
		}

		return $validCardUpdateSession;
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function isInvalidated( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
			return self::SESSION_STATUS_INVALIDATED === $cardUpdateSession->status;
		} else {
			return false;
		}
	}

	/**
	 * @param $cardUpdateSession
	 */
	private function invalidate( $cardUpdateSession ) {
		$this->db->update_card_update_session( $cardUpdateSession->id, array( 'status' => self::SESSION_STATUS_INVALIDATED ) );
	}

	public function createCardUpdateSession( $stripeCustomerEmail, $liveMode, $stripeCustomerId ) {

		$salt = wp_generate_password( 16, false );
		$data = time() . '|' . $stripeCustomerEmail . '|' . $liveMode . '|' . $stripeCustomerId . '|' . $salt;

		$cardUpdateSessionHash = hash( 'sha256', $data );

		$insertResult = $this->db->insert_card_update_session( $stripeCustomerEmail, $liveMode, $stripeCustomerId, $cardUpdateSessionHash );

		if ( $insertResult !== - 1 ) {
			return $this->findValidCardUpdateSessionByEmailAndCustomer( $stripeCustomerEmail, $stripeCustomerId );
		}

		return null;
	}

	/**
	 * @param $cardUpdateSession
	 */
	private function createCardUpdateSessionCookie( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) ) {
			setcookie( self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID, $cardUpdateSession->hash, time() + HOUR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	private function createAndSendSecurityCodeAsEmail( $cardUpdateSession, $stripeCustomer ) {
		try {
			if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->status ) ) {
				if ( self::SESSION_STATUS_WAITING_FOR_CONFIRMATION === $cardUpdateSession->status ) {
					if ( ! $this->securityCodeRequestExhausted( $cardUpdateSession ) ) {
						$securityCode   = wp_generate_password( 8, false );
						$securityCodeId = $this->db->insert_security_code( $cardUpdateSession->id, $securityCode );
						if ( $securityCodeId !== - 1 ) {
							$this->incrementSecurityCodeRequest( $cardUpdateSession );
							if ( ! $this->isDemo() ) {
								$this->mailer->send_card_update_confirmation_request( MM_WPFS_Utils::retrieve_customer_name( $stripeCustomer ), $cardUpdateSession->email, $cardUpdateSession->hash, $securityCode );
							}
							$this->db->update_security_code( $securityCodeId, array(
								'sent'   => current_time( 'mysql' ),
								'status' => self::SECURITY_CODE_STATUS_SENT
							) );
						}
					} else {
						$this->invalidate( $cardUpdateSession );
					}
				}
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
		}
	}

	/**
	 * @param $cardUpdateSession
	 *
	 * @return bool
	 */
	private function securityCodeRequestExhausted( $cardUpdateSession ) {
		if ( isset( $cardUpdateSession ) && isset( $cardUpdateSession->securityCodeRequest ) ) {

			return $cardUpdateSession->securityCodeRequest >= self::SECURITY_CODE_REQUEST_LIMIT;
		}

		return true;
	}

	private function incrementSecurityCodeRequest( $cardUpdateSession ) {
		$this->db->increment_security_code_request( $cardUpdateSession->id );
	}

	public function handleResetCardUpdateSessionRequest() {
		$return = array();

		try {

			$cardUpdateSessionHash = $this->findCardUpdateSessionCookieValue();
			if ( ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) ) {
					if ( $this->isWaitingForConfirmation( $cardUpdateSession ) || $this->isConfirmed( $cardUpdateSession ) ) {
						$this->invalidate( $cardUpdateSession );
					}
				}
			}

			$return['success'] = true;
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	public function handleSecurityCodeValidationRequest() {
		$return = array();

		try {

			$cardUpdateSessionHash = $this->findCardUpdateSessionCookieValue();
			$securityCode          = isset( $_POST['securityCode'] ) ? sanitize_text_field( $_POST['securityCode'] ) : null;
			if ( is_null( $securityCode ) || empty( $securityCode ) ) {
				$return['success'] = false;
				$return['message'] = __( 'Enter a security code', 'wp-full-stripe' );
			} else {
				$success = false;
				if ( ! is_null( $cardUpdateSessionHash ) ) {
					$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
					if ( ! is_null( $cardUpdateSession ) && $this->isWaitingForConfirmation( $cardUpdateSession ) && ! $this->securityCodeInputExhausted( $cardUpdateSession ) ) {
						$this->incrementSecurityCodeInput( $cardUpdateSession );
						$validationResult     = $this->validateSecurityCode( $cardUpdateSession, $securityCode );
						$valid                = $validationResult['valid'];
						$matchingSecurityCode = $validationResult['securityCode'];
						if ( $valid ) {
							$this->confirmCardUpdateSession( $cardUpdateSession, $matchingSecurityCode );
							$success = true;
						} else {
							$this->deleteCardUpdateSessionCookie();
						}
					}
				}

				if ( $success ) {
					$return['success'] = true;
				} else {
					$return['success'] = false;
					$return['message'] = __( 'Enter a valid security code', 'wp-full-stripe' );
				}
			}
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;
	}

	private function deleteCardUpdateSessionCookie() {
		unset( $_COOKIE[ self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID ] );
		setcookie( self::COOKIE_NAME_WPFS_CARD_UPDATE_SESSION_ID, '', time() - DAY_IN_SECONDS );
	}

	public function handleCardUpdateRequest() {
		$return = array();
		try {
			$stripeToken = isset( $_POST['token'] ) ? $_POST['token'] : null;
			// MM_WPFS_Utils::log( 'handleCardUpdateRequest(): stripeToken=' . print_r( $stripeToken, true ) );
			$cardUpdateSessionHash = $this->findCardUpdateSessionCookieValue();
			if ( ! is_null( $stripeToken ) && ! is_null( $cardUpdateSessionHash ) ) {
				$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
				if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
					$stripeCustomer = $this->stripe->retrieve_customer( $cardUpdateSession->stripeCustomerId );
					if ( isset( $stripeCustomer ) ) {
						$this->stripe->add_customer_source( $stripeCustomer->id, $stripeToken['id'] );
						// MM_WPFS_Utils::log( 'handleCardUpdateRequest(): token added to customer' );
					}
				}
			}

			$return['success'] = true;
			$return['message'] = __( 'The default credit card has been updated successfully', 'wp-full-stripe' );
		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	public function handleSubscriptionCancellationRequest() {
		$return = array();
		try {
			$subscriptionIdsToCancel = isset( $_POST[ self::PARAM_WPFS_SUBSCRIPTION_ID ] ) ? $_POST[ self::PARAM_WPFS_SUBSCRIPTION_ID ] : null;
			if ( isset( $subscriptionIdsToCancel ) && count( $subscriptionIdsToCancel ) > 0 ) {
				$cardUpdateSessionHash = $this->findCardUpdateSessionCookieValue();
				if ( ! is_null( $subscriptionIdsToCancel ) && ! is_null( $cardUpdateSessionHash ) ) {
					$cardUpdateSession = $this->findCardUpdateSessionByHash( $cardUpdateSessionHash );
					if ( ! is_null( $cardUpdateSession ) && $this->isConfirmed( $cardUpdateSession ) ) {
						$stripeCustomer = $this->stripe->retrieve_customer( $cardUpdateSession->stripeCustomerId );
						if ( isset( $stripeCustomer ) ) {
							foreach ( $subscriptionIdsToCancel as $subscriptionId ) {
								$this->db->cancel_subscription_by_stripe_subscription_id( $subscriptionId );
								$this->stripe->cancel_subscription( $stripeCustomer->id, $subscriptionId );
							}
						}
					}
				}
				$return['success'] = true;
				$return['message'] = __( 'The subscriptions have been cancelled', 'wp-full-stripe' );
			} else {
				$return['success'] = false;
				$return['message'] = __( 'Select at least one subscription!', 'wp-full-stripe' );
			}

		} catch ( Exception $e ) {
			MM_WPFS_Utils::logException( $e, $this );
			$return['success']    = false;
			$return['ex_code']    = $e->getCode();
			$return['ex_message'] = $e->getMessage();
		}

		header( "Content-Type: application/json" );
		echo json_encode( $return );
		exit;

	}

	/**
	 * Adds Cache-Control HTTP header if a page is displayed with the "Manage Subscriptions" shortcode
	 *
	 * @param $theWPObject
	 */
	public function addCacheControlHeader( $theWPObject ) {
		$started = round( microtime( true ) * 1000 );
		if ( ! is_null( $theWPObject ) && isset( $theWPObject->request ) ) {
			$pageByPath = get_page_by_path( $theWPObject->request );
			if ( ! is_null( $pageByPath ) && isset( $pageByPath->post_content ) ) {
				if ( has_shortcode( $pageByPath->post_content, self::FULLSTRIPE_SHORTCODE_SUBSCRIPTION_UPDATE ) ||
				     has_shortcode( $pageByPath->post_content, self::FULLSTRIPE_SHORTCODE_MANAGE_SUBSCRIPTIONS )
				) {
					header( 'Cache-Control: no-store, no-cache, must-revalidate' );
				}
			}
		}
		$finished = round( microtime( true ) * 1000 ) - $started;
		if ( $this->debugLog ) {
			MM_WPFS_Utils::log( 'addCacheControlHeader(): finished in ' . $finished . 'ms' );
		}
	}

	public function addAsyncDeferAttributes( $tag, $handle ) {
		if ( MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2 !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' async defer src', $tag );
	}

	private function prepareProducts( $subscriptions ) {
		$products = array();
		if ( isset( $subscriptions ) && count( $subscriptions ) > 0 ) {
			$productIds = array();
			foreach ( $subscriptions as $subscription ) {
				if ( isset( $subscription ) && isset( $subscription->plan ) && isset( $subscription->plan->product ) ) {
					array_push( $productIds, $subscription->plan->product );
				}
			}
			$productIds = array_unique( $productIds );

			$associativeArray = true;
			$products         = $this->stripe->get_products( $associativeArray, $productIds );
		}

		return $products;
	}

}

class MM_WPFS_CardUpdateModel {

	/**
	 * @var Stripe\Customer
	 */
	private $stripeCustomer;
	/**
	 * @var Stripe\Card
	 */
	private $defaultSource;
	/**
	 * @var string
	 */
	private $cardImageUrl;
	/**
	 * @var string
	 */
	private $cardName;
	/**
	 * @var string
	 */
	private $cardNumber;
	/**
	 * @var array
	 */
	private $subscriptions = array();
	/**
	 * @var array
	 */
	private $products = array();

	/**
	 * @return \Stripe\Customer
	 */
	public function getStripeCustomer() {
		return $this->stripeCustomer;
	}

	/**
	 * @param \Stripe\Customer $stripeCustomer
	 */
	public function setStripeCustomer( $stripeCustomer ) {
		$this->stripeCustomer = $stripeCustomer;
	}

	/**
	 * @return \Stripe\Card
	 */
	public function getDefaultSource() {
		return $this->defaultSource;
	}

	/**
	 * @param \Stripe\Card $defaultSource
	 */
	public function setDefaultSource( $defaultSource ) {
		$this->defaultSource = $defaultSource;
	}

	/**
	 * @return string
	 */
	public function getCardImageUrl() {
		return $this->cardImageUrl;
	}

	/**
	 * @param string $cardImageUrl
	 */
	public function setCardImageUrl( $cardImageUrl ) {
		$this->cardImageUrl = $cardImageUrl;
	}

	/**
	 * @return string
	 */
	public function getCardName() {
		return $this->cardName;
	}

	/**
	 * @param string $cardName
	 */
	public function setCardName( $cardName ) {
		$this->cardName = $cardName;
	}

	/**
	 * @return string
	 */
	public function getCardNumber() {
		return sprintf( 'x-%s', $this->cardNumber );
	}

	/**
	 * @param string $cardNumber
	 */
	public function setCardNumber( $cardNumber ) {
		$this->cardNumber = $cardNumber;
	}

	public function getExpiration() {
		return sprintf( '%02d / %d', $this->defaultSource->exp_month, $this->defaultSource->exp_year );
	}

	/**
	 * @return array
	 */
	public function getSubscriptions() {
		return $this->subscriptions;
	}

	/**
	 * @param array $subscriptions
	 */
	public function setSubscriptions( $subscriptions ) {
		$this->subscriptions = $subscriptions;
	}

	/**
	 * @return array
	 */
	public function getProducts() {
		return $this->products;
	}

	/**
	 * @param array $products
	 */
	public function setProducts( $products ) {
		$this->products = $products;
	}

	/**
	 * @return null|string
	 */
	public function getCustomerEmail() {
		if ( isset( $this->stripeCustomer ) ) {
			return $this->stripeCustomer->email;
		}

		return null;
	}

}

class MM_WPFS_ManagedSubscriptionEntry {

	const PARAM_WPFS_SUBSCRIPTION_ID = 'wpfs-subscription-id[]';

	/**
	 * @var Stripe\Subscription
	 */
	private $subscription;

	/**
	 * MM_WPFS_SubscriptionItem constructor.
	 *
	 * @param Stripe\Subscription $subscription
	 */
	public function __construct( $subscription ) {
		$this->subscription = $subscription;
	}

	public function getId() {
		return sprintf( 'wpfs-subscription--%s', $this->subscription->id );
	}

	public function getName() {
		return self::PARAM_WPFS_SUBSCRIPTION_ID;
	}


	public function getValue() {
		return $this->subscription->id;
	}

	public function getPlanName() {
		$planName = __( 'Unknown', 'wp-full-stripe' );
		if ( isset( $this->subscription ) && isset( $this->subscription->plan ) ) {
			$planName = $this->subscription->plan->name;
		}

		return $planName;
	}

	public function getCreated() {
		$dateFormat = get_option( 'date_format' );

		return date( $dateFormat, $this->subscription->created );
	}

	public function getStatus() {
		return MM_WPFS_Utils::translate_label( ucfirst( $this->subscription->status ) );
	}

	public function getClass() {
		$clazz = '';
		if ( MM_WPFS::STRIPE_SUBSCRIPTION_STATUS_TRAILING === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--trialing';
		} elseif ( MM_WPFS::STRIPE_SUBSCRIPTION_STATUS_ACTIVE === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--active';
		} elseif ( MM_WPFS::STRIPE_SUBSCRIPTION_STATUS_PAST_DUE === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--past-due';
		} elseif ( MM_WPFS::STRIPE_SUBSCRIPTION_STATUS_CANCELED === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--past-due';
		} elseif ( MM_WPFS::STRIPE_SUBSCRIPTION_STATUS_UNPAID === $this->subscription->status ) {
			$clazz = 'wpfs-subscription-status--unpaid';
		}

		return $clazz;
	}

	public function getPriceAndInterval() {
		$formattedAmount = MM_WPFS_Utils::format_amount( $this->subscription->plan->currency, $this->subscription->plan->amount );
		$currency        = strtoupper( $this->subscription->plan->currency );
		$interval        = MM_WPFS_Utils::format_interval_label( $this->subscription->plan->interval, $this->subscription->plan->interval_count );

		return sprintf( '%s %s / %s', $formattedAmount, $currency, $interval );
	}

}