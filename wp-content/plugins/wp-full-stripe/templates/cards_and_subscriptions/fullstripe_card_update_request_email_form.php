<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.04.10.
 * Time: 12:58
 */

?>
<form action="" method="post" id="wpfs-card-update-request-email-form">
	<div class="wpfs-update-card-row">
		<div id="wpfs-card-update-request-email-form-submit-feedback" role="alert"></div>
		<input type="email" name="emailAddress" placeholder="<?php esc_attr_e( 'Enter the email address used for your subscription(s)', 'wp-full-stripe' ); ?>"/>
	</div>
	<?php if ( MM_WPFS_Utils::get_secure_subscription_update_with_google_recaptcha() ): ?>
		<div class="wpfs-update-card-row">
			<div id="wpfs-card-update-request-email-form-captcha"></div>
		</div>
	<?php endif; ?>
	<br>
	<div class="wpfs-update-card-row">
		<button><?php esc_html_e( 'Proceed', 'wp-full-stripe' ); ?></button>
		<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading requestEmailLoading"/>
	</div>
</form>
