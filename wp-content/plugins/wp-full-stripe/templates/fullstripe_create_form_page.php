<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.02.23.
 * Time: 16:21
 */

$form_type = isset( $_GET['type'] ) ? $_GET['type'] : 'payment';

/**
 * @var WPFS_FormValidationData
 */
$form_data = MM_WPFS::getInstance()->get_form_validation_data();

?>
<div class="wrap">
	<h2> <?php esc_html_e( 'Full Stripe Create Form', 'wp-full-stripe' ); ?> </h2>

	<div id="updateDiv"><p><strong id="updateMessage"></strong></p></div>

	<div id="create">
		<?php if ( $form_type == 'payment' ): ?>
			<?php include 'partials/create_payment_form.php' ?>
		<?php elseif ( $form_type == 'inline_card_capture' ): ?>
			<?php include 'partials/create_inline_card_capture_form.php' ?>
		<?php elseif ( $form_type == 'popup_card_capture' ): ?>
			<?php
			$form_title                  = __( 'Card Details Form', 'wp-full-stripe' );
			$open_form_button_text_value = __( 'Save Card Details', 'wp-full-stripe' );
			$button_title                = __( 'Save Card Details', 'wp-full-stripe' );
			include 'partials/create_popup_card_capture_form.php';
			?>
		<?php elseif ( $form_type == 'checkout' ): ?>
			<?php include 'partials/create_checkout_form.php' ?>
		<?php elseif ( $form_type == 'subscription' ): ?>
			<?php include 'partials/create_subscription_form.php' ?>
		<?php elseif ( $form_type == 'checkout-subscription' ): ?>
			<?php include 'partials/create_checkout_subscription_form.php' ?>
		<?php endif; ?>
	</div>

</div>