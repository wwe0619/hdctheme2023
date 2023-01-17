<?php get_header(); ?>
<?php get_template_part( 'inc/breadcrumb' ); ?>

<div class="main">
<?php while ( have_posts() ) : the_post(); ?>
<section class="top">
	<h1 class="pagetitle"><?php the_title(); ?></h1>
	<?php the_content(); ?>
<?php endwhile; ?>
 </section>
 
 <section>
  <div>
    <span class="previous"><?php previous_post_link( '%link', '前へ', true ); ?></span>
    <span class="next"><?php next_post_link( '%link', '次へ', true ); ?></span>
  </div>
 </section>

<?php get_footer(); ?>