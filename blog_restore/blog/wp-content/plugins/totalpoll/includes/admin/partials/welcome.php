<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<style type="text/css">
	.totalpoll-welcome-panel {
		display: table;
		margin: 20px 0;
		width: 100%;
	}

	.totalpoll-welcome-panel-right, .totalpoll-welcome-panel-middle, .totalpoll-welcome-panel-left {
		position: relative;
		display: table-cell;
		vertical-align: middle;
	}

	.totalpoll-welcome-panel-right, .totalpoll-welcome-panel-middle {
		position: relative;
		padding: 20px;
		margin: 0;
		background: white;
		border: 1px solid #e5e5e5;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
		box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
	}

	.totalpoll-welcome-panel-left {
		width: 10%;
		color: white;
		fill: white;
		text-align: center;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
		background: #0073aa;
	}

	.totalpoll-welcome-panel-left h4 {
		margin: 20px 40px 0;
		font-size: 175%;
	}

	.totalpoll-welcome-panel-middle {
		width: 60%;
		border-right: 0;
	}

	.totalpoll-welcome-panel-middle:after {
		content: '';
		position: absolute;
		top: 0;
		right: 2px;
		left: 2px;
		height: 3px;

		background: #ffffff;
		background: -moz-linear-gradient(left, #ffffff 0%, #ffffff 2%, #0073aa 2%, #0073aa 50%, #ffffff 50%, #ffffff 52%, #f44336 52%, #f44336 52%, #f44336 100%);
		background: -webkit-linear-gradient(left, #ffffff 0%, #ffffff 2%, #0073aa 2%, #0073aa 50%, #ffffff 50%, #ffffff 52%, #f44336 52%, #f44336 52%, #f44336 100%);
		background: linear-gradient(to right, #ffffff 0%, #ffffff 2%, #0073aa 2%, #0073aa 50%, #ffffff 50%, #ffffff 52%, #f44336 52%, #f44336 52%, #f44336 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f44336', GradientType=1);
		background-position-x: -10px;
		background-size: 120px;

		transform: skewX(120deg);

	}

	.totalpoll-welcome-panel-middle p {
		margin-top: 0;
	}

	.totalpoll-welcome-panel-middle h4, .totalpoll-welcome-panel-right h4 {
		font-size: 150%;
		margin: 0 0 20px;
	}

	.totalpoll-welcome-panel-middle input {
		padding: 10px;
		line-height: 1.15;
	}

	.totalpoll-welcome-panel-buttons {
		padding-top: 20px;
		text-align: right;
	}

	.totalpoll-welcome-checkbox {
		word-break: normal;
		text-align: left;
		line-height: 30px;
		white-space: nowrap;
	}

	.totalpoll-welcome-panel-right {
		text-align: center;
		width: 30%;
	}

	.totalpoll-welcome-panel-right a {
		display: inline-block;
		margin: 10px;
	}

	@media screen and (max-width: 1170px) {
		.totalpoll-welcome-panel-left {
			display: none;
		}
	}

	@media screen and (max-width: 960px) {
		.totalpoll-welcome-panel, .totalpoll-welcome-panel-middle, .totalpoll-welcome-panel-right {
			display: block;
			width: auto;
			border-right: 0;
			border-left: 0;
		}

		.totalpoll-welcome-panel-middle label {
			display: block;
			float: none;
			text-align: left;
		}

		.totalpoll-welcome-panel-left {
			display: none;
		}

	}

</style>
<div class="wrap">
	<div class="totalpoll-welcome-panel">
		<form id="totalpoll-welcome" action="http://subscribe.misqtech.com/mymail/subscribe?utm_campaign=welcome-box&utm_medium=in-app&utm_source=totalpoll-pro" method="post" target="_blank" onsubmit="hideTotalPollWelcome()">
			<input name="formid" type="hidden" value="2">
			<div class="totalpoll-welcome-panel-left">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="72" height="72" viewBox="0 0 20 20" fill="#FFFFFF">
					<path d="M3.87 4h13.25c1.25 0 1.88 0.59 1.88 1.79v8.42c0 1.19-0.63 1.79-1.88 1.79h-13.25c-1.25 0-1.88-0.6-1.88-1.79v-8.42c0-1.2 0.63-1.79 1.88-1.79zM10.49 12.6l6.74-5.53c0.24-0.2 0.43-0.66 0.13-1.070-0.29-0.41-0.82-0.42-1.17-0.17l-5.7 3.86-5.69-3.86c-0.35-0.25-0.88-0.24-1.17 0.17-0.3 0.41-0.11 0.87 0.13 1.070z"></path>
				</svg>

				<h4><?php _e( 'Newsletter', TP_TD ); ?></h4>
			</div>

			<div class="totalpoll-welcome-panel-middle">
				<h4><?php _e( 'Tips, tutorials and useful WordPress resources.', TP_TD ); ?></h4>
				<p><?php _e( "Thank you for installing TotalPoll Pro! We're excited to share tips and tutorials with you to make the most of TotalPoll. Subscribe now and you will receive a tip everyday for a week plus a 20% discount coupon to use in our store.", TP_TD ); ?></p>
				<input type="email" name="email" required placeholder="<?php esc_attr_e( 'Enter your email here.', TP_TD ); ?>" class="widefat">

				<div class="totalpoll-welcome-panel-buttons">
					<label class="alignleft totalpoll-welcome-checkbox">
						<input type="checkbox" name="weekly-newsletter" value="1" checked>
						<?php printf( __( 'Count me in the <a href="%s" target="_blank">weekly newsletter</a>.', TP_TD ), 'http://wpeekly.com?utm_campaign=welcome-box&utm_medium=in-app&utm_source=totalpoll-pro' ); ?>
					</label>

					<button type="button" class="button button-large" onclick="hideTotalPollWelcome();"><?php _e( 'No, thank you', TP_TD ); ?></button>
					&nbsp;
					<button class="button button-primary button-large"><?php _e( 'Subscribe', TP_TD ); ?></button>
					<br class="clear">
				</div>
			</div>

			<div class="totalpoll-welcome-panel-right">
				<h4><?php _e( 'Keep in touch with us!', TP_TD ); ?></h4>

				<a href="https://twitter.com/MisqTech" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#55acee">
						<path
							d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/>
					</svg>
				</a>

				<a href="https://www.facebook.com/misqtech" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#3b5998">
						<path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/>
					</svg>
				</a>

				<a href="https://google.com/+Misqtech" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#dc4e41">
						<path
							d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2.917 16.083c-2.258 0-4.083-1.825-4.083-4.083s1.825-4.083 4.083-4.083c1.103 0 2.024.402 2.735 1.067l-1.107 1.068c-.304-.292-.834-.63-1.628-.63-1.394 0-2.531 1.155-2.531 2.579 0 1.424 1.138 2.579 2.531 2.579 1.616 0 2.224-1.162 2.316-1.762h-2.316v-1.4h3.855c.036.204.064.408.064.677.001 2.332-1.563 3.988-3.919 3.988zm9.917-3.5h-1.75v1.75h-1.167v-1.75h-1.75v-1.166h1.75v-1.75h1.167v1.75h1.75v1.166z"/>
					</svg>
				</a>

			</div>

		</form>

		<script type="text/javascript">
			function hideTotalPollWelcome() {
				jQuery.get(ajaxurl, {action: 'totalpoll_hide_welcome'});
				jQuery('#totalpoll-welcome').remove();
			}
		</script>


	</div>
</div>