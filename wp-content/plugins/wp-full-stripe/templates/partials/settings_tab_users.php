<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.04.13.
 * Time: 15:28
 */

$options                = get_option( 'fullstripe_options' );
$googleReCAPTCHASiteKey = '';
if ( array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY, $options ) ) {
	$googleReCAPTCHASiteKey = $options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY ];
}
$googleReCAPTCHASecretKey = '';
if ( array_key_exists( MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY, $options ) ) {
	$googleReCAPTCHASecretKey = $options[ MM_WPFS::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY ];
}
if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
    $googleReCAPTCHASiteKey   = __( 'Google reCaptcha site key here', 'wp-full-stripe' );
    $googleReCAPTCHASecretKey = __( 'Google reCaptcha secret key here', 'wp-full-stripe' );
}

?>
<div id="users-tab">
	<form class="form-horizontal" action="#" method="post" id="settings-email-receipts-form">
		<p class="tips"></p>
		<input type="hidden" name="action" value="wp_full_stripe_update_settings"/>
		<input type="hidden" name="tab" value="users">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Fill in email address for logged in users?: ', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio">
						<input type="radio" name="lock_email_field_for_logged_in_users" id="lock_email_field_for_logged_in_users_no" value="0" <?php echo ( $options['lock_email_field_for_logged_in_users'] == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio">
						<input type="radio" name="lock_email_field_for_logged_in_users" id="lock_email_field_for_logged_in_users_yes" value="1" <?php echo ( $options['lock_email_field_for_logged_in_users'] == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Secure inline forms with Google reCAPTCHA?: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="checkbox">
						<input type="radio" name="secure_inline_forms_with_google_recaptcha" id="secure_inline_forms_with_google_recaptcha_no" value="0" <?php echo ( $options['secure_inline_forms_with_google_recaptcha'] == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="checkbox">
						<input type="radio" name="secure_inline_forms_with_google_recaptcha" id="secure_inline_forms_with_google_recaptcha_yes" value="1" <?php echo ( $options['secure_inline_forms_with_google_recaptcha'] == '1' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Secure subscription update with Google reCAPTCHA?: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="checkbox">
						<input type="radio" name="secure_subscription_update_with_google_recaptcha" id="secure_subscription_update_with_google_recaptcha_no" value="0" <?php echo ( $options['secure_subscription_update_with_google_recaptcha'] == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="checkbox">
						<input type="radio" name="secure_subscription_update_with_google_recaptcha" id="secure_subscription_update_with_google_recaptcha_yes" value="1" <?php echo ( $options['secure_subscription_update_with_google_recaptcha'] == '1' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top" id="google_recaptcha_site_key_row" <?php echo $options['secure_subscription_update_with_google_recaptcha'] == '0' ? 'style="display: none;"' : '' ?>>
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Google reCAPTCHA site key: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input type="text" id="google_recaptcha_site_key" name="google_recaptcha_site_key" class="regular-text code" value="<?php echo esc_attr( $googleReCAPTCHASiteKey ); ?>">
				</td>
			</tr>
			<tr valign="top" id="google_recaptcha_secret_key_row" <?php echo $options['secure_subscription_update_with_google_recaptcha'] == '0' ? 'style="display: none;"' : '' ?>>
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Google reCAPTCHA secret key: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input type="text" id="google_recaptcha_secret_key" name="google_recaptcha_secret_key" class="regular-text code" value="<?php echo esc_attr( $googleReCAPTCHASecretKey ); ?>">
				</td>
			</tr>
		</table>
		<p class="submit">
			<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Changes' ) ?></button>
			<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
		</p>
	</form>
</div>
