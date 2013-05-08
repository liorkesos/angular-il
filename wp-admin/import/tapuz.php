<?php

class Tapuz_Import {
	var $FILEENC = 'Windows-1255';
	var $DSTENC = 'UTF-8';
	var $posts = array ();
	var $file;

	function header() {
		echo '<div class="wrap">';
		echo '<h2>'.__('Tapuz Import').'</h2>';
	}
	function unhtmlentities($string) { // From php.net for < 4.3 compat
		$trans_tbl = get_html_translation_table(HTML_SPECIALCHARS);
		$trans_tbl = array_flip($trans_tbl);
		$ret = strtr($string, $trans_tbl);
		return preg_replace('/&#(\d+);/me',"chr('\\1')",$ret);
	}

	function footer() {
		echo '</div>';
	}

	function greet() {
		echo '<div class="narrow">';
		echo '<p>'.__('Howdy! This imports posts and comments from a Tapuz backup file to your blog.').'</p>';
		wp_import_upload_form("admin.php?import=tapuz");
		echo '</div>';
	}

	function br2nl($str) { 
		$str = preg_replace("/(\r\n|\n|\r)/", "", $str);
		return preg_replace("=<br */?>=i", "\n", $str);
	}

	function commentData($response, $pid, $date) 
	{
		global $wpdb;

		$resp_match = '/\<R\>(.*?)?\^\*\^(.*)?,(.*)?,(.*)?,`?(.*)?`~\$~(.*)?~\$~(.*)?\<\/R\>/';
		preg_match($resp_match, $response, $x);
		// concat subject and content - no comment subject in WP
		$comment_content = $this->unhtmlentities($x[7]."  <br />".$x[1]);
		$comment_content = str_replace(array("&lt;", "&bg;"), array("<", ">"), $comment_content);
		$comment_content = $this->br2nl($comment_content);
		$comment_content = 
			iconv($this->FILEENC, $this->DSTENC, $comment_content);
		$comment_author = $wpdb->escape(trim($x[5]));
		$comment_author = 
			iconv($this->FILEENC, $this->DSTENC, $comment_author);
		$comment_author_url = $x[6];
		$comment_post_ID = $pid;
		$comment_approved = 1;
		$comment_date = $date;
		$data = compact('comment_post_ID', 'comment_author', 'comment_author_url', 'comment_content', 'comment_approved', 'comment_date');
		return $data;
	}

	function get_posts() {
		global $wpdb;


		set_magic_quotes_runtime(0);
		$datalines = file($this->file); // Read the file into an array
		$importdata = implode('', $datalines); // squish it
		$importdata = str_replace(array ("\r\n", "\r"), "\n", $importdata);

		preg_match_all('|<item>(.*?)</item>|is', $importdata, $this->posts);
		$this->posts = $this->posts[1];
		echo '<ol>';		
		foreach ($this->posts as $post) {
			set_time_limit(40);
			/* Handle Title */
			preg_match('|<title>(.*?)</title>|is', $post, $post_title);
			$post_title = iconv($this->FILEENC, $this->DSTENC, $post_title[1]);
			/* Handle Content */
			preg_match('|<description>(.*?)</description>|is', $post, $post_content);
			
			$post_content = str_replace(array("``"), array('"'), $post_content[1]);
			$post_content = html_entity_decode($post_content);
			$post_content = iconv($this->FILEENC, $this->DSTENC, $post_content);
			$post_content = $this->unhtmlentities(trim($post_content));

			// Clean up content
			$post_content = str_replace('<br>', '<br />', $post_content);
			$post_content = str_replace('<hr>', '<hr />', $post_content);
			/* Handle Date  d/m/y h:m:s*/
			preg_match('|<DateEntry>(.*?)</DateEntry>|is', $post, $post_date_gmt);
			$day = strtok($post_date_gmt[1], "/");
			$month = strtok("/");
			$year = strtok(" ");
			$time = strtok("%");

			$post_date = "$year-$month-$day $time";

			/* Handle Categories */
			preg_match_all('|<Cat>(.*?)</Cat>|is', $post, $categories);
			$categories = $categories[1];
			$cat_index = 0;
			foreach ($categories as $category) {
				if (!empty($category)) {
					$categories[$cat_index] = iconv($this->FILEENC, $this->DSTENC, $wpdb->escape(html_entity_decode($category)));
				$cat_index++;
				}
			}

			$post_author =  $GLOBALS['user_ID'];
			$post_status = 'publish';
			$PostData = compact('post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_status', 'categories'); 
			
			echo '<li>';
			if ($post_id = post_exists($post_title, $post_content, $post_date)) {
				printf(__('The post <i>%s</i> already exists.'), stripslashes($post_title));
				$ignor_comments=TRUE;
			} else {
				printf(__('Importing post <i>%s</i>...'), stripslashes($post_title));
				$ignor_comments = FALSE;
				$post_id = wp_insert_post($PostData);
				if (!$post_id) {
					_e("Error: could not recieve post identifier");
					echo '</li>';
					break;
				} 
			}
 
			/* Handle Comments for the post */
			preg_match_all('|<Responses>(.*?)</Responses>|is', $post, $comments);
			$comments = $comments[1][0];
			if ( $comments && !$ignor_comments) {
				$resp_string_match = '/(\<R\>.*?\<\/R\>)/';
				$resps = array();

				$resp_string = $comments;
				while (preg_match($resp_string_match, $resp_string, $x, PREG_OFFSET_CAPTURE))
				{
					array_push($resps, $x[0][0]);
					$resp_string = substr($resp_string,$x[0][1] + strlen($x[0][0]));
				}

				$num_comments = 0;
				foreach ($resps as $resp)
				{
					$comment = $this->commentData($resp, $post_id, $post_date);
					$comment = wp_filter_comment($comment);
					wp_insert_comment($comment);
					$num_comments++;
				}
				_e("$num_comments comments were imported for this post.");
			}
		}
		echo '</ol>';		

	}

	function import_posts() {
	}

	function import() {
		$file = wp_import_handle_upload();
		if ( isset($file['error']) ) {
			echo $file['error'];
			return;
		}

		$this->file = $file['file'];
		$this->get_posts();
		$this->import_posts();
		wp_import_cleanup($file['id']);

		echo '<h3>';
		printf(__('All done. <a href="%s">Have fun!</a>'), get_option('home'));
		echo '</h3>';
	}

	function start() {
		$start = (isset($_REQUEST['action'])) ? true : false;
		$this->header();
		if (!$start)
			$this->greet();
		else
			$this->import();
		$this->footer();
	}
}

$tapuz_import = new Tapuz_Import();

register_importer('tapuz', __('Tapuz'), __('Import posts and comments from a Tapuz blog'), array ($tapuz_import, 'start'));
?>
