<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$tools = TotalPoll::instance( 'admin/tools' );

if ( isset( $_POST['migrate'] ) ):
	if ( $_POST['migrate'] == 'yop-polls' ):
		$tools->migrate_yop_polls();
	elseif ( $_POST['migrate'] == 'wp-polls' ):
		$tools->migrate_wppolls_polls();
	elseif ( $_POST['migrate'] == 'totalpoll' ):
		$tools->migrate_totalpoll_polls();
	endif;

	?>
	<div id="message" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'Polls have been migrated successfully.', TP_TD ); ?></p>
		<button type="button" class="notice-dismiss"></button>
	</div>
	<?php
endif;

if ( isset( $_POST['purge-cache'] ) && $tools->purge_cache() ):
	$tools->purge_cache();
	?>
	<div id="message" class="updated notice notice-success is-dismissible">
		<p><?php _e( 'The cache has been purged.', TP_TD ); ?></p>
		<button type="button" class="notice-dismiss"></button>
	</div>
	<?php
endif;
?>

<div id="totalpoll-tools" class="wrap totalpoll-page">
	<div class="totalpoll-page-container">

		<svg class="totalpoll-tools-icon" width="72" height="72" viewBox="0 0 24 24">
			<path
				d="M23.27 19.743L11.324 7.798a2.706 2.706 0 0 1-.783-2.115 5.278 5.278 0 0 0-1.52-4.146A5.246 5.246 0 0 0 5.29 0c-.51 0-1.017.072-1.508.216l3.17 3.17c.344 1.59-1.96 3.918-3.566 3.567l-3.17-3.17A5.3 5.3 0 0 0 0 5.293a5.264 5.264 0 0 0 5.682 5.25 2.698 2.698 0 0 1 2.113.783L19.743 23.27a2.494 2.494 0 0 0 3.527-3.527zM21.5 22.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2zM13.125 6.747L19.848 0 24 4.128l-6.722 6.77-1.012-1.01 5.488-5.534a.427.427 0 0 0-.602-.602l-5.49 5.533-.934-.936 5.495-5.54a.426.426 0 0 0-.603-.61l-5.494 5.54-1-1zm-3.187 9.52l-5.308 5.35a.427.427 0 0 1-.603-.602l5.308-5.35-.936-.935-5.31 5.343a.423.423 0 0 1-.6 0 .426.426 0 0 1 0-.6l5.3-5.344-1.01-1.01-5.75 5.79L0 24l5.203-.937 5.743-5.786-1.008-1.01z"/>
		</svg>

		<h1 class="totalpoll-page-title"><?php _e( 'Tools', TP_TD ); ?></h1>

		<form method="post">
			<div class="links-cards clearfix">
				<button name="migrate" type="submit" class="link-card">
					<svg width="48" value="yop-polls" height="48" viewBox="0 0 24 24">
						<path d="M9 12l-4.463 4.97L0 12h3a9 9 0 0 1 9-9c2.395 0 4.565.942 6.18 2.468l-2.005 2.23A5.975 5.975 0 0 0 12 6c-3.31 0-6 2.69-6 6h3zm10.463-4.97L15 12h3c0 3.31-2.69 6-6 6a5.978 5.978 0 0 1-4.175-1.7L5.82 18.533A8.96 8.96 0 0 0 12 21a9 9 0 0 0 9-9h3l-4.537-4.97z"/>
					</svg>
					<p><?php printf( __( '%d Polls to migrate from YOP-Polls', TP_TD ), count( $tools->yop_polls() ) ); ?></p>
				</button>
				<button name="migrate" value="wp-polls" type="submit" class="link-card">
					<svg width="48" height="48" viewBox="0 0 24 24">
						<path d="M9 12l-4.463 4.97L0 12h3a9 9 0 0 1 9-9c2.395 0 4.565.942 6.18 2.468l-2.005 2.23A5.975 5.975 0 0 0 12 6c-3.31 0-6 2.69-6 6h3zm10.463-4.97L15 12h3c0 3.31-2.69 6-6 6a5.978 5.978 0 0 1-4.175-1.7L5.82 18.533A8.96 8.96 0 0 0 12 21a9 9 0 0 0 9-9h3l-4.537-4.97z"/>
					</svg>
					<p><?php printf( __( '%d Polls to migrate from WP-Polls', TP_TD ), count( $tools->wppolls_polls() ) ); ?></p>
				</button>
				<button name="migrate" value="totalpoll" type="submit" class="link-card">
					<svg width="48" height="48" viewBox="0 0 24 24">
						<path d="M9 12l-4.463 4.97L0 12h3a9 9 0 0 1 9-9c2.395 0 4.565.942 6.18 2.468l-2.005 2.23A5.975 5.975 0 0 0 12 6c-3.31 0-6 2.69-6 6h3zm10.463-4.97L15 12h3c0 3.31-2.69 6-6 6a5.978 5.978 0 0 1-4.175-1.7L5.82 18.533A8.96 8.96 0 0 0 12 21a9 9 0 0 0 9-9h3l-4.537-4.97z"/>
					</svg>
					<p><?php printf( __( '%d Polls to migrate from TotalPoll', TP_TD ), count( $tools->totalpoll_polls() ) ); ?></p>
				</button>
			</div>
			<div class="links-cards clearfix">
				<button name="purge-cache" type="submit" class="link-card">
					<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
						<path
							d="M12 0C7.008 0 2 1.242 2 3.144 2 3.55 5.556 21.632 5.633 22.03 6.768 23.345 9.368 24 11.967 24c2.6 0 5.2-.656 6.335-1.97C18.382 21.628 22 3.564 22 3.15 22 .675 14.662 0 12 0zM8.597 13.58l-.492-.322 1.824-.008.78 1.667-.51-.32c-.73 1.146-1.03 1.764-.8 2.48-1.83-1.797-1.62-2.18-.81-3.496zm.622-1.303L10 10.86c.195-.38 1.25-.076 1.688.898l-.797 1.445-1.67-.926zm2.67 5.175h-1.73c-.43.013-.67-1.06-.03-1.915h1.76v1.915zm.06-4.886l.52-.29c-.65-1.187-1.05-1.752-1.78-1.897 2.45-.73 2.59-.41 3.44.98l.52-.28-.79 1.65-1.92-.18zm3.06.005l.91 1.48c.23.36-.55 1.13-1.61 1.04l-.93-1.5L15 12.58zm-1.55 4.85l-.01.59-1.03-1.61 1.05-1.47-.01.6c1.35.01 2.03-.05 2.52-.63-.57 2.5-.95 2.54-2.54 2.54zM12.05 5.15c-4.21 0-7.626-.747-7.626-1.668 0-.92 3.414-1.666 7.625-1.666s7.62.746 7.62 1.667c0 .92-3.42 1.668-7.63 1.668z"/>
					</svg>
					<p><?php _e( 'Purge cache', TP_TD ); ?></p>
				</button>
				<a href="<?php echo admin_url( 'export.php?content=poll&download' ); ?>" class="link-card">

					<svg width="48" height="48" viewBox="0 0 24 24">
						<path
							d="M12 5c3.453 0 5.89 2.797 5.567 6.78 1.745-.046 4.433.75 4.433 3.72 0 1.93-1.57 3.5-3.5 3.5h-13C3.57 19 2 17.43 2 15.5c0-2.797 2.48-3.833 4.433-3.72C6.266 7.562 8.64 5 12 5zm0-2c-4.006 0-7.267 3.14-7.48 7.092A5.5 5.5 0 0 0 5.5 21h13a5.5 5.5 0 0 0 .98-10.908C19.266 6.142 16.005 3 12 3zM8 13h3V9h2v4h3l-4 4-4-4z"/>
					</svg>
					<p><?php printf( __( '%d Polls to export', TP_TD ), wp_count_posts( 'poll' )->publish ); ?></p>
				</a>
				<a href="<?php echo admin_url( 'import.php?import=wordpress' ); ?>" class="link-card">
					<svg width="48" height="48" viewBox="0 0 24 24">
						<path
							d="M16 16h-3v5h-2v-5H8l4-4 4 4zm3.48-5.908C19.266 6.142 16.005 3 12 3s-7.267 3.14-7.48 7.092A5.5 5.5 0 0 0 5.5 21H9v-2H5.5C3.57 19 2 17.43 2 15.5c0-2.797 2.48-3.833 4.433-3.72C6.266 7.562 8.64 5 12 5c3.453 0 5.89 2.797 5.567 6.78 1.745-.046 4.433.75 4.433 3.72 0 1.93-1.57 3.5-3.5 3.5H15v2h3.5a5.5 5.5 0 0 0 .98-10.908z"/>
					</svg>
					<p><?php _e( 'Import polls', TP_TD ); ?></p>
				</a>
			</div>
		</form>


	</div>
</div>
