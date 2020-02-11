<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$choices_chart_data = array();

foreach ( $this->poll->choices() as $choice ):
	if ( empty( $choice['content']['visible'] ) ):
		continue;
	endif;
	$choices_chart_data[ $choice['content']['label'] ] = $choice['votes'];
endforeach;

$statistician = TotalPoll::instance( 'admin/statistics' );

$statistics = $statistician->analyze( $this->poll->id(), 10 );

if ( isset( $statistics['status'][1] ) === true ):
	$statistics['status'][ __( 'Accepted', TP_TD ) ] = $statistics['status'][1];
	unset( $statistics['status'][1] );
endif;

if ( isset( $statistics['status'][0] ) === true ):
	$statistics['status'][ __( 'Denied', TP_TD ) ] = $statistics['status'][0];
	unset( $statistics['status'][0] );
endif;

$status_chart = $statistician->data_array( array( __( 'Status', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['status'] );

$time_days_chart   = $statistician->data_array( array( __( 'Date', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['time']['days'], 30 );
$time_months_chart = $statistician->data_array( array( __( 'Month', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['time']['months'] );
$time_years_chart  = $statistician->data_array( array( __( 'Year', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['time']['years'] );
$time_today_votes  = empty( $statistics['time']['days'][ date( 'm/d/Y' ) ] ) ? 0 : $statistics['time']['days'][ date( 'm/d/Y' ) ];
$time_week_votes   = empty( $statistics['time']['weeks'][ date( 'W/Y' ) ] ) ? 0 : $statistics['time']['weeks'][ date( 'W/Y' ) ];
$time_month_votes  = empty( $statistics['time']['months'][ date( 'm/Y' ) ] ) ? 0 : $statistics['time']['months'][ date( 'm/Y' ) ];

$ua_browsers_chart  = $statistician->data_array( array( __( 'Browser', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['ua']['browsers'] );
$ua_platforms_chart = $statistician->data_array( array( __( 'Platforms', TP_TD ), __( 'Votes', TP_TD ) ), $statistics['ua']['platforms'] );

$fields_cards = array();

$fields = $this->poll->settings( 'fields' );
$fields = empty( $fields ) ? array() : $fields;

foreach ( $fields as $field ):
	if ( ! isset( $field['name'] ) ):
		continue;
	endif;
	if ( isset( $statistics['fields'][ $field['name'] ] ) && ! empty( $field['statistics']['enabled'] ) ):
		$fields_cards[] = array(
			'title' => $field['label']['content'],
			'data'  => $statistician->data_array( array( $field['label']['content'], __( 'Votes', TP_TD ) ), $statistics['fields'][ $field['name'] ] ),
		);
	endif;
endforeach;

$choices_chart = $statistician->data_array( array( __( 'Choice', TP_TD ), __( 'Votes', TP_TD ) ), $choices_chart_data );
?>
<div class="totalpoll-statistics-cards clearfix">
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Choices', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $choices_chart_data ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="PieChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $choices_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>
	</div>
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Status', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $statistics['status'] ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="PieChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $status_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="totalpoll-statistics-card full">
	<h3 class="totalpoll-statistics-card-header"><?php _e( 'Votes over the last 30 days of activity', TP_TD ); ?></h3>

	<div class="totalpoll-statistics-card-content">
		<?php if ( empty( $statistics['time']['days'] ) ): ?>
			<div class="totalpoll-chart-canvas">
				<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
			</div>
		<?php else: ?>
			<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="LineChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $time_days_chart ) ); ?>"></div>
		<?php endif; ?>
	</div>

	<div class="totalpoll-statistics-inline">
		<div class="totalpoll-statistics-item">
			<p class="number"><?php echo number_format( $this->poll->votes() ); ?></p>

			<p class="text"><?php _e( 'Votes casted', TP_TD ); ?></p>
		</div>
		<div class="totalpoll-statistics-item">
			<p class="number"><?php echo number_format( $time_today_votes ); ?></p>

			<p class="text"><?php _e( 'Votes casted today', TP_TD ); ?></p>
		</div>
		<div class="totalpoll-statistics-item">
			<p class="number"><?php echo number_format( $time_week_votes ); ?></p>

			<p class="text"><?php _e( 'Votes casted this week', TP_TD ); ?></p>
		</div>
		<div class="totalpoll-statistics-item">
			<p class="number"><?php echo number_format( $time_month_votes ); ?></p>

			<p class="text"><?php _e( 'Votes casted this month', TP_TD ); ?></p>
		</div>
	</div>
</div>

<div class="totalpoll-statistics-cards clearfix">
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Votes over months', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $statistics['months']['months'] ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="PieChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $time_months_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>

	</div>
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Votes over years', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $statistics['months']['years'] ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="PieChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $time_years_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>

	</div>
</div>

<div class="totalpoll-statistics-cards clearfix">
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Browsers', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $statistics['ua']['browsers'] ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="BarChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $ua_browsers_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>

	</div>
	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php _e( 'Platforms', TP_TD ); ?></h3>

		<div class="totalpoll-statistics-card-content">
			<?php if ( empty( $statistics['ua']['platforms'] ) ): ?>
				<div class="totalpoll-chart-canvas">
					<?php _e( 'Insufficient data to analyze.', TP_TD ); ?>
				</div>
			<?php else: ?>
				<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="BarChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $ua_platforms_chart ) ); ?>"></div>
			<?php endif; ?>
		</div>

	</div>
</div>

<div class="totalpoll-statistics-cards clearfix">
	<?php
	foreach ( $fields_cards as $index => $card ):

	if ( $index > 0 && $index % 2 === 0 ):
	?>
</div>
<div class="totalpoll-statistics-cards clearfix">
	<?php
	endif;
	?>

	<div class="totalpoll-statistics-card">
		<h3 class="totalpoll-statistics-card-header"><?php echo $card['title']; ?></h3>

		<div class="totalpoll-statistics-card-content">
			<div class="totalpoll-chart-canvas" data-tp-chart-canvas data-tp-chart-type="PieChart" data-tp-chart-data="<?php echo esc_attr( json_encode( $card['data'] ) ); ?>"></div>
		</div>

	</div>
	<?php endforeach; ?>
</div>