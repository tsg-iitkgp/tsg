<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<style type="text/css">
	.totalpoll-phpversion-panel {
		display: table;
		margin: 20px 0;
		width: 100%;
	}

	.totalpoll-phpversion-panel-right, .totalpoll-phpversion-panel-middle, .totalpoll-phpversion-panel-left {
		position: relative;
		display: table-cell;
		vertical-align: middle;
	}

	.totalpoll-phpversion-panel-right, .totalpoll-phpversion-panel-middle {
		position: relative;
		padding: 20px;
		margin: 0;
		background: white;
		border: 1px solid #e5e5e5;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
		box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
	}

	.totalpoll-phpversion-panel-left {
		width: 15%;
		color: white;
		fill: white;
		text-align: center;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
		background: #aa1800;
	}

	.totalpoll-phpversion-panel-right {
		width: 85%;
		border-right: 0;
		border-top: 2px solid #aa1800;
	}

	.totalpoll-phpversion-panel-right p {
		margin-top: 0;
	}

	.totalpoll-phpversion-panel-right h4, .totalpoll-phpversion-panel-right h4 {
		font-size: 150%;
		margin: 0 0 20px;
	}

	@media screen and (max-width: 1170px) {
		.totalpoll-phpversion-panel-left {
			display: none;
		}
	}

	@media screen and (max-width: 960px) {
		.totalpoll-phpversion-panel, .totalpoll-phpversion-panel-middle, .totalpoll-phpversion-panel-right {
			display: block;
			width: auto;
			border-right: 0;
			border-left: 0;
		}

		.totalpoll-phpversion-panel-middle label {
			display: block;
			float: none;
			text-align: left;
		}

		.totalpoll-phpversion-panel-left {
			display: none;
		}

	}

</style>
<div class="wrap">
	<div class="totalpoll-phpversion-panel">
		<div id="totalpoll-phpversion">
			<div class="totalpoll-phpversion-panel-left">
				<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="#FFFFFF">
					<path d="M16.143 2l5.857 5.858v8.284l-5.857 5.858h-8.286l-5.857-5.858v-8.284l5.857-5.858h8.286zm.828-2h-9.942l-7.029 7.029v9.941l7.029 7.03h9.941l7.03-7.029v-9.942l-7.029-7.029zm-6.471 6h3l-1 8h-1l-1-8zm1.5 12.25c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25z"/>
				</svg>
			</div>
			<div class="totalpoll-phpversion-panel-right">
				<h4><?php _e( 'Heads up!', TP_TD ); ?></h4>
				<p>You're using an old version of PHP (<?php echo PHP_VERSION; ?>) which have reached official End Of Life and may expose your site to security vulnerabilities and limit TotalPoll developers from several new features. Therefore, PHP 5.4+ will be the minimum required version for running TotalPoll 3.3.</p>

				<button type="button" class="button button-large" onclick="hideTotalPollPHPVersion();">Alright!</button>
			</div>
		</div>


		<script type="text/javascript">
			function hideTotalPollPHPVersion() {
				jQuery.get(ajaxurl, {action: 'totalpoll_hide_php_version'});
				jQuery('#totalpoll-phpversion').remove();
			}
		</script>


	</div>
</div>