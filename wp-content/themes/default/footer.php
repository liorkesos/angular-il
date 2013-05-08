<?php /* // Modified for WPH */ ?>
<hr />
<div id="footer">
<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
	<p>
		<?php printf(__('%1$s is proudly powered by <a href="http://wph.co.il" title="וורדפרס בעברית, מערכת אישית בקוד פתוח לניהול בלוגים עצמאיים">וורדפרס בעברית</a>', 'kubrick'), get_bloginfo('name')); ?>.
		<?php echo __('Designed by <a href="http://binarybonsai.com/kubrick/">Michael Heilemann</a>', 'kubrick'); ?>. <?php /* WPH */ ?>
		<br /><?php printf(__('%1$s and %2$s.', 'kubrick'), '<a href="' . get_bloginfo('rss2_url') . '">' . __('Entries (RSS)', 'kubrick') . '</a>', '<a href="' . get_bloginfo('comments_rss2_url') . '">' . __('Comments (RSS)', 'kubrick') . '</a>'); ?>
		<!-- <?php printf(__('%d queries. %s seconds.', 'kubrick'), get_num_queries(), timer_stop(0, 3)); ?> -->
	</p>
</div>
</div>

<!-- Gorgeous design by Michael Heilemann - http://binarybonsai.com/kubrick/ -->
<?php /* "Just what do you think you're doing Dave?" */ ?>

		<?php wp_footer(); ?>
</body>
</html>
