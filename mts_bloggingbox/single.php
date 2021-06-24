<?php
/**
 * The template for displaying all single posts.
 */
$mts_options = get_option(MTS_THEME_NAME);
get_header(); ?>

<?php $header_animation = mts_get_post_header_effect(); ?>
<?php if ( has_post_thumbnail() && 'traditional' !== $header_animation && 'zoomout' !== $header_animation ) { ?>
	<?php if ( 'parallax' === $header_animation ) {?>
	<div class="single-image" id="parallax" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>>
	<?php } else { ?>
	<div class="single-image" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>>
	<?php } ?>
	<div id="post-info-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
		<div class="container">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<header>
						<?php if( isset($mts_options['mts_single_meta_info_enable']['category']) == '1' ) { ?>
							<span class="thecategory"><?php mts_the_category('') ?></span>
						<?php } ?>
						<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
						<div class="post-info">
					   		<?php if( isset($mts_options['mts_single_meta_info_enable']['author-image']) == '1' ) { ?>
								 <span class="theauthorimage"><span><?php echo get_avatar( get_the_author_meta('email'), 34 ); ?></span></span>
							<?php } ?>
							<?php if( isset($mts_options['mts_single_meta_info_enable']['author']) == '1' ) { ?>
								<span class="theauthor"><span><?php the_author_posts_link(); ?></span></span>
							<?php } ?>
							<?php if( isset($mts_options['mts_single_meta_info_enable']['time']) == '1' ) { ?>
								<span class="thetime updated"><span><?php the_time( get_option( 'date_format' ) ); ?></span></span>
							<?php } ?>
							<?php if( isset($mts_options['mts_single_meta_info_enable']['comment']) == '1' ) { ?>
								<span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><i class="fa fa-comment"></i> <?php comments_number( '0', '1', '%' );?></a></span>
							<?php } ?>
						</div>
					</header>
			<?php endwhile; /* end loop */ ?>
		</div>
	</div>
</div>
<?php } ?>

<div id="page" class="<?php mts_single_page_class(); ?>">
	<?php if ( 'zoomout' === $header_animation ) {?>
		 <?php if (mts_get_thumbnail_url()) : ?>
			<div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div></div>
		<?php endif; ?>
	<?php } ?>
	<article class="<?php mts_article_class(); ?>">
		<div id="content_box" >
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
					<?php
					// Single post parts ordering
					if ( isset( $mts_options['mts_single_post_layout'] ) && is_array( $mts_options['mts_single_post_layout'] ) && array_key_exists( 'enabled', $mts_options['mts_single_post_layout'] ) ) {
						$single_post_parts = $mts_options['mts_single_post_layout']['enabled'];
					} else {
						$single_post_parts = array( 'content' => 'content', 'related' => 'related', 'author' => 'author' );
					}
					foreach( $single_post_parts as $part => $label ) {
						switch ($part) {
							case 'content': ?>
								<div class="single_post">
									<?php
									if ( $mts_options['mts_breadcrumb'] == '1' ) {
										mts_the_breadcrumb();
									}
									?>
									<?php if ( !has_post_thumbnail() || 'traditional' === $header_animation || 'zoomout' === $header_animation ) { ?>
										<header>
											<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
											<div class="traditional-header">
												<div class="post-info">
											   		<?php if( isset($mts_options['mts_single_meta_info_enable']['author-image']) == '1' ) { ?>
														 <span class="theauthorimage"><span><?php echo get_avatar( get_the_author_meta('email'), 34 ); ?></span></span>
													<?php } ?>
													<?php if( isset($mts_options['mts_single_meta_info_enable']['author']) == '1' ) { ?>
														<span class="theauthor"><span><?php the_author_posts_link(); ?></span></span>
													<?php } ?>
													<?php if( isset($mts_options['mts_single_meta_info_enable']['time']) == '1' ) { ?>
														<span class="thetime updated"><span><?php the_time( get_option( 'date_format' ) ); ?></span></span>
													<?php } ?>
													<?php if( isset($mts_options['mts_single_meta_info_enable']['category']) == '1' ) { ?>
														<span class="thecategory"><?php mts_the_category(', ') ?></span>
													<?php } ?>
													<?php if( isset($mts_options['mts_single_meta_info_enable']['comment']) == '1' ) { ?>
														<span class="thecomment"><a href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><i class="fa fa-comment"></i> <?php comments_number( '0', '1', '%' );?></a></span>
													<?php } ?>
												</div>
											</div>
										</header><!--.headline_area-->
									<?php } ?>
									<div class="post-single-content box mark-links entry-content">
										<?php // Top Ad Code ?>
										<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
											<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
												<div class="topad">
													<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
												</div>
											<?php } ?>
										<?php } ?>

										<?php // Content ?>
										<div class="thecontent">
											<?php the_content(); ?>
										</div>

										<?php // Single Pagination ?>
										<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => '<i class="fa fa-angle-right"></i>', 'previouspagelink' => '<i class="fa fa-angle-left"></i>', 'pagelink' => '%','echo' => 1 )); ?>

										<?php // Bottom Ad Code ?>
										<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
											<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
												<div class="bottomad">
													<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
												</div>
											<?php } ?>
										<?php } ?>

										<?php // Bottom Social Share ?>
									</div><!--.post-single-content-->
								</div><!--.single_post-->
								<?php
							break;

							case 'social-share':
								?>
								<div class="social-share">
									<?php if($mts_options['mts_tags']) mts_the_tags('<div class="tags"></span>','') ?>
									<?php mts_social_buttons(); ?>
								</div>
								<?php
							break;

							case 'previous-next':
								$next_post = get_next_post();
								$previous_post = get_previous_post();
								if( $next_post || $previous_post ) : ?>
									<div class="single-prev-next">
										<?php if($previous_post) { ?>
											<div class="previous-post">
												<a class="previous-full-post" href="<?php echo esc_url( get_permalink($previous_post->ID) ); ?>" title="<?php echo $previous_post->post_title; ?>" <?php if(empty($next_post)) ?>>
													<div class="featured-thumbnail">
														<?php echo get_the_post_thumbnail( $previous_post->ID, 'bloggingbox-related' ); ?>
													</div>
													<header>
														<div class="prev"><i class="fa fa-caret-left"></i><?php _e('Previous Article', 'bloggingbox'); ?></div>
														<h3 class="title front-view-title"><?php echo $previous_post->post_title; ?></h3>
													</header>
												</a>
											</div>
										<?php } ?>
										<?php if($next_post) { ?>
											<div class="next-post">
												<a class="next-full-post" href="<?php echo esc_url( get_permalink($next_post->ID) ); ?>" title="<?php echo $next_post->post_title; ?>">
													<div class="featured-thumbnail">
														<?php echo get_the_post_thumbnail( $next_post->ID, 'bloggingbox-related' ); ?>
													</div>
													<header>
														<div class="next"><?php _e('Next Article', 'bloggingbox'); ?><i class="fa fa-caret-right"></i></div>
														<h3 class="title front-view-title"><?php echo $next_post->post_title; ?></h3>
													</header>
												</a>
											</div>
										<?php } ?>
									</div>
								<?php endif;
							break;

							case 'author': ?>
								<div class="postauthor">
									<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '90' );  } ?>
									<h5 class="vcard author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author_meta( 'display_name' ); ?></a></h5>
									<div class="author-description">
										<?php
										the_author_meta('description');
										$userID = get_current_user_id();
										$facebook = get_the_author_meta( 'facebook', $userID );
										$twitter = get_the_author_meta( 'twitter', $userID );
										$google = get_the_author_meta( 'google', $userID );
										$pinterest = get_the_author_meta( 'pinterest', $userID );
										$stumbleupon = get_the_author_meta( 'stumbleupon', $userID );
										$linkedin = get_the_author_meta( 'linkedin', $userID );

										if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($pinterest) || !empty($stumbleupon) || !empty($linkedin)){
											echo '<div class="author-social clearfix">';
												if(!empty($facebook)){
													echo '<a href="'.$facebook.'" class="facebook"><i class="fa fa-facebook"></i></a>';
												}
												if(!empty($twitter)){
													echo '<a href="'.$twitter.'" class="twitter"><i class="fa fa-twitter"></i></a>';
												}
												if(!empty($google)){
													echo '<a href="'.$google.'" class="google-plus"><i class="fa fa-google-plus"></i></a>';
												}
												if(!empty($pinterest)){
													echo '<a href="'.$pinterest.'" class="pinterest"><i class="fa fa-pinterest"></i></a>';
												}
												if(!empty($stumbleupon)){
													echo '<a href="'.$stumbleupon.'" class="stumble"><i class="fa fa-stumbleupon"></i></a>';
												}
												if(!empty($linkedin)){
													echo '<a href="'.$linkedin.'" class="linkedin"><i class="fa fa-linkedin"></i></a>';
												}
											echo '</div>';
										}
									?>
									</div>
								</div>
							<?php break;
						}
					}
					?>
				</div><!--.g post-->
				<?php comments_template( '', true ); ?>
			<?php endwhile; /* end loop */ ?>
		</div>
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
