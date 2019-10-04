<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.03.02.
 * Time: 16:48
 */
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Form Type:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<?php esc_html_e( 'Inline save card form', 'wp-full-stripe' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Form Name:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<input type="text" class="regular-text" name="form_name" id="form_name" value="<?php echo $form->name; ?>" maxlength="<?php echo $form_data::NAME_LENGTH; ?>">

			<p class="description"><?php esc_html_e( 'This name will be used to identify this form in the shortcode i.e. [fullstripe_payment form="FormName"].', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<input type="hidden" name="form_custom" value="card_capture">
</table>
