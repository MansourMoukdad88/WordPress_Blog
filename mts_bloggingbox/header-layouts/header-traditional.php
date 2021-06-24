<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<div id="header" class="header-traditional">								
	<div class="header-upper clearfix">	
		<div class="container">
			<div class="logo-wrap">
				<?php if ( $mts_options['mts_logo'] != '' && $mts_logo = wp_get_attachment_image_src( $mts_options['mts_logo'], 'full' ) ) { ?>
					<?php if ( is_front_page() || is_home() || is_404() ) { ?>
						<h1 id="logo" class="image-logo" itemprop="headline">
							<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>">
							</a>
						</h1><!-- END #logo -->
					<?php } else { ?>
						<h2 id="logo" class="image-logo" itemprop="headline">
							<a href="<?php echo esc_url( home_url() ); ?>">
								<img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>">
							</a>
						</h2><!-- END #logo -->
					<?php } ?>

				<?php } else { ?>

					<?php if ( is_front_page() || is_home() || is_404() ) { ?>
						<h1 id="logo" class="text-logo" itemprop="headline">
							<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
						</h1><!-- END #logo -->
					<?php } else { ?>
						<h2 id="logo" class="text-logo" itemprop="headline">
							<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
						</h2><!-- END #logo -->
					<?php } ?>
				<?php } ?>
			</div>
			<?php if(!empty($mts_options['mts_header_search'])) { ?>
				<div id="search-6" class="widget widget_search">
					<?php get_search_form(); ?>
				</div><!-- END #search-6 -->
			<?php } ?>
		</div>	
	</div>	
	<?php if( $mts_options['mts_sticky_nav'] == '1' ) { ?>
	<div id="catcher" class="clear" ></div>
	<div class="header-lower clearfix sticky-navigation">
	<?php } else { ?>
		<div class="header-lower clearfix">
	<?php } ?>
		<div class="container">
			<?php if ( $mts_options['mts_show_primary_nav'] == '1' ) { ?>
				<div id="primary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu', 'bloggingbox' ); ?></a>
					<?php if ( has_nav_menu( 'mobile' ) ) { ?>
						<nav class="navigation clearfix">
							<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>
								<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
							<?php } else { ?>
								<ul class="menu clearfix">
									<?php wp_list_categories('title_li='); ?>
								</ul>
							<?php } ?>
						</nav>
						<nav class="navigation mobile-only clearfix mobile-menu-wrapper">
							<?php wp_nav_menu( array( 'theme_location' => 'mobile', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
						</nav>
					<?php } else { ?>
						<nav class="navigation clearfix mobile-menu-wrapper">
							<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>
								<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
							<?php } else { ?>
								<ul class="menu clearfix">
									<?php wp_list_categories('title_li='); ?>
								</ul>
							<?php } ?>
						</nav>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if ( $mts_options['mts_header_cart'] == '1' ) mts_cart(); ?>
		</div>	
	</div>	
</div><!--.container-->