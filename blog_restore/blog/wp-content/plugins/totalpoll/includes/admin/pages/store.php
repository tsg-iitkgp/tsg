<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

// Some useful variables to be used later
$current_tab  = isset( $_GET['tab'] ) && in_array( $_GET['tab'], array( 'templates', 'extensions' ) ) ? $_GET['tab'] : 'templates';
$current_page = 'edit.php?post_type=poll&page=tp-store';

// Fetch downloads
$downloads = (array) $this->store( ! empty( $_REQUEST['refresh'] ) );

// Process installation
$installable = TotalPoll::instance( "admin/{$current_tab}" );
if ( ! empty( $_REQUEST["install-{$current_tab}"] ) && ! empty( $_REQUEST['url'] ) && wp_verify_nonce( $_REQUEST["install-{$current_tab}"] ) ):
	$installable->install( $_REQUEST['url'] );
endif;

$installed = $installable->fetch();

?>
<div id="totalpoll-store" class="wrap">
	<h1><?php _e( 'Store', TP_TD ); ?></h1>

	<h2 class="nav-tab-wrapper">
		<a href="<?php echo esc_attr( admin_url( "{$current_page}&tab=templates" ) ); ?>">
			<span class="nav-tab <?php echo $current_tab === 'templates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Templates', TP_TD ); ?></span>
		</a>
		<a href="<?php echo esc_attr( admin_url( "{$current_page}&tab=extensions" ) ); ?>">
			<span class="nav-tab <?php echo $current_tab === 'extensions' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Extensions', TP_TD ); ?></span>
		</a>
		<a href="<?php echo esc_attr( admin_url( "{$current_page}&tab={$current_tab}&refresh=1" ) ); ?>">
			<span class="nav-tab"><?php _e( 'Refresh (new purchases)', TP_TD ); ?></span>
		</a>
	</h2>

	<form method="post">
		<div class="extensions">
			<?php foreach ( $downloads[ $current_tab ] as $download ) : ?>
				<div class="extension-card">
					<div class="extension-card-header">
						<img src="<?php echo esc_attr( $download['thumbnail'] ); ?>" alt="<?php echo esc_attr( $download['title'] ); ?>">
						<?php if ( $download['price'] == 0 ): ?>
							<span class="extension-price-label"><?php _e( 'Free!', TP_TD ); ?></span>
						<?php endif; ?>
					</div>
					<div class="extension-card-content">
						<h3 class="extension-name"><a href="<?php echo esc_attr( esc_url( $download['permalink'] ) ); ?>" target="_blank"><?php echo esc_html( $download['title'] ); ?></a></h3>

						<p class="extension-meta">
							<strong class="extension-price">$<?php echo esc_html( $download['price'] ); ?></strong>
							&nbsp;&bull;&nbsp;
							<?php printf( __( '%s Downloads', TP_TD ), number_format( $download['sales'] ) ); ?>
						</p>

						<p class="extension-description"><?php echo strip_tags( $download['description'] ); ?></p>
					</div>

					<div class="extension-card-footer clearfix">
						<div class="alignleft">
							<?php printf( __( 'Version %s', TP_TD ), $download['version'] ); ?>
						</div>
						<div class="alignright">
							&nbsp;
							<?php if ( isset( $installed[ $download['slug'] ] ) ): ?>
								<?php if ( $download['purchased'] && version_compare( $installed[ $download['slug'] ]['version'], $download['version'], '<' ) ): ?>
									<button name="url" value="<?php echo esc_attr( $download['download'] ) ?>" type="submit" class="button"><?php printf( __( 'Update to %s', TP_TD ), $download['version'] ); ?></button>
								<?php else: ?>
									<button class="button button-disabled" disabled>&checkmark;&nbsp;<?php _e( 'Installed', TP_TD ); ?></button>
								<?php endif; ?>
							<?php elseif ( $download['purchased'] ): ?>
								<?php if ( version_compare( $download['requires'], TP_VERSION, '<=' ) ): ?>
									<button name="url" value="<?php echo esc_attr( $download['download'] ) ?>" type="submit" class="button button-primary"><?php _e( 'Install', TP_TD ); ?></button>
								<?php else: ?>
									<button class="button button-disabled" disabled><?php printf( __( 'Requires %s', TP_TD ), $download['requires'] ); ?></button>
								<?php endif; ?>
							<?php else: ?>
								<a href="<?php echo esc_attr( esc_url( $download['permalink'] ) ); ?>" target="_blank" class="button button-primary"><?php echo $download['price'] == 0 ? __( 'Get it', TP_TD ) : __( 'Buy now', TP_TD ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php wp_nonce_field( - 1, "install-{$current_tab}" ); ?>
	</form>
</div>