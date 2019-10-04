<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.04.10.
 * Time: 12:59
 */

/* @var \Stripe\Customer stripeCustomer */
/* @var array subscriptions */
/* @var array products */

$dateFormat = get_option( 'date_format' );

$defaultSource = null;
if ( isset( $stripeCustomer ) ) {
	if ( isset( $stripeCustomer->sources ) && isset( $stripeCustomer->sources->data ) ) {
		foreach ( $stripeCustomer->sources->data as $source ) {
			if ( is_null( $defaultSource ) ) {
				if ( $source->object == 'card' && $source->id == $stripeCustomer->default_source ) {
					$defaultSource = $source;
				}
			}
		}
	}
}
if ( ! isset( $subscriptions ) ) {
	$subscriptions = array();
}

?>
<div>
	<div id="wpfs-subscription-update-logout">
		<a class="wpfs-card-update-link wpfs-notrans" id="wpfs-card-update-request-reenter-email" href="#">[<?php esc_html_e( 'Logout', 'wp-full-stripe' ); ?>
			]</a>
		<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading requestReenterEmailLoading"/>
	</div>
	<h3 id="wpfs-card-update-headline"><?php esc_html_e( 'Update the card used for subscriptions', 'wp-full-stripe' ); ?></h3>
</div>
<form id="wpfs-cards-and-subscriptions-card-form">
	<input type="hidden" name="action" value="wp_full_stripe_create_card"/>
	<div id="wpfs-cards-and-subscriptions-card-errors" role="alert"></div>
	<div id="wpfs-cards-and-subscriptions-card-element"></div>
	<br>
	<button><?php esc_html_e( 'Update card', 'wp-full-stripe' ); ?></button>
	<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading updateCardLoading"/>
</form>
<h4><?php esc_html_e( 'Default card', 'wp-full-stripe' ); ?>:</h4>
<table id="wpfs-cards-and-subscriptions-default-card-table">
	<thead>
	<tr>
		<th><?php esc_html_e( 'Brand', 'wp-full-stripe' ); ?></th>
		<th><?php esc_html_e( 'Ending', 'wp-full-stripe' ); ?></th>
		<th><?php esc_html_e( 'Expiry', 'wp-full-stripe' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if ( ! is_null( $defaultSource ) ): ?>
		<tr>
			<td><?php echo esc_html( $defaultSource->brand ); ?></td>
			<td><?php echo esc_html( $defaultSource->last4 ); ?></td>
			<td><?php echo esc_html( sprintf( '%02d / %d', $defaultSource->exp_month, $defaultSource->exp_year ) ); ?></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>
<h3><?php esc_html_e( 'Cancel subscriptions', 'wp-full-stripe' ); ?></h3>
<div id="wpfs-cards-and-subscriptions-subscriptions-form-submit-feedback" role="alert"></div>
<form id="wpfs-cards-and-subscriptions-subscriptions-form" action="" method="post">
	<table id="wpfs-cards-and-subscriptions-subscriptions-table">
		<thead>
		<tr>
			<th></th>
			<th>
				<b><?php esc_html_e( 'Plan', 'wp-full-stripe' ); ?></b><br>
				<?php esc_html_e( 'Price', 'wp-full-stripe' ); ?>
			</th>
			<th>
				<b><?php esc_html_e( 'Status', 'wp-full-stripe' ); ?></b><br>
				<?php esc_html_e( 'Created at', 'wp-full-stripe' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php if ( ! is_null( $subscriptions ) ): ?>
			<?php foreach ( $subscriptions as $subscription ): ?>
				<tr>
					<td>
						<input type="checkbox" name="subscriptionId[]" value="<?php echo esc_attr( $subscription->id ); ?>"/>
					</td>
					<td>
						<b><?php echo esc_html( $subscription->plan->name ); ?></b><br>
						<?php echo sprintf( '%s %s / %s', MM_WPFS_Utils::format_amount( $subscription->plan->currency, $subscription->plan->amount ), strtoupper( $subscription->plan->currency ), MM_WPFS_Utils::format_interval_label( $subscription->plan->interval, $subscription->plan->interval_count ) ); ?>
					</td>
					<td>
						<b><?php MM_WPFS_Utils::echo_translated_label( ucfirst( $subscription->status ) ); ?></b><br>
						<?php echo esc_html( date( $dateFormat, $subscription->created ) ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<button><?php esc_html_e( 'Cancel subscription(s)', 'wp-full-stripe' ); ?></button>
	<img src="<?php echo MM_WPFS_Assets::images( 'loader.gif' ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading cancelSubscriptionLoading"/>
</form>
