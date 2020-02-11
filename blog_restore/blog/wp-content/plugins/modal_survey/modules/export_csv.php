<?php
if (function_exists('fputcsv')) {
	$path = str_replace( array( "\\", "/" ), array( MSDIRS, MSDIRS ), dirname( __FILE__ ) ) . MSDIRS . ".." . MSDIRS . "exports" . MSDIRS . $survey_exp[ 'id' ] . ".csv";
	$fp = fopen( $path, 'w' );
		$survey_csv[] = array( '"' . __( 'Survey ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'id' ] . '"' );
		$survey_csv[] = array('"' . __( 'Survey Name', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'name' ] . '"' );
		$survey_csv[] = array('"' . __( 'Generated', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'export_time' ] . '"' );
		if ( $personal ) {
			$survey_csv[] = array();
			$survey_csv[] = array('"' . __( 'User ID', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->autoid . '"' );
			$survey_csv[] = array('"' . __( 'Username', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . ( $survey_exp[ 'user_details' ]->username ? $survey_exp[ 'user_details' ]->username : __( 'Not Specified', MODAL_SURVEY_TEXT_DOMAIN ) ) . '"' );
			$survey_csv[] = array('"' . __( 'Created', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->created . '"' );
			$survey_csv[] = array('"' . __( 'Email', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->email . '"' );
			$survey_csv[] = array('"' . __( 'Name', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->name . '"' );
			if ( ! empty( $survey_exp[ 'user_details' ]->custom ) ) {
				foreach ( unserialize( $survey_exp[ 'user_details' ]->custom ) as $muc_index=>$muc ) {
					$survey_csv[] = array('"' . ucfirst( strtolower( $muc_index ) ) . '"','"' . $muc . '"' );
				}
			}
			$survey_csv[] = array('"' . __( 'Total Score', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->allscore . '"' );
			$survey_csv[] = array('"' . __( 'Required Time', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $survey_exp[ 'user_details' ]->alltimer . __( 'sec', MODAL_SURVEY_TEXT_DOMAIN ) . '"' );
			$survey_csv[] = array( '"' . __( 'Participant answers marked with stars: *', MODAL_SURVEY_TEXT_DOMAIN ) . '"','""','""');
			$survey_csv[] = array();
		}
		$survey_csv[] = array();
		if ( $personal ) {
			$survey_csv[] = array('"' . __( 'Question / Answer', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . __( 'Votes', MODAL_SURVEY_TEXT_DOMAIN ) . '"', '"' . __( 'Percentage', MODAL_SURVEY_TEXT_DOMAIN ) . '"', '"' . __( 'Score', MODAL_SURVEY_TEXT_DOMAIN ) . '"');
		}
		else {
			$survey_csv[] = array('"' . __( 'Question / Answer', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . __( 'Votes', MODAL_SURVEY_TEXT_DOMAIN ) . '"', '"' . __( 'Percentage', MODAL_SURVEY_TEXT_DOMAIN ) . '"');
		}	
		$totalscore = 0;
		foreach ( $survey_exp[ 'questions' ] as $qkey=> $questions ) {
			$survey_csv[] = array('"'.$questions['name'].'"','','');
			foreach ($questions as $key=>$answer) {
				if ( is_numeric( $key ) ) {
					$marker = "";
					if ( $personal ) {
						if ( in_array( $key, $user_votes[ $qkey ] ) ) {
							$marker = "* ";
						}
						$aoptions = unserialize( $answer[ 'aoptions' ] );
						if ( $aoptions[ 4 ] != "" && in_array( $key, $user_votes[ $qkey ] ) ) {
							$score = $aoptions[ 4 ];
							$totalscore += $score;
						}
						else {
							$score = "";
						}
						$survey_csv[] = array( '"' . $marker . $answer[ 'answer' ] . '"','"' . $answer[ 'count' ] . '"','"' . $answer[ 'percentage' ].'%"','"' . $score . '"' );
					}
					else {
						$survey_csv[] = array( '"' . $marker . $answer[ 'answer' ] . '"','"' . $answer[ 'count' ] . '"','"' . $answer[ 'percentage' ].'%"' );
					}
				}
			}
					if ( $personal ) {
						$survey_csv[] = array( '"' . __( 'Total', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $questions['count'] . '"','""','"' . $totalscore . '"' );
					}
					else {
						$survey_csv[] = array( '"' . __( 'Total', MODAL_SURVEY_TEXT_DOMAIN ) . '"','"' . $questions['count'] . '"' );
					}
					$survey_csv[] = array( '' );
		}

	foreach ($survey_csv as $fields) {
		fputcsv( $fp, $fields, ',', chr( 0 ) );
	}
	fclose( $fp );
	$result = "success";
}
else {
	$result = __( 'fputcsv function doesn\'t exists', MODAL_SURVEY_TEXT_DOMAIN );
}
?>