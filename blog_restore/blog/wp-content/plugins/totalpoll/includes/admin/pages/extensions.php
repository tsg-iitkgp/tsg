<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

global $wp_version;

// Init extensions manager
$extensions = TotalPoll::instance( 'admin/extensions' );

// Some useful variables to be used later
$current_tab      = isset( $_GET['tab'] ) ? $_GET['tab'] : 'browse';
$current_category = isset( $_GET['category'] ) ? $_GET['category'] : 'all';
$current_page     = 'edit.php?post_type=poll&page=tp-extensions';

// Load activated extensions
$extensions->load();

// Process actions ( activate, deactivate and delete)
if (
	! empty( $_REQUEST['action'] ) &&
	! empty( $_REQUEST['edit-extensions'] ) &&
	! empty( $_REQUEST['checked'] ) &&
	wp_verify_nonce( $_REQUEST['edit-extensions'] )
):

	$action  = isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] !== - 1 ? $_REQUEST['action2'] : $_REQUEST['action'];
	$checked = (array) $_REQUEST['checked'];

	if ( $action === 'activate' ):
		$errors = $extensions->activate( $checked );
	elseif ( $action === 'deactivate' ):
		$errors = $extensions->deactivate( $checked );
	elseif ( $action === 'delete' ):
		$errors = $extensions->uninstall( $checked );
	endif;

endif;

// Process installation
if (
	! empty( $_REQUEST['install-extension'] ) &&
	! empty( $_FILES ) &&
	wp_verify_nonce( $_REQUEST['install-extension'] )
):

	$extensions->install();

endif;

// Fetch extensions
$fetched_extensions = $extensions->fetch();
?>
<div class="wrap">
	<?php if ( isset( $errors ) && is_wp_error( $errors ) ): ?>
		<div class="notice-error"><p><?php echo $errors->get_error_message(); ?></p></div>
	<?php endif; ?>
	<h2>
		<?php _e( 'Extensions', TP_TD ); ?>
		<?php if ( $current_tab === 'browse' ): ?>
			<a href="<?php echo admin_url( "$current_page&tab=upload" ); ?>" class="upload add-new-h2"><?php _e( 'Upload', TP_TD ); ?></a>
		<?php else: ?>
			<a href="<?php echo admin_url( "$current_page&tab=browse" ); ?>" class="upload add-new-h2"><?php _e( 'Browse', TP_TD ); ?></a>
		<?php endif; ?>
	</h2>

	<?php if ( $current_tab === 'browse' ): ?>

		<ul class="subsubsub">
			<?php foreach ( $extensions->categories as $slug => $category ): ?>
				<li class="<?php echo esc_attr( $slug ); ?>"><a href="<?php echo esc_url( admin_url( "$current_page&tab=browse&category={$slug}" ) ); ?>"
				                                                class="<?php echo $current_category == $slug ? 'current' : ''; ?>"><?php echo esc_html( $category['name'] ); ?>
						<span class="count">(<?php echo $category['count']; ?>)</span></a> |
				</li>
			<?php endforeach; ?>
		</ul>

		<form method="post">
			<?php wp_nonce_field( - 1, 'edit-extensions' ); ?>

			<div class="tablenav top">

				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-top" class="screen-reader-text"><?php _e( 'Select bulk action' ); ?></label>
					<select name="action" id="bulk-action-selector-top">
						<option value="-1" selected="selected"><?php _e( 'Bulk Actions' ); ?></option>
						<option value="activate"><?php _e( 'Activate' ); ?></option>
						<option value="deactivate"><?php _e( 'Deactivate' ); ?></option>
						<option value="delete"><?php _e( 'Delete' ); ?></option>
					</select>
					<input type="submit" name="" id="doaction" class="button action" value="<?php esc_attr_e( 'Apply' ); ?>">
				</div>

				<br class="clear">

			</div>

			<table class="wp-list-table widefat plugins">
				<thead>
				<tr>
					<?php $table_tag = version_compare( $wp_version, '4.3', '<=' ) ? 'th' : 'td'; ?>
					<<?php echo $table_tag; ?> scope="col" id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All' ); ?></label>
					<input id="cb-select-all-1" type="checkbox">
				</<?php echo $table_tag; ?>>
				<th scope="col" id="name" class="manage-column column-name"><?php _e( 'Extension', TP_TD ); ?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php _e( 'Description', TP_TD ); ?></th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<<?php echo $table_tag; ?> scope="col" id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-2"><?php _e( 'Select All' ); ?></label>
					<input id="cb-select-all-2" type="checkbox">
				</<?php echo $table_tag; ?>>
				<th scope="col" id="name" class="manage-column column-name"><?php _e( 'Extension', TP_TD ); ?></th>
				<th scope="col" id="description" class="manage-column column-description"><?php _e( 'Description', TP_TD ); ?></th>
				</tr>
				</tfoot>

				<tbody id="the-list">
				<?php
				foreach ( $fetched_extensions as $directory => $extension ):
					if ( $current_category !== 'all' && $extension['category-slug'] !== $current_category ) {
						continue;
					}
					?>
					<tr class="<?php echo $extension['activated'] ? 'active' : ( $extension['compatible'] ? 'inactive' : 'active update' ); ?>">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="<?php echo md5( $directory ); ?>"><?php echo esc_html( $extension['name'] ); ?></label>

							<?php if ( $extension['compatible'] ): ?>
								<input type="checkbox" name="checked[]" value="<?php echo esc_attr( $directory ); ?>" id="<?php echo md5( $directory ); ?>">
							<?php endif; ?>
						</th>
						<td class="plugin-title">
							<strong><?php echo esc_html( $extension['name'] ); ?></strong>

							<div class="row-actions visible">

								<?php if ( $extension['compatible'] ): ?>
									<?php if ( $extension['activated'] ): ?>
										<span class="deactivate">
                                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( "$current_page&action=deactivate&checked[]=$directory" ), - 1, 'edit-extensions' ) ); ?>">
	                                    <?php _e( 'Deactivate' ); ?>
                                    </a> | 
                                </span>
									<?php else: ?>
										<span class="activate">
                                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( "$current_page&action=activate&checked[]=$directory" ), - 1, 'edit-extensions' ) ); ?>">
	                                    <?php _e( 'Activate' ); ?>
                                    </a> | 
                                </span>
									<?php endif; ?>
									<span class="edit">
                                    <a href="plugin-editor.php?file=<?php echo esc_attr( $extension['basename'] ); ?>" title="<?php _e( 'Open this file in the Plugin Editor' ); ?>"
                                       class="edit">
	                                    <?php _e( 'Edit' ); ?>
                                    </a> | 
                                </span>
								<?php endif; ?>

								<?php if ( ! $extension['activated'] ): ?>
									<span class="delete">
                                    <a href="<?php echo esc_url( wp_nonce_url( admin_url( "$current_page&action=delete&checked[]=$directory" ), - 1, 'edit-extensions' ) ); ?>"
                                       class="delete">
	                                    <?php _e( 'Delete' ); ?>
                                    </a>
                                </span>
								<?php endif; ?>

							</div>
						</td>
						<td class="column-description desc">
							<div class="plugin-description"><p><?php echo esc_html( $extension['description'] ); ?></p></div>
							<div class="active second plugin-version-author-uri">
								<?php _e( 'Version', TP_TD ); ?> <?php echo esc_html( $extension['version'] ); ?> |
								<?php if ( ! $extension['compatible'] ): ?>
									<a href="<?php echo esc_url( TP_WEBSITE ); ?>" class="delete" target="_blank">
										<?php _e( 'Requires', TP_TD ); ?><?php echo esc_html( $extension['requires'] ); ?>
									</a> |
								<?php endif; ?>
								<?php _e( 'By' ); ?> <a href="<?php echo esc_url( $extension['authorURI'] ); ?>" target="_blank">
									<?php echo esc_html( $extension['author'] ) ?>
								</a> |
								<a href="<?php echo esc_url( $extension['website'] ); ?>" target="_blank">
									<?php _e( 'Extension page', TP_TD ); ?>
								</a>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php if ( empty( $fetched_extensions ) ): ?>
					<tr>
						<td colspan="3"><?php printf( __( 'No extensions. Browse the <a href="%s">store</a>.' ), admin_url( 'edit.php?post_type=poll&page=tp-store' ) ); ?>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
			<div class="tablenav bottom">

				<div class="alignleft actions bulkactions">
					<label for="bulk-action-selector-top" class="screen-reader-text"><?php _e( 'Select bulk action' ); ?></label>
					<select name="action2" id="bulk-action-selector-top">
						<option value="-1" selected="selected"><?php _e( 'Bulk Actions' ); ?></option>
						<option value="activate"><?php _e( 'Activate' ); ?></option>
						<option value="deactivate"><?php _e( 'Deactivate' ); ?></option>
						<option value="delete"><?php _e( 'Delete' ); ?></option>
					</select>
					<input type="submit" name="" id="doaction2" class="button action" value="<?php esc_attr_e( 'Apply' ); ?>">
				</div>

				<br class="clear">

			</div>

		</form>

	<?php else: ?>
		<div class="upload-plugin totalpoll-upload">
			<p class="install-help"><?php _e( 'If you have an extension in a .zip format, you may install it by uploading it here.', TP_TD ); ?></p>

			<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo admin_url( "$current_page&tab=upload" ); ?>">
				<?php wp_nonce_field( - 1, 'install-extension' ); ?>
				<input type="file" id="extensionzip" name="extensionzip">
				<input type="submit" name="install-extension-submit" id="install-extension-submit" class="button" value="<?php esc_attr_e( 'Install Now', TP_TD ); ?>" disabled="">
			</form>
		</div>
	<?php endif; ?>
</div>