<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$short_version = substr( TP_VERSION, 0, 3 );

$texts = array(

	array(
		'image'       => TP_URL . 'assets/images/easy.gif',
		'heading'     => __( 'User friendly', TP_TD ),
		'description' => __( 'With TotalPoll, you can now create polls within 60 seconds! Its user friendly interface was expertly designed to make poll creation hassle-free.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/speed.gif',
		'heading'     => __( 'Overall performance', TP_TD ),
		'description' => __( 'TotalPoll was developed with performance in mind! It functions without a glitch with WordPress.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/customization.gif',
		'heading'     => __( 'Customization', TP_TD ),
		'description' => __( 'Do you need “Welcome message” or “Thank you message”? We have got your back! With over 5 customization settings, you definitely have the total control.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/custom-fields.gif',
		'heading'     => __( 'Custom fields', TP_TD ),
		'description' => __( 'Collecting additional information from voters has never been that easy. With our unique 5 different field types, you can absolutely collect everything.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/statistics.gif',
		'heading'     => __( 'Statistics', TP_TD ),
		'description' => __( 'Regular vote tracking has been made easy. You can now track votes daily, weekly and monthly. Even, you can track custom field values with votes.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/extensible.gif',
		'heading'     => __( 'Extensible', TP_TD ),
		'description' => __( 'The extensibility of TotalPoll makes such extensions and templates possible. Developers can certainly make TotalPoll a step further to suit your needs.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/seo.gif',
		'heading'     => __( 'SEO Friendly', TP_TD ),
		'description' => __( 'Search Engine Optimization is a very key factor for every website. Thus, TotalPoll makes polls more search-engine friendly in order to get more search traffic.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/mobile-first.gif',
		'heading'     => __( 'Mobile-first', TP_TD ),
		'description' => __( 'With Totalpoll, you gain more engagement with an optimal viewing experience for your visitors. And your polls will always look amazing whether in a laptop, tablet or a smartphone.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/limitations.gif',
		'heading'     => __( 'Voting limitations', TP_TD ),
		'description' => __( 'The ability of Totalpoll to reduce votes and results makes it easy for you to specify conditions under which visitors can vote in a poll or see its results.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/import.gif',
		'heading'     => __( 'Polls import', TP_TD ),
		'description' => __( 'Are you planning to switch? You can migrate from WP-Polls and YOP polls easily (and many more plugins are coming).', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/choices.gif',
		'heading'     => __( 'Choices', TP_TD ),
		'description' => __( 'Give your polls more attraction with Text, Image, Video, Audio, Short code or even HTML.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/logs.gif',
		'heading'     => __( 'Logs', TP_TD ),
		'description' => __( 'Trace back every vote, with an informative log to identify any cheating attempts.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/templates.gif',
		'heading'     => __( 'Templates', TP_TD ),
		'description' => __( 'Make your polls look compelling with customized templates from our store.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/extensions.gif',
		'heading'     => __( 'Extensions', TP_TD ),
		'description' => __( 'Take TotalPoll’s functionality a step further with powerful extensions from our store.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/integration.gif',
		'heading'     => __( 'Integration', TP_TD ),
		'description' => __( 'Totalpoll integrates your polls everywhere like with a short code, widget or even a direct link.', TP_TD ),
	),
	array(
		'image'       => TP_URL . 'assets/images/3rd-party.gif',
		'heading'     => __( '3rd-party Compatibility', TP_TD ),
		'description' => __( 'TotalPoll is compatible with other plugins like WPML, ACF, WP SUPER CACHE and Others.', TP_TD ),
	),

);

$changelog = array(
	array(
		'heading'     => __( 'Templates', TP_TD ),
		'description' => __( '<code>TP_Template</code> can be used now as a prototype for templates.', TP_TD ),
	),
	array(
		'heading'     => __( 'Extensions', TP_TD ),
		'description' => __( '<code>TP_Extension</code> can be used now as a prototype for extensions.', TP_TD ),
	),
	array(
		'heading'     => __( 'Fields', TP_TD ),
		'description' => __( '<code>TP_Field</code> can be used now as a prototype for custom fields.', TP_TD ),
	),
	array(
		'heading'     => __( 'HTML', TP_TD ),
		'description' => __( '<code>TP_HTML</code> is used for HTML manipulation and generation.', TP_TD ),
	),
	array(
		'heading'     => __( 'Poll', TP_TD ),
		'description' => __( '<code>TP_Poll</code> is the main class for poll manipulation and rendering.', TP_TD ),
	),
	array(
		'heading'     => __( 'Request', TP_TD ),
		'description' => __( '<code>TP_Request</code> is used to handle TotalPoll requests.', TP_TD ),
	),
);

$credits = array(
	array(
		'url'      => '#',
		'name'     => 'TotalPoll Team',
		'position' => 'Arabic Translation',
	),
	array(
		'url'      => 'http://iconmonstr.com',
		'name'     => 'Iconmonstr',
		'position' => 'Icons',
	),
	array(
		'url'      => 'https://pixelbuddha.net/ballicons2/',
		'name'     => 'Ballicons',
		'position' => 'Icons',
	),
	array(
		'url'      => 'http://xdsoft.net/jqplugins/datetimepicker/',
		'name'     => 'DateTime Picker',
		'position' => 'jQuery Plugin',
	),
);

?>

<div class="wrap about-wrap" id="totalpoll-about">
	<h1><?php _e( 'Welcome to', TP_TD ); ?> TotalPoll <?php echo $short_version; ?></h1>

	<div class="about-text"><?php _e( 'Thank you for using TotalPoll! This update brings several improvements and new features along with fixes of known bugs.', TP_TD ); ?></div>
	<div class="totalpoll-badge">
		<svg viewBox="0 0 47.268 52.373">
			<path class="st0" d="M43.702,10.483L27.2,0.956c-2.207-1.274-4.925-1.274-7.132,0L3.566,10.483C1.359,11.757,0,14.112,0,16.66
				l0,19.054c0,2.548,1.359,4.903,3.566,6.177l16.502,9.527c2.207,1.274,4.925,1.274,7.132,0l0.758-0.438
				c0.789-0.456,1.275-1.298,1.275-2.209V47.59c0-1.439-1.557-2.338-2.803-1.618l0,0c-1.73,0.999-3.862,0.999-5.592,0l-12.94-7.471
				c-1.73-0.999-2.796-2.845-2.796-4.843V18.716c0-1.998,1.066-3.844,2.796-4.843l12.94-7.471c1.73-0.999,3.862-0.999,5.593,0
				l12.94,7.471c1.73,0.999,2.796,2.845,2.796,4.843v14.941c0,0.047-0.008,0.092-0.009,0.139h0.009v7.207
				c0,0.788,0.853,1.281,1.536,0.887l0,0c2.207-1.274,3.566-3.629,3.566-6.177V16.659C47.268,14.111,45.908,11.757,43.702,10.483z"
			/>

			<path class="st0" d="M22.563,17.766v14.548c0,0.901-0.737,1.638-1.638,1.638h-0.617c-0.901,0-1.638-0.737-1.638-1.638V17.766
							c0-0.901,0.737-1.638,1.638-1.638h0.617C21.826,16.127,22.563,16.865,22.563,17.766z"/>
			<path class="st0" d="M16.181,26.254v6.06c0,0.901-0.737,1.638-1.638,1.638h-0.617c-0.901,0-1.638-0.737-1.638-1.638v-6.06
							c0-0.901,0.737-1.638,1.638-1.638h0.617C15.444,24.615,16.181,25.353,16.181,26.254z"/>
			<path class="st0" d="M28.945,22.38v9.934c0,0.901-0.737,1.638-1.638,1.638h-0.617c-0.901,0-1.638-0.737-1.638-1.638V22.38
							c0-0.901,0.737-1.638,1.638-1.638l0.617,0C28.207,20.741,28.945,21.479,28.945,22.38z"/>
			<path class="st0" d="M34.98,19.264v13.05c0,0.901-0.737,1.638-1.638,1.638h-0.617c-0.901,0-1.638-0.737-1.638-1.638v-13.05
							c0-0.901,0.737-1.638,1.638-1.638h0.617C34.243,17.626,34.98,18.363,34.98,19.264z"/>
		</svg>
		<?php printf( __( 'Version %s' ), $short_version ); ?>
	</div>

	<h2 class="nav-tab-wrapper">
		<span class="nav-tab nav-tab-active"><?php _e( 'What’s New', TP_TD ); ?></span>
	</h2>
	<div class="features boxes">
		<div class="boxes-row">
			<?php foreach ( $texts as $index => $feature ): ?>
			<?php if ( $index !== 0 && $index % 2 === 0 ): ?>
		</div>
		<div class="boxes-row">
			<?php endif; ?>
			<div class="boxes-cell">
				<div class="clearfix">
					<img src="<?php echo esc_attr( $feature['image'] ); ?>">

					<h3><?php echo $feature['heading']; ?></h3>

					<p><?php echo $feature['description']; ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<h2 class="nav-tab-wrapper">
		<span class="nav-tab nav-tab-active"><?php _e( 'Under the Hood' ); ?></span>
	</h2>

	<br>

	<div class="changelog boxes">
		<div class="boxes-row">
			<?php foreach ( $changelog as $index => $change ): ?>
			<?php if ( $index !== 0 && $index % 2 === 0 ): ?>
		</div>
		<div class="boxes-row">
			<?php endif; ?>
			<div class="boxes-cell">
				<h4><?php echo $change['heading']; ?></h4>

				<p><?php echo $change['description']; ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<h2 class="nav-tab-wrapper">
		<span class="nav-tab nav-tab-active"><?php _e( 'Credits', TP_TD ); ?></span>
	</h2>

	<br>

	<div class="credits boxes">
		<div class="boxes-row">
			<?php foreach ( $credits as $index => $credit ): ?>
			<?php if ( $index !== 0 && $index % 2 === 0 ): ?>
		</div>
		<div class="boxes-row">
			<?php endif; ?>
			<div class="boxes-cell">
				<h4>
					<a class="web" href="<?php echo esc_attr( $credit['url'] ); ?>" target="_blank">
						<?php echo $credit['name']; ?>
					</a>
				</h4>
				<span class="title"><?php echo $credit['position']; ?></span>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<h2 class="nav-tab-wrapper">
		<span class="nav-tab nav-tab-active"><?php _e( 'Testimonials', TP_TD ); ?></span>
	</h2>

	<div class="testimonials boxes">
		<div class="boxes-row">
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>TotalPoll Pro has everything to make polls engaging and attractive. The best thing about the plugin is, it allows me to insert links in the poll answers. I've increased my affiliate sales by adding affiliate links in poll answers.</p>
			</div>
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>The interactive poll works exactly how I had planned and provides instant online results. I especially like how I can customize the poll to match the website. I can also export the poll results.</p>
			</div>
		</div>
		<div class="boxes-row">
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>Great app does everything I need. Quality customer support very very fast and very professional!</p>
			</div>
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>Very happy with this plugin and the author's quick support! Bought the extras pack to get additional features too!</p>
			</div>
		</div>
		<div class="boxes-row">
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>This really is a great plugin, and support was so fast and precise when I needed some help. I can truly recommend this plugin to everyone who needs a good costumizable poll on the website!</p>
			</div>
			<div class="boxes-cell clearfix">
				<h4>CodeCanyon customer</h4>
				<p>This is a great plugin with loads of features and very clean code. The response to a support request came so quickly that I thought it was an automated message, It wasn't.</p>
			</div>
		</div>
	</div>

	<div class="return-to-dashboard">
		<a href="<?php admin_url( 'edit.php?post_type=poll' ) ?>"><?php _e( 'Go to Polls', TP_TD ); ?></a>
	</div>

</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function () {

		var reloadGif = function (e) {
			e.target.querySelector('img').src = e.target.querySelector('img').src;
		};

		var features = document.querySelectorAll('.features.boxes .boxes-cell');

		Array.prototype.slice.call(features).forEach(function (el) {
			el.addEventListener('mouseenter', reloadGif, false);
		});

	});
</script>