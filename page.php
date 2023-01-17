<?php get_header(); ?>
<?php get_template_part('inc/breadcrumb'); ?>


<div class="main">
<?php while (have_posts()) : the_post(); ?>

<section class="top">
	<h1 class="des"><?php the_title(); ?></h1>

  <?php the_content(); ?>
<?php endwhile; ?>

</section>

<?php get_footer(); ?>
