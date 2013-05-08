<?php // Modified for WPH (For proper directionality) http://trac.wordpress.org/ticket/6425
/**
 * Atom Feed Template for displaying Atom Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: application/atom+xml; charset=' . get_option('blog_charset'), true);
$more = 1;

?>
<?php echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<feed
  xmlns="http://www.w3.org/2005/Atom"
  xmlns:thr="http://purl.org/syndication/thread/1.0"
  xml:lang="<?php echo get_option('rss_language'); ?>"
  xml:base="<?php bloginfo_rss('home') ?>/wp-atom.php"
  <?php do_action('atom_ns'); ?>
 >
	<title type="text">&#8235;<?php bloginfo_rss('name'); wp_title_rss(); ?>&#8236;</title> <?php /* WPH */ ?>
	<subtitle type="text">&#8235;<?php bloginfo_rss("description") ?>&#8236;</subtitle> <?php /* WPH */ ?>

	<updated><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_lastpostmodified('GMT')); ?></updated>
	<?php the_generator( 'atom' ); ?>

	<link rel="alternate" type="text/html" href="<?php bloginfo_rss('home') ?>" />
	<id><?php bloginfo('atom_url'); ?></id>
	<link rel="self" type="application/atom+xml" href="<?php self_link(); ?>" />

	<?php do_action('atom_head'); ?>
	<?php while (have_posts()) : the_post(); ?>
	<entry>
		<author>
			<name>&#8235;<?php the_author() ?>&#8236;</name> <?php /* WPH */ ?>
			<?php $author_url = get_the_author_url(); if ( !empty($author_url) ) : ?>
			<uri><?php the_author_url()?></uri>
			<?php endif; ?>
		</author>
		<title type="<?php html_type_rss(); ?>"><![CDATA[&#8235;<?php the_title_rss() ?>&#8236;]]></title><?php /* WPH */ ?>
		<link rel="alternate" type="text/html" href="<?php the_permalink_rss() ?>" />
		<id><?php the_guid(); ?></id>
		<updated><?php echo get_post_modified_time('Y-m-d\TH:i:s\Z', true); ?></updated>
		<published><?php echo get_post_time('Y-m-d\TH:i:s\Z', true); ?></published>
		<?php the_category_rss('atom') ?>
		<summary type="<?php html_type_rss(); ?>"><![CDATA[&#8235;<?php the_excerpt_rss(); ?>&#8236;]]></summary> <?php /* WPH */ ?>
<?php if ( !get_option('rss_use_excerpt') ) : ?>
		<content type="<?php html_type_rss(); ?>" xml:base="<?php the_permalink_rss() ?>"><![CDATA[<div dir="rtl"><?php the_content('', 0, '') ?></div>]]></content> <?php /* WPH */ ?>
<?php endif; ?>
<?php atom_enclosure(); ?>
<?php do_action('atom_entry'); ?>
		<link rel="replies" type="text/html" href="<?php the_permalink_rss() ?>#comments" thr:count="<?php echo get_comments_number()?>"/>
		<link rel="replies" type="application/atom+xml" href="<?php echo get_post_comments_feed_link(0,'atom') ?>" thr:count="<?php echo get_comments_number()?>"/>
		<thr:total><?php echo get_comments_number()?></thr:total>
	</entry>
	<?php endwhile ; ?>
</feed>
