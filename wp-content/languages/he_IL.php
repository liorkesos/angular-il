<?php

$text_direction = 'rtl';

/* --------------------------------------------------------------------------- 
Localize defaults
http://trac.wordpress.org/ticket/6854
 ---------------------------------------------------------------------------*/ 

function schema_il() {
	add_option('start_of_week', 0); 
	add_option('gmt_offset', '2'); 
	add_option('rss_language', 'he');
}

add_action ('populate_options', 'schema_il');

/* --------------------------------------------------------------------------- 
Fix for diacritics with fully justified text, by Tom Sella 
http://www.dontsmile.info/my-plugins/plugin-dj_c/
 ---------------------------------------------------------------------------*/ 

$do_fix = is_moz();
$djs = '<span style="text-align: right;">';
$dje = '</span>';
// match: one or more base hebrew chars, followed by one or more diacritics, followed by any number of hebrew chars including diacritics
$match_heb_with_dia = '/([\x{05D0}-\x{05F2}]+[\x{05B0}-\x{05C3}]+[\x{05B0}-\x{05F2}]*)/u';
function is_moz()
{
  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
  return (substr($ua, 0, 8) == 'mozilla/' && (!strpos($ua, 'msie') && !strpos($ua, 'opera')));
}

function diacritic_justification($data = '')
{
  global $do_fix;
  global $djs, $dje;
  global $match_heb_with_dia;
  $newdata = '';
  $intags = false;
  // this workround applies only to Mozilla based (e.g. Firefox) browsers
  if ($do_fix)
  {
    while (preg_match($match_heb_with_dia, $data, $x, PREG_OFFSET_CAPTURE))
    {
      // substring before match
      $s = substr($data, 0, $x[0][1]);
      // match data
      $w = $x[0][0];
      // check if last tag was closed
      if (strrpos($s, '>') != null)
      {
        $intags = false;
      }        
      // check if within html tags
      if ((strrpos($s, '<') >= strrpos($s, '>') && strrpos($s, '<') != null) || $intags)
      {
        $intags = true;
        // if so, do nothing
        $newdata .= $s . $w;
      }
      else
      {
        // otherwise, do something
        $newdata .= $s . $djs . $w . $dje;
      }
      // set up the next section for match
      $data = substr($data,$x[0][1] + strlen($x[0][0]));
    }
  }
  return $newdata . $data;
}

function title_fix()
{
  // i can't use the add_filter('the_title', ...), since the_title() is also called within
  // html tags, in a stage this script does not know the context, that would result
  // in a nested tag, breaking proper html.
  // instead, i add a stylesheet that targets the title in the header
  echo "<style type='text/css'>\n/* dj_c title diacritics fix */\nh2 { text-align: right; }\n.post h3 { text-align: right; }\n</style>\n";
}

if (function_exists('diacritic_justification'))
{
  // apply fix to the title - disabled, and for a good reason
  // 
  //add_filter('the_title', 'diacritic_justification');
  // apply fix to the content
  add_filter('the_content', 'diacritic_justification');
  // apply fix to the_excerpt
  add_filter('the_excerpt', 'diacritic_justification');
  // apply fix to comments
  add_action('comment_text', 'diacritic_justification');
  // apply fix for title
  if (is_moz())
  {
    add_action('wp_head', 'title_fix');
  }
}

/* --------------------------------------------------------------------------- 
Comment directionality auto-detection by Tom Sella
http://www.dontsmile.info/my-plugins/plugin-comment_direction/
---------------------------------------------------------------------------*/ 

class comment_direction
{
  function domina($data = '')
  {
    $text  = $this->sanitize_text($data);
    $c_eng = $this->count_it($text, '/\w+/u');
    $c_heb = $this->count_it($text, '/[\x{05B0}-\x{05F4}\x{FB1D}-\x{FBF4}]+/u');
    $c_arb = $this->count_it($text, '/[\x{060C}-\x{06FE}\x{FB50}-\x{FEFC}]+/u');
    $dir   = ($c_eng >= ($c_heb + $c_arb)) ? 'ltr' : 'rtl';
    $data  = "<div style='direction: {$dir};'><p>" . $data . "</p></div>";
    return $data;
  }

  function sanitize_text($data = '')
  {
    $sanitized = preg_replace('/<.*?>/x', '', $data); // remove html content
    $sanitized = preg_replace('/[0-9]+/', '', $sanitized); // remove numbers content
    return $sanitized;
  }

  function count_it($data = '', $match)
  {
    $i = 0;
    while (preg_match($match, $data, $x, PREG_OFFSET_CAPTURE))
    {
      $i += strlen($x[0][0]);
      $data = substr($data,$x[0][1] + strlen($x[0][0]));
    }
    return $i;
  }
  
  function comment_direction()
  {
    add_action('comment_text', array(&$this, 'domina'));
  }
}

$countit = new comment_direction();

/* --------------------------------------------------------------------------- 
wpuntexturize, by Scott Reilly 
http://coffee2code.com/wp-plugins/wpuntexturize
 ---------------------------------------------------------------------------*/ 

function wpuntexturize($text) {
	$char_codes = array('&#8216;', '&#8217;', '&#8220;', '&#8221;');
	$replacements = array("'", "'", '"', '"');
	return str_replace($char_codes, $replacements, $text);
} // end wpuntexturize()

add_filter('comment_text', 'wpuntexturize', 11);
add_filter('single_post_title', 'wpuntexturize', 11);
add_filter('the_title', 'wpuntexturize', 11);
add_filter('the_content', 'wpuntexturize', 11);
add_filter('the_excerpt', 'wpuntexturize', 11);

?>