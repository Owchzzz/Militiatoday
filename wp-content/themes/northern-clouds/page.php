<?php
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<?php the_post_thumbnail('thumbnail'); ?>
<div class="meta">
<?php edit_post_link(__('Edit This', 'northern')); ?>
<ul>
<li><?php northern_the_breadcrumb(); ?></li>
</ul>
</div>
<?php the_content(__('(more...)', 'northern')); ?>
<div class="commentlink">
<?php wp_link_pages(); ?>
</div>
</article>

<?php endwhile; else: ?>
<?php endif; ?>
<?php comments_template( '', true ); ?>

</section>

<?php get_sidebar('2'); ?>

<?php get_footer(); ?>