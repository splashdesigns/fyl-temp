<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.03.02.
 * Time: 16:47
 */
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Amount Selector Style:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<select class="regular-text" name="amount_selector_style" id="amount_selector_style">
				<option value="dropdown" <?php echo( MM_WPFS::AMOUNT_SELECTOR_STYLE_DROPDOWN === $form->amountSelectorStyle ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'Product selector dropdown', 'wp-full-stripe' ); ?></option>
				<option value="radio-buttons" <?php echo( MM_WPFS::AMOUNT_SELECTOR_STYLE_RADIO_BUTTONS === $form->amountSelectorStyle ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'List of products', 'wp-full-stripe' ); ?></option>
				<option value="button-group" <?php echo( MM_WPFS::AMOUNT_SELECTOR_STYLE_BUTTON_GROUP === $form->amountSelectorStyle ? 'selected="selected"' : '' ); ?>><?php esc_html_e( 'Donation button group', 'wp-full-stripe' ); ?></option>
			</select>

			<p class="description"><?php esc_html_e( 'Choose how you\'d like the amount(s) of the form to look.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Payment Button Text:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="<?php echo $form->buttonTitle; ?>" maxlength="<?php echo $form_data::BUTTON_TITLE_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'The text on the payment button.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top" id="include_amount_on_button_row" <?php echo $form->customAmount == MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE ? 'style="display: none;"' : '' ?>>
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Amount on Button?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_button_amount" id="hide_button_amount" value="0" <?php echo ( $form->showButtonAmount == '0' ) ? 'checked' : '' ?> >
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_button_amount" id="show_button_amount" value="1" <?php echo ( $form->showButtonAmount == '1' ) ? 'checked' : '' ?> >
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'For set amount forms, choose to show/hide the amount on the payment button.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Billing Address Field?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" <?php echo ( $form->showAddress == '0' ) ? 'checked' : '' ?> >
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_address_input" id="show_address_input" value="1" <?php echo ( $form->showAddress == '1' ) ? 'checked' : '' ?> >
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Should this payment form also ask for the customers billing address?', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
</table>
