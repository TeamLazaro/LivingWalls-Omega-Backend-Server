<?php

namespace CRM;

ini_set( "display_errors", 0 );
ini_set( "error_reporting", E_ALL );

require_once __DIR__ . '/../../vendor/autoload.php';

use CristianPontes\ZohoCRMClient\ZohoCRMClient;
use CristianPontes\ZohoCRMClient\Exception as ZohoException;

/*
 *
 * Declare constants
 *
 */
$authToken = require __DIR__ . '/../../api.php';


/*
 * -----
 * Get user by UID
 * -----
 *
 * Users with UIDs are prospects.
 *
 */
function getUserByUid ( $uid, $project ) {

	global $authToken;
	$zohoClient = new ZohoCRMClient( 'Contacts', $authToken, 'com', 0 );

	try {
		$records = $zohoClient->searchRecords()
					->where( 'UID', $uid )
					->where( 'Project', $project )
					->request();
		$records = array_values( $records );
	} catch ( ZohoException\NoDataException $e ) {
		$records = [ ];
	} catch ( Exception $e ) {
		$records = [ ];
	}

	if ( empty( $records ) ) {
		return null;
	}

	if ( count( $records ) > 1 ) {
		throw new Exception( 'More than one user found with the provided UID.' );
	}

	$user = [
		'SMOWNERID' => $records[ 0 ]->data[ 'SMOWNERID' ],
		'_id' => $records[ 0 ]->data[ 'CONTACTID' ],
		'Phone' => $records[ 0 ]->data[ 'Phone' ] ?? '',
		'Full Name' => $records[ 0 ]->data[ 'Full Name' ] ?? '',
		'First Name' => $records[ 0 ]->data[ 'First Name' ] ?? '',
		'Last Name' => $records[ 0 ]->data[ 'Last Name' ] ?? '',
		'Co-applicant Name' => $records[ 0 ]->data[ 'Co-applicant Name' ] ?? '',
		'Email' => $records[ 0 ]->data[ 'Email' ] ?? ''
	];
	foreach ( $records[ 0 ]->data as $key => $value ) {
		if ( strpos( $key, '_ ' ) === 0 )
			$user[ $key ] = $value;
	}

	return $user;

}
