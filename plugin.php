<?php
/*
Plugin Name: Audiomark extension
Plugin URI: http://mysite.com/yourls-sample-plugin/
Description: Different features to support audiomark (Add Metatags, Retrieve them via API, new Keyword Shortener algorithm etc.)
Version: 0.3
Author: Jens Lukas
Author URI: http://jenslukas.com/
*/

require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/functions.php');

// add action
yourls_add_action( 'audiomark_add_metadata', 'audiomark_add_metadata_to_db');
/*
 * Add additional specified metadata to database
 * Function is executed when a URL is added successfully
 * 
 * @param array $args contains the metadata
 */
function audiomark_add_metadata_to_db( $args ) {
	global $ydb;
	
	$keyword = $args[0];
	$radio = $args[1];
	$portal = $args[2];
	$title = $args[3];
	$sub_title = $args[4];
	$desc = $args[5];
	$audiomark = $args[6];
	
	// create audiomark
	$pd_message = make_pd_message_konform($keyword);
	create_socket_for_pure_data($pd_message);
	sleep(5);
	
	// get Audiofile to Upload to Database
	// 	try {
	// 		$audio_file = get_recent_audiomark();
	// 	} catch (Exception $e) {
	// 	    echo 'Problem: ',  $e->getMessage(), "\n";
	// 	}
	
	// add data to db
	$table = YOURLS_AUDIOMARK_TABLE_URL;
	$insert = $ydb->query("INSERT INTO `$table` VALUES('$keyword', '$radio', '$portal', '$title', '$sub_title', '$desc', '$audio_file');");
}

// add filter
yourls_add_filter( 'api_expand', 'audiomark_api_get_metadata');
/*
 * Add metadata to the API action expand if the request
 * was successfully (keyword found, necessary parameters specified etc.)
 * 
 * @param array $return normal return information already created by the system
 * @return array $return return information + metadata if request was successfully otherwise just the already created error message 
 */
function audiomark_api_get_metadata( $return ) {
	global $ydb;
	
	if($return['message'] == 'success') {
		$keyword = $return['keyword'];
		
		$table = YOURLS_AUDIOMARK_TABLE_URL;
		$metadata = $ydb->get_row("SELECT * FROM `$table` WHERE `keyword` = '$keyword';");
		
		// add metadata to return message
		$return['station_name'] = $metadata->station_name;
		$return['station_uri'] = $metadata->station_uri;
		$return['item_title'] = $metadata->item_title;
		$return['item_subtitle'] = $metadata->item_subtitle;
		$return['item_abstract'] = $metadata->item_abstract;
	}
	
	return $return;
}

// add filter
yourls_add_filter( 'random_keyword', 'audiomark_create_keyword' );
/*
 * Use our own keyword generator instead of the existing one.
 * 
 * @param string $keyword
 * @return string $return created keyword
 */
function audiomark_create_keyword( $keyword ) {
	// Use URL instead the handed over keyword (unfortuately yourls does not hand it over)
	return create_short_url(yourls_sanitize_url( $_REQUEST['url'] ));
}
?>