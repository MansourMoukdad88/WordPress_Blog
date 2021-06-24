<?php
/**
 * The template for displaying the footer.
 */
$mts_options = get_option(MTS_THEME_NAME);

// default = 5
$first_footer_num  = empty($mts_options['mts_first_footer_num']) ? 5 : $mts_options['mts_first_footer_num']; ?>
	</div><!--#page-->
	<footer id="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
			<?php if ( is_active_sidebar( 'widget-subscribe' ) && is_home() ) { ?>
				<div class="mts-newsletter">
					<div class="container">
						<?php dynamic_sidebar( 'widget-subscribe' ); ?>
					</div>	
				</div>	
			<?php } ?>

			<div class="footer-upper">
				<div class="container">
					<div class="footer-upper-left">
						<?php if( $mts_options['mts_footer_logo_button'] == 1 ) { ?>
							<div class="footer-logo">
								<?php if ($mts_options['mts_footer_logo'] != '') {
									$logo_id = mts_get_image_id_from_url( $mts_options['mts_footer_logo'] );
									$logo_w_h = '';
									if ( $logo_id ) {
										$logo	= wp_get_attachment_image_src( $logo_id, 'full' );
										$logo_w_h = ' width="'.$logo[1].'" height="'.$logo[2].'"';
									} ?>						
									<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h4 id="logo" class="image-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_options['mts_footer_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php echo $logo_w_h; ?>></a>
										</h4><!-- END #logo -->
									<?php } else { ?>
										<h4 id="logo" class="image-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_options['mts_footer_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php echo $logo_w_h; ?>></a>
										</h4><!-- END #logo -->
									<?php } ?>
									
								<?php } else { ?>

									<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h4 id="logo" class="text-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
										</h1><!-- END #logo -->
									<?php } else { ?>
										<h4 id="logo" class="text-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
										</h4><!-- END #logo -->
									<?php } ?>

								<?php } ?>
							</div>	 
						<?php } ?>			 
							<!-- /footer-logo -->
						<?php if ( !empty($mts_options['mts_footer_social']) && is_array($mts_options['mts_footer_social']) && !empty($mts_options['mts_social_icon_head'])) { ?>
								<div class="footer-social">
									<?php foreach( $mts_options['mts_footer_social'] as $footer_icons ) : ?>
										<?php if( ! empty( $footer_icons['mts_footer_icon'] ) && isset( $footer_icons['mts_footer_icon'] ) && ! empty( $footer_icons['mts_footer_icon_link'] )) : ?>
											<a href="<?php print $footer_icons['mts_footer_icon_link'] ?>" class="footer-<?php print $footer_icons['mts_footer_icon'] ?>" target="_blank"><span class="fa fa-<?php print $footer_icons['mts_footer_icon'] ?>"></span></a>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php } ?>
					</div>		
					<!-- /footer-upper-left -->
					<?php if ( $mts_options['mts_readers'] == 1 ) : ?>
						<div class="readers">
							<span class="number"><?php echo number_format_i18n(absint($mts_options['mts_readers_count'])); ?></span>
							<span class="text"><?php echo $mts_options['mts_readers_text']; ?></span></div>
					<?php endif; ?>
				</div>
			</div>	
			<!-- /container -->
			<?php if ($mts_options['mts_first_footer']) : ?>
				<div class="footer-widgets first-footer-widgets widgets-num-<?php echo $first_footer_num; ?>">
					<div class="container">
						<?php
						for ( $i = 1; $i <= $first_footer_num; $i++ ) {
							$sidebar = ( $i == 1 ) ? 'footer-first' : 'footer-first-'.$i;
							$class = ( $i == $first_footer_num ) ? 'f-widget last f-widget-'.$i : 'f-widget f-widget-'.$i;
							?>
							<div class="<?php echo $class;?>">
								<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $sidebar ) ) : ?><?php endif; ?>
							</div>
							<?php
						} ?>
					</div><!--.container-->
				</div><!--.footer widgets-->
			<?php endif; ?>

			<div class="copyrights">
				<div class="container">
					<?php mts_copyrights_credit(); ?>
				</div>
			</div> 
	</footer><!--#site-footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>
