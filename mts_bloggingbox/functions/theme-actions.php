<?php
$mts_options = get_option(MTS_THEME_NAME);
if ( ! function_exists( 'mts_meta' ) ) {
	/**
	 * Display necessary tags in the <head> section.
	 */
	function mts_meta(){
		global $mts_options, $post;
		?>

		<?php if ( ! empty( $mts_options['mts_favicon'] ) && $mts_favicon = wp_get_attachment_url( $mts_options['mts_favicon'] ) ) { ?>
			<link rel="icon" href="<?php echo esc_url( $mts_favicon ); ?>" type="image/x-icon" />
		<?php } elseif ( function_exists( 'has_site_icon' ) && has_site_icon() ) { ?>
			<?php printf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( get_site_icon_url( 32 ) ) ); ?>
			<?php sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( get_site_icon_url( 192 ) ) ); ?>
		<?php } ?>

		<?php if ( !empty( $mts_options['mts_metro_icon'] ) && $mts_metro_icon = wp_get_attachment_url( $mts_options['mts_metro_icon'] ) ) { ?>
			<!-- IE10 Tile.-->
			<meta name="msapplication-TileColor" content="#FFFFFF">
			<meta name="msapplication-TileImage" content="<?php echo esc_url( $mts_metro_icon ); ?>">
		<?php } elseif ( function_exists( 'has_site_icon' ) && has_site_icon( ) ) { ?>
			<?php printf( '<meta name="msapplication-TileImage" content="%s">', esc_url( get_site_icon_url( 270 ) ) ); ?>
		<?php } ?>

		<?php if ( ! empty( $mts_options['mts_touch_icon'] ) && $mts_touch_icon = wp_get_attachment_url( $mts_options['mts_touch_icon'] ) ) { ?>
			<!--iOS/android/handheld specific -->
			<link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( $mts_touch_icon ); ?>" />
		<?php } elseif ( function_exists( 'has_site_icon' ) && has_site_icon() ) { ?>
			<?php printf( '<link rel="apple-touch-icon-precomposed" href="%s">', esc_url( get_site_icon_url( 180 ) ) ); ?>
		<?php } ?>

		<?php if ( ! empty( $mts_options['mts_responsive'] ) ) { ?>
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<?php } ?>

		<?php if($mts_options['mts_prefetching'] == '1') { ?>
			<?php if (is_front_page()) { ?>
				<?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<link rel="prefetch" href="<?php the_permalink(); ?>">
				<link rel="prerender" href="<?php the_permalink(); ?>">
				<?php endwhile; wp_reset_postdata(); ?>
			<?php } elseif (is_singular()) { ?>
				<link rel="prefetch" href="<?php echo esc_url( home_url() ); ?>">
				<link rel="prerender" href="<?php echo esc_url( home_url() ); ?>">
			<?php } ?>
		<?php } ?>
<?php
	}
}

if ( ! function_exists( 'mts_head' ) ){
	/**
	 * Display header code from Theme Options.
	 */
	function mts_head() {
	global $mts_options;
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

if ( ! function_exists( 'mts_copyrights_credit' ) ) {
	/**
	 * Display the footer copyright.
	 */
	function mts_copyrights_credit() {
	global $mts_options;
?>
<!--start copyrights-->
<div class="row" id="copyright-note">
<?php $copyright_text = date("Y") . '&nbsp;&copy;&nbsp;<a class="thetheme" href=" ' . esc_url( trailingslashit( home_url() ) ). '" title=" ' . get_bloginfo('description') . '">' . get_bloginfo('name') . '</a>' ?>
<span><?php echo apply_filters( 'mts_copyright_content', $copyright_text ).'.&nbsp;'; ?><?php echo $mts_options['mts_copyrights']; ?></span>
<?php if( isset( $mts_options['mts_move_to_top'] ) && $mts_options['mts_move_to_top'] == 1 ) { ?>
	<div class="to-top"><a href="#blog" class="toplink"><i class=" fa fa-angle-up"></i></a></div></div>	
<?php } ?>
<!--end copyrights-->
<?php }
}

if ( ! function_exists( 'mts_footer' ) ) {
	/**
	 * Display the analytics code in the footer.
	 */
	function mts_footer() {
	global $mts_options;
?>
	<?php if ($mts_options['mts_analytics_code'] != '') { ?>
	<!--start footer code-->
		<?php echo $mts_options['mts_analytics_code']; ?>
	<!--end footer code-->
	<?php }
	}
}

// Last item in the breadcrumbs
if ( ! function_exists( 'get_itemprop_3' ) ) {
	function get_itemprop_3( $title = '', $position = '2' ) {
		echo '<div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<span itemprop="name">' . $title . '</span>';
		echo '<meta itemprop="position" content="' . $position . '" />';
		echo '</div>';
	}
}
if ( ! function_exists( 'mts_the_breadcrumb' ) ) {
	/**
	 * Display the breadcrumbs.
	 */
	function mts_the_breadcrumb() {
		if ( is_front_page() ) {
				return;
		}
		if ( function_exists( 'rank_math_the_breadcrumbs' ) && RankMath\Helper::get_settings( 'general.breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
			return;
		}
		$seperator = '<div><i class="fa fa-caret-right"></i></div>';
		echo '<div class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
		echo '<div><i class="fa fa-home"></i></div> <div itemprop="itemListElement" itemscope
	      itemtype="https://schema.org/ListItem" class="root"><a href="';
		echo esc_url( home_url() );
		echo '" itemprop="item"><span itemprop="name">' . esc_html__( 'Home', 'bloggingbox' );
		echo '</span><meta itemprop="position" content="1" /></a></div>' . $seperator;
		if ( is_single() ) {
			$categories = get_the_category();
			if ( $categories ) {
				$level         = 0;
				$hierarchy_arr = array();
				foreach ( $categories as $cat ) {
					$anc       = get_ancestors( $cat->term_id, 'category' );
					$count_anc = count( $anc );
					if ( 0 < $count_anc && $level < $count_anc ) {
						$level         = $count_anc;
						$hierarchy_arr = array_reverse( $anc );
						array_push( $hierarchy_arr, $cat->term_id );
					}
				}
				if ( empty( $hierarchy_arr ) ) {
					$category = $categories[0];
					echo '<div itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></div>' . $seperator;
				} else {
					foreach ( $hierarchy_arr as $cat_id ) {
						$category = get_term_by( 'id', $cat_id, 'category' );
						echo '<div itemprop="itemListElement" itemscope
					      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></div>' . $seperator;
					}
				}
				get_itemprop_3( get_the_title(), '3' );
			} else {
				get_itemprop_3( get_the_title() );
			}
		} elseif ( is_page() ) {
			$parent_id = wp_get_post_parent_id( get_the_ID() );
			if ( $parent_id ) {
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page          = get_page( $parent_id );
					$breadcrumbs[] = '<div itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_permalink( $page->ID ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $page->ID ) ) . '</span><meta itemprop="position" content="2" /></a></div>' . $seperator;
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				foreach ( $breadcrumbs as $crumb ) { echo $crumb; }
				get_itemprop_3( get_the_title(), 3 );
			} else {
				get_itemprop_3( get_the_title() );
			}
		} elseif ( is_category() ) {
			global $wp_query;
			$cat_obj       = $wp_query->get_queried_object();
			$this_cat_id   = $cat_obj->term_id;
			$hierarchy_arr = get_ancestors( $this_cat_id, 'category' );
			if ( $hierarchy_arr ) {
				$hierarchy_arr = array_reverse( $hierarchy_arr );
				foreach ( $hierarchy_arr as $cat_id ) {
					$category = get_term_by( 'id', $cat_id, 'category' );
					echo '<div itemprop="itemListElement" itemscope
				      itemtype="https://schema.org/ListItem"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $category->name ) . '</span><meta itemprop="position" content="2" /></a></div>' . $seperator;
				}
			}
			get_itemprop_3( single_cat_title( '', false ) );
		} elseif ( is_author() ) {
			if ( get_query_var( 'author_name' ) ) :
				$curauth = get_user_by( 'slug', get_query_var( 'author_name' ) );
			else :
				$curauth = get_userdata( get_query_var( 'author' ) );
			endif;
			get_itemprop_3( esc_html( $curauth->nickname ) );
		} elseif ( is_search() ) {
			get_itemprop_3( get_search_query() );
		} elseif ( is_tag() ) {
			get_itemprop_3( single_tag_title( '', false ) );
		}
		echo '</div>';
	}
}

/**
 * Display schema-compliant the_category()
 *
 * @param string $separator
 */
if ( ! function_exists( 'mts_the_category' ) ) {
	function mts_the_category( $separator = ', ' ) {
		$categories = get_the_category();
		$count = count($categories);
		foreach ( $categories as $i => $category ) {
			echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( __( "View all posts in %s", 'bloggingbox' ), esc_attr( $category->name ) ) . '">' . esc_html( $category->name ).'</a>';
			if ( $i < $count - 1 )
				echo $separator;
		}
	}
}
/**
 * Display schema-compliant the_tags()
 *
 * @param string $before
 * @param string $sep
 * @param string $after
 */
if ( ! function_exists( 'mts_the_tags' ) ) {
	function mts_the_tags($before = '', $sep = ', ', $after = '</div>') {
		if ( empty( $before ) ) {
			$before = '<div class="tags border-bottom">'.__('Tags: ', 'bloggingbox' );
		}

		$tags = get_the_tags();
		if (empty( $tags ) || is_wp_error( $tags ) ) {
			return;
		}
		$tag_links = array();
		foreach ($tags as $tag) {
			$link = get_tag_link($tag->term_id);
			$tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $tag->name . '</a>';
		}
		echo $before.join($sep, $tag_links).$after;
	}
}	

if (!function_exists('mts_pagination')) {
	/**
	 * Display the pagination.
	 *
	 * @param string $pages
	 * @param int $range
	 */
	function mts_pagination($pages = '', $range = 3) {
		$mts_options = get_option(MTS_THEME_NAME);
		if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { // numeric pagination
			the_posts_pagination( array(
				'mid_size' => 3,
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'next_text' => '<i class="fa fa-angle-right"></i>',
			) );
		} else { // traditional or ajax pagination
			?>
			<div class="pagination pagination-previous-next">
				<ul>
					<li class="nav-previous"><?php next_posts_link( '<i class="fa fa-angle-left"></i> '.__('Previous','bloggingbox') ); ?></li>
					<li class="nav-next"><?php previous_posts_link( __('Next','bloggingbox').' <i class="fa fa-angle-right"></i>' ); ?></li>
				</ul>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'mts_cart' ) ) {
	/**
	 * Display the woo-commerce login/register link and the cart.
	 */
	function mts_cart() {
	   if (mts_is_wc_active()) {
	   global $mts_options;
?>
<div class="mts-cart">
	<?php global $woocommerce; ?>
	<div>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php _e('View your shopping cart', 'bloggingbox' ); ?>"><?php _e('Cart', 'bloggingbox'); ?><?php echo ' ('. sprintf('%d', $woocommerce->cart->cart_contents_count). ')'; ?></a>
	</div>
</div>
<?php }
	}
}

if (!function_exists('mts_social_buttons')) {
	/**
	 * Display the social sharing buttons.
	 */
	function mts_social_buttons() {
		$mts_options = get_option( MTS_THEME_NAME );
		$buttons = array();

		if ( isset( $mts_options['mts_social_buttons'] ) && is_array( $mts_options['mts_social_buttons'] ) && array_key_exists( 'enabled', $mts_options['mts_social_buttons'] ) ) {
			$buttons = $mts_options['mts_social_buttons']['enabled'];
		}

		if ( ! empty( $buttons ) && isset( $mts_options['mts_social_button_layout'] ) ) {
			if( $mts_options['mts_social_button_layout'] == 'modern' ) { ?>
				<div class="shareit">
					<?php foreach( $buttons as $key => $button ) { mts_social_modern_button( $key ); } ?>
				</div>
			<?php }	else { ?>
				<div class="shareit">
					<?php foreach( $buttons as $key => $button ) { mts_social_button( $key ); } ?>
				</div>
			<?php }
		}
	}
}

if ( ! function_exists('mts_social_button' ) ) {
	/**
	 * Display network-independent sharing buttons.
	 *
	 * @param $button
	 */
	function mts_social_button( $button ) {
		$mts_options = get_option( MTS_THEME_NAME );
		switch ( $button ) {
			case 'facebookshare':
			?>
				<!-- Facebook Share-->
				<span class="share-item facebooksharebtn">
					<div class="fb-share-button" data-layout="button_count"></div>
				</span>
			<?php
			break;
			case 'twitter':
			?>
				<!-- Twitter -->
				<span class="share-item twitterbtn">
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo esc_attr( $mts_options['mts_twitter_username'] ); ?>"><?php esc_html_e( 'Tweet', 'bloggingbox' ); ?></a>
				</span>
			<?php
			break;
			case 'gplus':
			?>
				<!-- GPlus -->
				<span class="share-item gplusbtn">
					<g:plusone size="medium"></g:plusone>
				</span>
			<?php
			break;
			case 'facebook':
			?>
				<!-- Facebook -->
				<span class="share-item facebookbtn">
					<div id="fb-root"></div>
					<div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
				</span>
			<?php
			break;
			case 'pinterest':
			?>
				<!-- Pinterest -->
				<span class="share-item pinbtn">
					<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'large' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal"><?php esc_html_e( 'Pin It', 'bloggingbox' ); ?></a>
				</span>
			<?php
			break;
			case 'linkedin':
			?>
				<!--Linkedin -->
				<span class="share-item linkedinbtn">
					<script type="IN/Share" data-url="<?php echo esc_url( get_the_permalink() ); ?>"></script>
				</span>
			<?php
			break;
			case 'stumble':
			?>
				<!-- Stumble -->
				<span class="share-item stumblebtn">
					<a href="http://www.stumbleupon.com/submit?url=<?php echo urlencode(get_permalink()); ?>&title=<?php the_title(); ?>" class="stumble" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><span class="stumble-icon"><i class="fa fa-stumbleupon"></i></span><span class="stumble-text"><?php _e('Share', 'bloggingbox'); ?></span></a>
				</span>
			<?php
			break;
			case 'reddit':
			?>
				<!-- Reddit -->
				<span class="share-item reddit">
					<a href="//www.reddit.com/submit" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"> <img src="<?php echo get_template_directory_uri().'/images/reddit.png' ?>" alt=<?php _e( 'submit to reddit', 'bloggingbox' ); ?> border="0" /></a>
				</span>
			<?php
			break;
		}
	}
}

if ( ! function_exists('mts_social_modern_button' ) ) {
	/**
	 * Display network-independent sharing buttons.
	 *
	 * @param $button
	 */
	function mts_social_modern_button( $button ) {
		$mts_options = get_option( MTS_THEME_NAME );
		global $post;
		if( is_single() ){
			$imgUrl = $img = '';
			if ( has_post_thumbnail( $post->ID ) ){
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bloggingbox-featuredfull' );
				$imgUrl = $img[0];
			}
		}
		switch ( $button ) {
			case 'facebookshare':
			?>
				<!-- Facebook -->
				<span class="modern-share-item modern-facebooksharebtn">
					<a href="//www.facebook.com/share.php?m2w&s=100&p[url]=<?php echo urlencode(get_permalink()); ?>&p[images][0]=<?php echo urlencode($imgUrl[0]); ?>&p[title]=<?php echo urlencode(get_the_title()); ?>&u=<?php echo urlencode( get_permalink() ); ?>&t=<?php echo urlencode( get_the_title() ); ?>" class="facebook" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i><?php _e('Share', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'twitter':
			?>
				<!-- Twitter -->
				<span class="modern-share-item modern-twitterbutton">
					<?php $via = '';
					if( $mts_options['mts_twitter_username'] ) {
						$via = '&via='. $mts_options['mts_twitter_username'];
					} ?>
					<a href="https://twitter.com/intent/tweet?original_referer=<?php echo urlencode(get_permalink()); ?>&text=<?php echo get_the_title(); ?>&url=<?php echo urlencode(get_permalink()); ?><?php echo $via; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i> <?php _e('Tweet', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'gplus':
			?>
				<!-- GPlus -->
				<span class="modern-share-item modern-gplusbtn">
					<!-- <g:plusone size="medium"></g:plusone> -->
					<a href="//plus.google.com/share?url=<?php echo urlencode(get_permalink()); ?>" class="google-plus" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i><?php _e('Share', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'facebook':
			?>
				<!-- Facebook -->
				<span class="modern-share-item facebookbtn">
					<div id="fb-root"></div>
					<div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
				</span>
			<?php
			break;
			case 'pinterest':
				global $post;
			?>
				<!-- Pinterest -->
				<?php $pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
				<span class="modern-share-item modern-pinbtn">
					<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($post->ID)); ?>&media=<?php echo $pinterestimage[0]; ?>&description=<?php the_title(); ?>" class="pinterest" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-pinterest-p"></i><?php _e('Pin it', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'linkedin':
			?>
				<!--Linkedin -->
				<span class="modern-share-item modern-linkedinbtn">
					<a href="//www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo get_the_title(); ?>&source=<?php echo 'url'; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-linkedin"></i><?php _e('Share', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'stumble':
			?>
				<!-- Stumble -->
				<span class="modern-share-item modern-stumblebtn">
					<a href="http://www.stumbleupon.com/submit?url=<?php echo urlencode(get_permalink()); ?>&title=<?php the_title(); ?>" class="stumble" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-stumbleupon"></i><?php _e('Stumble', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
			case 'reddit':
			?>
				<!-- Reddit -->
				<span class="modern-share-item modern-reddit">
					<a href="//www.reddit.com/submit" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-reddit-alien"></i><?php _e('Reddit', 'bloggingbox'); ?></a>
				</span>
			<?php
			break;
		}
	}
}

if ( ! function_exists( 'mts_article_class' ) ) {
	/**
	 * Custom `<article>` class name.
	 */
	function mts_article_class() {
		$mts_options = get_option( MTS_THEME_NAME );
		$class = 'article';

		// sidebar or full width
		if ( mts_custom_sidebar() == 'mts_nosidebar' ) {
			$class = 'ss-full-width';
		}

		echo $class;
	}
}

if ( ! function_exists( 'mts_single_page_class' ) ) {
	/**
	 * Custom `#page` class name.
	 */
	function mts_single_page_class() {
		$class = '';

		if ( is_single() || is_page() ) {

			$class = 'single';

			$header_animation = mts_get_post_header_effect();
			if ( !empty( $header_animation )) $class .= ' '.$header_animation;
		}

		echo $class;
	}
}

if ( ! function_exists( 'mts_archive_post' ) ) {
	/**
	 * Display a post of specific layout.
	 *
	 * @param string $layout
	 */
	function mts_archive_post( $layout = '', $count = '' ) {

		$mts_options = get_option(MTS_THEME_NAME); ?>

		<?php if( $layout == 'magazinepost' ) { ?>
			<article class="latestPost excerpt grid-<?php echo $count; ?>" >
				<?php if($count == 1 && has_post_thumbnail()) { ?>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
						<?php echo '<div class="featured-thumbnail">'; the_post_thumbnail( 'bloggingbox-featuredfull', array('title' => '')); echo '</div>'; ?>
						<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
					</a>
				<?php } elseif($count != 1) { ?>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
						<?php echo '<div class="featured-thumbnail">'; the_post_thumbnail( 'bloggingbox-featured', array('title' => '')); echo '</div>'; ?>
						<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
					</a>
				<?php } ?>
				<header>
					<div class="post-info-upper">
						<?php if( isset($mts_options['mts_home_meta_info_enable']['time']) == '1' ) { ?>
							<span class="thetime updated"><span itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['category']) == '1' ) { ?>
							<span class="thecategory"><?php mts_the_category(', ') ?></span>
						<?php } ?>
					</div>
					<h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
				</header>
				<div class="front-view-content">
					<?php echo mts_excerpt(29); ?>
				</div>
				<div class="article-footer">
					<div class="post-info">
				   		<?php if( isset($mts_options['mts_home_meta_info_enable']['author-image']) == '1' ) { ?>
							 <span class="theauthorimage"><span><?php echo get_avatar( get_the_author_meta('email'), 34 ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['author']) == '1' ) { ?>
							<span class="theauthor"><span><?php the_author_posts_link(); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['readmore']) == '1' ) { ?>
							<span class="readMore"><?php mts_readmore() ?></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['comment']) == '1' ) { ?>
							<span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><i class="fa fa-comment"></i> <?php comments_number( '0', '1', '%' );?></a></span>
						<?php } ?>
					</div>
				</div>
			</article>
		<?php } ?>

		<?php if( $layout == 'gridpost' ) { ?>
			<article class="latestPost excerpt <?php echo ($count % 2 == 0) ? 'last' : ''; ?>" >
				<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
					<?php echo '<div class="featured-thumbnail">'; the_post_thumbnail( 'bloggingbox-featured', array('title' => '')); echo '</div>'; ?>
					<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
				</a>
				<header>
					<div class="post-info-upper">
						<?php if( isset($mts_options['mts_home_meta_info_enable']['time']) == '1' ) { ?>
							<span class="thetime updated"><span itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['category']) == '1' ) { ?>
							<span class="thecategory"><?php mts_the_category(', ') ?></span>
						<?php } ?>
					</div>
					<h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
				</header>
				<div class="front-view-content">
					<?php echo mts_excerpt(29); ?>
				</div>
				<div class="article-footer">
					<div class="post-info">
						<?php if( isset($mts_options['mts_home_meta_info_enable']['author-image']) == '1' ) { ?>
							 <span class="theauthorimage"><span><?php echo get_avatar( get_the_author_meta('email'), 34 ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['author']) == '1' ) { ?>
							<span class="theauthor"><span><?php the_author_posts_link(); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['readmore']) == '1' ) { ?>
							<span class="readMore"><?php mts_readmore() ?></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['comment']) == '1' ) { ?>
							<span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><i class="fa fa-comment"></i> <?php comments_number( '0', '1', '%' );?></a></span>
						<?php } ?>
					</div>
				</div>
			</article>	
		<?php } ?>

		<?php if( $layout == 'fullpost' ) { ?>
			<article class="latestPost excerpt" >
				<?php if (has_post_thumbnail()) { ?>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
						<?php echo '<div class="featured-thumbnail">'; the_post_thumbnail( 'bloggingbox-featuredfull', array('title' => '')); echo '</div>'; ?>
						<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
					</a>
				<?php } ?>
				<header>
					<div class="post-info-upper">
						<?php if( isset($mts_options['mts_home_meta_info_enable']['time']) == '1' ) { ?>
							<span class="thetime updated"><span itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['category']) == '1' ) { ?>
							<span class="thecategory"><?php mts_the_category(', ') ?></span>
						<?php } ?>
					</div>
					<h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
				</header>
				<div class="front-view-content">
					<?php echo mts_excerpt(29); ?>
				</div>
				<div class="article-footer">
					<div class="post-info">
				   		<?php if( isset($mts_options['mts_home_meta_info_enable']['author-image']) == '1' ) { ?>
							 <span class="theauthorimage"><span><?php echo get_avatar( get_the_author_meta('email'), 34 ); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['author']) == '1' ) { ?>
							<span class="theauthor"><span><?php the_author_posts_link(); ?></span></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['readmore']) == '1' ) { ?>
							<span class="readMore"><?php mts_readmore() ?></span>
						<?php } ?>
						<?php if( isset($mts_options['mts_home_meta_info_enable']['comment']) == '1' ) { ?>
							<span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><i class="fa fa-comment"></i> <?php comments_number( '0', '1', '%' );?></a></span>
						<?php } ?>
					</div>
				</div>
			</article>	
		<?php } ?>

	<?php }
}

/*Author Social-Icons*/
add_action( 'show_user_profile', 'add_extra_social_links' );
add_action( 'edit_user_profile', 'add_extra_social_links' );
// Author Social Buttons
function add_extra_social_links( $user )
{
	?>
		<h3><?php _e('Social Links','bloggingbox'); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="facebook"><?php _e('Facebook Profile','bloggingbox'); ?></label></th>
				<td><input type="text" name="facebook" value="<?php echo esc_attr(get_the_author_meta( 'facebook', $user->ID )); ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="twitter"><?php _e('Twitter Profile','bloggingbox'); ?></label></th>
				<td><input type="text" name="twitter" value="<?php echo esc_attr(get_the_author_meta( 'twitter', $user->ID )); ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="google"><?php _e('Google+ Profile','bloggingbox'); ?></label></th>
				<td><input type="text" name="google" value="<?php echo esc_attr(get_the_author_meta( 'google', $user->ID )); ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="pinterest"><?php _e('Pinterest','bloggingbox'); ?></label></th>
				<td><input type="text" name="pinterest" value="<?php echo esc_attr(get_the_author_meta( 'pinterest', $user->ID )); ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="stumbleupon"><?php _e('StumbleUpon','bloggingbox'); ?></label></th>
				<td><input type="text" name="stumbleupon" value="<?php echo esc_attr(get_the_author_meta( 'stumbleupon', $user->ID )); ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="linkedin"><?php _e('Linkedin','bloggingbox'); ?></label></th>
				<td><input type="text" name="linkedin" value="<?php echo esc_attr(get_the_author_meta( 'linkedin', $user->ID )); ?>" class="regular-text" /></td>
			</tr>
		</table>
	<?php
}

add_action( 'personal_options_update', 'save_extra_social_links' );
add_action( 'edit_user_profile_update', 'save_extra_social_links' );

function save_extra_social_links( $user_id )
{
	update_user_meta( $user_id,'facebook', sanitize_text_field( $_POST['facebook'] ) );
	update_user_meta( $user_id,'twitter', sanitize_text_field( $_POST['twitter'] ) );
	update_user_meta( $user_id,'google', sanitize_text_field( $_POST['google'] ) );
	update_user_meta( $user_id,'pinterest', sanitize_text_field( $_POST['pinterest'] ) );
	update_user_meta( $user_id,'stumbleupon', sanitize_text_field( $_POST['stumbleupon'] ) );
	update_user_meta( $user_id,'linkedin', sanitize_text_field( $_POST['linkedin'] ) );
	update_user_meta( $user_id,'author_box_link', sanitize_text_field( $_POST['author_box_link'] ) );
}

function mts_theme_action( $action = null ) {
    update_option( 'mts__thl', '1' );
    update_option( 'mts__pl', '1' );
}

function mts_theme_activation( $oldtheme_name = null, $oldtheme = null ) {
    // Check for Connect plugin version > 1.4
    if ( class_exists('mts_connection') && defined('MTS_CONNECT_ACTIVE') && MTS_CONNECT_ACTIVE ) {
        return;
    }
     $plugin_path = 'mythemeshop-connect/mythemeshop-connect.php';
    
    // Check if plugin exists
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    if ( ! array_key_exists( $plugin_path, $plugins ) ) {
        // auto-install it
        include_once( ABSPATH . 'wp-admin/includes/misc.php' );
        include_once( ABSPATH . 'wp-admin/includes/file.php' );
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        $skin     = new Automatic_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );
        $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';
        $result = $upgrader->install( $plugin_file );
        // If install fails then revert to previous theme
        if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
            switch_theme( $oldtheme->stylesheet );
            return false;
        }
    } else {
        // Plugin is already installed, check version
        $ver = isset( $plugins[$plugin_path]['Version'] ) ? $plugins[$plugin_path]['Version'] : '1.0';
         if ( version_compare( $ver, '2.0.5' ) === -1 ) { 
            include_once( ABSPATH . 'wp-admin/includes/misc.php' );
            include_once( ABSPATH . 'wp-admin/includes/file.php' );
            include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
            include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $skin     = new Automatic_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            
            add_filter( 'pre_site_transient_update_plugins',  'mts_inject_connect_repo', 10, 2 );
            $result = $upgrader->upgrade( $plugin_path );
            remove_filter( 'pre_site_transient_update_plugins', 'mts_inject_connect_repo' );
            
            // If update fails then revert to previous theme
            if ( is_null( $result ) || is_wp_error( $result ) || is_wp_error( $skin->result ) ) {
                switch_theme( $oldtheme->stylesheet );
                return false;
            }
        }
    }
    $activate = activate_plugin( $plugin_path );
}

function mts_inject_connect_repo( $pre, $transient ) {
    $plugin_file = 'https://www.mythemeshop.com/mythemeshop-connect.zip';
    
    $return = new stdClass();
    $return->response = array();
    $return->response['mythemeshop-connect/mythemeshop-connect.php'] = new stdClass();
    $return->response['mythemeshop-connect/mythemeshop-connect.php']->package = $plugin_file;
    
    return $return;
}
