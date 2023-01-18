<?php get_header(); ?>
<?php get_template_part( 'inc/breadcrumb' ); ?>

<section class="columns">
<?php while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
<?php endwhile; ?>	
</section>	
test01
<?php get_footer(); ?>

