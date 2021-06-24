<?php
/**
 * The main template file.
 *
 * Used to display the homepage when home.php doesn't exist.
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header(); ?>

<?php if ( isset( $mts_options['mts_header_layout'] ) && is_array( $mts_options['mts_header_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_header_layout'] ) ) {
		$header_parts = $mts_options['mts_header_layout']['enabled'];
	} else {
		$header_parts = array( 'slider' => 'slider', 'cat' => 'cat' );
	}

	foreach( $header_parts as $part => $label ) {
		switch ($part) {
			case 'slider':
				if ( is_home() && $mts_options['mts_featured_slider'] == '1' ) { ?>
					<div class="primary-slider-container clearfix loading">
						<div id="slider" class="primary-slider">
							<?php if ( empty( $mts_options['mts_custom_slider'] ) ) { ?>
								<?php
								// prevent implode error
								if ( empty( $mts_options['mts_featured_slider_cat'] ) || !is_array( $mts_options['mts_featured_slider_cat'] ) ) {
									$mts_options['mts_featured_slider_cat'] = array('0');
								}
								$slider_cat = implode( ",", $mts_options['mts_featured_slider_cat'] );
								$slider_query = new WP_Query('cat='.$slider_cat.'&posts_per_page='.$mts_options['mts_featured_slider_num']);
								$i = 1;
								while ( $slider_query->have_posts() ) : $slider_query->the_post(); 
								$featured_class = isset($i) ? ' class="primary-slider-item big-'.$i.'"' : 'primary-slider-item'; ?>
								<?php if ( $i % 3 == 1 )  { ?>
								<div <?php echo $featured_class; ?> style="background:url(<?php echo mts_get_thumbnail_url( 'bloggingbox-sliderlarge' ); ?>); min-height: 360px; background-position: center center; float: left; width: 340px;"> 
								<?php } else { ?>
								<div <?php echo $featured_class; ?> style="background:url(<?php echo mts_get_thumbnail_url( 'bloggingbox-slider' ); ?>); min-height: 360px; background-position: center center; float: left; width: 300px;"> 
								<?php } ?>
									<a href="<?php echo esc_url( get_the_permalink() ); ?>">
										<div class="slide-caption">
										<div class="thecategory"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></div>
											<h2 class="slide-title"><?php echo substr(the_title( $before = '', $after = '', FALSE), 0, 50) . '...'; ?></h2>
										</div>
									</a> 
								</div>
								<?php $i++; endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php $j = 1;
								foreach( $mts_options['mts_custom_slider'] as $slide ) : 
									$featured_class = isset($j) ? ' class="primary-slider-item big-'.$j.'"' : 'primary-slider-item'; ?>
									<div class="primary-slider-item">
										<a href="<?php echo esc_url( $slide['mts_custom_slider_link'] ); ?>">
											<?php if ( $j % 3 == 1 )  {
												$image = wp_get_attachment_image_src($slide['mts_custom_slider_image'],'bloggingbox-sliderlarge');?>
												<div <?php echo $featured_class; ?> style="background:url(<?php echo $image[0]; ?>); min-height: 360px; background-position: center center; float: left; width: 340px;">
											<?php } else {
												$image = wp_get_attachment_image_src($slide['mts_custom_slider_image'],'bloggingbox-sliderlarge');?>
												<div <?php echo $featured_class; ?> style="background:url(<?php echo $image[0]; ?>); min-height: 360px; background-position: center center; float: left; width: 300px;">
											<?php } ?>
											<div class="slide-caption">
												<h2 class="slide-title"><?php echo esc_html( $slide['mts_custom_slider_title'] ); ?></h2>
											</div>
											</div>
										</a>
									</div>
								<?php $j++; endforeach; ?>
							<?php } ?>
						</div><!-- .primary-slider -->
					</div><!-- .primary-slider-container -->

				<?php }
			break;

			case 'cat':
				if ( !empty($mts_options['mts_featured_section']) && is_array($mts_options['mts_featured_section']) && !empty($mts_options['mts_featured_category_section'])) { ?>
					<div class="featured-categories-container">
						<div class="container">
							<?php 
							foreach( $mts_options['mts_featured_section'] as $featured_cats ) :
							$mts_featured_text_color = $featured_cats['mts_featured_text_color']; ?>
								<?php if( ! empty( $featured_cats['mts_featured_cats'] )) : ?>
									<a class="featured-wrap-<?php echo $featured_cats['mts_featured_cats']; ?>" href="<?php echo get_category_link( $featured_cats['mts_featured_cats'] )?>" title="<?php print get_the_category_by_ID( $featured_cats['mts_featured_cats'] )?>"><?php print get_the_category_by_ID( $featured_cats['mts_featured_cats'] )?></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php }
			break;
		}
	}
?>

<div id="page">
	<div class="article">
		<div id="content_box">
			<?php if ( !is_paged() ) {
				$featured_categories = array();
				if ( !empty( $mts_options['mts_featured_categories'] ) ) {
					$mts_pagination = $mts_options['mts_pagenavigation_type'];
					foreach ( $mts_options['mts_featured_categories'] as $section ) {
						$category_id = $section['mts_featured_category'];
						$featured_categories[] = $category_id;
						$posts_num = $section['mts_featured_category_postsnum'];
						$layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'magazinepost';
						if( $layout == 'gridpost' ) {
							$class = 'page-grid';
						} elseif( $layout == 'magazinepost' ) {
							$class = 'page-magazine';
						} elseif( $layout == 'fullpost' ) {
							$class = 'page-fullpost';
						}
						if ( 'latest' == $category_id ) { ?>
							<?php if( isset($mts_options['mts_enable_tabs']) && $mts_options['mts_enable_tabs'] == 1 ) { ?>
							<div class="featured-stories-tabs clearfix">
								<ul class="tabs">
									<li class="tab-link active" data-tab="tab-1"><?php _e('Latest Stories', 'bloggingbox'); ?></li>
									<li class="tab-link" data-tab="tab-2"><?php _e((empty($mts_options['mts_popular_tab_posts_title']) ? __('Popular Posts', 'bloggingbox') : $mts_options['mts_popular_tab_posts_title'] ), 'bloggingbox'); ?></li>
								</ul>
								<div class="tabs-content">
									<div id="tab-1" class="<?php if ( $mts_pagination > 1 ) echo $class; ?> tab-content active">
							<?php } ?>
							<div class="article-wrap <?php echo $class; ?>">
								<?php $j = 1; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
									<?php mts_archive_post($layout, $j); ?>
								<?php ++$j; endwhile; endif; ?>
								
								<?php if ( $j !== 1 ) { // No pagination if there is no posts ?>
									<?php mts_pagination(); ?>
								<?php } ?>
							</div>
							<?php if( isset($mts_options['mts_enable_tabs']) && $mts_options['mts_enable_tabs'] == 1 ) { ?>
								</div><!-- #tab-1 -->
								<div id="tab-2" class="tab-content">
									<div class="ball-pulse">
										<div></div>
										<div></div>
										<div></div>
									</div>
								</div><!-- #tab-2 -->
									</div><!-- .tabs-content -->
								</div><!-- .featured-stories-tabs -->
							<?php } ?>
						<?php } else { // if $category_id != 'latest': ?>
							<h3 class="featured-category-title"><a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" title="<?php echo esc_attr( get_cat_name( $category_id ) ); ?>"><?php echo esc_html( get_cat_name( $category_id ) ); ?></a></h3>
							<?php //echo $layout; ?>
							<div class="article-wrap <?php echo $class; ?>">
								<?php $j = 1; $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num);
								if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
									<?php mts_archive_post($layout, $j); ?>
								<?php ++$j; endwhile; endif; wp_reset_postdata(); ?>
							</div>
						<?php }
					}
				}

			} else { //Paged
				foreach ( $mts_options['mts_featured_categories'] as $section ) {
					$category_id = $section['mts_featured_category'];
					$featured_categories[] = $category_id;
					$posts_num = $section['mts_featured_category_postsnum'];
					$layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'magazinepost';
					if( $layout == 'gridpost' ) {
						$class = 'page-grid';
					} elseif( $layout == 'magazinepost' ) {
						$class = 'page-magazine';
					} elseif( $layout == 'fullpost' ) {
						$class = 'page-fullpost';
					}
					if ( 'latest' == $category_id ) { ?>
					<div class="article-wrap <?php echo $class; ?>">
						<?php $j = 1; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php mts_archive_post($layout, $j); ?>
						<?php ++$j; endwhile; endif; ?>
					</div>
					<?php }
				} ?>

				<?php if ( $j !== 1 ) { // No pagination if there is no posts ?>
					<?php mts_pagination(); ?>
				<?php } ?>

			<?php } ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>