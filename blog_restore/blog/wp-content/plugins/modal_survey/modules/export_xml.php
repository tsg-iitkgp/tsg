<?php
$x=new XMLWriter();
$x->openMemory();
$x->setIndent(true);
$x->startDocument( '1.0', 'UTF-8' );
$x->startElement( 'survey' );
$x->writeElement( 'name', $survey_exp[ 'name' ] );
$x->writeElement( 'id', $survey_exp[ 'id' ] );
$x->writeElement( 'time', $survey_exp['export_time'] );
if ( $personal ) {
	$x->writeElement( 'userid', $survey_exp[ 'user_details' ]->autoid );
	$x->writeElement( 'participantname', ( $survey_exp[ 'user_details' ]->name ? $survey_exp[ 'user_details' ]->name : __( 'Anonymous', MODAL_SURVEY_TEXT_DOMAIN ) ) );
	$x->writeElement( 'username', ( $survey_exp[ 'user_details' ]->username ? $survey_exp[ 'user_details' ]->username : __( 'Not Specified', MODAL_SURVEY_TEXT_DOMAIN ) ) );
	$x->writeElement( 'email', $survey_exp[ 'user_details' ]->email );
	if ( ! empty( $survey_exp[ 'user_details' ]->custom ) ) {
		foreach ( unserialize( $survey_exp[ 'user_details' ]->custom ) as $muc_index=>$muc ) {
			$x->writeElement( strtolower( $muc_index ), $muc );
		}
	}
	$x->writeElement( 'totalscore', $survey_exp[ 'user_details' ]->allscore );
	if ( $survey_exp[ 'user_details' ]->alltimer > 0 ) {
		$x->writeElement( 'requiredtime', $survey_exp[ 'user_details' ]->alltimer . __( 'sec', MODAL_SURVEY_TEXT_DOMAIN ) );
	}
	$x->writeElement( 'votedate', $survey_exp[ 'user_details' ]->created );
}
	foreach ( $survey_exp[ 'questions' ] as $qkey=>$questions ) {
		$x->startElement('question');
		$x->writeElement('name',$questions['name']);
		$x->writeElement('totalvotes',$questions['count']);
		foreach ( $questions as $key=>$answer ) {
			if ( is_numeric( $key ) ) {
				$x->startElement('answer');
				if ( $personal ) {
					if ( in_array( $key, $user_votes[ $qkey ] ) ) {
						$x->writeElement('selected','true');
					}
					else {
						$x->writeElement('selected','false');
					}
				}
				$x->writeElement('votes',$answer['count']);
				$x->writeElement('votespercentage',$answer['percentage']);
				$x->writeElement('answer',$answer['answer']);
				$x->endElement(); // answer
			}
		}

		$x->endElement(); // question
	}
$x->endElement();
$x->endDocument();
$xml = $x->outputMemory();
$path = str_replace( array( "\\", "/" ), array( MSDIRS, MSDIRS ), dirname( __FILE__ ) ) . MSDIRS . ".." . MSDIRS . "exports" . MSDIRS . $survey_exp[ 'id' ] . ".xml";
if ( file_put_contents( $path, $xml ) ) {
	$result = "success";
}
else {
	$result = __( 'Write error', MODAL_SURVEY_TEXT_DOMAIN );
}
?>