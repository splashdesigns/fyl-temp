<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.09.06.
 * Time: 16:30
 */
?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'VAT Rate:' ); ?></label>
		</th>
		<td>
			<select id="formVATRateTypeSelect" name="form_vat_rate_type">
				<?php
				$vatRateTypeValues = MM_WPFS_Utils::get_vat_rate_type_values();
				foreach ( $vatRateTypeValues as $vatRateId => $vatRateLabel ) {
					$optionRow = '<option';
					$optionRow .= ' value="' . esc_attr( $vatRateId ) . '"';
					$optionRow .= '>';
					$optionRow .= esc_html( $vatRateLabel );
					$optionRow .= '</option>';
					echo $optionRow;
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( 'Should this form add VAT to the subscription plan amount and to the setup fee?', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr id="formVATPercentRow" valign="top" style="display: none;">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'VAT Percent:', 'wp-full-stripe' ); ?></label>
		</th>
		<td>
			<input type="text" class="wpfs-vat-percent" name="form_vat_percent" id="form_vat_percent">%
			<p class="description"><?php esc_html_e( 'VAT Percent with up to 4 decimal places.' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Collect Billing Address?', 'wp-full-stripe' ); ?> </label>
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
			<p class="description"><?php esc_html_e( 'Should this form also ask for the customers billing address?', 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
	<tr id="defaultBillingCountryRow" valign="top" style="display: none;">
		<th scope="row">
			<label class="control-label"><?php esc_html_e( 'Default Billing Country:', 'wp-full-stripe' ); ?> </label>
		</th>
		<td>
			<select name="form_default_billing_country" class="fullstripe-form-input input-xlarge">
				<?php
				foreach ( MM_WPFS_Countries::get_available_countries() as $countryKey => $countryObject ) {
					$option = '<option';
					$option .= " value=\"{$countryKey}\"";
					if ( $countryKey == MM_WPFS::DEFAULT_BILLING_COUNTRY_INITIAL_VALUE ) {
						$option .= ' selected="selected"';
					}
					$option .= '>';
					$option .= MM_WPFS_Utils::translate_label( $countryObject['name'] );
					$option .= '</option>';
					echo $option;
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( "It's the selected country when the form is rendered for the first time, and is used also as the supplier's country for custom VAT calculation.", 'wp-full-stripe' ); ?></p>
		</td>
	</tr>
</table>
