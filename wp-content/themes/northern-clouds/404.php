<?php
get_header();
?>

<h2 class="storytitle"><?php _e("Error 404 - Page Not Found!", 'northern'); ?></h2>
<article class="noresults">
<p><?php _e('The requested page  was not found.', 'northern'); ?></p>
<?php get_search_form(); ?>
</article>

</section>

<?php get_sidebar('2'); ?>

<?php get_footer(); ?>