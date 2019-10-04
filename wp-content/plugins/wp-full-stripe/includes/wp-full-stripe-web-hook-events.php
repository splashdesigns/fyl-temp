<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.06.15.
 * Time: 13:25
 */
class MM_WPFS_WebHookEventHandler {

	/* @var $db MM_WPFS_Database */
	private $db = null;
	/* @var $stripe MM_WPFS_Stripe */
	private $stripe = null;
	/* @var $mailer MM_WPFS_Mailer */
	private $mailer = null;

	private $event_processors = array();

	/**
	 * MM_WPFS_WebHookEventHandler constructor.
	 *
	 * @param MM_WPFS_Database $db
	 * @param MM_WPFS_Stripe $stripe
	 * @param MM_WPFS_Mailer $mailer
	 */
	public function __construct( MM_WPFS_Database $db, MM_WPFS_Stripe $stripe, MM_WPFS_Mailer $mailer ) {
		$this->db     = $db;
		$this->stripe = $stripe;
		$this->mailer = $mailer;
		$this->init_processors();
	}

	private function init_processors() {
		$processors = array(
			new MM_WPFS_CustomerSubscriptionDeleted( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_InvoiceCreated( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_InvoicePaymentSucceeded( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeCaptured( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeExpired( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeFailed( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargePending( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeRefunded( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeSucceeded( $this->db, $this->stripe, $this->mailer ),
			new MM_WPFS_ChargeUpdated( $this->db, $this->stripe, $this->mailer )
		);
		foreach ( $processors as $processor ) {
			$this->event_processors[ $processor->get_type() ] = $processor;
		}
	}

	public function handle( $event ) {
		$event_processed = false;
		if ( isset( $event ) && isset( $event->type ) ) {
			$event_processor = null;
			if ( array_key_exists( $event->type, $this->event_processors ) ) {
				$event_processor = $this->event_processors[ $event->type ];
			}
			if ( $event_processor instanceof MM_WPFS_WebHookEventProcessor ) {
				$event_processor->process( $event );
				$event_processed = true;
			}
		}

		return $event_processed;
	}

}

abstract class MM_WPFS_WebHookEventProcessor {

	const STRIPE_API_VERSION_2018_02_28 = '2018-02-28';
	const STRIPE_API_VERSION_2018_05_21 = '2018-05-21';

	/* @var $db MM_WPFS_Database */
	protected $db = null;
	/* @var $stripe MM_WPFS_Stripe */
	protected $stripe = null;
	/* @var $mailer MM_WPFS_Mailer */
	protected $mailer = null;
	/* @var boolean */
	protected $debug_log = false;

	/**
	 * MM_WPFS_WebHookEventProcessor constructor.
	 *
	 * @param MM_WPFS_Database $db
	 * @param MM_WPFS_Stripe $stripe
	 * @param MM_WPFS_Mailer $mailer
	 */
	public function __construct( MM_WPFS_Database $db, MM_WPFS_Stripe $stripe, MM_WPFS_Mailer $mailer ) {
		$this->db     = $db;
		$this->stripe = $stripe;
		$this->mailer = $mailer;
	}

	public final function process( $event_object ) {
		if ( $this->get_type() === $event_object->type ) {
			$this->process_event( $event_object );
		}
	}

	public abstract function get_type();

	protected function process_event( $event ) {
		// tnagy default implementation, override in subclasses
	}

	/**
	 * @param $event
	 *
	 * @return null|\Stripe\Charge
	 */
	protected function get_data_object( $event ) {
		$object = null;
		if ( isset( $event ) && isset( $event->data ) && isset( $event->data->object ) ) {
			$object = $event->data->object;
		}

		return $object;
	}

}

abstract class MM_WPFS_InvoiceWebHookEventProcessor extends MM_WPFS_WebHookEventProcessor {

	const INVOICE_ITEM_TYPE_SUBSCRIPTION = 'subscription';

	protected function get_subscription_id_from_line( $event, $line ) {
		$stripe_subscription_id        = null;
		$stripe_subscription_id_source = null;
		if ( strtotime( self::STRIPE_API_VERSION_2018_05_21 ) <= strtotime( $event->api_version ) ) {
			if ( self::INVOICE_ITEM_TYPE_SUBSCRIPTION === $line->type ) {
				$stripe_subscription_id        = $line->subscription;
				$stripe_subscription_id_source = 'subscription';
			}
		} else {
			$stripe_subscription_id        = $line->id;
			$stripe_subscription_id_source = 'id';
		}

		if ( $this->debug_log ) {
			MM_WPFS_Utils::log( 'MM_WPFS_InvoiceWebHookEventProcessor->get_subscription_id_from_line(): ' . "api_version={$event->api_version}, stripe_subscription_id={$stripe_subscription_id}, stripe_subscription_id_source={$stripe_subscription_id_source}" );
		}

		return $stripe_subscription_id;
	}

}

class MM_WPFS_CustomerSubscriptionDeleted extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CUSTOMER_SUBSCRIPTION_DELETED = 'customer.subscription.deleted';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CUSTOMER_SUBSCRIPTION_DELETED;
	}

	protected function process_event( $event ) {
		$stripe_subscription = $this->get_data_object( $event );
		if ( ! is_null( $stripe_subscription ) ) {
			$subscription = $this->db->find_subscription_by_stripe_subscription_id( $stripe_subscription->id );
			if ( isset( $subscription ) ) {
				if ( MM_WPFS::SUBSCRIPTION_STATUS_ENDED !== $subscription->status && MM_WPFS::SUBSCRIPTION_STATUS_CANCELLED !== $subscription->status ) {
					if ( $subscription->chargeMaximumCount > 0 ) {
						if ( $subscription->chargeCurrentCount >= $subscription->chargeMaximumCount ) {
							$this->db->complete_subscription_by_stripe_subscription_id( $stripe_subscription->id );
						} else {
							$this->db->cancel_subscription_by_stripe_subscription_id( $stripe_subscription->id );
						}
					} else {
						$this->db->cancel_subscription_by_stripe_subscription_id( $stripe_subscription->id );
					}
				}
			}
		}
	}

}

class MM_WPFS_InvoicePaymentSucceeded extends MM_WPFS_InvoiceWebHookEventProcessor {

	const WEB_HOOK_EVENT_INVOICE_PAYMENT_SUCCEEDED = 'invoice.payment_succeeded';

	public function get_type() {
		return self::WEB_HOOK_EVENT_INVOICE_PAYMENT_SUCCEEDED;
	}

	protected function process_event( $event ) {
		foreach ( $event->data->object->lines->data as $line ) {
			$subscription           = null;
			$stripe_subscription_id = $this->get_subscription_id_from_line( $event, $line );
			if ( $this->debug_log ) {
				MM_WPFS_Utils::log( 'MM_WPFS_InvoicePaymentSucceeded->process_event(): ' . "stripe_subscription_id=$stripe_subscription_id" );
			}
			if ( ! is_null( $stripe_subscription_id ) ) {
				$subscription = $this->db->find_subscription_by_stripe_subscription_id( $stripe_subscription_id );
			}
			if ( isset( $subscription ) ) {
				if ( 'ended' !== $subscription->status && 'cancelled' !== $subscription->status ) {
					$this->db->update_subscription_with_payment( $stripe_subscription_id );
				} else {
					if ( $this->debug_log ) {
						MM_WPFS_Utils::log( 'MM_WPFS_InvoicePaymentSucceeded->process_event(): ' . "subscription status is 'ended' or 'cancelled', skip" );
					}
				}
			} else {
				if ( $this->debug_log ) {
					MM_WPFS_Utils::log( 'MM_WPFS_InvoicePaymentSucceeded->process_event(): ' . "subscription not found" );
				}
			}
		}
	}

}

class MM_WPFS_InvoiceCreated extends MM_WPFS_InvoiceWebHookEventProcessor {

	const WEB_HOOK_EVENT_INVOICE_CREATED = 'invoice.created';

	public function get_type() {
		return self::WEB_HOOK_EVENT_INVOICE_CREATED;
	}

	protected function process_event( $event ) {
		foreach ( $event->data->object->lines->data as $line ) {
			$subscription           = null;
			$stripe_subscription_id = $this->get_subscription_id_from_line( $event, $line );
			if ( $this->debug_log ) {
				MM_WPFS_Utils::log( 'MM_WPFS_InvoiceCreated->process_event(): ' . "stripe_subscription_id=$stripe_subscription_id" );
			}
			if ( ! is_null( $stripe_subscription_id ) ) {
				$subscription = $this->db->find_subscription_by_stripe_subscription_id( $stripe_subscription_id );
			}
			if ( isset( $subscription ) ) {
				if ( 'ended' !== $subscription->status && 'cancelled' !== $subscription->status ) {
					if ( $subscription->chargeMaximumCount > 0 ) {
						if ( $subscription->chargeCurrentCount >= $subscription->chargeMaximumCount ) {
							$this->complete_subscription( $subscription );
						} else {
							if ( $this->debug_log ) {
								MM_WPFS_Utils::log( 'MM_WPFS_InvoiceCreated->process_event(): ' . "subscription already charged maximum times" );
							}
						}
					} else {
						if ( $this->debug_log ) {
							MM_WPFS_Utils::log( 'MM_WPFS_InvoiceCreated->process_event(): ' . "subscription->chargeMaximumCount is zero" );
						}
					}
				} else {
					if ( $this->debug_log ) {
						MM_WPFS_Utils::log( 'MM_WPFS_InvoiceCreated->process_event(): ' . "subscription status is 'ended' or 'cancelled', skip" );
					}
				}
			} else {
				if ( $this->debug_log ) {
					MM_WPFS_Utils::log( 'MM_WPFS_InvoiceCreated->process_event(): ' . "subscription not found" );
				}
			}
		}
	}

	/**
	 * @param $subscription
	 */
	private function complete_subscription( $subscription ) {

		$this->db->complete_subscription_by_stripe_subscription_id( $subscription->stripeSubscriptionID );
		$this->stripe->cancel_subscription( $subscription->stripeCustomerID, $subscription->stripeSubscriptionID );

		$plan              = $this->stripe->retrieve_plan( $subscription->planID );
		$country_composite = MM_WPFS_Countries::get_country_by_name( $subscription->addressCountry );
		$billing_address   = MM_WPFS_Utils::prepare_address_data( $subscription->addressLine1, $subscription->addressLine2, $subscription->addressCity, $subscription->addressState, $subscription->addressCountry, is_null( $country_composite ) ? '' : $country_composite['alpha-2'], $subscription->addressZip );
		$shipping_address  = MM_WPFS_Utils::prepare_address_data( $subscription->shippingAddressLine1, $subscription->shippingAddressLine2, $subscription->shippingAddressCity, $subscription->shippingAddressState, $subscription->shippingAddressCountry, is_null( $country_composite ) ? '' : $country_composite['alpha-2'], $subscription->addressZip );
		$product_name      = '';

		$send_receipt = false;
		$subscriber   = $this->db->find_subscription_by_stripe_subscription_id( $subscription->stripeSubscriptionID );
		if ( isset( $plan ) && isset( $subscriber ) ) {
			$form_send_receipt = false;
			$form              = $this->db->get_subscription_form_by_id( $subscriber->formId );
			if ( isset( $form ) ) {
				$form_send_receipt = $form->sendEmailReceipt == 1 ? true : false;
			}
			if ( $form_send_receipt ) {
				$options           = get_option( 'fullstripe_options' );
				$send_plugin_email = false;
				if ( $options['receiptEmailType'] == 'plugin' ) {
					$send_plugin_email = true;
				}
				$send_receipt = $form_send_receipt && $send_plugin_email;
			}
		}

		if ( $send_receipt ) {
			$vat_percent                 = $subscriber->vatPercent;
			$plan_net_setup_fee          = MM_WPFS_Utils::get_setup_fee_for_plan( $plan );
			$setup_fee_gross_composite   = MM_WPFS_Utils::calculateGrossFromNet( $plan_net_setup_fee, $vat_percent );
			$plan_gross_setup_fee        = $setup_fee_gross_composite['gross'];
			$plan_net_amount             = $plan->amount;
			$plan_amount_gross_gomposite = MM_WPFS_Utils::calculateGrossFromNet( $plan_net_amount, $vat_percent );
			$plan_gross_amount           = $plan_amount_gross_gomposite['gross'];
			$this->mailer->send_subscription_finished_email_receipt(
				$subscription->email,
				$plan->name,
				$plan->currency,
				$plan_net_setup_fee,
				$plan_gross_setup_fee,
				$plan_gross_setup_fee - $plan_net_setup_fee,
				$vat_percent,
				$plan_net_amount,
				$plan_gross_amount,
				$plan_gross_amount - $plan_net_amount,
				$vat_percent,
				$plan_gross_amount + $plan_gross_setup_fee,
				$subscription->name,
				$billing_address,
				$subscription->shippingName,
				$shipping_address,
				$product_name
			);
		}

	}
}

class MM_WPFS_ChargeCaptured extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_CAPTURED = 'charge.captured';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_CAPTURED;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status
				)
			);
		}
	}
}

class MM_WPFS_ChargeExpired extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_EXPIRED = 'charge.expired';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_EXPIRED;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status,
					'expired'            => true
				)
			);
		}
	}
}

class MM_WPFS_ChargeFailed extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_FAILED = 'charge.failed';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_FAILED;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status,
					'failure_code'       => $charge->failure_code,
					'failure_message'    => $charge->failure_message,
				)
			);
		}
	}
}

class MM_WPFS_ChargePending extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_PENDING = 'charge.pending';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_PENDING;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status
				)
			);
		}
	}
}

class MM_WPFS_ChargeRefunded extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_REFUNDED = 'charge.refunded';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_REFUNDED;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status,
				)
			);
		}
	}
}

class MM_WPFS_ChargeSucceeded extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_SUCCEEDED = 'charge.succeeded';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_SUCCEEDED;
	}

	protected function process_event( $event ) {
		$charge = $this->get_data_object( $event );
		if ( ! is_null( $charge ) ) {
			$this->db->update_payment_by_charge(
				$charge->id,
				array(
					'paid'               => $charge->paid,
					'captured'           => $charge->captured,
					'refunded'           => $charge->refunded,
					'last_charge_status' => $charge->status,
				)
			);
		}
	}
}

class MM_WPFS_ChargeUpdated extends MM_WPFS_WebHookEventProcessor {

	const WEB_HOOK_EVENT_CHARGE_UPDATED = 'charge.updated';

	public function get_type() {
		return self::WEB_HOOK_EVENT_CHARGE_UPDATED;
	}

	protected function process_event( $event ) {
		// tnagy charge description or metadata updated, nothing to do here
	}
}
