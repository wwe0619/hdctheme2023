<?php if ( ! is_front_page() && ! is_page( 'thanks' ) ) { ?>
  <div class="breadcrumbs">
		<?php if ( function_exists( 'bcn_display' ) ) {
			bcn_display();
		} ?>
  </div>
<?php } ?>