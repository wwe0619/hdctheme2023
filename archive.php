<?php get_header(); ?>
<?php get_template_part( 'inc/breadcrumb' ); ?>
  <div>
		<?php if ( have_posts() ) :
			$html = ''; ?>
      <ul>
				<?php while ( have_posts() ) : the_post();
					$time = get_post_time( 'Y年n月j日', false, $post->ID );
					$html .= '<li><a href="' . get_the_permalink( $post->ID ) . '" ><span>' . $time . '</span>' . get_the_title() . '</a></li>';
				endwhile;
				echo $html; ?>
      </ul>
		<?php endif; ?>

		<?php
		global $wp_rewrite;
		$paginate_base = get_pagenum_link( 1 );
		if ( strpos( $paginate_base, '?' ) || ! $wp_rewrite->using_permalinks() ) {
			$paginate_format = '';
			$paginate_base   = add_query_arg( 'paged', '%#%' );
		} else {
			$paginate_format = ( substr( $paginate_base, - 1, 1 ) == '/' ? '' : '/' ) .
			                   user_trailingslashit( 'page/%#%/', 'paged' );
			$paginate_base   .= '%_%';
		}
		echo paginate_links( array(
			'base'      => $paginate_base,
			'format'    => $paginate_format,
			'total'     => $wp_query->max_num_pages,
			'mid_size'  => 1,
			'current'   => ( $paged ? $paged : 1 ),
			'prev_text' => '←',
			'next_text' => '→',
		) ); ?>
  </div>
<?php get_footer(); ?>