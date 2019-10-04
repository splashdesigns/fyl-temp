<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.02.21.
 * Time: 16:11
 */

$customInputLabels = array();
if ( $form->customInputs ) {
	$customInputLabels = MM_WPFS_Utils::decode_custom_input_labels( $form->customInputs );
}

?>
<h2 id="edit-checkout-form-tabs" class="nav-tab-wrapper wpfs-admin-form-tabs">
	<a href="#edit-checkout-form-tab-payment" class="nav-tab"><?php esc_html_e( 'Payment', 'wp-full-stripe' ); ?></a>
	<a href="#edit-checkout-form-tab-appearance" class="nav-tab"><?php esc_html_e( 'Appearance', 'wp-full-stripe' ); ?></a>
	<a href="#edit-checkout-form-tab-custom-fields" class="nav-tab"><?php esc_html_e( 'Custom Fields', 'wp-full-stripe' ); ?></a>
	<a href="#edit-checkout-form-tab-actions-after-payment" class="nav-tab"><?php esc_html_e( 'Actions after payment', 'wp-full-stripe' ); ?></a>
</h2>
<form class="form-horizontal wpfs-admin-form" action="" method="POST" id="edit-checkout-form">
	<p class="tips"></p>
	<input type="hidden" name="action" value="wp_full_stripe_edit_checkout_form">
	<input type="hidden" name="formID" value="<?php echo $form->checkoutFormID; ?>">
	<div id="edit-checkout-form-tab-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Type:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td><?php esc_html_e( 'Popup payment form', 'wp-full-stripe' ); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Name:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_name" id="form_name" value="<?php echo $form->name; ?>" maxlength="<?php echo $form_data::NAME_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'This name will be used to identify this form in the shortcode i.e. [fullstripe_checkout form="FormName"].', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Type:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_specific_amount" value="specified_amount" <?php echo ( $form->customAmount == MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Set Amount', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_amount_list" value="list_of_amounts" <?php echo ( $form->customAmount == MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Select Amount from List', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_custom_amount" value="custom_amount" <?php echo ( $form->customAmount == MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Custom Amount', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Choose to set a specific amount or a list of amounts for this form, or allow customers to set custom amounts.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Charge Type: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_charge_type" value="<?php echo MM_WPFS::CHARGE_TYPE_IMMEDIATE; ?>" <?php echo ( $form->chargeType == MM_WPFS::CHARGE_TYPE_IMMEDIATE ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Immediate', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_charge_type" value="<?php echo MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE; ?>" <?php echo ( $form->chargeType == MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Authorize and Capture', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Choose whether you want to charge immediately, or authorize the payment now, and capture it later.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top" id="payment_currency_row" <?php echo $form->customAmount == MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE ? 'style="display: none;"' : '' ?>>
				<th scope="row">
					<label class="control-label" for="currency"><?php esc_html_e( "Payment Currency: ", 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<div class="ui-widget">
						<select id="currency" name="form_currency">
							<option value=""><?php esc_attr_e( 'Select from the list or start typing', 'wp-full-stripe' ); ?></option>
							<?php
							foreach ( MM_WPFS_Currencies::get_available_currencies() as $currency_key => $currency_obj ) {
								$currency_array = MM_WPFS_Currencies::get_currency_for( $currency_key );
								$option         = '<option value="' . $currency_key . '"';
								$option .= ' data-currency-symbol="' . $currency_array['symbol'] . '"';
								$option .= ' data-zero-decimal-support="' . ( $currency_array['zeroDecimalSupport'] == true ? 'true' : 'false' ) . '"';
								if ( $form->currency === $currency_key ) {
									$option .= ' selected="selected"';
								}
								$option .= '>';
								$option .= $currency_obj['name'] . ' (' . $currency_obj['code'] . ')';
								$option .= '</option>';
								echo $option;
							}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr valign="top" id="payment_amount_row" <?php echo $form->customAmount == MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS || $form->customAmount == MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT || $form->customAmount == MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE ? 'style="display: none;"' : '' ?>>
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Amount:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_amount" id="form_amount" value="<?php echo $form->amount; ?>">

					<p class="description"><?php esc_html_e( 'The amount this form will charge your customer, in the smallest unit for the currency. i.e. for $10.00 enter 1000, for ¥10 enter 10.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top" id="payment_amount_list_row" <?php echo $form->customAmount != MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS ? 'style="display: none;"' : '' ?>>
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Amount Options:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<a href="#" class="button button-primary" id="add_payment_amount_button"><?php esc_html_e( 'Add', 'wp-full-stripe' ); ?></a><input type="text" id="payment_amount_value" placeholder="<?php esc_attr_e( 'Amount', 'wp-full-stripe' ); ?>" maxlength="<?php echo $form_data::PAYMENT_AMOUNT_LENGTH; ?>"><input type="text" id="payment_amount_description" placeholder="<?php esc_attr_e( 'Description', 'wp-full-stripe' ); ?>" maxlength="<?php echo $form_data::PAYMENT_AMOUNT_DESCRIPTION_LENGTH; ?>" class="large-text"><br>

					<ul id="payment_amount_list">
						<?php
						$list_of_amounts = json_decode( $form->listOfAmounts );
						if ( isset( $list_of_amounts ) && ! empty( $list_of_amounts ) ) {
							foreach ( $list_of_amounts as $list_element ) {
								$list_item_row = "<li";
								$list_item_row .= " class=\"ui-state-default\"";
								$list_item_row .= " title=\"" . __( 'You can reorder this list by using drag\'n\'drop.', 'wp-full-stripe' ) . "\"";
								$list_item_row .= " data-toggle=\"tooltip\"";
								$list_item_row .= " data-payment-amount-value=\"{$list_element[0]}\"";
								$list_item_row .= " data-payment-amount-description=\"" . rawurlencode( $list_element[1] ) . "\"";
								$list_item_row .= ">";
								$list_item_row .= "<a href=\"#\" class=\"dd_delete\">" . __( 'Delete', 'wp-full-stripe' ) . "</a>";
								$list_item_row .= "<span class=\"amount\">" . esc_html(MM_WPFS_Currencies::format_amount_with_currency( $form->currency, $list_element[0] )) . "</span>";
								$list_item_row .= "<span class=\"desc\">{$list_element[1]}</span>";
								$list_item_row .= "</li>";
								echo $list_item_row;
							}
						}
						?>
					</ul>
					<input type="hidden" name="payment_amount_values">
					<input type="hidden" name="payment_amount_descriptions">

					<p class="description"><?php esc_html_e( 'The amount in smallest common currency unit. i.e. for $10.00 enter 1000, for ¥10 enter 10. The description will be displayed in the dropdown for the amount. Use the {amount} placeholder to include the amount value. You can use drag\'n\'drop to reorder the payment amounts.', 'wp-full-stripe' ); ?></p>
					<label class="checkbox inline"><input type="checkbox" name="allow_custom_payment_amount" id="allow_custom_payment_amount" value="1" <?php echo $form->allowListOfAmountsCustom == '1' ? 'checked' : '' ?>><?php esc_html_e( 'Allow Custom Amount to Be Entered?', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
			<!--			tnagy remove Alipay support temporarly-->
			<!--			<tr valign="top" id="use_alipay_row" <?php /*echo $form->customAmount == MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE ? 'style="display: none;"' : '' */ ?>>
				<th scope="row">
					<label class="control-label"><?php /*esc_html_e( 'Use Alipay?', 'wp-full-stripe' ); */ ?></label>
				</th>
				<td>
					<div id="alipay_usage_info_panel" <?php /*echo ( $form->currency === MM_WPFS::CURRENCY_USD ) ? 'style="display: none;"' : '' */ ?>>
						<p class="alert alert-info"><?php /*esc_html_e( "In order to use AliPay for payments, you have to set the form currency to USD, and you have to link an US bank account to your Stripe account.", "wp-full-stripe" ); */ ?></p>
					</div>
					<div id="alipay_usage_panel" <?php /*echo ( $form->currency === MM_WPFS::CURRENCY_USD ) ? '' : 'style="display: none;"' */ ?>>
						<label class="radio inline">
							<input type="radio" name="form_use_alipay" id="use_alipay_no" value="0" <?php /*echo ( $form->useAlipay == '0' ) ? 'checked' : '' */ ?> >
							<?php /*esc_html_e( 'No', 'wp-full-stripe' ); */ ?>
						</label>
						<label class="radio inline">
							<input type="radio" name="form_use_alipay" id="use_alipay_yes" value="1" <?php /*echo ( $form->useAlipay == '1' ) ? 'checked' : '' */ ?> >
							<?php /*esc_html_e( 'Yes', 'wp-full-stripe' ); */ ?>
						</label>
						<p class="description"><?php /*esc_html_e( 'Accept payments from hundreds of millions of new customers using Alipay, China’s most popular payment method.', 'wp-full-stripe' ); */ ?></p>
					</div>
				</td>
			</tr>
-->
			<tr valign="top" id="stripe_description_row">
				<th scope="row">
					<label class="control-label">
						<?php esc_html_e( 'Payment Description: ', 'wp-full-stripe' ); ?>
					</label>
				</th>
				<td>
					<textarea rows="3" class="large-text code" name="stripe_description"><?php echo esc_html( $form->stripeDescription ); ?></textarea>
					<p class="description"><?php printf( __( 'This description appears on the Stripe dashboard and on customer statements. You can use placeholders, see the <a target="_blank" href="%s">Help page</a> for more options.', 'wp-full-stripe' ), admin_url( "admin.php?page=fullstripe-help#receipt-tokens" ) ); ?> </p>
				</td>
			</tr>
		</table>
	</div>
	<div id="edit-checkout-form-tab-appearance" class="wpfs-tab-content">
		<?php include( 'edit_checkout_form_tab_appearance.php' ); ?>
	</div>
	<div id="edit-checkout-form-tab-custom-fields" class="wpfs-tab-content">
		<?php include( 'edit_payment_form_tab_custom_fields.php' ); ?>
	</div>
	<div id="edit-checkout-form-tab-actions-after-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Send Email Receipt?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="0" <?php echo ( $form->sendEmailReceipt == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="1" <?php echo ( $form->sendEmailReceipt == '1' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Send an email receipt on successful payment?', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Redirect On Success?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect" id="do_redirect_no" value="0" <?php echo ( $form->redirectOnSuccess == '0' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1" <?php echo ( $form->redirectOnSuccess == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'When payment is successful you can choose to redirect to another page or post.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<?php include( 'redirect_to_for_edit.php' ); ?>
		</table>
	</div>
	<p class="submit">
		<button class="button button-primary" type="submit"><?php esc_html_e( 'Save Changes', 'wp-full-stripe' ); ?></button>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'wp-full-stripe' ); ?></a>
		<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
	</p>
</form>
