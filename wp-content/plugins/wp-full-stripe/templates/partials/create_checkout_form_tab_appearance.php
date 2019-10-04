<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.03.29.
 * Time: 14:01
 */

if ( ! isset( $form_title ) ) {
	$form_title = '';
}
if ( ! isset( $open_form_button_text_value ) ) {
	$open_form_button_text_value = __( 'Pay With Card', 'wp-full-stripe' );
}
if ( ! isset( $button_title ) ) {
	$button_title = __( 'Pay {{amount}}', 'wp-full-stripe' );
}
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Form Title:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="company_name" id="company_name" maxlength="<?php echo $form_data::COMPANY_NAME_LENGTH; ?>" value="<?php echo esc_attr( $form_title ); ?>">

			<p class="description"><?php esc_html_e( 'Used as the title of the form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Product Description:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="prod_desc" id="prod_desc" maxlength="<?php echo $form_data::PRODUCT_DESCRIPTION_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'A short description (one line) about the product sold using this form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Open Form Button Text:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="open_form_button_text" id="open_form_button_text" value="<?php echo esc_attr( $open_form_button_text_value ); ?>" maxlength="<?php echo $form_data::OPEN_BUTTON_TITLE_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'The text on the button used to pop open this form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Payment Button Text:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="<?php echo esc_attr( $button_title ); ?>" maxlength="<?php echo $form_data::BUTTON_TITLE_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'The text on the payment button. Use {{amount}} to show the payment amount on this button.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top" id="include_amount_on_button_row">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Amount on Button?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_button_amount" id="hide_button_amount" value="0">
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_button_amount" id="show_button_amount" value="1" checked="checked">
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'For set amount forms, choose to show/hide the amount on the payment button.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<?php if ( MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD !== $form_type ): ?>
		<tr valign="top">
			<th scope="row">
				<label class="control-label"><?php esc_html_e( 'Amount Selector Style:', 'wp-full-stripe' ); ?> </label>
			</th>
			<td>
				<select class="regular-text" name="amount_selector_style" id="amount_selector_style">
					<option value="dropdown"><?php esc_html_e( 'Product selector dropdown', 'wp-full-stripe' ); ?></option>
					<option value="radio-buttons"><?php esc_html_e( 'List of products', 'wp-full-stripe' ); ?></option>
					<option value="button-group"><?php esc_html_e( 'Donation button group', 'wp-full-stripe' ); ?></option>
				</select>

				<p class="description"><?php esc_html_e( 'Choose how you\'d like the amount(s) of the form to look.', 'wp-full-stripe' ); ?></p>
			</td>
		</tr>
	<?php endif; ?>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Billing Address Field?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" checked="checked">
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_address_input" id="show_address_input" value="1">
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Should this payment form also ask for the customers billing address?', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Shipping Address Field?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_show_shipping_address_input" id="hide_shipping_address_input" value="0" checked="checked">
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_shipping_address_input" id="show_shipping_address_input" value="1">
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Should this payment form also ask for the customers shipping address?', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Remember Me Field?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_show_remember_me" id="hide_remember_me" value="0" checked="checked">
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_remember_me" id="show_remember_me" value="1">
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Show the Stripe Remember Me checkbox, allowing users to save their information with Stripe for later use.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Image', 'wp-full-stripe' ); ?></label>
		</th>
		<td>
			<input id="form_checkout_image" type="text" name="form_checkout_image" maxlength="<?php echo $form_data::IMAGE_LENGTH; ?>" placeholder="<?php esc_attr_e( 'Enter image URL', 'wp-full-stripe' ); ?>">
			<button id="upload_image_button" class="button" type="button" value="<?php esc_attr_e( 'Upload Image', 'wp-full-stripe' ); ?>">
				<?php esc_html_e( 'Upload Image', 'wp-full-stripe' ); ?>
			</button>
			<p class="description"><?php esc_html_e( 'A square image of your brand or product which is shown on the form. Min size 128px x 128px.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Disable Button Styling?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_disable_styling" id="form_disable_styling_no" value="0" checked="checked">
				<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_disable_styling" id="form_disable_styling_yes" value="1">
				<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Disable the styling on the checkout button if you are noticing conflicts with your theme.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label" for=""><?php esc_html_e( "Popup Form Language: ", 'wp-full-stripe' ); ?></label>
		</th>
		<td>
			<select name="form_preferred_language">
				<option value="<?php echo MM_WPFS::PREFERRED_LANGUAGE_AUTO; ?>"><?php esc_html_e( 'Auto', 'wp-full-stripe' ); ?></option>
				<?php
				foreach ( MM_WPFS::get_available_checkout_languages() as $language ) {
					$option = '<option value="' . $language['value'] . '"';
					$option .= '>';
					$option .= $language['name'] . ' (' . $language['value'] . ')';
					$option .= '</option>';
					echo $option;
				}
				?>
			</select>

			<p class="description"><?php esc_html_e( "Display the popup form in the selected language. Use 'Auto' to determine the language from the locale sent by the customer's browser.", 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
</table>
