<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2019.02.19.
 * Time: 09:28
 */
?>
<form id="wpfs-enter-email-address-form" class="wpfs-form wpfs-w-60" action="" method="post">
	<div class="wpfs-form-title"><?php esc_html_e( 'Manage your subscriptions', 'wp-full-stripe' ); ?></div>
	<div class="wpfs-form-lead">
		<div class="wpfs-form-description">
			<?php esc_html_e( 'Enter your e-mail address used for subscription(s).', 'wp-full-stripe' ); ?>
			<?php if ( !defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ): ?>
                <?php esc_html_e( 'You are going to receive a security code to your e-mail address.', 'wp-full-stripe' ); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="wpfs-form-group">
		<input type="text" class="wpfs-form-control" id="wpfs-email-address" name="wpfs-email-address" placeholder="<?php esc_attr_e( 'Enter e-mail address', 'wp-full-stripe' ); ?>">
	</div>
	<?php if ( MM_WPFS_Utils::get_secure_subscription_update_with_google_recaptcha() ): ?>
		<div class="wpfs-recaptcha">
			<div id="wpfs-enter-email-address-form-recaptcha"></div>
		</div>
	<?php endif; ?>
	<div class="wpfs-form-actions">
		<button class="wpfs-btn wpfs-btn-primary" type="submit"><?php esc_html_e( 'Get security code', 'wp-full-stripe' ); ?></button>
	</div>
</form>
