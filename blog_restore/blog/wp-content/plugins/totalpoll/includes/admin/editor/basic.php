<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<?php do_action( 'totalpoll/actions/admin/editor/includes/question/before' ); ?>
<?php include 'question.php'; ?>
<?php do_action( 'totalpoll/actions/admin/editor/includes/choices/before' ); ?>
<?php include 'choices.php'; ?>
<?php do_action( 'totalpoll/actions/admin/editor/includes/settings/before' ); ?>
<?php include 'settings.php'; ?>
<?php do_action( 'totalpoll/actions/admin/editor/includes/browser/before' ); ?>
<?php include 'browse.php'; ?>
