<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$key = get_option( 'totalpoll_license_key', false );

if ( isset( $_POST['totalpoll_license_key'] ) ):
	$status = $this->activate( $_POST['totalpoll_license_key'] );
else:
	$status = get_option( 'totalpoll_license_status', false );
endif;

?>
<div id="totalpoll-support" class="wrap totalpoll-page">
	<div class="totalpoll-page-container">

		<svg class="totalpoll-page-icon" width="72" height="72" viewBox="0 0 24 24">
			<path
				d="M4,2A2,2 0 0,0 2,4V16A2,2 0 0,0 4,18H8V21A1,1 0 0,0 9,22H9.5V22C9.75,22 10,21.9 10.2,21.71L13.9,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2H4M4,4H20V16H13.08L10,19.08V16H4V4M12.19,5.5C11.3,5.5 10.59,5.68 10.05,6.04C9.5,6.4 9.22,7 9.27,7.69C0.21,7.69 6.57,7.69 11.24,7.69C11.24,7.41 11.34,7.2 11.5,7.06C11.7,6.92 11.92,6.85 12.19,6.85C12.5,6.85 12.77,6.93 12.95,7.11C13.13,7.28 13.22,7.5 13.22,7.8C13.22,8.08 13.14,8.33 13,8.54C12.83,8.76 12.62,8.94 12.36,9.08C11.84,9.4 11.5,9.68 11.29,9.92C11.1,10.16 11,10.5 11,11H13C13,10.72 13.05,10.5 13.14,10.32C13.23,10.15 13.4,10 13.66,9.85C14.12,9.64 14.5,9.36 14.79,9C15.08,8.63 15.23,8.24 15.23,7.8C15.23,7.1 14.96,6.54 14.42,6.12C13.88,5.71 13.13,5.5 12.19,5.5M11,12V14H13V12H11Z"/>
		</svg>

		<h1 class="totalpoll-page-title"><?php _e( 'Support Center', TP_TD ); ?></h1>

		<form target="_blank" method="get" action="<?php echo TP_SUPPORT; ?>">
			<input type="hidden" name="utm_campaign" value="support-search">
			<input type="hidden" name="utm_medium" value="in-app">
			<input type="hidden" name="utm_source" value="totalpoll-pro">
			<input name="s" type="text" class="input-search" placeholder="<?php esc_attr_e( 'What can we help you with?', TP_TD ); ?>">
		</form>

		<p><?php _e( 'Use common keywords for best results.', TP_TD ); ?></p>

		<div class="links-cards clearfix">
			<a href="<?php echo TP_SUPPORT; ?>?utm_campaign=support&utm_medium=in-app&utm_source=totalpoll-pro" target="_blank" class="link-card">

				<svg width="48" height="48" viewBox="0 0 24 24">
					<path d="M11,19V9A2,2 0 0,0 9,7H5V17H9A2,2 0 0,1 11,19M13,9V19A2,2 0 0,1 15,17H19V7H15A2,2 0 0,0 13,9M21,19H15A2,2 0 0,0 13,21H11A2,2 0 0,0 9,19H3V5H9A2,2 0 0,1 11,7H13A2,2 0 0,1 15,5H21V19Z"/>
				</svg>
				<p><?php _e( 'Check our knowledge base.', TP_TD ); ?></p>
			</a>
			<a href="<?php echo TP_SUPPORT; ?>?utm_campaign=support&utm_medium=in-app&utm_source=totalpoll-pro" target="_blank" class="link-card">

				<svg width="48" height="48" viewBox="0 0 24 24">
					<path
						d="M4,2A2,2 0 0,0 2,4V16A2,2 0 0,0 4,18H8V21A1,1 0 0,0 9,22H9.5V22C9.75,22 10,21.9 10.2,21.71L13.9,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2H4M4,4H20V16H13.08L10,19.08V16H4V4M12.19,5.5C11.3,5.5 10.59,5.68 10.05,6.04C9.5,6.4 9.22,7 9.27,7.69C0.21,7.69 6.57,7.69 11.24,7.69C11.24,7.41 11.34,7.2 11.5,7.06C11.7,6.92 11.92,6.85 12.19,6.85C12.5,6.85 12.77,6.93 12.95,7.11C13.13,7.28 13.22,7.5 13.22,7.8C13.22,8.08 13.14,8.33 13,8.54C12.83,8.76 12.62,8.94 12.36,9.08C11.84,9.4 11.5,9.68 11.29,9.92C11.1,10.16 11,10.5 11,11H13C13,10.72 13.05,10.5 13.14,10.32C13.23,10.15 13.4,10 13.66,9.85C14.12,9.64 14.5,9.36 14.79,9C15.08,8.63 15.23,8.24 15.23,7.8C15.23,7.1 14.96,6.54 14.42,6.12C13.88,5.71 13.13,5.5 12.19,5.5M11,12V14H13V12H11Z"/>
				</svg>

				<p><?php _e( 'Have a question? Read the FAQs.', TP_TD ); ?></p>
			</a>
			<a href="<?php echo TP_SUPPORT; ?>new-support-ticket/?product=totalpoll-pro&utm_campaign=support&utm_medium=in-app&utm_source=totalpoll-pro" target="_blank" class="link-card">
				<svg width="48" height="48" viewBox="0 0 24 24">
					<path d="M20,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V8L12,13L20,8V18M20,6L12,11L4,6V6H20V6Z"/>
				</svg>
				<p><?php _e( 'Open a support ticket.', TP_TD ); ?></p>
			</a>
		</div>

		<br>
		<p><?php _e( 'Please attach the following information when you open a new ticket.', TP_TD ); ?></p>
		<pre><?php echo esc_textarea( $this->get_system_details() ); ?></pre>

		<button onclick="downloadDebug()" target="_blank" class="button button-primary button-large widefat"><?php _e( 'Download', TP_TD ); ?></button>

		<script type="text/javascript">
			function downloadDebug() {
				var link = document.createElement('a');
				link.download = 'debug.txt';
				link.href = 'data:,' + encodeURIComponent(jQuery('pre').html());
				link.click();
			}
		</script>

	</div>
</div>