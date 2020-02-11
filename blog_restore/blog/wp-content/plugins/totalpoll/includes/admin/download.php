<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Admin_Download' ) ) :

	/**
	 * Download Class
	 *
	 * @package TotalPoll/Classes/Admin/Download
	 * @since   3.0.0
	 */
	class TP_Admin_Download {

		private $filename = '';
		private $header = '';
		private $labels = '';
		private $content = '';
		private $footer = '';
		private $driver = '';

		public function __construct() {
		}

		public function __call( $name, $args ) {
			if ( isset( $args[0] ) ):
				$this->{$name} = $args[0];
			endif;

			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

		public function driver( $driver = 'csv' ) {
			$driver       = 'TP_Admin_Download_' . strtoupper( $driver );
			$this->driver = new $driver( $this );
		}

		public function send() {
			exit( $this->driver->output() );
		}
	}


endif;

if ( ! class_exists( 'TP_Admin_Download_Driver' ) ) :

	/**
	 * Driver Class
	 *
	 * @package TotalPoll/Classes/Admin/Driver
	 * @since   3.0.0
	 */
	class TP_Admin_Download_Driver {

		protected $source;
		protected $extension = 'txt';

		public function __construct( $source ) {
			$this->source = $source;
		}

		public function output() {
			$this->headers();
		}

		protected function headers() {
			header( "Content-Disposition: attachment; filename=\"{$this->source->filename()}.{$this->extension}\"" );
			header( 'Content-Type: application/octet-stream' );
			header( 'Connection: Keep-Alive' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Expires: 0' );
		}
	}


endif;


if ( ! class_exists( 'TP_Admin_Download_CSV' ) && class_exists( 'TP_Admin_Download_Driver' ) ) :

	/**
	 * CSV Class
	 *
	 * @package TotalPoll/Classes/Admin/CSV
	 * @since   3.0.0
	 */
	class TP_Admin_Download_CSV extends TP_Admin_Download_Driver {

		protected $extension = 'csv';

		public function output() {

			parent::output();

			$output = fopen( 'php://temp', 'r+' );

			fputcsv( $output, (array) $this->source->labels(), ';' );

			foreach ( (array) $this->source->content() as $row ):
				fputcsv( $output, $this->row( $row ), ';' );
			endforeach;

			rewind( $output );
			echo mb_convert_encoding( stream_get_contents( $output ), 'UTF-16LE' );

			fclose( $output );

		}

		public function row( $cells = '' ) {
			foreach ( $cells as $index => $cell ):
				$cells[ $index ] = implode( ', ', (array) $cell );
			endforeach;

			return $cells;
		}

	}


endif;

if ( ! class_exists( 'TP_Admin_Download_HTML' ) && class_exists( 'TP_Admin_Download_Driver' ) ) :

	/**
	 * HTML Class
	 *
	 * @package TotalPoll/Classes/Admin/HTML
	 * @since   3.0.0
	 */
	class TP_Admin_Download_HTML extends TP_Admin_Download_Driver {

		protected $extension = 'html';

		public function output() {

			parent::output();

			echo $this->header();

			echo $this->style();

			$rows = $this->row( implode( '', array_map( array( $this, 'cell' ), $this->source->labels() ) ) );

			foreach ( (array) $this->source->content() as $row ):
				$rows .= $this->row( implode( '', array_map( array( $this, 'cell' ), (array) $row ) ) );
			endforeach;

			echo $this->table( $rows );

			echo $this->footer();

		}

		private function cell( $content = '' ) {
			$content = esc_html( $content );

			return "<td>{$content}</td>";

		}

		private function row( $content = '' ) {
			return "<tr>{$content}</tr>";
		}

		private function table( $content = '' ) {
			return "<table>{$content}</table>";
		}

		private function header( $title = '' ) {
			$title = esc_html( $title );

			return <<<HEADER
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{$title}</title>
	</head>
	<body>
HEADER;

		}

		private function footer( $content = '' ) {
			return <<<FOOTER
				{$content}
	</body>
</html>
FOOTER;

		}

		private function style() {
			return <<<STLYE

<style type="text/css">
	* {
		margin:0;
		padding:0;
		border:0;
		outline:0;
		font-size:100%;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		vertical-align:baseline;
		background:transparent;
	}

	table {
		width: 100%;
		min-height: .01%;
		overflow-x: auto;
		max-width: 100%;
		margin-bottom: 1rem;
		border-spacing: 0;
		border-collapse: collapse;
	}

	table th,
	table td {
		padding: .75rem;
		line-height: 1.5;
		vertical-align: top;
		border-top: 1px solid #eceeef;
	}

	table tbody tr:nth-of-type(odd) {
		background-color: #f9f9f9;
	}
</style>

STLYE;

		}
	}


endif;