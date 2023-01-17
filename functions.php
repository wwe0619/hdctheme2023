<?php
function jz_setup() {
	add_editor_style();
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'menus' );
	register_nav_menu( 'primary', 'Primary Menu' );
}

add_action( 'after_setup_theme', 'jz_setup' );

function jz_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	$title .= get_bloginfo( 'name' );

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( 'ページ %s', max( $paged, $page ) );
	}

	return $title;
}

add_filter( 'wp_title', 'jz_wp_title', 10, 2 );

function jz_widgets_init() {
	register_sidebar( array(
		'name'          => 'バナーエリア',
		'id'            => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

}

add_action( 'widgets_init', 'jz_widgets_init' );


add_filter( 'wpcf7_validate_email', 'wpcf7_text_validation_filter_extend', 11, 2 );
add_filter( 'wpcf7_validate_email*', 'wpcf7_text_validation_filter_extend', 11, 2 );
function wpcf7_text_validation_filter_extend( $result, $tag ) {
	$type           = $tag['type'];
	$name           = $tag['name'];
	$_POST[ $name ] = trim( strtr( (string) $_POST[ $name ], "\n", " " ) );
	if ( 'email' == $type || 'email*' == $type ) {
		if ( preg_match( '/(.*)_confirm$/', $name, $matches ) ) {
			$target_name = $matches[1];
			if ( $_POST[ $name ] != $_POST[ $target_name ] ) {
				if ( method_exists( $result, 'invalidate' ) ) {
					$result->invalidate( $tag, "確認用のメールアドレスが一致していません" );
				} else {
					$result['valid']           = false;
					$result['reason'][ $name ] = '確認用のメールアドレスが一致していません';
				}
			}
		}
	}

	return $result;
}

function custom_wp_footer() {
	global $post;

	if ( is_page( array( 'contact', 'inquiry', 'reservation' ) ) ) {
		$url = get_permalink( $post->ID );
		?>
    <script type="text/javascript">
      document.addEventListener('wpcf7mailsent', function (event) {
        location.replace("<?php echo $url; ?>thanks/");
      }, false);
    </script>
		<?php
	}
}

add_action( 'wp_footer', 'custom_wp_footer' );

//中サイズ画像をトリミングする
update_option( 'medium_crop', true );
//大サイズ画像をトリミングする
update_option( 'large_crop', true );

function auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
	if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
		$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
	}

	return $slug;
}

add_filter( 'wp_unique_post_slug', 'auto_post_slug', 10, 4 );

/* 画像編集の際に勝手にwidth/heightが入るので削除 */
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
	$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );

	return $html;
}

function lig_wp_category_terms_checklist_no_top( $args, $post_id = null ) {
	$args['checked_ontop'] = false;

	return $args;
}

add_action( 'wp_terms_checklist_args', 'lig_wp_category_terms_checklist_no_top' );

remove_action( 'wp_head', 'wp_generator' );


add_action( 'wp_enqueue_scripts', 'my_init' );
function my_init() {
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0' );
}

/* 郵便番号自動住所 */
add_action( 'wp_enqueue_scripts', 'my_ajaxzip3' );
function my_ajaxzip3() {
	wp_enqueue_script( 'ajaxzip3', 'https://ajaxzip3.github.io/ajaxzip3.js' );
}

add_action( 'wp_enqueue_scripts', 'autozip' );
function autozip() {
	wp_enqueue_script( 'autozip', get_template_directory_uri() . '/assets/js/autozip.js' );
}


/* 自動pタグ機能を固定ページでのみ停止 */
add_filter( 'the_content', 'wpautop_filter', 9 );
function wpautop_filter( $content ) {
	global $post;
	$remove_filter = false;

	$arr_types = array( 'page' ); //自動整形を無効にする投稿タイプを記述 ＝固定ページ
	$post_type = get_post_type( $post->ID );
	if ( in_array( $post_type, $arr_types ) ) {
		$remove_filter = true;
	}

	if ( $remove_filter ) {
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
	}

	return $content;
}


/* 画像にsrcsetが埋め込まれるのを削除 */
add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );