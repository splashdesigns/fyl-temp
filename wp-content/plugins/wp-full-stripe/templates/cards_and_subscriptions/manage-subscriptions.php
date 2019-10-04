<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2019.02.19.
 * Time: 09:57
 */

/**
 * @var MM_WPFS_CardUpdateModel $model
 */

?>
<div id="wpfs-manage-subscriptions-container" class="wpfs-form wpfs-w-60">
	<div class="wpfs-form-title"><?php esc_html_e( 'Manage your subscriptions', 'wp-full-stripe' ); ?></div>
	<div class="wpfs-form-lead">
		<div class="wpfs-form-description wpfs-form-description--sm">
			<?php printf( esc_html__( 'You can manage the credit card and subscriptions associated with %s.', 'wp-full-stripe' ), $model->getCustomerEmail() ); ?>
			<a href="" id="wpfs-anchor-logout" class="wpfs-btn wpfs-btn-link"><?php esc_html_e( 'Log out', 'wp-full-stripe' ); ?></a>
		</div>
	</div>
	<div class="wpfs-form-subtitle"><?php esc_html_e( 'Credit/debit card', 'wp-full-stripe' ); ?></div>
	<form id="wpfs-default-card-form">
		<div class="wpfs-credit-cards">
			<div class="wpfs-credit-card">
				<div class="wpfs-credit-card-logo">
					<img src="<?php echo esc_attr( $model->getCardImageUrl() ); ?>" alt="<?php echo esc_attr( $model->getCardName() ); ?>">
				</div>
				<div class="wpfs-credit-card-data">
					<div class="wpfs-credit-card-name"><?php echo esc_html( $model->getCardName() . ' ' . $model->getCardNumber() ); ?></div>
					<div class="wpfs-credit-card-expires">
						<?php esc_html_e( 'Expires', 'wp-full-stripe' ); ?>
						<br><?php echo esc_html( $model->getExpiration() ); ?>
					</div>
				</div>
			</div>
			<a id="wpfs-anchor-update-card" class="wpfs-btn wpfs-btn-link"><?php esc_html_e( 'Update card', 'wp-full-stripe' ); ?></a>
		</div>
	</form>
	<form id="wpfs-update-card-form" style="display: none;">
		<div class="wpfs-form-group wpfs-w-45">
			<div class="wpfs-form-control" id="wpfs-card" data-toggle="card"></div>
		</div>
		<div class="wpfs-form-actions wpfs-mt-3 wpfs-mb-4">
			<button class="wpfs-btn wpfs-btn-primary wpfs-mr-2" type="submit"><?php esc_html_e( 'Update card', 'wp-full-stripe' ); ?></button>
			<a id="wpfs-anchor-discard-card-changes" class="wpfs-btn wpfs-btn-link"><?php esc_html_e( 'Discard', 'wp-full-stripe' ); ?></a>
		</div>
	</form>
	<div class="wpfs-form-subtitle"><?php esc_html_e( 'Subscriptions', 'wp-full-stripe' ); ?></div>
	<form id="wpfs-cancel-subscription-form">
		<div class="wpfs-subscriptions">
			<?php foreach ( $model->getSubscriptions() as $subscription ): ?>
				<?php
				$entry = new MM_WPFS_ManagedSubscriptionEntry( $subscription );
				?>
				<div class="wpfs-subscription">
					<div class="wpfs-form-check">
						<input type="checkbox" class="wpfs-form-check-input" id="<?php echo esc_attr( $entry->getId() ); ?>" name="<?php echo esc_attr( $entry->getName() ); ?>" value="<?php echo esc_attr( $entry->getValue() ); ?>">
						<label class="wpfs-form-check-label" for="<?php echo esc_attr( $entry->getId() ); ?>">
						<span class="wpfs-subscription-data">
							<span class="wpfs-subscription-name">
								<?php echo esc_html( $entry->getPlanName() ); ?>
								- <span class="wpfs-subscription-status <?php echo esc_attr( $entry->getClass() ); ?>"><?php echo esc_html( $entry->getStatus() ); ?></span>
							</span>
							<span class="wpfs-subscription-price"><?php echo esc_html( $entry->getPriceAndInterval() ); ?></span>
						</span>
						<span class="wpfs-subscription-created">
							<?php esc_html_e( 'Created at', 'wp-full-stripe' ); ?>
							<br><?php echo esc_html( $entry->getCreated() ); ?>
						</span>
						</label>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="wpfs-form-actions">
			<button id="wpfs-button-cancel-subscription" class="wpfs-btn wpfs-btn-primary" type="submit" disabled><?php esc_html_e( 'Cancel subscription', 'wp-full-stripe' ); ?></button>
		</div>
	</form>
</div>
