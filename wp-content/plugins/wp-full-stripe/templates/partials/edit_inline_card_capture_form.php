<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.02.20.
 * Time: 15:39
 */

$customInputLabels = array();
if ( $form->customInputs ) {
	$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $form->customInputs );
}

$currency_symbol = MM_WPFS_Currencies::get_currency_symbol_for( $form->currency );

?>
<h2 id="edit-payment-form-tabs" class="nav-tab-wrapper wpfs-admin-form-tabs">
	<a href="#edit-payment-form-tab-payment" class="nav-tab"><?php esc_html_e( 'Payment', 'wp-full-stripe' ); ?></a>
	<a href="#edit-payment-form-tab-appearance" class="nav-tab"><?php esc_html_e( 'Appearance', 'wp-full-stripe' ); ?></a>
	<a href="#edit-payment-form-tab-custom-fields" class="nav-tab"><?php esc_html_e( 'Custom Fields', 'wp-full-stripe' ); ?></a>
	<a href="#edit-payment-form-tab-actions-after-payment" class="nav-tab"><?php esc_html_e( 'Actions after payment', 'wp-full-stripe' ); ?></a>
</h2>
<form class="form-horizontal wpfs-admin-form" action="" method="POST" id="edit-payment-form">
	<p class="tips"></p>
	<input type="hidden" name="action" value="wp_full_stripe_edit_inline_card_capture_form">
	<input type="hidden" name="formID" value="<?php echo $form->paymentFormID; ?>">
	<div id="edit-payment-form-tab-payment" class="wpfs-tab-content">
		<?php include( 'edit_inline_card_capture_form_tab_payment.php' ); ?>
	</div>
	<div id="edit-payment-form-tab-appearance" class="wpfs-tab-content">
		<?php include( 'edit_inline_card_capture_form_tab_appearance.php' ); ?>
	</div>
	<div id="edit-payment-form-tab-custom-fields" class="wpfs-tab-content">
		<?php include( 'edit_payment_form_tab_custom_fields.php' ); ?>
	</div>
	<div id="edit-payment-form-tab-actions-after-payment" class="wpfs-tab-content">
		<?php include( 'edit_payment_form_tab_actions_after_payment.php' ); ?>
	</div>
	<p class="submit">
		<button class="button button-primary" type="submit"><?php esc_html_e( 'Save Changes', 'wp-full-stripe' ); ?></button>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-saved-cards&tab=forms' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'wp-full-stripe' ); ?></a>
		<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
	</p>
</form>
