# radiomark-yourls-plugin

Plugin your the OpenSource URL-Shortener YOURLS to support the specific implementation of the "Radiomark" project.

## Common YOURLS configuration issues
THis is one common mistake in the configuration, which cause the plugin to stop working.
Remember to set the URL shortening method attribute in config.php to 62 instead of 36

(Line 61: "define( 'YOURLS_URL_CONVERT', 62 );") Otherwise the keywords are not stored correctly

Automatically exported from code.google.com/p/radiomark-yourls-plugin

