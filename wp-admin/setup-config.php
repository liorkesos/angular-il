<?php // Modified for WPH (Translated)
define('WP_INSTALLING', true);
//These three defines are required to allow us to use require_wp_db() to load the database class while being wp-content/wp-db.php aware
define('ABSPATH', dirname(dirname(__FILE__)).'/');
define('WPINC', 'wp-includes');
define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

require_once('../wp-includes/compat.php');
require_once('../wp-includes/functions.php');
require_once('../wp-includes/classes.php');

if (!file_exists('../wp-config-sample.php'))
	wp_die('סליחה, אבל אני צריכה קובץ wp-config-sample.php כדי ליצור ממנו את קובץ ההגדרות. צריך להעלות את הקובץ הזה מחדש לשרת, מתוך ההתקנה של וורדפרס.');

$configFile = file('../wp-config-sample.php');

if ( !is_writable('../'))
	wp_die("אני לא יכולה לכתוב לתיקיה של וורדפרס בעברית. צריך לשנות את ההרשאות כדי שאני אוכל לכתוב אליה, או ליצור את הקובץ wp-config.php ידנית.");

// Check if wp-config.php has been created
if (file_exists('../wp-config.php'))
	wp_die("<p>הקובץ '<code>wp-config.php</code>' קיים כבר. כדי להגדיר מחדש את פרטי החיבור לבסיס הנתונים, יש למחוק אותו. אפשר לנסות <a href='install.php'>להתחיל בהתקנה</a>.</p>");
// Check if wp-config.php exists above the root directory
if (file_exists('../../wp-config.php'))
	wp_die("<p>הקובץ '<code>wp-config.php</code>' קיים כבר בתיקיה אחת מעל התיקיה בה התקנת את וורדפרס. כדי להגדיר מחדש את פרטי החיבור לבסיס הנתונים, יש למחוק אותו. אפשר לנסות <a href='install.php'>להתחיל בהתקנה</a>.</p>");

if (isset($_GET['step']))
	$step = $_GET['step'];
else
	$step = 0;

function display_header(){
	header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>וורדפרס בעברית &rsaquo; יצירת קובץ הגדרות</title>
<link rel="stylesheet" href="<?php echo $admin_dir; ?>css/install.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $admin_dir; ?>css/install-rtl.css" type="text/css" />
<style media="screen" type="text/css">
<!--
input {direction: ltr;}
-->
</style>
</head>
<body>
<div dir="rtl">
<h1 id="logo"><img alt="WordPress" src="images/wordpress-logo.png" /></h1>
<?php
}//end function display_header();

switch($step) {
	case 0:
		display_header();
?>

<p>ברוכים הבאים לוורדפרס. לפני ההתקנה, אני צריכה קצת מידע על בסיס הנתונים. כדאי לברר את זה <em>לפני</em> שממשיכים.</p>
<ol>
	<li>שם בסיס הנתונים</li>
	<li>שם משתמש בבסיס הנתונים</li>
	<li>הסיסמה של אותו משתמש</li>
	<li>הכתובת (host) של בסיס הנתונים</li>
	<li>קידומת לטבלאות (בשביל להפעיל כמה התקנות של וורדפרס מבסיס נתונים אחד) </li>
</ol>
<p><strong>אם אני לא אצליח ליצור את הקובץ - אין מה לדאוג. אפשר פשוט לפתוח את הקובץ  <code>wp-config-sample.php</code> בכל עורך טקסט, למלא את הנתונים לפי ההוראות, ולשמור אותו בשם <code>wp-config.php</code>. </strong></p>
<p>ברוב המקרים, מי שמארח את האתר שלך גם יתן לך את הפרטים האלה. אם עוד אין לך אותם - צריך לברר אותם, כאמור, לפני ההתקנה.</p>

<p><a href="setup-config.php?step=1" class="button">לשלב הבא &raquo;</a></p>

<?php
	break;

	case 1:
		display_header();
	?>
<form method="post" action="setup-config.php?step=2">
	<p>אלו הנתונים שאני צריכה על בסיס הנתונים שלך:</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">שם בסיס הנתונים</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="wordpress" /></td>
			<td>השם של בסיס הנתונים בו וורדפרס תשמור את הנתונים שלה.</td>
		</tr>
		<tr>
			<th scope="row"><label for="uname">שם משתמש</label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="username" /></td>
			<td>שם משתמש בבסיס נתונים עם הרשאות מלאות MySQL.</td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd">סיסמה</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="password" /></td>
			<td>הסיסמה של אותו משתמש.</td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost">כתובת (hostname)</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			<td>הכתובת של בסיס הנתונים. ב-99% מהמקרים לא צריך לשנות את זה - אבל לפעמים כן (עם Dreamhost, למשל).</td>
		</tr>
		<tr>
			<th scope="row"><label for="prefix">קידומת לטבלאות</label></th>
			<td><input name="prefix" id="prefix" type="text" id="prefix" value="wp_" size="25" /></td>
			<td>כדי להפעיל כמה התקנות של WP מבסיס נתונים אחד, אפשר להגדיר לכל התקנה קידומת טבלאות אחרת. לא צריך לשנות את זה אם זו ההתקנה היחידה.</td>
		</tr>
	</table>
	<p class="step"><input name="submit" type="submit" value="לשמור" class="button" /></p>
</form>
<?php
	break;

	case 2:
	$dbname  = trim($_POST['dbname']);
	$uname   = trim($_POST['uname']);
	$passwrd = trim($_POST['pwd']);
	$dbhost  = trim($_POST['dbhost']);
	$prefix  = trim($_POST['prefix']);
	if (empty($prefix)) $prefix = 'wp_';

	// Test the db connection.
	define('DB_NAME', $dbname);
	define('DB_USER', $uname);
	define('DB_PASSWORD', $passwrd);
	define('DB_HOST', $dbhost);

	// We'll fail here if the values are no good.
	require_wp_db();
	if ( !empty($wpdb->error) )
		wp_die($wpdb->error->get_error_message());

	$handle = fopen('../wp-config.php', 'w');

	foreach ($configFile as $line_num => $line) {
		switch (substr($line,0,16)) {
			case "define('DB_NAME'":
				fwrite($handle, str_replace("putyourdbnamehere", $dbname, $line));
				break;
			case "define('DB_USER'":
				fwrite($handle, str_replace("'usernamehere'", "'$uname'", $line));
				break;
			case "define('DB_PASSW":
				fwrite($handle, str_replace("'yourpasswordhere'", "'$passwrd'", $line));
				break;
			case "define('DB_HOST'":
				fwrite($handle, str_replace("localhost", $dbhost, $line));
				break;
			case '$table_prefix  =':
				fwrite($handle, str_replace('wp_', $prefix, $line));
				break;
			default:
				fwrite($handle, $line);
		}
	}
	fclose($handle);
	chmod('../wp-config.php', 0666);

	display_header();
?>
<p>זה הכל, סיימנו עם ההכנות! עכשיו אני יכולה להתחבר לבסיס הנתונים ולהתקין בו את וורדפרס.</p>
<p><a href="install.php" class="button">להתחיל בהתקנה</a></p>
<?php
	break;
}
?>
</div><!-- /rtl -->
</body>
</html>
