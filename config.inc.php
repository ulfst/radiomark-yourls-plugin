<?php 
// Some configuration attributes for the plugin...
define('KEYWORD_LENGTH', 8); // max length for keyword
define('AUDIO_FOLDER', "/link/to/your/audiomark/folder/"); // folder where the audio files from PD are located

define('AUDIO_LINK','http://serveraddress/link/to/your/audiomark/folder/'); // Link to Audiofile directory

define('YOURLS_AUDIOMARK_TABLE_URL', YOURLS_DB_PREFIX.'metadata'); // metadata table

// PureDate configuration
define("PUREDATA_SERVER_ADDRESS", "localhost");
define("PUREDATA_SERVER_PORT", 13001);
?>