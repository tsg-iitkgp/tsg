<?php if ( $this->notice_success ) : ?>
  <div class="updated notice is-dismissible">
    <p><strong><?php echo $this->notice_success ?>.</strong></p>
  </div>
<?php elseif ( $this->notice_error ) : ?>
  <div class="error notice is-dismissible">
    <p><strong><?php echo $this->notice_error ?>.</strong></p>
  </div>
<?php endif ?>
