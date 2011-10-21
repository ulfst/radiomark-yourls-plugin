<?php
/*
 * Retrieves the last created (audio) file in folder
 * and reads it in as a binary String
 * 
 * @return binary string $filecontent
 */
function get_recent_audiomark() {
	// get the most recent filename
	$files_in_directory = scandir(AUDIO_FOLDER);
	$recent_file_name = '';
	$recent_file_access_time = 0;
	foreach($files_in_directory as $file ) {
		if($file == '.' || $file == '..') continue;
		$file_time = fileatime(AUDIO_FOLDER.$file);
		if($file_time > $recent_file_access_time) {
			$recent_file_access_time = $file_time;
			$recent_file_name = $file;
		}
	}

	// get file as binary string
	$filename = AUDIO_FOLDER.$file;
	try {
		$handle = fopen ($filename, "rb");
		$filecontent = fread($handle, filesize($filename));
		fclose($handle);
	} catch (Exception $e) {
	    echo 'Problem: ',  $e->getMessage(), "\n";
	}
	
	$filecontent = addslashes($filecontent);

	return $filecontent;
}

/*
 * Retrieves the last created (audio) file in folder
 * for the user to download
 * @return string $filename
 */
function get_recent_audiomark_link() {
	// get the most recent filename
	$files_in_directory = scandir(AUDIO_FOLDER);
	$recent_file_name = '';
	$recent_file_access_time = 0;
	foreach($files_in_directory as $file ) {
		if($file == '.' || $file == '..') continue;
		
		$file_time = filemtime(AUDIO_FOLDER.$file);
		if($file_time > $recent_file_access_time) {
			$recent_file_access_time = $file_time;
			$recent_file_name = $file;
		}
	}
	// get file as binary string
	$filename = AUDIO_LINK.$file;
	return $filename;
}

// shorten url using the oplop algorithm
/*
 * Shortener Algorithm
 * Based upon the one developed from oplop (http://code.google.com/p/oplop/wiki/HowItWorks)
 *
 * param string $url
 * #return string $keyword
 */
function create_short_url($url) {
	$url = md5($url);
	$url = base64_encode($url);

	$length = KEYWORD_LENGTH; // max length
	$digit_regex = '/\d+/';

	if(preg_match($digit_regex, $url, $matches) != 0) {
		if(strpos($url, $matches[0]) > $length) {
			$url = $matches[0].$url;
		}
	} else {
		$url = '1'.$url;
	}
	return substr($url, 0, $length);
}


/************************************
 * PD functions (open socket etc.)
 ************************************/

// as found on http://wiki.puredata.info/en/FUDI
/*
 * Create a Socket-Connection to PureData (PD)
 * 
 * @param string $data
 * 
 */
function create_socket_for_pure_data($data)
{
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$message = $data;

	// Parameters to open the connection
	$result = socket_connect($socket, PUREDATA_SERVER_ADDRESS, PUREDATA_SERVER_PORT);
	socket_send($socket, $message, strlen($message), MSG_DONTROUTE);
}


/*
 * Convert a String so it will be understood as a List message in PureData (PD) 
 * Author: Ulf Stoffels 
 *
 * @param string $string
 * return string $ausgangsstring
 * 
 */
function make_pd_message_konform($string){
	$ausgangsstring = "";
	
	for ($i=0; $i < strlen($string); $i++) {
		$ausgangsstring .= $string[$i];
		if (strlen($ausgangsstring) < (strlen($string)*2)-1){
			$ausgangsstring .= " ";
		}
	}
	$startSignal = "# ";
	$stopSignal = " *";
	$ausgangsstring = $startSignal . $ausgangsstring . $stopSignal;
	$ausgangsstring .= ";";
	return $ausgangsstring;
}
?>
