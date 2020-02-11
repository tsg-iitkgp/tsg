<div class="wrap card">
  <h2><?php _e( 'Database Reset', 'wordpress-database-reset' ) ?></h2>

  <?php include( 'partials/notice.php' ) ?>

  <form method="post" id="db-reset-form">
    <?php include( 'partials/select-tables.php' ) ?>
    <?php include( 'partials/security-code.php' ) ?>
    <?php include( 'partials/submit-button.php' ) ?>
  </form>

  <?php include( 'partials/donate.php' ) ?>
</div>
