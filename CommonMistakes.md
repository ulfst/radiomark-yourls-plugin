# Introduction #

Here a list of some common mistakes in the configuration, which cause the plugin to stop working


# YOURLS Configuration #
Remember to set the URL shortening method attribute in config.php to 62 instead of 36

(Line 61: "define( 'YOURLS\_URL\_CONVERT', 62 );")
Otherwise the keywords are not stored correctly