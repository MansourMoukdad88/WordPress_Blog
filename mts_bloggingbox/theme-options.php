<?php

defined('ABSPATH') or die;

/*
 *
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 *
 */
require_once( dirname( __FILE__ ) . '/options/options.php' );

/*
 *
 * Add support tab
 *
 */
if ( ! defined('MTS_THEME_WHITE_LABEL') || ! MTS_THEME_WHITE_LABEL ) {
	require_once( dirname( __FILE__ ) . '/options/support.php' );
	$mts_options_tab_support = MTS_Options_Tab_Support::get_instance();
}

/*
 *
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){

	//$sections = array();
	$sections[] = array(
		'title' => __('A Section added by hook', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.', 'bloggingbox' ) . '</p>',
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
		'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
		//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array()
	);

	return $sections;

}//function
//add_filter('nhp-opts-sections-twenty_eleven', 'add_another_section');


/*
 *
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){

	//$args['dev_mode'] = false;

	return $args;

}//function
//add_filter('nhp-opts-args-twenty_eleven', 'change_framework_args');

/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
	$args = array();

	//Set it to dev mode to view the class settings/info in the form - default is false
	$args['dev_mode'] = false;
	//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
	//$args['stylesheet_override'] = true;

	//Add HTML before the form
	//$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'bloggingbox' );

	if ( ! MTS_THEME_WHITE_LABEL ) {
		//Setup custom links in the footer for share icons
		$args['share_icons']['twitter'] = array(
			'link' => 'http://twitter.com/mythemeshopteam',
			'title' => __( 'Follow Us on Twitter', 'bloggingbox' ),
			'img' => 'fa fa-twitter-square'
		);
		$args['share_icons']['facebook'] = array(
			'link' => 'http://www.facebook.com/mythemeshop',
			'title' => __( 'Like us on Facebook', 'bloggingbox' ),
			'img' => 'fa fa-facebook-square'
		);
	}

	//Choose to disable the import/export feature
	//$args['show_import_export'] = false;

	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
	$args['opt_name'] = MTS_THEME_NAME;

	//Custom menu icon
	//$args['menu_icon'] = '';

	//Custom menu title for options page - default is "Options"
	$args['menu_title'] = __('Theme Options', 'bloggingbox' );

	//Custom Page Title for options page - default is "Options"
	$args['page_title'] = __('Theme Options', 'bloggingbox' );

	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
	$args['page_slug'] = 'theme_options';

	//Custom page capability - default is set to "manage_options"
	//$args['page_cap'] = 'manage_options';

	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	//$args['page_type'] = 'submenu';

	//parent menu - default is set to "themes.php" (Appearance)
	//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	//$args['page_parent'] = 'themes.php';

	//custom page location - default 100 - must be unique or will override other items
	$args['page_position'] = 62;

	//Custom page icon class (used to override the page icon next to heading)
	//$args['page_icon'] = 'icon-themes';

	if ( ! MTS_THEME_WHITE_LABEL ) {
		//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition
		$args['help_tabs'][] = array(
			'id' => 'nhp-opts-1',
			'title' => __('Support', 'bloggingbox' ),
			'content' => '<p>' . sprintf( __('If you are facing any problem with our theme or theme option panel, head over to our %s.', 'bloggingbox' ), '<a href="http://community.mythemeshop.com/">'. __( 'Support Forums', 'bloggingbox' ) . '</a>' ) . '</p>'
		);
		$args['help_tabs'][] = array(
			'id' => 'nhp-opts-2',
			'title' => __('Earn Money', 'bloggingbox' ),
			'content' => '<p>' . sprintf( __('Earn 70%% commision on every sale by refering your friends and readers. Join our %s.', 'bloggingbox' ), '<a href="http://mythemeshop.com/affiliate-program/">' . __( 'Affiliate Program', 'bloggingbox' ) . '</a>' ) . '</p>'
		);
	}

	//Set the Help Sidebar for the options page - no sidebar by default
	//$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'bloggingbox' );

	$mts_patterns = array(
		'nobg' => array('img' => NHP_OPTIONS_URL.'img/patterns/nobg.png'),
		'pattern0' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern0.png'),
		'pattern1' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern1.png'),
		'pattern2' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern2.png'),
		'pattern3' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern3.png'),
		'pattern4' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern4.png'),
		'pattern5' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern5.png'),
		'pattern6' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern6.png'),
		'pattern7' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern7.png'),
		'pattern8' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern8.png'),
		'pattern9' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern9.png'),
		'pattern10' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern10.png'),
		'pattern11' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern11.png'),
		'pattern12' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern12.png'),
		'pattern13' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern13.png'),
		'pattern14' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern14.png'),
		'pattern15' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern15.png'),
		'pattern16' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern16.png'),
		'pattern17' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern17.png'),
		'pattern18' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern18.png'),
		'pattern19' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern19.png'),
		'pattern20' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern20.png'),
		'pattern21' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern21.png'),
		'pattern22' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern22.png'),
		'pattern23' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern23.png'),
		'pattern24' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern24.png'),
		'pattern25' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern25.png'),
		'pattern26' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern26.png'),
		'pattern27' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern27.png'),
		'pattern28' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern28.png'),
		'pattern29' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern29.png'),
		'pattern30' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern30.png'),
		'pattern31' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern31.png'),
		'pattern32' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern32.png'),
		'pattern33' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern33.png'),
		'pattern34' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern34.png'),
		'pattern35' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern35.png'),
		'pattern36' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern36.png'),
		'pattern37' => array('img' => NHP_OPTIONS_URL.'img/patterns/pattern37.png'),
		'hbg' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg.png'),
		'hbg2' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg2.png'),
		'hbg3' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg3.png'),
		'hbg4' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg4.png'),
		'hbg5' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg5.png'),
		'hbg6' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg6.png'),
		'hbg7' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg7.png'),
		'hbg8' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg8.png'),
		'hbg9' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg9.png'),
		'hbg10' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg10.png'),
		'hbg11' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg11.png'),
		'hbg12' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg12.png'),
		'hbg13' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg13.png'),
		'hbg14' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg14.png'),
		'hbg15' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg15.png'),
		'hbg16' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg16.png'),
		'hbg17' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg17.png'),
		'hbg18' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg18.png'),
		'hbg19' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg19.png'),
		'hbg20' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg20.png'),
		'hbg21' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg21.png'),
		'hbg22' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg22.png'),
		'hbg23' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg23.png'),
		'hbg24' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg24.png'),
		'hbg25' => array('img' => NHP_OPTIONS_URL.'img/patterns/hbg25.png')
	);

	$sections = array();

	$sections[] = array(
		'icon' => 'fa fa-cogs',
		'title' => __('General Settings', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('This tab contains common setting options which will be applied to the whole theme.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_logo',
				'type' => 'upload',
				'title' => __('Logo Image', 'bloggingbox' ),
				'sub_desc' => __('Upload your logo using the Upload Button or insert image URL. <i>Recommended Size <strong>86x40px</strong></i>', 'bloggingbox' ),
				'return' => 'id'
			),
			array(
				'id' => 'mts_favicon',
				'type' => 'upload',
				'title' => __('Favicon', 'bloggingbox' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s favicon.', 'bloggingbox' ), '<strong>32 x 32 px</strong>' ),
				'return' => 'id'
			),
			array(
				'id' => 'mts_touch_icon',
				'type' => 'upload',
				'title' => __('Touch icon', 'bloggingbox' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s touch icon for iOS 2.0+ and Android 2.1+ devices.', 'bloggingbox' ), '<strong>152 x 152 px</strong>' ),
				'return' => 'id'
			),
			array(
				'id' => 'mts_metro_icon',
				'type' => 'upload',
				'title' => __('Metro icon', 'bloggingbox' ),
				'sub_desc' => sprintf( __('Upload a %s image that will represent your website\'s IE 10 Metro tile icon.', 'bloggingbox' ), '<strong>144 x 144 px</strong>' ),
				'return' => 'id'
			),
			array(
				'id' => 'mts_twitter_username',
				'type' => 'text',
				'title' => __('Twitter Username', 'bloggingbox' ),
				'sub_desc' => __('Enter your Username here.', 'bloggingbox' ),
			),
			array(
				'id' => 'mts_feedburner',
				'type' => 'text',
				'title' => __('FeedBurner URL', 'bloggingbox' ),
				'sub_desc' => sprintf( __('Enter your FeedBurner\'s URL here, ex: %s and your main feed (http://example.com/feed) will get redirected to the FeedBurner ID entered here.)', 'bloggingbox' ), '<strong>http://feeds.feedburner.com/mythemeshop</strong>' ),
				'validate' => 'url'
			),
			array(
				'id' => 'mts_header_code',
				'type' => 'textarea',
				'title' => __('Header Code', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Enter the code which you need to place <strong>before closing &lt;/head&gt; tag</strong>. (ex: Google Webmaster Tools verification, Bing Webmaster Center, BuySellAds Script, Alexa verification etc.)', 'bloggingbox' ), array( 'strong' => array() ) )
			),
			array(
				'id' => 'mts_analytics_code',
				'type' => 'textarea',
				'title' => __('Footer Code', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Enter the codes which you need to place in your footer. <strong>(ex: Google Analytics, Clicky, STATCOUNTER, Woopra, Histats, etc.)</strong>.', 'bloggingbox' ), array( 'strong' => array() ) )
			),
			array(
				'id' => 'mts_pagenavigation_type',
				'type' => 'radio',
				'title' => __('Pagination Type', 'bloggingbox' ),
				'sub_desc' => __('Select pagination type.', 'bloggingbox' ),
				'options' => array(
					'0'=> __('Next / Previous', 'bloggingbox' ),
					'1' => __('Default (Numbered (1 2 3 4...)', 'bloggingbox' ),
					'2' => __( 'AJAX (Load More Button)', 'bloggingbox' ),
					'3' => __( 'AJAX (Auto Infinite Scroll)', 'bloggingbox' )
				),
				'std' => '1'
			),
			array(
				'id' => 'mts_ajax_search',
				'type' => 'button_set',
				'title' => __('AJAX Quick search', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Enable or disable search results appearing instantly below the search form', 'bloggingbox' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_responsive',
				'type' => 'button_set',
				'title' => __('Responsiveness', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('MyThemeShop themes are responsive, which means they adapt to tablet and mobile devices, ensuring that your content is always displayed beautifully no matter what device visitors are using. Enable or disable responsiveness using this option.', 'bloggingbox' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_rtl',
				'type' => 'button_set',
				'title' => __('Right To Left Language Support', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Enable this option for right-to-left sites.', 'bloggingbox' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_shop_products',
				'type' => 'text',
				'title' => __('No. of Products', 'bloggingbox' ),
				'sub_desc' => __('Enter the total number of products which you want to show on shop page (WooCommerce plugin must be enabled).', 'bloggingbox' ),
				'validate' => 'numeric',
				'std' => '9',
				'class' => 'small-text'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-bolt',
		'title' => __('Performance', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('This tab contains performance-related options which can help speed up your website.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_prefetching',
				'type' => 'button_set',
				'title' => __('Prefetching', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Enable or disable prefetching. If user is on homepage, then single page will load faster and if user is on single page, homepage will load faster in modern browsers.', 'bloggingbox' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_lazy_load',
				'type' => 'button_set_hide_below',
				'title' => __('Theme\'s Lazy Loading', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Delay loading of images outside of viewport, until user scrolls to them.', 'bloggingbox' ),
				'std' => '0',
				'args' => array('hide' => 2)
				),
				array(
					'id' => 'mts_lazy_load_thumbs',
					'type' => 'button_set',
					'title' => __('Lazy load featured images', 'bloggingbox' ),
					'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
					'sub_desc' => __('Enable or disable Lazy load of featured images across site.', 'bloggingbox' ),
					'std' => '0'
				),
				array(
					'id' => 'mts_lazy_load_content',
					'type' => 'button_set',
					'title' => __('Lazy load post content images', 'bloggingbox' ),
					'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
					'sub_desc' => __('Enable or disable Lazy load of images inside post/page content.', 'bloggingbox' ),
					'std' => '0'
			),
			array(
				'id' => 'mts_async_js',
				'type' => 'button_set',
				'title' => __('Async JavaScript', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => sprintf( __('Add %s attribute to script tags to improve page download speed.', 'bloggingbox' ), '<code>async</code>' ),
				'std' => '1',
			),
			array(
				'id' => 'mts_remove_ver_params',
				'type' => 'button_set',
				'title' => __('Remove ver parameters', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => sprintf( __('Remove %s parameter from CSS and JS file calls. It may improve speed in some browsers which do not cache files having the parameter.', 'bloggingbox' ), '<code>ver</code>' ),
				'std' => '1',
			),
			array(
				'id' => 'mts_optimize_wc',
				'type' => 'button_set',
				'title' => __('Optimize WooCommerce scripts', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Load WooCommerce scripts and styles only on WooCommerce pages (WooCommerce plugin must be enabled).', 'bloggingbox' ),
				'std' => '1'
			),
			'cache_message' => array(
				'id' => 'mts_cache_message',
				'type' => 'info',
				'title' => __('Use Cache', 'bloggingbox' ),
				// Translators: %1$s = popup link to W3 Total Cache, %2$s = popup link to WP Super Cache
				'desc' => sprintf(
					__('A cache plugin can increase page download speed dramatically. We recommend using %1$s or %2$s.', 'bloggingbox' ),
					'<a href="https://community.mythemeshop.com/tutorials/article/8-make-your-website-load-faster-using-w3-total-cache-plugin/" target="_blank" title="W3 Total Cache">W3 Total Cache</a>',
					'<a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-super-cache&TB_iframe=true&width=772&height=574' ).'" class="thickbox" title="WP Super Cache">WP Super Cache</a>'
				),
			),
		)
	);

	// Hide cache message on multisite or if a chache plugin is active already
	if ( is_multisite() || strstr( join( ';', get_option( 'active_plugins' ) ), 'cache' ) ) {
		unset( $sections[1]['fields']['cache_message'] );
	}

	$sections[] = array(
		'icon' => 'fa fa-adjust',
		'title' => __('Styling Options', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('Control the visual appearance of your theme, such as colors, layout and patterns, from here.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_color_scheme',
				'type' => 'color',
				'title' => __('Primary Color', 'bloggingbox' ),
				'sub_desc' => __('The theme comes with unlimited color schemes for your theme\'s styling.', 'bloggingbox' ),
				'std' => '#00bcd4'
			),
			array(
				'id' => 'mts_color_scheme2',
				'type' => 'color',
				'title' => __('Secondary Color', 'bloggingbox' ),
				'sub_desc' => __('The theme comes with unlimited color schemes for your theme\'s styling.', 'bloggingbox' ),
				'std' => '#f44336'
			),
			array(
				'id' => 'mts_layout',
				'type' => 'radio_img',
				'title' => __('Layout Style', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Choose the <strong>default sidebar position</strong> for your site. The position of the sidebar for individual posts can be set in the post editor.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'options' => array(
					'cslayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/cs.png'),
					'sclayout' => array('img' => NHP_OPTIONS_URL.'img/layouts/sc.png')
				),
				'std' => 'cslayout'
			),
			array(
				'id' => 'mts_background',
				'type' => 'background',
				'title' => __('Site Background', 'bloggingbox' ),
				'sub_desc' => __('Set background color, pattern and image from here.', 'bloggingbox' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	=> array(),
					'size'		=> array(),
					'gradient'	=> '',
					'parallax'	=> array(),
				),
				'std' => array(
					'color'		 => '#eeeeee',
					'use'		 => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	=> 'left top',
					'size'		=> 'cover',
					'gradient'	=> array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	=> '0',
				)
			),
			array(
				'id' => 'mts_custom_css',
				'type' => 'textarea',
				'title' => __('Custom CSS', 'bloggingbox' ),
				'sub_desc' => __('You can enter custom CSS code here to further customize your theme. This will override the default CSS used on your site.', 'bloggingbox' )
			),
			array(
				'id' => 'mts_lightbox',
				'type' => 'button_set',
				'title' => __('Lightbox', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('A lightbox is a stylized pop-up that allows your visitors to view larger versions of images without leaving the current page. You can enable or disable the lightbox here.', 'bloggingbox' ),
				'std' => '0'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-credit-card',
		'title' => __('Header', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of header section.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_sticky_nav',
				'type' => 'button_set',
				'title' => __('Floating Navigation Menu', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => sprintf( __('Use this button to enable %s.', 'bloggingbox' ), '<strong>' . __('Floating Navigation Menu', 'bloggingbox' ) . '</strong>' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_show_primary_nav',
				'type' => 'button_set',
				'title' => __('Show Primary Menu', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => sprintf( __('Use this button to enable %s.', 'bloggingbox' ), '<strong>' . __( 'Primary Navigation Menu', 'bloggingbox' ) . '</strong>' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_header_cart',
				'type' => 'button_set',
				'title' => __('Show Cart Button', 'bloggingbox'),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => wp_kses( __('Use this button to Show or Hide the <strong>Cart button</strong> in the header. Note: WooCommerce plugin required.', 'bloggingbox'), array( 'strong' => array() ) ),
				'std' => '1'
			),
			array(
				'id' => 'mts_header_search',
				'type' => 'button_set',
				'title' => __('Header Search Form', 'bloggingbox'),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => wp_kses( __('Use this button to Show or Hide the <strong>Search Form</strong> in the header.', 'bloggingbox'), array( 'strong' => array() ) ),
				'std' => '1'
			),
			array(
				'id' => 'mts_header_section2',
				'type' => 'button_set',
				'title' => __('Show Logo', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => wp_kses( __('Use this button to Show or Hide the <strong>Logo</strong> completely.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'std' => '1'
			),
			array(
				'id' => 'mts_header_layouts',
				'type' => 'radio_img',
				'title' => __('Header Layout', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Choose <strong>Header Layouts</strong> form here..', 'bloggingbox' ), array( 'strong' => array() ) ),
				'options' => array(
					'default' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-default.jpg'),
					'traditional' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-traditional.jpg'),
					'traditional-2' => array('img' => NHP_OPTIONS_URL.'img/layouts/header-traditional-2.jpg')
				),
				'std' => 'default',
				'reset_at_version' => '1.1'
			),
			array(
				'id' => 'mts_header_bg',
				'type' => 'background',
				'title' => __('Navigation Background', 'bloggingbox' ),
				'sub_desc' => __('Set navigation background color, pattern and image from here.', 'bloggingbox' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	=> array(),
					'size'		=> array(),
					'gradient'	=> '',
					'parallax'	=> array(),
				),
				'std' => array(
					'color'		 => '#00bcd4',
					'use'		 => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	=> 'left top',
					'size'		=> 'cover',
					'gradient'	=> array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	=> '0',
				)
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-table',
		'title' => __('Footer', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of Footer section.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_footer_bg',
				'type' => 'background',
				'title' => __('Footer Background', 'bloggingbox' ),
				'sub_desc' => __('Set footer background color, pattern and image from here.', 'bloggingbox' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	=> array(),
					'size'		=> array(),
					'gradient'	=> '',
					'parallax'	=> array(),
				),
				'std' => array(
					'color'		 => '#252525',
					'use'		 => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	=> 'left top',
					'size'		=> 'cover',
					'gradient'	=> array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	=> '0',
				)
			),
			array(
				'id' => 'mts_footer_logo_button',
				'type' => 'button_set_hide_below',
				'title' => __('Show Footer Logo','bloggingbox'),
				'sub_desc' => __('You can enable/disable Footer logo', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'std' => '1',
				'args' => array('hide' => '1')
			),
			array(
				'id' => 'mts_footer_logo',
				'type' => 'upload',
				'title' => __('Footer Logo Image', 'bloggingbox'),
				'sub_desc' => __('Upload your logo using the Upload Button or insert image URL. <i>Recommended Size <strong>86x40px</strong></i>', 'bloggingbox')
			),
			array(
				'id' => 'mts_social_icon_head',
				'type' => 'button_set_hide_below',
				'title' => __('Footer Social Icons','bloggingbox'),
				'sub_desc' => __('You can enable/disable social icon in footer', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'std' => '1',
				'args' => array('hide' => '1')
			),
			array(
			 	'id' => 'mts_footer_social',
			 	'title' => __('Add Social Icons','bloggingbox'),
			 	'sub_desc' => __( 'Add Social Media icons in footer.', 'bloggingbox' ),
			 	'type' => 'group',
			 	'groupname' => __('footer Icons','bloggingbox'), // Group name
			 	'subfields' => array(
					array(
						'id' => 'mts_footer_icon_title',
						'type' => 'text',
						'title' => __('Title', 'bloggingbox'),
					),
					array(
						'id' => 'mts_footer_icon',
						'type' => 'icon_select',
						'title' => __('Icon', 'bloggingbox')
					),
					array(
						'id' => 'mts_footer_icon_link',
						'type' => 'text',
						'title' => __('URL', 'bloggingbox'),
					),
					array(
						'id' => 'mts_footer_icon_color',
						'type' => 'color',
						'title' => __('Color', 'bloggingbox'),
						'std' => '#aaaaaa'
					),
					array(
						'id' => 'mts_footer_icon_hover_color',
						'type' => 'color',
						'title' => __('Hover Background Color', 'bloggingbox'),
						'std' => '#00bcd4'
					),
				),
				'std' => array(
					'facebook' => array(
						'group_title' => 'Facebook',
						'group_sort' => '1',
						'mts_footer_icon_title' => 'Facebook',
						'mts_footer_icon' => 'facebook',
						'mts_footer_icon_link' => '#',
						'mts_footer_icon_color' => '#aaaaaa',
						'mts_footer_icon_hover_color' => '#aaaaaa'
					),
					'twitter' => array(
						'group_title' => 'Twitter',
						'group_sort' => '2',
						'mts_footer_icon_title' => 'Twitter',
						'mts_footer_icon' => 'twitter',
						'mts_footer_icon_link' => '#',
						'mts_footer_icon_color' => '#aaaaaa',
						'mts_footer_icon_hover_color' => '#aaaaaa'
					),
					'gplus' => array(
						'group_title' => 'Google Plus',
						'group_sort' => '3',
						'mts_footer_icon_title' => 'Google Plus',
						'mts_footer_icon' => 'google-plus',
						'mts_footer_icon_link' => '#',
						'mts_footer_icon_color' => '#aaaaaa',
						'mts_footer_icon_hover_color' => '#aaaaaa'
					),
					'instagram' => array(
						'group_title' => 'Instagram',
						'group_sort' => '4',
						'mts_footer_icon_title' => 'Instagram',
						'mts_footer_icon' => 'instagram',
						'mts_footer_icon_link' => '#',
						'mts_footer_icon_color' => '#aaaaaa',
						'mts_footer_icon_hover_color' => '#aaaaaa'
					)
				)
			),
			array(
				'id' => 'mts_readers',
				'type' => 'button_set_hide_below',
				'title' => __('Readers Subscription', 'bloggingbox'),
				'sub_desc' => __('You can enable/disable readers subscribed', 'bloggingbox'),
				'options' => array('0' => 'Off','1' => 'On'),
				'std' => '1',
				'args' => array('hide' => 2)
				),
			array(
				'id' => 'mts_readers_count',
				'type' => 'text',
				'title' => __('Reader&#39;s count', 'bloggingbox'),
				'sub_desc' => __('Enter Readers count', 'bloggingbox'),
				'std' => '37676651',
				'args' => array( 'type' => 'number')
				),
			array(
				'id' => 'mts_readers_text',
				'type' => 'text',
				'title' => __("Reader's text", "bloggingbox"),
				'sub_desc' => __('Enter Readers text', 'bloggingbox'),
				'std' => 'Monthly Readers'
			),
			array(
				'id' => 'mts_first_footer',
				'type' => 'button_set_hide_below',
				'title' => __('Footer Widgets', 'bloggingbox' ),
				'sub_desc' => __('Enable or disable first footer with this option.', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'std' => '0'
				),
				array(
					'id' => 'mts_first_footer_num',
					'type' => 'button_set',
					'class' => 'green',
					'title' => __('Footer Widget Layout', 'bloggingbox' ),
					'sub_desc' => wp_kses( __('Choose the number of widget areas in the <strong>footer</strong>', 'bloggingbox' ), array( 'strong' => array() ) ),
					'options' => array(
						'3' => __( '3 Widgets', 'bloggingbox' ),
						'4' => __( '4 Widgets', 'bloggingbox' ),
						'5' => __( '5 Widgets', 'bloggingbox' ),
					),
					'std' => '5'
			),
			array(
				'id' => 'mts_copyrights',
				'type' => 'textarea',
				'title' => __('Copyrights Text', 'bloggingbox' ),
				'sub_desc' => __( 'You can change or remove our link from footer and use your own custom text.', 'bloggingbox' ) . ( MTS_THEME_WHITE_LABEL ? '' : wp_kses( __('(You can also use your affiliate link to <strong>earn 70% of sales</strong>. Ex: <a href="https://mythemeshop.com/go/aff/aff" target="_blank">https://mythemeshop.com/?ref=username</a>)', 'bloggingbox' ), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ) ),
				'std' => MTS_THEME_WHITE_LABEL ? null : sprintf( __( 'Theme by %s', 'bloggingbox' ), '<a href="http://mythemeshop.com/">MyThemeShop</a>' )
			),
			array(
				'id' => 'mts_move_to_top',
				'type' => 'button_set',
				'title' => __('Show Move To Top Button', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => sprintf( __('Use this button to show or hide move to top button.', 'bloggingbox' ), '<strong>' . __( 'Move To Top button', 'bloggingbox' ) . '</strong>' ),
				'std' => '1',
				'reset_at_version' => '1.1'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-home',
		'title' => __('Homepage', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('From here, you can control the elements of the homepage.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id'	 => 'mts_header_layout',
				'type'	 => 'layout2',
				'title'	=> __('Layout', 'bloggingbox' ),
				'sub_desc' => __('Customize the look of Slider and Category Section', 'bloggingbox' ),
				'options'  => array(
					'enabled'  => array(
						'slider'   => array(
							'label' 	=> __('Slider', 'bloggingbox' ),
							'subfields'	=> array()
						),
						'cat'   => array(
							'label' 	=> __('Featured Categories', 'bloggingbox' ),
							'subfields'	=> array()
						),
					),
					'disabled' => array()
				)
			),
			array(
				'id' => 'mts_featured_slider',
				'type' => 'button_set_hide_below',
				'title' => __('Homepage Slider', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => wp_kses( __('<strong>Enable or Disable</strong> homepage slider with this button. The slider will show recent articles from the selected categories.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'std' => '0',
				'args' => array('hide' => 6)
			),
			array(
				'id' => 'mts_featured_slider_cat',
				'type' => 'cats_multi_select',
				'title' => __('Slider Category(s)', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Select a category from the drop-down menu, latest articles from this category will be shown <strong>in the slider</strong>.', 'bloggingbox' ), array( 'strong' => array() ) ),
			),
			array(
				'id' => 'mts_featured_slider_num',
				'type' => 'text',
				'class' => 'small-text',
				'title' => __('Number of posts', 'bloggingbox' ),
				'sub_desc' => __('Enter the number of posts to show in the slider', 'bloggingbox' ),
				'std' => '4',
				'args' => array('type' => 'number')
			),
			array(
				'id' => 'mts_custom_slider',
				'type' => 'group',
				'title' => __('Custom Slider', 'bloggingbox' ),
				'sub_desc' => __('With this option you can set up a slider with custom image and text instead of the default slider automatically generated from your posts.<strong><i> Add atleast 4 custom slide </i></strong>', 'bloggingbox' ),
				'groupname' => __('Slider', 'bloggingbox' ), // Group name
				'subfields' =>
				array(
					array(
						'id' => 'mts_custom_slider_title',
						'type' => 'text',
						'title' => __('Title', 'bloggingbox' ),
						'sub_desc' => __('Title of the slide', 'bloggingbox' ),
					),
					array(
						'id' => 'mts_custom_slider_image',
						'type' => 'upload',
						'title' => __('Image', 'bloggingbox' ),
						'sub_desc' => __('Upload or select an image for this slide', 'bloggingbox' ),
						'return' => 'id'
					),
					array('id' => 'mts_custom_slider_link',
						'type' => 'text',
						'title' => __('Link', 'bloggingbox' ),
						'sub_desc' => __('Insert a link URL for the slide', 'bloggingbox' ),
						'std' => '#'
					),
				),
			),
			array(
				'id' => 'mts_featured_slider_controls',
				'type' => 'button_set',
				'title' => __('Slider Controls', 'bloggingbox' ),
				'options' => array('next-prev' => __('Arrows', 'bloggingbox' ), 'dots' => __('Dots', 'bloggingbox' ), 'both' => __('Both', 'bloggingbox' )),
				'sub_desc' => __('Set homepage featured slider controls from here.', 'bloggingbox' ),
				'std' => 'dots',
				'class' => 'green',
				'reset_at_version' => '1.1'
			),
			array(
				'id' => 'mts_featured_slider_autoplay',
				'type' => 'button_set_hide_below',
				'title' => __('Slider AutoPlay', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Enable/Disable Homepage featured slider autoplay with this button.', 'bloggingbox' ),
				'std' => '1',
				'args' => array('hide' => 1),
				'reset_at_version' => '1.1'
			),
			array(
				'id' => 'mts_featured_slider_speed',
				'type' => 'text',
				'class' => 'small-text',
				'title' => __('AutoPlay Speed ', 'bloggingbox' ),
				'sub_desc' => __('Enter the autoplay speed here. Autoplay speed will be in milisecond.', 'bloggingbox' ),
				'std' => '1000',
				'args' => array('type' => 'number'),
				'reset_at_version' => '1.1'
			),
			array(
				'id' => 'mts_featured_category_section',
				'type' => 'button_set_hide_below',
				'title' => __('Show Featured Categories Section','bloggingbox'),
				'sub_desc' => __('Featured category section will appear just after slider section on the homepage.', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'std' => '0',
				'args' => array('hide' => '2')
			),
			array(
			 	'id' => 'mts_featured_section',
			 	'title' => __('Featured Categories','bloggingbox'),
			 	'sub_desc' => __( 'Show Categories .', 'bloggingbox' ),
			 	'type' => 'group',
			 	'groupname' => __('Category','bloggingbox'), // Group name
			 	'subfields' => array(
					array(
						'id' => 'mts_featured_cats',
						'type' => 'cats_select',
						'title' => __('Category', 'bloggingbox' ),
						'sub_desc' => __('Select a category or the latest posts for this section', 'bloggingbox' ),
						'std' => 'latest',
						'args' => array('include_latest' => 1, 'hide_empty' => 0),
					),
					array(
						'id' => 'mts_featured_text_color',
						'type' => 'color',
						'title' => __('Text and Border Color', 'bloggingbox' ),
						'sub_desc' => __('change text and border color', 'bloggingbox' ),
						'std' => '#ffffff'
					),
					array(
						'id' => 'mts_featured_hover_color',
						'type' => 'color',
						'title' => __('Hover Background Color', 'bloggingbox' ),
						'sub_desc' => __('Choose hover background color for featured category.', 'bloggingbox' ),
						'std' => '#f44336'
					),
				),
			),
			array(
				'id' => 'mts_featured_bg',
				'type' => 'background',
				'title' => __('Featured Category Section Background', 'bloggingbox' ),
				'sub_desc' => __('Set background color, pattern or image from here for thte Featured Category section.', 'bloggingbox' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	=> array(),
					'size'		=> array(),
					'gradient'	=> '',
					'parallax'	=> array(),
				),
				'std' => array(
					'color'		 => '#00bcd4',
					'use'		 => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	=> 'left top',
					'size'		=> 'cover',
					'gradient'	=> array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	=> '0',
				)
			),
			array(
				'id'		=> 'mts_featured_categories',
				'type'	  => 'group',
				'title'	 => __('Latest Posts', 'bloggingbox'),
				'sub_desc'  => __('Select categories appearing on the homepage.', 'bloggingbox'),
				'groupname' => __('Section', 'bloggingbox'), // Group name
				'subfields' =>
					array(
						array(
							'id' => 'mts_featured_category',
							'type' => 'cats_select',
							'title' => __('Category', 'bloggingbox'),
							'sub_desc' => __('Select a category or the latest posts for this section', 'bloggingbox'),
							'std' => 'latest',
							'args' => array('include_latest' => 1, 'hide_empty' => 0),
							),
						array(
							'id' => 'mts_featured_category_layout',
							'type' => 'select',
							'title' => __('Posts Layout', 'bloggingbox'),
							'sub_desc' => __('Select the posts layout for this section.', 'bloggingbox'),
							'options' => array(
								'magazinepost' => 'Magazine Posts',
								'gridpost' => 'Grid Posts',
								'fullpost' => 'Full Posts'
							),
							'std' => 'magazinepost'
						),
						array(
							'id' => 'mts_featured_category_postsnum',
							'type' => 'text',
							'class' => 'small-text',
							'title' => __('Number of posts', 'bloggingbox'),
							'sub_desc' => __('Enter the number of posts to show in this section.', 'bloggingbox' ),
							'std' => '3',
							'args' => array('type' => 'number')
							),
					),
					'std' => array(
						'1' => array(
							'group_title' => '',
							'group_sort' => '1',
							'mts_featured_category' => 'latest',
							'mts_featured_category_postsnum' => get_option('posts_per_page')
						)
					)
				),
			array(
				'id' => 'mts_enable_tabs',
				'type' => 'button_set_hide_below',
				'title' => __('Show Tabs','bloggingbox'),
				'sub_desc' => __('Enable or disable homepage tabs. First tab will show latest posts and second tab will show most popular posts.', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'std' => '1',
				'args' => array('hide' => '3')
			),
			array(
				'id' => 'mts_popular_tab_posts_title',
				'type' => 'text',
				'title' => __('Popular Tab Title', 'bloggingbox'),
				'sub_desc' => __('Enter Popular tab title.', 'bloggingbox'),
				'std' => 'Most Popular'
			),
			array(
				'id' => 'mts_popular_tab_posts_num',
				'type' => 'text',
				'class' => 'small-text',
				'title' => __('No. of Popular Posts', 'bloggingbox'),
				'sub_desc' => __('Enter the number of posts to show in popular tab.', 'bloggingbox'),
				'std' => '6',
				'args' => array( 'type' => 'number')
			),
			array(
				'id' => 'mts_popular_tab_posts_limit',
				'type' => 'text',
				'class' => 'small-text',
				'title' => __('Popular Post limit (days)', 'bloggingbox'),
				'sub_desc' => __('Enter popular post limit. Enter \'0\' to disable the limit', 'bloggingbox'),
				'std' => '300',
				'args' => array( 'type' => 'number')
			),
			array(
				'id' => 'mts_home_meta_info_enable',
				'type' => 'multi_checkbox',
				'title' => __('HomePage Post Meta Info', 'bloggingbox' ),
				'sub_desc' => __('Organize how you want the post meta info to appear on the homepage', 'bloggingbox' ),
				'options' => array(
					'time'=> __('Enable Time/Date', 'bloggingbox' ),
					'category'=>__('Enable Category', 'bloggingbox' ),
					'author-image' => __('Enable Author Image', 'bloggingbox' ),
					'author' => __('Enable Author Name', 'bloggingbox' ),
					'comment' => __('Enable Comments', 'bloggingbox' ),
					'readmore' => __('Enable Readmore', 'bloggingbox' )
				),
				'std' => array(
					'time'=> '1',
					'category'=>'0',
					'author-image'=> '1',
					'author'=> '1',
					'comment'=> '1',
					'readmore'=> '1'
				)
			),
			array(
				'id' => 'mts_subscribe_widget_bg',
				'type' => 'background',
				'title' => __('Footer Newsletter Background', 'bloggingbox' ),
				'sub_desc' => __('Set newsletter background color, pattern and image from here.', 'bloggingbox' ),
				'options' => array(
					'color'		 => '',
					'image_pattern' => $mts_patterns,
					'image_upload'  => '',
					'repeat'		=> array(),
					'attachment'	=> array(),
					'position'	=> array(),
					'size'		=> array(),
					'gradient'	=> '',
					'parallax'	=> array(),
				),
				'std' => array(
					'color'		 => '#282828',
					'use'		 => 'pattern',
					'image_pattern' => 'nobg',
					'image_upload'  => '',
					'repeat'		=> 'repeat',
					'attachment'	=> 'scroll',
					'position'	=> 'left top',
					'size'		=> 'cover',
					'gradient'	=> array('from' => '#ffffff', 'to' => '#000000', 'direction' => 'horizontal' ),
					'parallax'	=> '0',
				),
				'reset_at_version' => '1.1'
			),
			array(
				'id' => 'mts_subscribe_widget_color',
				'type' => 'color',
				'title' => __('Footer Newsletter Color', 'bloggingbox' ),
				'sub_desc' => __('Change footer newsletter color from here.', 'bloggingbox' ),
				'std' => '#dddddd',
				'reset_at_version' => '1.1'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-file-text',
		'title' => __('Single Posts', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('From here, you can control the appearance and functionality of your single posts page.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id'	 => 'mts_single_post_layout',
				'type'	 => 'layout2',
				'title'	=> __('Single Post Layout', 'bloggingbox' ),
				'sub_desc' => __('Customize the look of single posts', 'bloggingbox' ),
				'options'  => array(
					'enabled'  => array(
						'content'   => array(
							'label' 	=> __('Post Content', 'bloggingbox' ),
							'subfields'	=> array()
						),
						'author'   => array(
							'label' 	=> __('Author Box', 'bloggingbox' ),
							'subfields'	=> array()
						),
						'social-share'   => array(
							'label' 	=> __('Social Share & Tags', 'bloggingbox' ),
							'subfields'	=> array(
								array(
									'id' => 'mts_tags',
									'type' => 'button_set',
									'title' => __('Tags', 'bloggingbox'),
									'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
									'sub_desc' => __('Use this button to show tags along with the Social Share icons.', 'bloggingbox'),
									'std' => '0'
								),
								array(
									'id' => 'mts_social_info',
									'type' => 'info',
									'desc' => __('You can control Social Share buttons from "Social Buttons" tab present in the options panel.', 'bloggingbox')
								),
							)
						),
						'previous-next'   => array(
							'label' 	=> __('Previous & Next Posts', 'bloggingbox' ),
							'subfields'	=> array()
						),
					),
					'disabled' => array()
				)
			),
			array(
				'id' => 'mts_single_meta_info_enable',
				'type' => 'multi_checkbox',
				'title' => __('Single Page Meta Info', 'bloggingbox' ),
				'sub_desc' => __('Organize how you want the post meta info to appear on the homepage', 'bloggingbox' ),
				'options' => array(
					'category'=>__('Enable Category', 'bloggingbox' ),
					'author-image' => __('Enable Author Image', 'bloggingbox' ),
					'author' => __('Enable Author Name', 'bloggingbox' ),
					'time'=> __('Enable Time/Date', 'bloggingbox' ),
					'comment' => __('Enable Comments', 'bloggingbox' )
				),
				'std' => array(
					'category'=>'1',
					'author-image'=> '1',
					'author'=> '1',
					'time'=> '1',
					'comment'=> '1'
				)
			),
			array(
				'id' => 'mts_breadcrumb',
				'type' => 'button_set',
				'title' => __('Breadcrumbs', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Breadcrumbs are a great way to make your site more user-friendly. You can enable them by checking this box.', 'bloggingbox' ),
				'std' => '0'
			),
			array(
				'id' => 'mts_like_dislike',
				'type' => 'button_set',
				'title' => __('Comments Like/Dislike', 'bloggingbox'),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Use this button to enable Like &amp; Dislike features for comments post.', 'bloggingbox'),
				'std' => '1'
			),
			array(
				'id' => 'mts_author_comment',
				'type' => 'button_set',
				'title' => __('Highlight Author Comment', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Use this button to highlight author comments.', 'bloggingbox' ),
				'std' => '1'
			),
			array(
				'id' => 'mts_comment_date',
				'type' => 'button_set',
				'title' => __('Date in Comments', 'bloggingbox' ),
				'options' => array( '0' => __( 'Off', 'bloggingbox' ), '1' => __( 'On', 'bloggingbox' ) ),
				'sub_desc' => __('Use this button to show the date for comments.', 'bloggingbox' ),
				'std' => '1'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-group',
		'title' => __('Social Buttons', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('Enable or disable social sharing buttons on single posts using these buttons.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_social_button_layout',
				'type' => 'radio_img',
				'title' => __('Social Sharing Buttons Layout', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Choose default or modern <strong>social sharing buttons</strong> layout.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'options' => array(
					'default' => array('img' => NHP_OPTIONS_URL.'img/layouts/default-social.jpg'),
					'modern' => array('img' => NHP_OPTIONS_URL.'img/layouts/modern-social.jpg')
				),
				'std' => 'default',
				'reset_at_version' => '1.0.7'
			),
			array(
				'id'   => 'mts_social_buttons',
				'type' => 'layout',
				'title'	=> __('Social Media Buttons', 'bloggingbox' ),
				'sub_desc' => __('Organize how you want the social sharing buttons to appear on single posts', 'bloggingbox' ),
				'options'  => array(
					'enabled'  => array(
						'facebookshare'   => __('Facebook Share', 'bloggingbox' ),
						'twitter'   => __('Twitter', 'bloggingbox' ),
						'gplus' => __('Google Plus', 'bloggingbox' ),
					),
					'disabled' => array(
						'facebook'  => __('Facebook Like', 'bloggingbox' ),
						'pinterest' => __('Pinterest', 'bloggingbox' ),
						'linkedin'  => __('LinkedIn', 'bloggingbox' ),
						'stumble'   => __('StumbleUpon', 'bloggingbox' ),
						'reddit'   => __('Reddit', 'bloggingbox' ),
					)
				),
				'std'  => array(
					'enabled'  => array(
						'facebookshare'   => __('Facebook Share', 'bloggingbox' ),
						'facebook'  => __('Facebook Like', 'bloggingbox' ),
						'twitter'   => __('Twitter', 'bloggingbox' ),
						'gplus' => __('Google Plus', 'bloggingbox' ),
						'pinterest' => __('Pinterest', 'bloggingbox' ),
					),
					'disabled' => array(
						'linkedin'  => __('LinkedIn', 'bloggingbox' ),
						'stumble'   => __('StumbleUpon', 'bloggingbox' ),
						'reddit'   => __('Reddit', 'bloggingbox' ),
					)
				),
				'reset_at_version' => '1.0.7'
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-bar-chart-o',
		'title' => __('Ad Management', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('Now, ad management is easy with our options panel. You can control everything from here, without using separate plugins.', 'bloggingbox' ) . '</p>',
		'fields' => array(
			array(
				'id' => 'mts_posttop_adcode',
				'type' => 'textarea',
				'title' => __('Below Post Title', 'bloggingbox' ),
				'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below your article title on single posts.', 'bloggingbox' )
			),
			array(
				'id' => 'mts_posttop_adcode_time',
				'type' => 'text',
				'title' => __('Show After X Days', 'bloggingbox' ),
				'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'bloggingbox' ),
				'validate' => 'numeric',
				'std' => '0',
				'class' => 'small-text',
				'args' => array('type' => 'number')
			),
			array(
				'id' => 'mts_postend_adcode',
				'type' => 'textarea',
				'title' => __('Below Post Content', 'bloggingbox' ),
				'sub_desc' => __('Paste your Adsense, BSA or other ad code here to show ads below the post content on single posts.', 'bloggingbox' )
			),
			array(
				'id' => 'mts_postend_adcode_time',
				'type' => 'text',
				'title' => __('Show After X Days', 'bloggingbox' ),
				'sub_desc' => __('Enter the number of days after which you want to show the Below Post Title Ad. Enter 0 to disable this feature.', 'bloggingbox' ),
				'validate' => 'numeric',
				'std' => '0',
				'class' => 'small-text',
				'args' => array('type' => 'number')
			),
		)
	);
	$sections[] = array(
		'icon' => 'fa fa-columns',
		'title' => __('Sidebars', 'bloggingbox' ),
		'desc' => '<p class="description">' . __('Now you have full control over the sidebars. Here you can manage sidebars and select one for each section of your site, or select a custom sidebar on a per-post basis in the post editor.', 'bloggingbox' ) . '<br></p>',
		'fields' => array(
			array(
				'id' => 'mts_custom_sidebars',
				'type'  => 'group', //doesn't need to be called for callback fields
				'title' => __('Custom Sidebars', 'bloggingbox' ),
				'sub_desc'  => wp_kses( __('Add custom sidebars. <strong style="font-weight: 800;">You need to save the changes to use the sidebars in the dropdowns below.</strong><br />You can add content to the sidebars in Appearance &gt; Widgets.', 'bloggingbox' ), array( 'strong' => array(), 'br' => array() ) ),
				'groupname' => __('Sidebar', 'bloggingbox' ), // Group name
				'subfields' =>
					array(
						array(
							'id' => 'mts_custom_sidebar_name',
							'type' => 'text',
							'title' => __('Name', 'bloggingbox' ),
							'sub_desc' => __('Example: Homepage Sidebar', 'bloggingbox' )
						),
						array(
							'id' => 'mts_custom_sidebar_id',
							'type' => 'text',
							'title' => __('ID', 'bloggingbox' ),
							'sub_desc' => __('Enter a unique ID for the sidebar. Use only alphanumeric characters, underscores (_) and dashes (-), eg. "sidebar-home"', 'bloggingbox' ),
							'std' => 'sidebar-'
						),
					),
			),
			array(
				'id' => 'mts_sidebar_for_home',
				'type' => 'sidebars_select',
				'title' => __('Homepage', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the homepage.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_post',
				'type' => 'sidebars_select',
				'title' => __('Single Post', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the single posts. If a post has a custom sidebar set, it will override this.', 'bloggingbox' ),
				'args' => array('exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_page',
				'type' => 'sidebars_select',
				'title' => __('Single Page', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the single pages. If a page has a custom sidebar set, it will override this.', 'bloggingbox' ),
				'args' => array('exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_archive',
				'type' => 'sidebars_select',
				'title' => __('Archive', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the archives. Specific archive sidebars will override this setting (see below).', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_category',
				'type' => 'sidebars_select',
				'title' => __('Category Archive', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the category archives.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_tag',
				'type' => 'sidebars_select',
				'title' => __('Tag Archive', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the tag archives.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_date',
				'type' => 'sidebars_select',
				'title' => __('Date Archive', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the date archives.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_author',
				'type' => 'sidebars_select',
				'title' => __('Author Archive', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the author archives.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_search',
				'type' => 'sidebars_select',
				'title' => __('Search', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the search results.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_notfound',
				'type' => 'sidebars_select',
				'title' => __('404 Error', 'bloggingbox' ),
				'sub_desc' => __('Select a sidebar for the 404 Not found pages.', 'bloggingbox' ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => ''
			),
			array(
				'id' => 'mts_sidebar_for_shop',
				'type' => 'sidebars_select',
				'title' => __('Shop Pages', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Select a sidebar for Shop main page and product archive pages (WooCommerce plugin must be enabled). Default is <strong>Shop Page Sidebar</strong>.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => 'shop-sidebar'
			),
			array(
				'id' => 'mts_sidebar_for_product',
				'type' => 'sidebars_select',
				'title' => __('Single Product', 'bloggingbox' ),
				'sub_desc' => wp_kses( __('Select a sidebar for single products (WooCommerce plugin must be enabled). Default is <strong>Single Product Sidebar</strong>.', 'bloggingbox' ), array( 'strong' => array() ) ),
				'args' => array('allow_nosidebar' => false, 'exclude' => array('sidebar', 'footer-first', 'footer-first-2', 'footer-first-3', 'footer-first-4', 'footer-first-5', 'footer-second', 'footer-second-2', 'footer-second-3', 'footer-second-4', 'widget-header','shop-sidebar', 'product-sidebar')),
				'std' => 'product-sidebar'
			),
		),
	);

	$sections[] = array(
		'icon' => 'fa fa-list-alt',
		'title' => __('Navigation', 'bloggingbox' ),
		'desc' => '<p class="description"><div class="controls">' . sprintf( __('Navigation settings can now be modified from the %s.', 'bloggingbox' ), '<a href="nav-menus.php"><b>' . __( 'Menus Section', 'bloggingbox' ) . '</b></a>' ) . '<br></div></p>'
	);


	$tabs = array();

	$args['presets'] = array();
	$args['show_translate'] = false;
	include('theme-presets.php');

	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

} //function

add_action('init', 'setup_framework_options', 0);

/*
 *
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 *
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){

	$error = false;
	$value =  'just testing';
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;

}//function

/*--------------------------------------------------------------------
 *
 * Default Font Settings
 *
 --------------------------------------------------------------------*/
if(function_exists('mts_register_typography')) {
	mts_register_typography( array(
		'logo_font' => array(
			'preview_text' => __('Logo Font','bloggingbox'),
			'preview_color' => 'dark',
			'font_family' => 'Roboto',
			'font_variant' => 'normal',
			'font_size' => '28px',
			'font_color' => '#ffffff',
			'css_selectors' => '#header h1, #header h2'
		),
   		'navigation_font' => array(
		  	'preview_text' => __('Navigation Font','bloggingbox'),
		  	'preview_color' => 'dark',
		  	'font_family' => 'Roboto',
			'font_variant' => '700',
			'font_size' => '14px',
			'font_color' => '#ffffff',
			'css_selectors' => '#primary-navigation a, #header .mts-cart .cart-contents',
			'additional_css' => 'text-transform: uppercase;'
		),
		'home_title_font' => array(
			'preview_text' => __( 'Home Article Title', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_size' => '30px',
			'font_variant' => 'normal',
			'font_color' => '#252525',
			'css_selectors' => '.latestPost .title a, .latestPost.grid-1 .title a'
		),
		'single_title_font' => array(
			'preview_text' => __( 'Single Article Title', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_size' => '40px',
			'font_variant' => '300',
			'font_color' => '#252525',
			'css_selectors' => '.single-title'
		),
		'post_info' => array(
			'preview_text' => __( 'Post Info', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_size' => '14px',
			'font_variant' => 'normal',
			'font_color' => '#959595',
			'css_selectors' => '.post-info, .post-info-upper, .post-excerpt, .ago, .widget .wpt_widget_content .wpt-postmeta, .widget .wpt_comment_content, .widget .wpt_excerpt'
		),
		'content_font' => array(
			'preview_text' => __( 'Content Font', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_size' => '16px',
			'font_variant' => 'normal',
			'font_color' => '#656565',
			'css_selectors' => 'body'
		),
		'sidebar_title_font' => array(
			'preview_text' => __( 'Sidebar Widget Title', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '20px',
			'font_color' => '#252525',
			'additional_css' => 'text-transform: uppercase;',
			'css_selectors' => '.widget h3, .widget h3 a'
		),
		'sidebar_heading' => array(
			'preview_text' => __( 'Sidebar Post Heading', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '15px',
			'font_color' => '#252525',
			'css_selectors' => '.widget .post-title, #sidebar .wp_review_tab_widget_content .entry-title, #sidebar .wpt_widget_content .entry-title'
		),
		'sidebar_font' => array(
			'preview_text' => __( 'Sidebar Font', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => 'normal',
			'font_size' => '14px',
			'font_color' => '#656565',
			'css_selectors' => '.widget'
		),
		'footer_title_font' => array(
			'preview_text' => __( 'Footer Widget Title', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '700',
			'font_size' => '14px',
			'font_color' => '#aaaaaa',
			'additional_css' => 'text-transform: uppercase;',
			'css_selectors' => '#site-footer .widget h3, #site-footer .widget h3 a'
		),
		'footer_heading' => array(
			'preview_text' => __( 'Footer Post Heading', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '15px',
			'font_color' => '#aaaaaa',
			'css_selectors' => '#site-footer .widget .post-title, #site-footer .wp_review_tab_widget_content .entry-title, #site-footer .wpt_widget_content .entry-title'
		),
		'footer_font' => array(
			'preview_text' => __( 'Footer Font', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => 'normal',
			'font_size' => '14px',
			'font_color' => '#aaaaaa',
			'css_selectors' => '#site-footer .widget'
		),
		'copyrights_font' => array(
			'preview_text' => __( 'Copyrights Font', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => 'normal',
			'font_size' => '14px',
			'font_color' => '#656565',
			'css_selectors' => '#copyright-note'
		),
		'readers_font' => array(
			'preview_text' => __( 'Footer Logo', 'bloggingbox' ),
			'preview_color' => 'dark',
			'font_family' => 'Roboto',
			'font_variant' => 'normal',
			'font_size' => '30px',
			'font_color' => '#ffffff',
			'css_selectors' => '.footer-upper, .footer-logo #logo'
		),
		'h1_headline' => array(
			'preview_text' => __( 'Content H1', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '38px',
			'font_color' => '#252525',
			'css_selectors' => 'h1'
		),
		'h2_headline' => array(
			'preview_text' => __( 'Content H2', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '34px',
			'font_color' => '#252525',
			'css_selectors' => 'h2'
		),
		'h3_headline' => array(
			'preview_text' => __( 'Content H3', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '30px',
			'font_color' => '#252525',
			'css_selectors' => 'h3'
		),
		'h4_headline' => array(
			'preview_text' => __( 'Content H4', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '26px',
			'font_color' => '#252525',
			'css_selectors' => 'h4'
		),
		'h5_headline' => array(
			'preview_text' => __( 'Content H5', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '22px',
			'font_color' => '#252525',
			'css_selectors' => 'h5'
		),
		'h6_headline' => array(
			'preview_text' => __( 'Content H6', 'bloggingbox' ),
			'preview_color' => 'light',
			'font_family' => 'Roboto',
			'font_variant' => '500',
			'font_size' => '18px',
			'font_color' => '#252525',
			'css_selectors' => 'h6'
		)
	));
}
