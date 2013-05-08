<?php // Modified for WPH (For proper directionality and setting of RSS language)
/**
 * RSS2 Feed Template for displaying RSS2 Comments feed.
 *
 * @package WordPress
 */

header('Content-Type: text/xml;charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	>
<channel>
	<title>&#8235;<?php // WPH
		if ( is_singular() )
			printf(__('Comments on: %s'), get_the_title_rss());
		elseif ( is_search() )
			printf(__('Comments for %s searching on %s'), get_bloginfo_rss( 'name' ), attribute_escape($wp_query->query_vars['s']));
		else
			printf(__('Comments for %s'), get_bloginfo_rss( 'name' ) . get_wp_title_rss());
	?>&#8236;</title>
	<link><?php (is_single()) ? the_permalink_rss() : bloginfo_rss("url") ?></link>
	<description>&#8235;<?php bloginfo_rss("description") ?>&#8236;</description> <?php /* WPH */ ?>
	<pubDate><?php echo gmdate('r'); ?></pubDate>
	<?php the_generator( 'rss2' ); ?>
	<?php do_action('commentsrss2_head'); ?>
<?php
if ( have_comments() ) : while ( have_comments() ) : the_comment();
	$comment_post = get_post($comment->comment_post_ID);
	get_post_custom($comment_post->ID);
?>
	<item>
		<title>&#8235;<?php // WPH
			if ( !is_singular() ) {
				$title = get_the_title($comment_post->ID);
				$title = apply_filters('the_title_rss', $title);
				printf(__('Comment on %1$s by %2$s'), $title, get_comment_author_rss());
			} else {
				printf(__('By: %s'), get_comment_author_rss());
			}
		?>&#8236;</title>
		<link><?php comment_link() ?></link>
		<dc:creator>&#8235;<?php echo get_comment_author_rss() ?>&#8236;</dc:creator>  <?php /* WPH */ ?>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_comment_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<guid isPermaLink="false"><?php comment_guid() ?></guid>
<?php if (!empty($comment_post->post_password) && $_COOKIE['wp-postpass'] != $comment_post->post_password) : ?>
		<description>&#8235;<?php _e('Protected Comments: Please enter your password to view comments.'); ?>&#8236;</description> <?php /* WPH */ ?>
		<content:encoded><![CDATA[<?php echo get_the_password_form() ?>]]></content:encoded><?php /* WPH */ ?>
<?php else : // post pass ?>
		<description>&#8235;<?php comment_text_rss() ?>&#8236;</description> <?php /* WPH */ ?>
		<content:encoded><![CDATA[<?php comment_text() ?>]]></content:encoded><?php /* WPH */ ?>
<?php endif; // post pass
	do_action('commentrss2_item', $comment->comment_ID, $comment_post->ID);
?>
	</item>
<?php endwhile; endif; ?>
</channel>
</rss>
