<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.04.10.
 * Time: 12:59
 */

?>

<form action="" method="post" id="wpfs-card-update-request-code-form">
	<div class="wpfs-update-card-row">
		<label><?php esc_html_e( 'We sent a security code to your email address.', 'wp-full-stripe' ); ?></label>
	</div>
	<div class="wpfs-update-card-row">
		<div id="wpfs-card-update-request-code-form-submit-feedback" role="alert"></div>
		<input type="text" name="securityCode" placeholder="<?php esc_attr_e( 'Enter security code', 'wp-full-stripe' ); ?>"/>
	</div>
	<br>
	<div class="wpfs-update-card-row">
		<button><?php esc_html_e( 'Validate', 'wp-full-stripe' ); ?></button>
		<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading requestSecurityCodeLoading"/>
	</div>
</form>

<div class="wpfs-update-card-row">
	<a class="wpfs-card-update-link wpfs-notrans" id="wpfs-card-update-request-reenter-email" href="#"><<< <?php esc_html_e( 'Back to entering an email address', 'wp-full-stripe' ); ?></a>
	<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading requestReenterEmailLoading"/>
</div>
