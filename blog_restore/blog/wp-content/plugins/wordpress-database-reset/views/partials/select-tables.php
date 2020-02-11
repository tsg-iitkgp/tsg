<p>1. <?php _e( 'Select the database tables you would like to reset', 'wordpress-database-reset' ) ?>:</p>

<div id="select-container">
  <a href='#' id="select-all"><?php _e( 'Select All', 'wordpress-database-reset' ) ?></a>
  <select id="wp-tables" multiple="multiple" name="db-reset-tables[]">
    <?php foreach ( $this->wp_tables as $key => $value ) : ?>
      <option value="<?php echo $key ?>"><?php echo $key ?></option>
    <?php endforeach ?>
  </select>
</div>

<p id="reactivate" style="display: none">
  <label for="db-reset-reactivate-theme-data">
    <input type="checkbox" name="db-reset-reactivate-theme-data" id="db-reset-reactivate-theme-data" checked="checked" value="true" />
    <?php _e( 'Reactivate current theme and plugins after reset?', 'wordpress-database-reset' ) ?>
  </label>
</p>

<?php include( 'disclaimer.php' ) ?>

<hr>
