<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.08.25.
 * Time: 15:33
 */
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Form Title:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="company_name" id="company_name" value="<?php echo esc_attr( $form->companyName ); ?>" maxlength="<?php echo $form_data::COMPANY_NAME_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'Used as the title of the checkout form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Product Description:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="prod_desc" id="prod_desc" value="<?php echo esc_attr( $form->productDesc ); ?>" maxlength="<?php echo $form_data::PRODUCT_DESCRIPTION_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'A short description (one line) about the product sold using this form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label" for=""><?php esc_html_e( 'Plan Selector Style: ', 'wp-full-stripe' ); ?></label>
		</th>
		<td>
			<select name="plan_selector_style">
				<option value="<?php echo MM_WPFS::PLAN_SELECTOR_STYLE_DROPDOWN; ?>" <?php echo ( $form->planSelectorStyle == MM_WPFS::PLAN_SELECTOR_STYLE_DROPDOWN ) ? 'selected' : '' ?>><?php esc_html_e( 'Dropdown', 'wp-full-stripe' ); ?></option>
				<option value="<?php echo MM_WPFS::PLAN_SELECTOR_STYLE_LIST; ?>" <?php echo ( $form->planSelectorStyle == MM_WPFS::PLAN_SELECTOR_STYLE_LIST ) ? 'selected' : '' ?>><?php esc_html_e( 'List', 'wp-full-stripe' ); ?></option>
			</select>

			<p class="description"><?php esc_html_e( 'Style of the plan selector component.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Open Form Button Text:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="open_form_button_text" id="open_form_button_text" value="<?php echo esc_attr( $form->openButtonTitle ); ?>" maxlength="<?php echo $form_data::OPEN_BUTTON_TITLE_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'The text on the button used to pop open this form.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Payment Button Text:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="<?php echo esc_attr( $form->buttonTitle ); ?>" maxlength="<?php echo $form_data::BUTTON_TITLE_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'The text on the payment button. Use {{amount}} to show the payment amount on this button.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Include Shipping Address Field?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_show_shipping_address_input" id="hide_shipping_address" value="0" <?php echo ( $form->showShippingAddress == '0' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_shipping_address_input" id="show_shipping_address" value="1" <?php echo ( $form->showShippingAddress == '1' ) ? 'checked' : '' ?> >
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
				<input type="radio" name="form_show_remember_me" id="hide_remember_me" value="0" <?php echo ( $form->showRememberMe == '0' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_show_remember_me" id="show_remember_me" value="1" <?php echo ( $form->showRememberMe == '1' ) ? 'checked' : '' ?> >
				<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Show the Stripe Remember Me checkbox, allowing users to save their information with Stripe for later use.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Image:', 'wp-full-stripe' ); ?></label>
		</th>
		<td>
			<input id="form_checkout_image" type="text" name="form_checkout_image" value="<?php echo $form->image; ?>" maxlength="<?php echo $form_data::IMAGE_LENGTH; ?>" placeholder="<?php esc_attr_e( 'Enter image URL', 'wp-full-stripe' ); ?>">
			<button id="upload_image_button" class="button" type="button" value="Upload Image"><?php esc_html_e( 'Upload Image', 'wp-full-stripe' ); ?></button>
			<p class="description"><?php esc_html_e( 'A square image of your brand or product which is shown on the form. Min size 128px x 128px.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Disable Button Styling?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_disable_styling" id="form_disable_styling_no" value="0" <?php echo ( $form->disableStyling == '0' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_disable_styling" id="form_disable_styling_yes" value="1" <?php echo ( $form->disableStyling == '1' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( 'Disable the styling on the checkout button if you are noticing conflicts with your theme.', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Simple Button Layout?', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<label class="radio inline">
				<input type="radio" name="form_simple_button_layout" id="form_simple_button_layout_no" value="0" <?php echo ( $form->simpleButtonLayout == '0' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
			</label>
			<label class="radio inline">
				<input type="radio" name="form_simple_button_layout" id="form_simple_button_layout_yes" value="1" <?php echo ( $form->simpleButtonLayout == '1' ) ? 'checked' : '' ?>>
				<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
			</label>

			<p class="description"><?php esc_html_e( "Display only a 'Subscribe' button. It hides the plan selector, the custom input fields, and the coupon field.", 'wp-full-stripe' ); ?></p>
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
					if ( $form->preferredLanguage == $language['value'] ) {
						$option .= ' selected="selected"';
					}
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
