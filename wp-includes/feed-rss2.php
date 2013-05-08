<?php // Modified for WPH (For proper directionality)
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
$more = 1;

?>
<?php echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title>&#8235;<?php bloginfo_rss('name'); wp_title_rss(); ?>&#8236;</title> <?php /* WPH  */ ?>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description>&#8235;<?php bloginfo_rss("description") ?>&#8236;</description> <?php /* WPH */ ?>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
	<?php the_generator( 'rss2' ); ?>
	<language><?php echo get_option('rss_language'); ?></language>
	<?php do_action('rss2_head'); ?>
	<?php while( have_posts()) : the_post(); ?>
	<item>
		<title>&#8235;<?php the_title_rss() ?>&#8236;</title> <?php /* WPH */ ?>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link(); ?></comments>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator>&#8235;<?php the_author() ?>&#8236;</dc:creator> <?php /* WPH */ ?>
		<?php the_category_rss() ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
<?php if (get_option('rss_use_excerpt')) : ?>
		<description><![CDATA[&#8235;<?php the_excerpt_rss() ?>&#8236;]]></description> <?php /* WPH */ ?>
<?php else : ?>
		<description><![CDATA[&#8235;<?php the_excerpt_rss() ?>&#8236;]]></description> <?php /* WPH */ ?>
	<?php if ( strlen( $post->post_content ) > 0 ) : ?>
		<content:encoded><![CDATA[<div dir="rtl"><?php the_content() ?></div>]]></content:encoded> <?php /* WPH */ ?>
	<?php else : ?>
		<content:encoded><![CDATA[&#8235;<?php the_excerpt_rss() ?>&#8236;]]></content:encoded> <?php /* WPH */ ?>
	<?php endif; ?>
<?php endif; ?>
		<wfw:commentRss><?php echo get_post_comments_feed_link(); ?></wfw:commentRss>
<?php rss_enclosure(); ?>
	<?php do_action('rss2_item'); ?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
