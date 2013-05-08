<?php // WPH - Localized error message
/**
 * Bootstrap file for setting the ABSPATH constant
 * and loading the wp-config.php file. The wp-config.php
 * file will then load the wp-settings.php file, which
 * will then set up the WordPress environment.
 *
 * If the wp-config.php file is not found then an error
 * will be displayed asking the visitor to set up the
 * wp-config.php file.
 *
 * Will also search for wp-config.php in WordPress' parent
 * directory to allow the WordPress directory to remain
 * untouched.
 *
 * @package WordPress
 */

/** Define ABSPATH as this files directory */
define( 'ABSPATH', dirname(__FILE__) . '/' );

error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

if ( file_exists( ABSPATH . 'wp-config.php') ) {

	/** The config file resides in ABSPATH */
	require_once( ABSPATH . 'wp-config.php' );

} elseif ( file_exists( dirname(ABSPATH) . '/wp-config.php' ) ) {

	/** The config file resides one level below ABSPATH */
	require_once( dirname(ABSPATH) . '/wp-config.php' );

} else {

	// A config file doesn't exist

	// Set a path for the link to the installer
	if (strpos($_SERVER['PHP_SELF'], 'wp-admin') !== false) $path = '';
	else $path = 'wp-admin/';

	// Die with an error message
	require_once( ABSPATH . '/wp-includes/classes.php' );
	require_once( ABSPATH . '/wp-includes/functions.php' );
	require_once( ABSPATH . '/wp-includes/plugin.php' );
	  wp_die("<p>לא מצאתי את הקובץ <code>wp-config.php</code>. אני צריכה אותו בשביל להתחיל. אפשר ליצור אותו <a href='{$path}setup-config.php'> עם הטופס הזה</a> (זה לא עובד בכל המקרים), או ידנית לפי ההוראות בקובץ <a href=\"readme.html\">readme.html</a>. הדרך הבטוחה ביותר היא ליצור את הקובץ ידנית. רוצה עוד עזרה? <a href='http://docs.wph.co.il/wiki/%D7%A2%D7%A8%D7%99%D7%9B%D7%AA_%D7%A7%D7%95%D7%91%D7%A5_%D7%94%D7%94%D7%92%D7%93%D7%A8%D7%95%D7%AA'>פה יש הרבה</a>.</p>
<p><a href='{$path}setup-config.php' class='button'>ליצירת קובץ הגדרות &raquo;</a></p>", "וורדפרס בעברית &rsaquo; בעיה"); // WPH - Localized error message

}

?>
