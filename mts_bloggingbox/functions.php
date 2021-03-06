<?php
/*-----------------------------------------------------------------------------------*/
/*	Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define( 'MTS_THEME_NAME', 'bloggingbox' );
define( 'MTS_THEME_VERSION', '1.2.8' );

require_once( get_theme_file_path( 'theme-options.php' ) );
if ( ! isset( $content_width ) ) {
	$content_width = 800; //article content width without padding
}

/*-----------------------------------------------------------------------------------*/
/*	Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option( MTS_THEME_NAME );

/**
 * Register supported theme features, image sizes and nav menus.
 * Also loads translated strings.
 */
function mts_after_setup_theme() {
	if ( ! defined( 'MTS_THEME_WHITE_LABEL' ) ) {
		define( 'MTS_THEME_WHITE_LABEL', false );
	}

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );

	load_theme_textdomain( 'bloggingbox', get_template_directory().'/lang' );

	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 385, 350, true );
	add_image_size( 'bloggingbox-featured', 385, 350, true ); //featured
	add_image_size( 'bloggingbox-featuredfull', 800, 500, true ); //featured full width
	add_image_size( 'bloggingbox-related', 400, 220, true ); //related
	add_image_size( 'bloggingbox-widgetthumb', 60, 60, true ); //widget
	add_image_size( 'bloggingbox-widgetfull', 300, 200, true ); //sidebar full width
	add_image_size( 'bloggingbox-slider', 300, 360, true ); //slider
	add_image_size( 'bloggingbox-sliderlarge', 515, 360, true ); //slider

	add_action( 'init', 'bloggingbox_wp_review_thumb_size', 11 );

   	function bloggingbox_wp_review_thumb_size() {
		add_image_size( 'wp_review_small', 60, 60, true );
		add_image_size( 'wp_review_large', 300, 200, true );
   	}

	register_nav_menus( array(
		'primary-menu' => __( 'Menu', 'bloggingbox' ),
		'mobile' => __( 'Mobile', 'bloggingbox' )
	) );

	if ( mts_is_wc_active() ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action('after_setup_theme', 'mts_after_setup_theme' );

/*
 * Disable theme updates from WordPress.org theme repository.
 * Check if MTS Connect plugin already does this.
 */
if ( !class_exists('mts_connection') ) {
	/**
	 * If wrong updates are already shown, delete transient so that we can run our workaround
	 */
	function mts_hide_themes_plugins() {
		if ( !is_admin() ) return;
		if ( false === get_site_transient( 'mts_wp_org_check_disabled' ) ) { // run only once
			delete_site_transient('update_themes' );
			delete_site_transient('update_plugins' );
			add_action('current_screen', 'mts_remove_themes_plugins_from_update' );
		}
	}
	add_action('init', 'mts_hide_themes_plugins');
	/**
	 * Hide mts themes/plugins.
	 *
	 * @param WP_Screen $screen
	 */
	function mts_remove_themes_plugins_from_update( $screen ) {
		$run_on_screens = array( 'themes', 'themes-network', 'plugins', 'plugins-network', 'update-core', 'network-update-core' );
		if ( in_array( $screen->base, $run_on_screens ) ) {
			//Themes
			if ( $themes_transient = get_site_transient( 'update_themes' ) ) {
				if ( property_exists( $themes_transient, 'response' ) && is_array( $themes_transient->response ) ) {
					foreach ( $themes_transient->response as $key => $value ) {
						$theme = wp_get_theme( $value['theme'] );
						$theme_uri = $theme->get( 'ThemeURI' );
						if ( 0 !== strpos( $theme_uri, 'mythemeshop.com' ) ) {
							unset( $themes_transient->response[$key] );
						}
					}
					set_site_transient( 'update_themes', $themes_transient );
				}
			}
			//Plugins
			if ( $plugins_transient = get_site_transient( 'update_plugins' ) ) {
				if ( property_exists( $plugins_transient, 'response' ) && is_array( $plugins_transient->response ) ) {
					foreach ( $plugins_transient->response as $key => $value ) {
						$plugin = get_plugin_data( WP_PLUGIN_DIR.'/'.$key, false, false );
						$plugin_uri = $plugin['PluginURI'];
						if ( 0 !== strpos( $plugin_uri, 'mythemeshop.com' ) ) {
							unset( $plugins_transient->response[$key] );
						}
					}
					set_site_transient( 'update_plugins', $plugins_transient );
				}
			}
			set_site_transient( 'mts_wp_org_check_disabled', time() );
		}
	}
	/**
	 * Delete `mts_wp_org_check_disabled` transient.
	 */
	function mts_clear_check_transient(){
		delete_site_transient( 'mts_wp_org_check_disabled');
	}
	add_action( 'load-themes.php', 'mts_clear_check_transient' );
	add_action( 'load-plugins.php', 'mts_clear_check_transient' );
	add_action( 'upgrader_process_complete', 'mts_clear_check_transient' );
}

// Disable auto-updating the theme.
function mts_disable_auto_update_theme( $update, $item ) {
	if ( isset( $item->slug ) && $item->slug == MTS_THEME_NAME ) {
		return false;
	}
	return $update;
}
add_filter( 'auto_update_theme', 'mts_disable_auto_update_theme', 10, 2 );

/**
 * Disable Google Typography plugin
 */
function mts_deactivate_google_typography_plugin() {
	if ( in_array( 'google-typography/google-typography.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		deactivate_plugins( 'google-typography/google-typography.php' );
	}
}
add_action( 'admin_init', 'mts_deactivate_google_typography_plugin' );

/**
 * Determines whether the WooCommerce plugin is active or not.
 * @return bool
 */
function mts_is_wc_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * MTS icons for use in nav menus and icon select option.
 *
 * @return array
 */
function mts_get_icons() {
	$icons = array(
		__( 'Web Application Icons', 'bloggingbox' ) => array(
			'address-book', 'address-book-o', 'address-card', 'address-card-o', 'adjust', 'american-sign-language-interpreting', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'asl-interpreting', 'assistive-listening-systems', 'asterisk', 'at', 'audio-description', 'automobile', 'balance-scale', 'ban', 'bank', 'bar-chart', 'bar-chart-o', 'barcode', 'bars', 'bath', 'bathtub', 'battery', 'battery-0', 'battery-1', 'battery-2', 'battery-3', 'battery-4', 'battery-empty', 'battery-full', 'battery-half', 'battery-quarter', 'battery-three-quarters', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'blind', 'bluetooth', 'bluetooth-b', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'braille', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'cab', 'calculator', 'calendar', 'calendar-check-o', 'calendar-minus-o', 'calendar-o', 'calendar-plus-o', 'calendar-times-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'clone', 'close', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'commenting', 'commenting-o', 'comments', 'comments-o', 'compass', 'copyright', 'creative-commons', 'credit-card', 'credit-card-alt', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'dashboard', 'database', 'deaf', 'deafness', 'desktop', 'diamond', 'dot-circle-o', 'download', 'drivers-license', 'drivers-license-o', 'edit', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-open', 'envelope-open-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'feed', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-movie-o', 'file-pdf-o', 'file-photo-o', 'file-picture-o', 'file-powerpoint-o', 'file-sound-o', 'file-video-o', 'file-word-o', 'file-zip-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flash', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gear', 'gears', 'gift', 'glass', 'globe', 'graduation-cap', 'group', 'hand-grab-o', 'hand-lizard-o', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hand-stop-o', 'handshake-o', 'hard-of-hearing', 'hashtag', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'hotel', 'hourglass', 'hourglass-1', 'hourglass-2', 'hourglass-3', 'hourglass-end', 'hourglass-half', 'hourglass-o', 'hourglass-start', 'i-cursor', 'id-badge', 'id-card', 'id-card-o', 'image', 'inbox', 'industry', 'info', 'info-circle', 'institution', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'legal', 'lemon-o', 'level-down', 'level-up', 'life-bouy', 'life-buoy', 'life-ring', 'life-saver', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'low-vision', 'magic', 'magnet', 'mail-forward', 'mail-reply', 'mail-reply-all', 'male', 'map', 'map-marker', 'map-o', 'map-pin', 'map-signs', 'meh-o', 'microchip', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'mobile-phone', 'money', 'moon-o', 'mortar-board', 'motorcycle', 'mouse-pointer', 'music', 'navicon', 'newspaper-o', 'object-group', 'object-ungroup', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'percent', 'phone', 'phone-square', 'photo', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'podcast', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'question-circle-o', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'registered', 'remove', 'reorder', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 's15', 'search', 'search-minus', 'search-plus', 'send', 'send-o', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-bag', 'shopping-basket', 'shopping-cart', 'shower', 'sign-in', 'sign-language', 'sign-out', 'signal', 'signing', 'sitemap', 'sliders', 'smile-o', 'snowflake-o', 'soccer-ball-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-down', 'sort-numeric-asc', 'sort-numeric-desc', 'sort-up', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-empty', 'star-half-full', 'star-half-o', 'star-o', 'sticky-note', 'sticky-note-o', 'street-view', 'suitcase', 'sun-o', 'support', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'television', 'terminal', 'thermometer', 'thermometer-0', 'thermometer-1', 'thermometer-2', 'thermometer-3', 'thermometer-4', 'thermometer-empty', 'thermometer-full', 'thermometer-half', 'thermometer-quarter', 'thermometer-three-quarters', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'times-rectangle', 'times-rectangle-o', 'tint', 'toggle-down', 'toggle-left', 'toggle-off', 'toggle-on', 'toggle-right', 'toggle-up', 'trademark', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'tv', 'umbrella', 'universal-access', 'university', 'unlock', 'unlock-alt', 'unsorted', 'upload', 'user', 'user-circle', 'user-circle-o', 'user-o', 'user-plus', 'user-secret', 'user-times', 'users', 'vcard', 'vcard-o', 'video-camera', 'volume-control-phone', 'volume-down', 'volume-off', 'volume-up', 'warning', 'wheelchair', 'wheelchair-alt', 'wifi', 'window-close', 'window-close-o', 'window-maximize', 'window-minimize', 'window-restore', 'wrench'
		),
		__( 'Accessibility Icons', 'bloggingbox' ) => array(
			'american-sign-language-interpreting', 'asl-interpreting', 'assistive-listening-systems', 'audio-description', 'blind', 'braille', 'cc', 'deaf', 'deafness', 'hard-of-hearing', 'low-vision', 'question-circle-o', 'sign-language', 'signing', 'tty', 'universal-access', 'volume-control-phone', 'wheelchair', 'wheelchair-alt'
		),
		__( 'Hand Icons', 'bloggingbox' ) => array(
			'hand-grab-o', 'hand-lizard-o', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hand-stop-o', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up'
		),
		__( 'Transportation Icons', 'bloggingbox' ) => array(
			'ambulance', 'automobile', 'bicycle', 'bus', 'cab', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair', 'wheelchair-alt'
		),
		__( 'Gender Icons', 'bloggingbox' ) => array(
			'genderless', 'intersex', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars'
		),
		__( 'File Type Icons', 'bloggingbox' ) => array(
			'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-movie-o', 'file-o', 'file-pdf-o', 'file-photo-o', 'file-picture-o', 'file-powerpoint-o', 'file-sound-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o', 'file-zip-o'
		),
		__( 'Spinner Icons', 'bloggingbox' ) => array(
			'circle-o-notch', 'cog', 'gear', 'refresh', 'spinner'
		),
		__( 'Form Control Icons', 'bloggingbox' ) => array(
			'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
		),
		__( 'Payment Icons', 'bloggingbox' ) => array(
			'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'credit-card-alt', 'google-wallet', 'paypal'
		),
		__( 'Chart Icons', 'bloggingbox' ) => array(
			'area-chart', 'bar-chart', 'bar-chart-o', 'line-chart', 'pie-chart'
		),
		__( 'Currency Icons', 'bloggingbox' ) => array(
			'bitcoin', 'btc', 'cny', 'dollar', 'eur', 'euro', 'gbp', 'gg', 'gg-circle', 'ils', 'inr', 'jpy', 'krw', 'money', 'rmb', 'rouble', 'rub', 'ruble', 'rupee', 'shekel', 'sheqel', 'try', 'turkish-lira', 'usd', 'won', 'yen'
		),
		__( 'Text Editor Icons', 'bloggingbox' ) => array(
			'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain', 'chain-broken', 'clipboard', 'columns', 'copy', 'cut', 'dedent', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'paste', 'repeat', 'rotate-left', 'rotate-right', 'save', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo', 'unlink'
		),
		__( 'Directional Icons', 'bloggingbox' ) => array(
			'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'exchange', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up', 'toggle-down', 'toggle-left', 'toggle-right', 'toggle-up'
		),
		__( 'Video Player Icons', 'bloggingbox' ) => array(
			'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'pause-circle', 'pause-circle-o', 'play', 'play-circle', 'play-circle-o', 'random', 'step-backward', 'step-forward', 'stop', 'stop-circle', 'stop-circle-o', 'youtube-play'
		),
		__( 'Brand Icons', 'bloggingbox' ) => array(
			'500px', 'adn', 'amazon', 'android', 'angellist', 'apple', 'bandcamp', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'bitcoin', 'black-tie', 'bluetooth', 'bluetooth-b', 'btc', 'buysellads', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'chrome', 'codepen', 'codiepie', 'connectdevelop', 'contao', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'edge', 'eercast', 'empire', 'envira', 'etsy', 'expeditedssl', 'fa', 'facebook', 'facebook-f', 'facebook-official', 'facebook-square', 'firefox', 'first-order', 'flickr', 'font-awesome', 'fonticons', 'fort-awesome', 'forumbee', 'foursquare', 'free-code-camp', 'ge', 'get-pocket', 'gg', 'gg-circle', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'gitlab', 'gittip', 'glide', 'glide-g', 'google', 'google-plus', 'google-plus-circle', 'google-plus-official', 'google-plus-square', 'google-wallet', 'gratipay', 'grav', 'hacker-news', 'houzz', 'html5', 'imdb', 'instagram', 'internet-explorer', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linode', 'linux', 'maxcdn', 'meanpath', 'medium', 'meetup', 'mixcloud', 'modx', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid', 'opera', 'optin-monster', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pied-piper-pp', 'pinterest', 'pinterest-p', 'pinterest-square', 'product-hunt', 'qq', 'quora', 'ra', 'ravelry', 'rebel', 'reddit', 'reddit-alien', 'reddit-square', 'renren', 'resistance', 'safari', 'scribd', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'superpowers', 'telegram', 'tencent-weibo', 'themeisle', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'usb', 'viacoin', 'viadeo', 'viadeo-square', 'vimeo', 'vimeo-square', 'vine', 'vk', 'wechat', 'weibo', 'weixin', 'whatsapp', 'wikipedia-w', 'windows', 'wordpress', 'wpbeginner', 'wpexplorer', 'wpforms', 'xing', 'xing-square', 'y-combinator', 'y-combinator-square', 'yahoo', 'yc', 'yc-square', 'yelp', 'yoast', 'youtube', 'youtube-play', 'youtube-square'
		),
		__( 'Medical Icons', 'bloggingbox' ) => array(
			'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair', 'wheelchair-alt'
		)
	);

	return $icons;
}


/**
 * Get the current post's thumbnail URL.
 *
 * @param string $size
 *
 * @return string
 */
if( !function_exists('mts_get_thumbnail_url')){
	function mts_get_thumbnail_url( $size = 'full' ) {
		$post_id = get_the_ID() ;
		if (has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			return $image[0];
		}

		// use first attached image
		$images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
		if (!empty($images)) {
			$image = reset($images);
			$image_data = wp_get_attachment_image_src( $image->ID, $size );
			return $image_data[0];
		}

		// use no preview fallback
		if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
			return get_template_directory_uri().'/images/nothumb-'.$size.'.png';
		}

		return '';
	}
}

/**
 * Create and show column for featured in portfolio items list admin page.
 * @param $post_ID
 *
 * @return string url
 */
if( !function_exists('mts_get_featured_image')){
	function mts_get_featured_image($post_ID) {
		$post_thumbnail_id = get_post_thumbnail_id($post_ID);
		if ($post_thumbnail_id) {
			$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'bloggingbox-widgetfull');
			return $post_thumbnail_img[0];
		}
	}
}
/**
 * Adds a `Featured Image` column header in the item list admin page.
 *
 * @param array $defaults
 *
 * @return array
 */
function mts_columns_head($defaults) {
	if (get_post_type() == 'post') {
		$defaults['featured_image'] = __('Featured Image', 'bloggingbox' );
	}

	return $defaults;
}
add_filter('manage_posts_columns', 'mts_columns_head');

/**
 * Adds a `Featured Image` column row value in the item list admin page.
 *
 * @param string $column_name The name of the column to display.
 * @param int $post_ID The ID of the current post.
 */
function mts_columns_content($column_name, $post_ID) {
	if ($column_name == 'featured_image') {
		$post_featured_image = mts_get_featured_image($post_ID);
		if ($post_featured_image) {
			echo '<img width="150" height="100" src="' . esc_url( $post_featured_image ) . '" />';
		}
	}
}
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);

/**
 * Admin styles
 */
function mts_columns_css() {
	echo '<style type="text/css">.posts .column-featured_image img { max-width: 100%; height: auto }</style>';
}
add_action( 'admin_print_styles', 'mts_columns_css' );

/**
 * Change the HTML markup of the post thumbnail.
 *
 * @param string $html
 * @param int $post_id
 * @param string $post_image_id
 * @param int $size
 * @param string $attr
 *
 * @return string
 */
function mts_post_image_html( $html, $post_id, $post_image_id, $size, $attr ) {
	if ( has_post_thumbnail( $post_id ) || 'shop_thumbnail' === $size )
		return $html;

	// use first attached image
	$images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
	if (!empty($images)) {
		$image = reset($images);
		return wp_get_attachment_image( $image->ID, $size, false, $attr );
	}

	// use no preview fallback
	if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
		$placeholder = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
		$mts_options = get_option( MTS_THEME_NAME );
		if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
			$placeholder_src = '';
			$layzr_attr = ' data-layzr="'.esc_attr( $placeholder ).'"';
		} else {
			$placeholder_src = $placeholder;
			$layzr_attr = '';
		}

		$placeholder_classs = 'attachment-'.$size.' wp-post-image';
		return '<img src="'.esc_url( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'"'.$layzr_attr.'>';
	}

	return '';
}
add_filter( 'post_thumbnail_html', 'mts_post_image_html', 10, 5 );

/**
 * Add data-layzr attribute to featured image ( for lazy load )
 *
 * @param array $attr
 * @param WP_Post $attachment
 * @param string|array $size
 *
 * @return array
 */
function mts_image_lazy_load_attr( $attr, $attachment, $size ) {
	if ( is_admin() || is_feed() ) return $attr;
	if ( is_single() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) return $attr;
	$mts_options = get_option( MTS_THEME_NAME );
	if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
		$attr['data-layzr'] = $attr['src'];
		$attr['src'] = '';
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-layzr-srcset'] = $attr['srcset'];
			$attr['srcset'] = '';
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mts_image_lazy_load_attr', 10, 3 );

/**
 * Add data-layzr attribute to post content images ( for lazy load )
 *
 * @param string $content
 *
 * @return string
 */

function mts_content_image_lazy_load_attr( $content ) {
	$mts_options = get_option( MTS_THEME_NAME );
	if ( is_single() && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		return $content;
	}
	if ( ! empty( $mts_options['mts_lazy_load'] )
		 && ! empty( $mts_options['mts_lazy_load_content'] )
		 && ! empty( $content ) ) {
		$content = preg_replace_callback(
			'/<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>/',
			'mts_content_image_lazy_load_attr_callback',
			$content
		);
	}

	return $content;
}
add_filter('the_content', 'mts_content_image_lazy_load_attr');

/**
 * Callback to move src to data-src and replace it with a 1x1 tranparent image.
 *
 * @param $matches
 *
 * @return string
 */
function mts_content_image_lazy_load_attr_callback( $matches ) {
	$transparent_img = 'data:image/gif,GIF89a%01%00%01%00%80%00%00%00%00%00%FF%FF%FF%21%F9%04%01%00%00%00%00%2C%00%00%00%00%01%00%01%00%00%02%01D%00%3B';
	if ( preg_match( '/ data-lazy=[\'"]false[\'"]/', $matches[0] ) ) {
		return '<img ' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '>';
	} else {
		return '<img ' . $matches[1] . 'src="' . $transparent_img . '" data-layzr="' . $matches[2] . '"' . str_replace( 'srcset=', 'data-layzr-srcset=', $matches[3]). '>';
	}
}

/**
 * Remove Lazy Load from core.
 *
 * @param boolean $default Image.
 *
 */
function disable_template_image_lazy_loading( $default ) {
	$mts_options = get_option( MTS_THEME_NAME );
	if ( ! empty( $mts_options['mts_lazy_load'] ) ) {
		return false;
	}
	return $default;
}
add_filter( 'wp_lazy_loading_enabled', 'disable_template_image_lazy_loading', 10, 1 );

/**
 * Enable Widgetized sidebar and Footer
 */
function mts_register_sidebars() {
	$mts_options = get_option( MTS_THEME_NAME );

	// Default sidebar
	register_sidebar( array(
		'name' => __('Sidebar', 'bloggingbox'),
		'description'   => __( 'Default sidebar.', 'bloggingbox' ),
		'id' => 'sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Homepage Subscribe Widget
	register_sidebar(array(
		'name' => __('Subscribe Widget', 'dividend'),
		'description'   => __( 'Show WP Subscribe Widget in the footer on the homepage. You can change the background from homepage tabs.', 'dividend' ),
		'id' => 'widget-subscribe',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	// Top level footer widget areas
	if ( !empty( $mts_options['mts_first_footer'] )) {
		if ( empty( $mts_options['mts_first_footer_num'] )) $mts_options['mts_first_footer_num'] = 4;
		register_sidebars( $mts_options['mts_first_footer_num'], array(
			'name' => __( 'Footer %d', 'bloggingbox' ),
			'description'   => __( 'Appears in the footer area.', 'bloggingbox' ),
			'id' => 'footer-first',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}

	// Custom sidebars
	if ( !empty( $mts_options['mts_custom_sidebars'] ) && is_array( $mts_options['mts_custom_sidebars'] )) {
		foreach( $mts_options['mts_custom_sidebars'] as $sidebar ) {
			if ( !empty( $sidebar['mts_custom_sidebar_id'] ) && !empty( $sidebar['mts_custom_sidebar_id'] ) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-' ) {
				register_sidebar( array( 'name' => ''.$sidebar['mts_custom_sidebar_name'].'', 'id' => ''.sanitize_title( strtolower( $sidebar['mts_custom_sidebar_id'] )).'', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ));
			}
		}
	}

	if ( mts_is_wc_active() ) {
		// Register WooCommerce Shop and Single Product Sidebar
		register_sidebar( array(
			'name' => __('Shop Page Sidebar', 'bloggingbox' ),
			'description'   => __( 'Appears on Shop main page and product archive pages.', 'bloggingbox' ),
			'id' => 'shop-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => __('Single Product Sidebar', 'bloggingbox' ),
			'description'   => __( 'Appears on single product pages.', 'bloggingbox' ),
			'id' => 'product-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
}

add_action( 'widgets_init', 'mts_register_sidebars' );

/**
 * Retrieve the ID of the sidebar to use on the active page.
 *
 * @return string
 */
function mts_custom_sidebar() {
	$mts_options = get_option( MTS_THEME_NAME );

	// Default sidebar.
	$sidebar = 'sidebar';

	if ( is_home() && !empty( $mts_options['mts_sidebar_for_home'] )) $sidebar = $mts_options['mts_sidebar_for_home'];
	if ( is_single() && !empty( $mts_options['mts_sidebar_for_post'] )) $sidebar = $mts_options['mts_sidebar_for_post'];
	if ( is_page() && !empty( $mts_options['mts_sidebar_for_page'] )) $sidebar = $mts_options['mts_sidebar_for_page'];

	// Archives.
	if ( is_archive() && !empty( $mts_options['mts_sidebar_for_archive'] )) $sidebar = $mts_options['mts_sidebar_for_archive'];
	if ( is_category() && !empty( $mts_options['mts_sidebar_for_category'] )) $sidebar = $mts_options['mts_sidebar_for_category'];
	if ( is_tag() && !empty( $mts_options['mts_sidebar_for_tag'] )) $sidebar = $mts_options['mts_sidebar_for_tag'];
	if ( is_date() && !empty( $mts_options['mts_sidebar_for_date'] )) $sidebar = $mts_options['mts_sidebar_for_date'];
	if ( is_author() && !empty( $mts_options['mts_sidebar_for_author'] )) $sidebar = $mts_options['mts_sidebar_for_author'];

	// Other.
	if ( is_search() && !empty( $mts_options['mts_sidebar_for_search'] )) $sidebar = $mts_options['mts_sidebar_for_search'];
	if ( is_404() && !empty( $mts_options['mts_sidebar_for_notfound'] )) $sidebar = $mts_options['mts_sidebar_for_notfound'];

	// Woocommerce.
	if ( mts_is_wc_active() ) {
		if ( is_shop() || is_product_taxonomy() ) {
			$sidebar = 'shop-sidebar';
			if ( !empty( $mts_options['mts_sidebar_for_shop'] )) {
				$sidebar = $mts_options['mts_sidebar_for_shop'];
			}
		}
		if ( is_product() || is_account_page() ) {
			$sidebar = 'product-sidebar';
			if ( !empty( $mts_options['mts_sidebar_for_product'] )) {
				$sidebar = $mts_options['mts_sidebar_for_product'];
			}
		}
	}

	// Page/post specific custom sidebar-
	if ( is_page() || is_single() ) {
		wp_reset_postdata();
		global $wp_registered_sidebars;
		$custom = get_post_meta( get_the_ID(), '_mts_custom_sidebar', true );
		if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) {
			$sidebar = $custom;
		}
	}

	// Posts page
	if ( is_home() && ! is_front_page() && 'page' == get_option( 'show_on_front' ) ) {
		wp_reset_postdata();
		global $wp_registered_sidebars;
		$custom = get_post_meta( get_option( 'page_for_posts' ), '_mts_custom_sidebar', true );
		if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) {
			$sidebar = $custom;
		}
	}

	return apply_filters( 'mts_custom_sidebar', $sidebar );
}

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// Add the 125x125 Ad Block Custom Widget.
include_once( get_theme_file_path( "functions/widget-ad125.php" ) );

// Add the 300x250 Ad Block Custom Widget.
include_once( get_theme_file_path( "functions/widget-ad300.php" ) );

// Add the Latest Tweets Custom Widget.
include_once( get_theme_file_path( "functions/widget-tweets.php" ) );

// Add Recent Posts Widget.
include_once( get_theme_file_path( "functions/widget-recentposts.php" ) );

// Add Related Posts Widget.
include_once( get_theme_file_path( "functions/widget-relatedposts.php" ) );

// Add Author Posts Widget.
include_once( get_theme_file_path( "functions/widget-authorposts.php" ) );

// Add Popular Posts Widget.
include_once( get_theme_file_path( "functions/widget-popular.php" ) );

// Add Facebook Like box Widget.
include_once( get_theme_file_path( "functions/widget-fblikebox.php" ) );

// Add Social Profile Widget.
include_once( get_theme_file_path( "functions/widget-social.php" ) );

// Add Category Posts Widget.
include_once( get_theme_file_path( "functions/widget-catposts.php" ) );

// Add Category Posts Widget.
include_once( get_theme_file_path( "functions/widget-postslider.php" ) );

// Add Adcode Widget.
include_once( get_theme_file_path( "functions/widget-adcode.php" ) );

// Add Welcome message.
include_once( get_theme_file_path( "functions/welcome-message.php" ) );

// Template Functions.
include_once( get_theme_file_path( "functions/theme-actions.php" ) );

// Post/page editor meta boxes.
include_once( get_theme_file_path( "functions/metaboxes.php" ) );

// TGM Plugin Activation.
include_once( get_theme_file_path( "functions/plugin-activation.php" ) );

// AJAX Contact Form - `mts_contact_form()`.
include_once( get_theme_file_path( 'functions/contact-form.php' ) );

// Custom menu walker.
include_once( get_theme_file_path( 'functions/nav-menu.php' ) );

// Rank Math SEO.
include_once( get_theme_file_path( 'functions/rank-math-notice.php' ) );

/*-----------------------------------------------------------------------------------*/
/* RTL
/*-----------------------------------------------------------------------------------*/
if ( ! empty( $mts_options['mts_rtl'] ) ) {
	/**
	 * RTL language support
	 *
	 * @see mts_load_footer_scripts()
	 */
	function mts_rtl() {
		if ( is_admin() ) {
			return;
		}
		global $wp_locale, $wp_styles;
		$wp_locale->text_direction = 'rtl';
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			$wp_styles = new WP_Styles();
			$wp_styles->text_direction = 'rtl';
		}
	}
	add_action( 'init', 'mts_rtl' );
}

/**
 * Replace `no-js` with `js` from the body's class name.
 */
function mts_nojs_js_class() {
	echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
}
add_action( 'wp_head', 'mts_nojs_js_class', 1 );

/**
 * Enqueue .js files.
 */
function mts_add_scripts() {
	$mts_options = get_option( MTS_THEME_NAME );

	wp_enqueue_script( 'jquery' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 'customscript', get_template_directory_uri() . '/js/customscript.js', array( 'jquery' ), MTS_THEME_VERSION, true );
	if ( ! empty( $mts_options['mts_show_primary_nav'] ) && ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
		$nav_menu = 'both';
	} else {
		$nav_menu = 'none';

		if ( ! empty( $mts_options['mts_show_primary_nav'] ) ) {
			$nav_menu = 'primary-menu';
		} elseif ( ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
			$nav_menu = 'secondary-menu';
		}
	}
	wp_localize_script(
		'customscript',
		'mts_customscript',
		array(
			'responsive' => ( empty( $mts_options['mts_responsive'] ) ? false : true ),
			'like' => ( empty( $mts_options['mts_like_dislike'] ) ? false : true ),
			'nav_menu' => $nav_menu,
			'ajax_url' => admin_url( 'admin-ajax.php' )
		)
	);
	wp_enqueue_script( 'customscript' );

	// Slider
	wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
	wp_localize_script(
		'owl-carousel',
		'slideropts',
		array(
			'rtl_support' => $mts_options['mts_rtl'],
			'control_support' => isset($mts_options['mts_featured_slider_controls']) ? $mts_options['mts_featured_slider_controls'] : 'both',
			'autoplay_support' => isset($mts_options['mts_featured_slider_autoplay']) ? $mts_options['mts_featured_slider_autoplay'] : '1',
			'autoplay_speed' => !empty($mts_options['mts_featured_slider_speed']) ? $mts_options['mts_featured_slider_speed'] : '1000',
		)
	);
	if(!empty($mts_options['mts_featured_slider'])) {
		wp_enqueue_script ('owl-carousel');
	}

	// Animated single post/page header
	if ( is_singular() ) {
		$header_animation = mts_get_post_header_effect();
		if ( 'parallax' == $header_animation ) {
			wp_enqueue_script( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.js', array( 'jquery' ) );
		} else if ( 'zoomout' == $header_animation ) {
			wp_enqueue_script( 'jquery-zoomout', get_template_directory_uri() . '/js/zoomout.js', array( 'jquery' ) );
		}
	}
	//Lightbox
	if ( ! empty( $mts_options['mts_lightbox'] ) ) {
		wp_enqueue_script( 'magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), false, true );
	}

	//Sticky Nav
	if ( ! empty( $mts_options['mts_sticky_nav'] ) ) {
		wp_enqueue_script( 'StickyNav', get_template_directory_uri() . '/js/sticky.js', array( 'jquery' ), false, true );
	}

	// Lazy Load
	if ( ! empty( $mts_options['mts_lazy_load'] ) ) {
		if ( ! empty( $mts_options['mts_lazy_load_thumbs'] ) || ( ! empty( $mts_options['mts_lazy_load_content'] ) && is_singular() ) ) {
			wp_enqueue_script( 'layzr', get_template_directory_uri() . '/js/layzr.min.js', array( 'jquery' ), false, true );
		}
	}

	// Ajax Load More and Search Results
	wp_register_script( 'mts_ajax', get_template_directory_uri() . '/js/ajax.js', array( 'jquery' ), false, true );
	if( ! empty( $mts_options['mts_pagenavigation_type'] ) && $mts_options['mts_pagenavigation_type'] >= 2 && !is_singular() ) {
		wp_enqueue_script( 'mts_ajax' );

		wp_enqueue_script( 'historyjs', get_template_directory_uri() . '/js/history.js', array(), false, true );

		// Add parameters for the JS
		global $wp_query;
		$max = $wp_query->max_num_pages;
		$paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
		$autoload = ( $mts_options['mts_pagenavigation_type'] == 3 );
		wp_localize_script(
			'mts_ajax',
			'mts_ajax_loadposts',
			array(
				'startPage' => $paged,
				'maxPages' => $max,
				'nextLink' => next_posts( $max, false ),
				'autoLoad' => $autoload,
				'i18n_loadmore' => __( 'Load More Posts', 'bloggingbox' ),
				'i18n_loading' => __('Loading...', 'bloggingbox' ),
				'i18n_nomore' => __( 'No more posts.', 'bloggingbox' ),
			 )
		);
	}
	if ( ! empty( $mts_options['mts_ajax_search'] ) ) {
		wp_enqueue_script( 'mts_ajax' );
		wp_localize_script(
			'mts_ajax',
			'mts_ajax_search',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'ajax_search' => '1',
			 )
		);
	}
	if ( ! empty( $mts_options['mts_enable_tabs'] ) && is_home() ) {
		wp_enqueue_script( 'mts_ajax' );
		wp_localize_script(
			'mts_ajax',
			'mts_ajax_tabs',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
			 )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'mts_add_scripts' );

/**
 * Load CSS files.
 */
function mts_enqueue_css() {
	$mts_options = get_option( MTS_THEME_NAME );

	wp_enqueue_style( 'bloggingbox-stylesheet', get_stylesheet_uri() );

	// Slider
	// also enqueued in slider widget
	wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), null);
	if(!empty($mts_options['mts_featured_slider'])) {
		wp_enqueue_style('owl-carousel');
	}

	$handle = 'bloggingbox-stylesheet';

	// WooCommerce
	if ( mts_is_wc_active() ) {
		if ( empty( $mts_options['mts_optimize_wc'] ) || ( ! empty( $mts_options['mts_optimize_wc'] ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) ) {
			wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce2.css' );
			$handle = 'woocommerce';
		}
	}

	// Lightbox
	if ( ! empty( $mts_options['mts_lightbox'] ) ) {
		wp_register_style( 'magnificPopup', get_template_directory_uri() . '/css/magnific-popup.css' );
		wp_enqueue_style( 'magnificPopup' );
	}

	// RTL
	if ( ! empty( $mts_options['mts_rtl'] ) ) {
		wp_enqueue_style( 'mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'bloggingbox-style' );
	}

	// Responsive
	if ( ! empty( $mts_options['mts_responsive'] ) ) {
		wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css' );
	}

	// Font Awesome
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css' );

	$mts_sclayout = '';
	$mts_author = '';
	$mts_social_color = '';
	$mts_header_section = '';
	$mts_footer_upper = '';
	$mts_sidebar_location = '';
	$mts_featured_cat = '';
	$mts_article_footer = '';
	$mts_single_footer = '';
	$mts_tags = '';

	if ( is_page() || is_single() ) {
		$mts_sidebar_location = get_post_meta( get_the_ID(), '_mts_sidebar_location', true );
	}
	if ( $mts_sidebar_location != 'right' && ( $mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left' )) {
		$mts_sclayout = '.article { float: right;}
		.sidebar.c-4-12 { float: left; padding-right: 0; }';
	}
	if ( empty( $mts_options['mts_header_section2'] ) ) {
		$mts_header_section = '.logo-wrap { display: none }';
	}
	if ( empty( $mts_options['mts_footer_logo_button'] ) && empty( $mts_options['mts_social_icon_head'] ) && empty( $mts_options['mts_readers'] )) {
		$mts_footer_upper = '.footer-upper { padding: 0 }';
	}
	if ( ! empty( $mts_options['mts_author_comment'] ) ) {
		$mts_author = '.bypostauthor > div .fn:after { content: "'.__( 'Author', 'bloggingbox' ).'"; position: relative; margin-left: 5px; padding: 3px 10px; color: #FFF; font-weight: normal; font-size: 13px; border-radius: 30px; }';
	}
	if ( !empty($mts_options['mts_footer_social']) ) {
		foreach( $mts_options['mts_footer_social'] as $footer_icons ) {
			$footer_social_icon = $footer_icons['mts_footer_icon'];
			$footer_social_icon_color = $footer_icons['mts_footer_icon_color'];
			$footer_social_icon_hover_color = $footer_icons['mts_footer_icon_hover_color'];
			$hex_color2 = mts_convert_hex_to_rgb($footer_icons['mts_footer_icon_color']);
			$mts_social_color .= '.footer-social a.footer-'. $footer_social_icon .' { color: '. $footer_social_icon_color .'; border-color: rgba('.$hex_color2.', 0.4); } .footer-social a.footer-'. $footer_social_icon .':hover { background: '.$footer_social_icon_hover_color.'}';
		}
	}
	if ( !empty($mts_options['mts_featured_section']) ) {
		foreach( $mts_options['mts_featured_section'] as $featured_cats ) {
			if( ! empty( $featured_cats['mts_featured_cats'] )) :
				$mts_featured_color = $featured_cats['mts_featured_text_color'];
				$hex_color1 = mts_convert_hex_to_rgb($featured_cats['mts_featured_text_color']);
				$category_id = $featured_cats['mts_featured_cats'];
				$mts_featured_hover = $featured_cats['mts_featured_hover_color'];
				$mts_featured_cat .= '.featured-categories-container .featured-wrap-'.$category_id.'{ color: '. $mts_featured_color .'; border-color: rgba('.$hex_color1.',0.5); } .featured-categories-container .featured-wrap-'.$category_id.':hover { background: '. $mts_featured_hover .'; }';
			endif;
		}
	}
	if ( ( isset($mts_options['mts_home_meta_info_enable']['author-image']) == '0' ) && ( isset($mts_options['mts_home_meta_info_enable']['author']) == '0' ) && ( isset($mts_options['mts_home_meta_info_enable']['readmore']) == '0' ) && ( isset($mts_options['mts_home_meta_info_enable']['comment']) == '0' ) ) {
		$mts_article_footer = '.article-footer { padding: 0; border-top: 0; } .latestPost header { margin-top: 30px; } .article-footer { margin-top: 30px; }';
	}
	if ( ( isset($mts_options['mts_single_meta_info_enable']['author-image']) == '0' ) && ( isset($mts_options['mts_single_meta_info_enable']['author']) == '0' ) && ( isset($mts_options['mts_single_meta_info_enable']['time']) == '0' ) && ( isset($mts_options['mts_single_meta_info_enable']['comment']) == '0' ) && ( isset($mts_options['mts_single_meta_info_enable']['category']) == '0' ) ) {
		$mts_single_footer = '.traditional-header { margin: 0; }';
	}
	$tags = get_the_tags();
	if (empty( $tags ) || is_wp_error( $tags ) ) {
		$mts_tags = '.shareit { float: left }';
	}
	$mts_bg = mts_get_background_styles( 'mts_background' );
	$mts_header_bg = mts_get_background_styles( 'mts_header_bg' );
	$mts_footer_bg = mts_get_background_styles( 'mts_footer_bg' );
	$mts_featured_bg = mts_get_background_styles( 'mts_featured_bg' );
	$mts_subscribe_widget_bg = mts_get_background_styles( 'mts_subscribe_widget_bg' );
	$mts_color1 = mts_convert_hex_to_rgb($mts_options['mts_color_scheme']);
	$mts_color2 = mts_convert_hex_to_rgb($mts_options['mts_color_scheme2']);
	$mts_color1_dark = '#'.mts_darken_color($mts_options['mts_color_scheme'], 38);
	$mts_color1_dark_rgba = mts_convert_hex_to_rgb($mts_color1_dark);
	$custom_css = "
		body {{$mts_bg}}
		#header, .navigation.mobile-menu-wrapper, .header-traditional .header-lower, .header-traditional-2 .header-lower {{$mts_header_bg}}
		#site-footer {{$mts_footer_bg}}
		.featured-categories-container {{$mts_featured_bg}}
		.mts-newsletter {{$mts_subscribe_widget_bg}}
		a, .primary-slider .slide-caption .thecategory { color: {$mts_options['mts_color_scheme']} }
		.post-info .thecomment a, .post-info .readMore a, .pagination .nav-previous a, .pagination .nav-next a, .pagination a, .pagination .page-numbers.dots, .woocommerce nav.woocommerce-pagination ul li a, .widget .wpt_widget_content .tab_title.selected a, .widget .wp_review_tab_widget_content .tab_title.selected a, .tagcloud a, .tags a:hover, input[type='submit'], .single-image .post-info .thecomment a:hover, .ball-pulse > div, .pace .pace-progress, .woocommerce a.button, .woocommerce-page a.button, .woocommerce button.button, .woocommerce-page button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .latestPost-review-wrapper, .widget .review-total-only.large-thumb, #wpmm-megamenu .review-total-only, .widget .widget-slider .slide-caption, .single-image .thecategory a:hover, .widget #wp-subscribe, #load-posts a, #page #commentform.wp-review-comment-form input[type='submit'], .latestPost .review-type-circle.latestPost-review-wrapper, .woocommerce-account .woocommerce-MyAccount-navigation li.is-active, .mts-newsletter .widget #wp-subscribe input.submit, .woocommerce-product-search button[type=\"submit\"], .woocommerce .woocommerce-widget-layered-nav-dropdown__submit { background: {$mts_options['mts_color_scheme']} }

		a:hover, .widget li a:hover, #site-footer .widget li a:hover, .widget li:hover > .toggle-caret, .widget.widget_nav_menu li:hover > a, #site-footer .widget.widget_nav_menu li:hover > a, .latestPost .title a:hover, .latestPost.grid-1 .title a:hover, .postauthor h5 a:hover, .post-like.active, .post-dislike.active, .like-count.dislike-count, .comm-number, .widget .textwidget a, #site-footer .widget .textwidget a { color: {$mts_options['mts_color_scheme2']} }

		.post-info .thecomment a:hover, .post-info .readMore a:hover, .pagination a:hover, .pagination .page-numbers.current, .single .pagination > .current, .widget .wp_review_tab_widget_content .tab_title a, .widget .wpt_widget_content .tab_title a, .tagcloud a:hover, .navigation ul.menu > li:hover > a, .navigation ul ul li, .single-image .post-info .thecomment a, input[type='submit']:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current, .woocommerce #content nav.woocommerce-pagination ul li span.current, .woocommerce-page #content nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce-page nav.woocommerce-pagination ul li a:hover, .woocommerce #content nav.woocommerce-pagination ul li a:hover, .woocommerce-page #content nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce-page nav.woocommerce-pagination ul li a:focus, .woocommerce #content nav.woocommerce-pagination ul li a:focus, .woocommerce-page #content nav.woocommerce-pagination ul li a:focus, .to-top:hover, #load-posts a:hover, #load-posts a.loading, .widget #wp-subscribe input.submit, #page #commentform.wp-review-comment-form input[type='submit']:hover, #header .mts-cart .cart-contents:hover, .bypostauthor .fn:after, .woocommerce a.button:hover, .woocommerce-page a.button:hover, .woocommerce button.button:hover, .woocommerce-page button.button:hover, .woocommerce input.button:hover, .woocommerce-page input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce-page #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page #content input.button:hover, .mts-newsletter .widget #wp-subscribe input.submit:hover, .woocommerce-product-search button[type=\"submit\"]:hover, .woocommerce-product-search button[type=\"submit\"]:hover, .woocommerce .woocommerce-widget-layered-nav-dropdown__submit:hover { background:{$mts_options['mts_color_scheme2']} }

		.woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce a.button.alt.disabled, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled[disabled], .woocommerce button.button.alt.disabled, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled[disabled] { background: {$mts_options['mts_color_scheme']}!important; }

		.tags a:hover, .single-image .thecategory a:hover { border-color: {$mts_options['mts_color_scheme']} }
		.to-top:hover { border-color: {$mts_options['mts_color_scheme2']} }
		.owl-prev, .owl-next { background-color: rgba( $mts_color1, 0.75); }
		#slider.primary-slider.owl-carousel .owl-item:after { background-color: rgba( $mts_color1_dark_rgba, 0.75); }
		#slider.primary-slider.owl-carousel .owl-item:hover:after, #slider.owl-prev, #slider.owl-next, .single-prev-next .next-post:hover .featured-thumbnail:after, .single-prev-next .previous-post:hover .featured-thumbnail:after { background-color: rgba( $mts_color2, 0.75)!important; }

		.mts-newsletter .widget #wp-subscribe { color: {$mts_options['mts_subscribe_widget_color']}; }

		{$mts_sclayout}
		{$mts_author}
		{$mts_social_color}
		{$mts_header_section}
		{$mts_footer_upper}
		{$mts_featured_cat}
		{$mts_article_footer}
		{$mts_single_footer}
		{$mts_tags}
		{$mts_options['mts_custom_css']}
			";
	wp_add_inline_style( $handle, $custom_css );
}
add_action( 'wp_enqueue_scripts', 'mts_enqueue_css', 99 );

/**
 * Wrap videos in .responsive-video div
 *
 * @param $html
 * @param $url
 * @param $attr
 *
 * @return string
 */
function mts_responsive_video( $html, $url, $attr ) {

	// Only video embeds
	$video_providers = array(
		'youtube',
		'vimeo',
		'dailymotion',
		'wordpress.tv',
		'vine.co',
		'animoto',
		'blip.tv',
		'collegehumor.com',
		'funnyordie.com',
		'hulu.com',
		'revision3.com',
		'ted.com',
	);

	// Allow user to wrap other embeds
	$providers = apply_filters('mts_responsive_video', $video_providers );

	foreach ( $providers as $provider ) {
		if ( strstr($url, $provider) ) {
			$html = '<div class="flex-video flex-video-' . sanitize_html_class( $provider ) . '">' . $html . '</div>';
			break;// Break if video found
		}
	}

	return $html;
}
add_filter( 'embed_oembed_html', 'mts_responsive_video', 99, 3 );

if ( ! function_exists( 'mts_comments' ) ) {
	/**
	 * Custom comments template.
	 * @param $comment
	 * @param $args
	 * @param $depth
	 */
	function mts_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$mts_options = get_option( MTS_THEME_NAME ); ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<?php
			switch( $comment->comment_type ) :
				case 'pingback':
				case 'trackback': ?>
					<div id="comment-<?php comment_ID(); ?>" class="comment-list">
						<div class="comment-author vcard">
							Pingback: <?php comment_author_link(); ?>
							<span class="comment-meta">
								<?php edit_comment_link( __( '( Edit )', 'bloggingbox' ), '  ', '' ) ?>
							</span>
							<?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
								<span class="ago"><?php comment_date( get_option( 'date_format' ) ); ?></span>
							<?php } ?>
							<?php if ( $mts_options['mts_like_dislike'] ) {
									mts_like_dislike(get_comment_ID());
							} ?>
						</div>
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em><?php _e( 'Your comment is awaiting moderation.', 'bloggingbox' ) ?></em>
							<br />
						<?php endif; ?>
					</div>
				<?php
					break;

				default: ?>
					<div id="comment-<?php comment_ID(); ?>" itemscope itemtype="http://schema.org/UserComments" class="comment-list">
						<div class="comment-author vcard">
							<?php echo get_avatar( $comment->comment_author_email, 70 ); ?>
							<?php printf( '<span class="fn" itemprop="creator" itemscope itemtype="http://schema.org/Person"><span itemprop="name">%s</span></span>', get_comment_author_link() ) ?>
							<span class="comment-meta">
								<?php edit_comment_link( __( '( Edit )', 'bloggingbox' ), '  ', '' ) ?>
							</span>
							<div class="reply">
								<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="fa fa-reply"></i>' )) ) ?>
							</div>
						</div>
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em><?php _e( 'Your comment is awaiting moderation.', 'bloggingbox' ) ?></em>
							<br />
						<?php endif; ?>
						<div class="commentmetadata">
							<div class="commenttext" itemprop="commentText">
								<?php comment_text() ?>
							</div>
							<?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
								<span class="ago"><?php comment_date( get_option( 'date_format' ) ); ?></span>
							<?php } ?>
							<?php if ( $mts_options['mts_like_dislike'] ) {
									mts_like_dislike(get_comment_ID());
							} ?>
						</div>
					</div>
				<?php
				   break;
			 endswitch; ?>
		<!-- WP adds </li> -->
	<?php }
}

/**
 * Increase excerpt length to 100.
 *
 * @param $length
 *
 * @return int
 */
function mts_excerpt_length( $length ) {
	return 100;
}
add_filter( 'excerpt_length', 'mts_excerpt_length', 20 );

/**
 * Remove [...] and shortcodes
 *
 * @param $output
 *
 * @return string
 */
function mts_custom_excerpt( $output ) {
  return preg_replace( '/\[[^\]]*]/', '', $output );
}
add_filter( 'get_the_excerpt', 'mts_custom_excerpt' );

/**
 * Truncate string to x letters/words.
 *
 * @param $str
 * @param int $length
 * @param string $units
 * @param string $ellipsis
 *
 * @return string
 */
function mts_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
	if ( $units == 'letters' ) {
		if ( mb_strlen( $str ) > $length ) {
			return mb_substr( $str, 0, $length ) . $ellipsis;
		} else {
			return $str;
		}
	} else {
		return wp_trim_words( $str, $length, $ellipsis );
	}
}

if ( ! function_exists( 'mts_excerpt' ) ) {
	/**
	 * Get HTML-escaped excerpt up to the specified length.
	 *
	 * @param int $limit
	 *
	 * @return string
	 */
	function mts_excerpt( $limit = 40 ) {
	  return esc_html( mts_truncate( get_the_excerpt(), $limit, 'words' ) );
	}
}

/**
 * Change the "read more..." link to "".
 * @param $more_link
 * @param $more_link_text
 *
 * @return string
 */
function mts_remove_more_link( $more_link, $more_link_text ) {
	return '';
}
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );


if ( ! function_exists( 'mts_post_has_moretag' ) ) {
	/**
	 * Shorthand function to check for more tag in post.
	 *
	 * @return bool|int
	 */
	function mts_post_has_moretag() {
		$post = get_post();
		return preg_match( '/<!--more(.*?)?-->/', $post->post_content );
	}
}

if ( ! function_exists( 'mts_readmore' ) ) {
	/**
	 * Display a "read more" link.
	 */
	function mts_readmore() {
		?>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
				<i class="fa fa-long-arrow-right"></i>
			</a>
		<?php
	}
}

/**
 * Exclude trackbacks from the comment count.
 *
 * @param $count
 *
 * @return int
 */
function mts_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments = get_comments( 'status=approve&post_id=' . $id );
		$comments_by_type = separate_comments( $comments );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}
add_filter( 'get_comments_number', 'mts_comment_count', 0 );

/**
 * Add `has_thumb` to the post's class name if it has a thumbnail.
 *
 * @param $classes
 *
 * @return array
 */
function has_thumb_class( $classes ) {
	if( has_post_thumbnail( get_the_ID() ) ) { $classes[] = 'has_thumb'; }
		return $classes;
}
add_filter( 'post_class', 'has_thumb_class' );

/*-----------------------------------------------------------------------------------*/
/* Add the title tag for compability with older WP versions.
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function theme_slug_render_title() { ?>
	   <title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php }
	add_action( 'wp_head', 'theme_slug_render_title' );
}

/**
 * Handle AJAX search queries.
 */
if( ! function_exists( 'ajax_mts_search' ) ) {
	function ajax_mts_search() {
		$query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
		$search_query = new WP_Query( array( 's' => $query, 'posts_per_page' => 3, 'post_status' => 'publish' ));
		$search_count = new WP_Query( array( 's' => $query, 'posts_per_page' => -1, 'post_status' => 'publish' ));
		$search_count = $search_count->post_count;
		if ( !empty( $query ) && $search_query->have_posts() ) :
			//echo '<h5>Results for: '. $query.'</h5>';
			echo '<ul class="ajax-search-results">';
			while ( $search_query->have_posts() ) : $search_query->the_post();
				?><li>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>">
						<?php if ( has_post_thumbnail() ) { ?>
							<?php the_post_thumbnail( 'bloggingbox-widgetthumb', array( 'title' => '' ) ); ?>
						<?php } else { ?>
							<img class="wp-post-image" src="<?php echo get_template_directory_uri() . '/images/nothumb-bloggingbox-widgetthumb.png'; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
						<?php } ?>
						<?php the_title(); ?>
					</a>
					<div class="meta">
						<span class="thetime"><?php the_time( 'F j, Y' ); ?></span>
					</div> <!-- / .meta -->
				</li>
				<?php
			endwhile;
			echo '</ul>';
			echo '<div class="ajax-search-meta"><span class="results-count">'.$search_count.' '.__( 'Results', 'bloggingbox' ).'</span><a href="'.esc_url( get_search_link( $query ) ).'" class="results-link">'.__('Show all results.', 'bloggingbox' ).'</a></div>';
		else:
			echo '<div class="no-results">'.__( 'No results found.', 'bloggingbox' ).'</div>';
		endif;
		wp_reset_postdata();
		exit; // required for AJAX in WP
	}
}
if( !empty( $mts_options['mts_ajax_search'] )) {
	add_action( 'wp_ajax_mts_search', 'ajax_mts_search' );
	add_action( 'wp_ajax_nopriv_mts_search', 'ajax_mts_search' );
}

/**
 *  Filters that allow shortcodes in Text Widgets
 */
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_content_rss', 'do_shortcode' );

if ( trim( $mts_options['mts_feedburner'] ) !== '' ) {
	/**
	 * Redirect feed to FeedBurner if a FeedBurner URL has been set.
	 */
	function mts_rss_feed_redirect() {
		$mts_options = get_option( MTS_THEME_NAME );
		global $feed;
		$new_feed = $mts_options['mts_feedburner'];
		if ( !is_feed() ) {
				return;
		}
		if ( preg_match( '/feedburner/i', $_SERVER['HTTP_USER_AGENT'] )){
				return;
		}
		if ( $feed != 'comments-rss2' ) {
				if ( function_exists( 'status_header' )) status_header( 302 );
				header( "Location:" . $new_feed );
				header( "HTTP/1.1 302 Temporary Redirect" );
				exit();
		}
	}
	add_action( 'template_redirect', 'mts_rss_feed_redirect' );
}

/**
 * Single Post Pagination - Numbers + Previous/Next.
 *
 * @param $args
 *
 * @return mixed
 */
function mts_wp_link_pages_args( $args ) {
	global $page, $numpages, $more, $pagenow;
	if ( $args['next_or_number'] != 'next_and_number' ) {
		return $args;
	}

	$args['next_or_number'] = 'number';

	if ( !$more ) {
		return $args;
	}

	if( $page-1 ) {
		$args['before'] .= _wp_link_page( $page-1 )
						. $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
	}

	if ( $page<$numpages ) {
		$args['after'] = _wp_link_page( $page+1 )
						 . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
						 . $args['after'];
	}

	return $args;
}
add_filter( 'wp_link_pages_args', 'mts_wp_link_pages_args' );

/**
 * Remove hentry class from pages
 *
 * @param $classes
 *
 * @return array
 */
function mts_remove_hentry( $classes ) {
	if ( is_page() ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}
	return $classes;
}
add_filter( 'post_class','mts_remove_hentry' );

/*-----------------------------------------------------------------------------------*/
/* WooCommerce
/*-----------------------------------------------------------------------------------*/
if ( mts_is_wc_active() ) {
	if ( !function_exists( 'mts_loop_columns' )) {
		/**
		 * Change number or products per row to 3
		 *
		 * @return int
		 */
		function mts_loop_columns() {
			return 3; // 3 products per row
		}
	}
	add_filter( 'loop_shop_columns', 'mts_loop_columns' );

	/**
	 * Redefine woocommerce_output_related_products()
	 */
	if( ! function_exists( 'woocommerce_output_related_products' ) ) {
		function woocommerce_output_related_products() {
			$args = array(
				'posts_per_page' => 3,
				'columns' => 3,
			);
			woocommerce_related_products($args); // Display 3 products in rows of 1
		}
	}

	global $pagenow;
	if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
		/**
		 * Define WooCommerce image sizes.
		 */
		function mts_woocommerce_image_dimensions() {
			$catalog = array(
				'width' 	=> '227',	// px
				'height'	=> '311',	// px
				'crop'		=> 1 		// true
			);
			$single = array(
				'width' 	=> '355',	// px
				'height'	=> '484',	// px
				'crop'		=> 1 		// true
			);
			$thumbnail = array(
				'width' 	=> '60',	// px
				'height'	=> '60',	// px
				'crop'		=> 1 		// false
			);
			// Image sizes
			update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
			update_option( 'shop_single_image_size', $single ); 		// Single product image
			update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
		}
		add_action( 'init', 'mts_woocommerce_image_dimensions', 1 );
	}


	/**
	 * Change the number of product thumbnails to show per row to 4.
	 *
	 * @return int
	 */
	function mts_thumb_cols() {
	 return 4; // .last class applied to every 4th thumbnail
	}
	add_filter( 'woocommerce_product_thumbnails_columns', 'mts_thumb_cols' );

	/**
	 * Change the number of WooCommerce products to show per page.
	 *
	 * @return mixed
	 */
	function mts_products_per_page() {
		$mts_options = get_option( MTS_THEME_NAME );
		return $mts_options['mts_shop_products'];
	}
	add_filter( 'loop_shop_per_page', 'mts_products_per_page', 20 );

	/**
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	function mts_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();	?>

		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php _e('View your shopping cart', 'bloggingbox' ); ?>"><?php _e('Cart', 'bloggingbox'); ?><?php echo ' ('. sprintf('%d', $woocommerce->cart->cart_contents_count). ')'; ?></a>

		<?php $fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
	add_filter( 'add_to_cart_fragments', 'mts_header_add_to_cart_fragment' );

	/**
	 * Optimize WooCommerce Scripts
	 * Updated for WooCommerce 2.0+
	 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
	 */
	function mts_child_manage_woocommerce_styles() {
		//remove generator meta tag
		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

		//first check that woo exists to prevent fatal errors
		if ( function_exists( 'is_woocommerce' ) ) {
			//dequeue scripts and styles
			if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
				wp_dequeue_style( 'woocommerce-layout' );
				wp_dequeue_style( 'woocommerce-smallscreen' );
				wp_dequeue_style( 'woocommerce-general' );
				wp_dequeue_style( 'wc-bto-styles' ); //Composites Styles
				wp_dequeue_script( 'wc-add-to-cart' );
				wp_dequeue_script( 'wc-cart-fragments' );
				wp_dequeue_script( 'woocommerce' );
				wp_dequeue_script( 'jquery-blockui' );
				wp_dequeue_script( 'jquery-placeholder' );
			}
		}
	}
	if ( ! empty( $mts_options['mts_optimize_wc'] ) ) {
		add_action( 'wp_enqueue_scripts', 'mts_child_manage_woocommerce_styles', 99 );
	}

	// Remove WooCommerce generator tag.
	remove_action('wp_head', 'wc_generator_tag');
}

/**
 * Add <!-- next-page --> button to tinymce.
 *
 * @param $mce_buttons
 *
 * @return array
 */
function mts_wysiwyg_editor( $mce_buttons ) {
   $pos = array_search( 'wp_more', $mce_buttons, true );
   if ( $pos !== false ) {
	   $tmp_buttons = array_slice( $mce_buttons, 0, $pos+1 );
	   $tmp_buttons[] = 'wp_page';
	   $mce_buttons = array_merge( $tmp_buttons, array_slice( $mce_buttons, $pos+1 ));
   }
   return $mce_buttons;
}
add_filter( 'mce_buttons', 'mts_wysiwyg_editor' );

/**
 * Get Post header animation.
 *
 * @return string
 */
function mts_get_post_header_effect() {
	$postheader_effect = get_post_meta( get_the_ID(), '_mts_postheader', true );

	return $postheader_effect;
}

/**
 * Add Custom Gravatar Support.
 *
 * @param $avatar_defaults
 *
 * @return mixed
 */
function mts_custom_gravatar( $avatar_defaults ) {
	$mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
	$avatar_defaults[$mts_avatar] = __( 'Custom Gravatar ( /images/gravatar.png )', 'bloggingbox' );
	return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'mts_custom_gravatar' );

/**
 * Add `.primary-navigation` the WP Mega Menu's
 * @param $selector
 *
 * @return string
 */
function mts_megamenu_parent_element( $selector ) {
	return '.container';
}
add_filter( 'wpmm_container_selector', 'mts_megamenu_parent_element' );

/**
 * Change the image size of WP Mega Menu's thumbnails.
 *
 * @param $thumbnail_html
 * @param $post_id
 *
 * @return string
 */
if( ! function_exists( 'mts_megamenu_thumbnails' ) ) {
	function mts_megamenu_thumbnails( $thumbnail_html, $post_id ) {
		$thumbnail_html = '<div class="wpmm-thumbnail">';
		$thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
		if(has_post_thumbnail($post_id)):
			$thumbnail_html .= get_the_post_thumbnail($post_id, 'bloggingbox-widgetfull', array('title' => ''));
		else:
			$thumbnail_html .= '<img src="'.get_template_directory_uri().'/images/nothumb-bloggingbox-widgetfull.png" alt="'.__('No Preview', 'bloggingbox').'"  class="wp-post-image" />';
		endif;
		$thumbnail_html .= '</a>';

		// WP Review
		$thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');

		$thumbnail_html .= '</div>';

		return $thumbnail_html;
	}
}
add_filter( 'wpmm_thumbnail_html', 'mts_megamenu_thumbnails', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/
/**
 * Set default colors for new reviews.
 *
 * @param $colors
 *
 * @return array
 */
function mts_new_default_review_colors( $colors ) {
	$colors = array(
		'color' => '#fff',
		'fontcolor' => '#fff',
		'bgcolor1' => '#00bcd4',
		'bgcolor2' => '#00bcd4',
		'bordercolor' => '#00bcd4'
	);
  return $colors;
}
add_filter( 'wp_review_default_colors', 'mts_new_default_review_colors' );

/**
 * Set default location for new reviews.
 *
 * @param $position
 *
 * @return string
 */
function mts_new_default_review_location( $position ) {
  $position = 'top';
  return $position;
}
add_filter( 'wp_review_default_location', 'mts_new_default_review_location' );


/*-----------------------------------------------------------------------------------*/
/* Post view count
/*-----------------------------------------------------------------------------------*/

/**
 * Update the view count of a post.
 *
 * @param $post_id
 */
function mts_update_view_count( $post_id = null ) {
	if ( ! $post_id ) {
		if ( ! is_singular() ) {
			return;
		}
		global $post;
		$post_id = $post->ID;
	}
	$count = get_post_meta( $post_id, '_mts_view_count', true );
	update_post_meta( $post_id, '_mts_view_count', ++$count );

	do_action( 'mts_view_count_after_update', $post_id, $count );

	return $count;
}
add_action( 'wp', 'mts_update_view_count', 10, 0 );

/**
 * Convert color format from HEX to HSL.
 * @param $color
 *
 * @return array
 */
function mts_hex_to_hsl( $color ){

	// Sanity check
	$color = mts_check_hex_color($color);

	// Convert HEX to DEC
	$R = hexdec($color[0].$color[1]);
	$G = hexdec($color[2].$color[3]);
	$B = hexdec($color[4].$color[5]);

	$HSL = array();

	$var_R = ($R / 255);
	$var_G = ($G / 255);
	$var_B = ($B / 255);

	$var_Min = min($var_R, $var_G, $var_B);
	$var_Max = max($var_R, $var_G, $var_B);
	$del_Max = $var_Max - $var_Min;

	$L = ($var_Max + $var_Min)/2;

	if ($del_Max == 0) {
		$H = 0;
		$S = 0;
	} else {
		if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
		else			$S = $del_Max / ( 2 - $var_Max - $var_Min );

		$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
		$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
		$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

		if	  ($var_R == $var_Max) $H = $del_B - $del_G;
		else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
		else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

		if ($H<0) $H++;
		if ($H>1) $H--;
	}

	$HSL['H'] = ($H*360);
	$HSL['S'] = $S;
	$HSL['L'] = $L;

	return $HSL;
}

/**
 * Convert color format from HSL to HEX.
 *
 * @param array $hsl
 *
 * @return string
 */
function mts_hsl_to_hex( $hsl = array() ){

	list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

	if( $S == 0 ) {
		$r = $L * 255;
		$g = $L * 255;
		$b = $L * 255;
	} else {

		if($L<0.5) {
			$var_2 = $L*(1+$S);
		} else {
			$var_2 = ($L+$S) - ($S*$L);
		}

		$var_1 = 2 * $L - $var_2;

		$r = round(255 * mts_huetorgb( $var_1, $var_2, $H + (1/3) ));
		$g = round(255 * mts_huetorgb( $var_1, $var_2, $H ));
		$b = round(255 * mts_huetorgb( $var_1, $var_2, $H - (1/3) ));
	}

	// Convert to hex
	$r = dechex($r);
	$g = dechex($g);
	$b = dechex($b);

	// Make sure we get 2 digits for decimals
	$r = (strlen("".$r)===1) ? "0".$r:$r;
	$g = (strlen("".$g)===1) ? "0".$g:$g;
	$b = (strlen("".$b)===1) ? "0".$b:$b;

	return $r.$g.$b;
}

/**
 * Convert color format from Hue to RGB.
 *
 * @param $v1
 * @param $v2
 * @param $vH
 *
 * @return mixed
 */
function mts_huetorgb( $v1,$v2,$vH ) {
	if( $vH < 0 ) {
		$vH += 1;
	}

	if( $vH > 1 ) {
		$vH -= 1;
	}

	if( (6*$vH) < 1 ) {
		   return ($v1 + ($v2 - $v1) * 6 * $vH);
	}

	if( (2*$vH) < 1 ) {
		return $v2;
	}

	if( (3*$vH) < 2 ) {
		return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
	}

	return $v1;

}

/**
 * Get the 6-digit hex color.
 *
 * @param $hex
 *
 * @return mixed|string
 */
function mts_check_hex_color( $hex ) {
	// Strip # sign is present
	$color = str_replace("#", "", $hex);

	// Make sure it's 6 digits
	if( strlen($color) == 3 ) {
		$color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
	}

	return $color;
}

/**
 * Check if color is considered light or not.
 * @param $color
 *
 * @return bool
 */
function mts_is_light_color( $color ){

	$color = mts_check_hex_color( $color );

	// Calculate straight from rbg
	$r = hexdec($color[0].$color[1]);
	$g = hexdec($color[2].$color[3]);
	$b = hexdec($color[4].$color[5]);

	return ( ( $r*299 + $g*587 + $b*114 )/1000 > 130 );
}

/**
 * Darken color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function mts_darken_color( $color, $amount = 10 ) {

	$hsl = mts_hex_to_hsl( $color );

	// Darken
	$hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
	$hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L']/100;

	// Return as HEX
	return mts_hsl_to_hex($hsl);
}

/**
 * Lighten color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function mts_lighten_color( $color, $amount = 10 ) {

	$hsl = mts_hex_to_hsl( $color );

	// Lighten
	$hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
	$hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L']/100;

	// Return as HEX
	return mts_hsl_to_hex($hsl);
}

/**
 * Used to create rgb from hexa decimal value
 * @return string
 */
function mts_convert_hex_to_rgb($hex){
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	return "$r, $g, $b";
}

/**
 * Generate css from background theme option.
 *
 * @param $option_id
 *
 * @return string|void
 */
if( ! function_exists( 'mts_get_background_styles' ) ) {
	function mts_get_background_styles( $option_id ) {

		$mts_options = get_option( MTS_THEME_NAME );

		if ( ! isset( $mts_options[ $option_id ]) ) {
			return;
		}

		$background_option = $mts_options[ $option_id ];
		$output = '';
		$background_image_type = isset( $background_option['use'] ) ? $background_option['use'] : '';

		if ( isset( $background_option['color'] ) && !empty( $background_option['color'] ) && 'gradient' !== $background_image_type ) {
			$output .= 'background-color:'.$background_option['color'].';';
		}

		if ( !empty( $background_image_type ) ) {

			if ( 'upload' == $background_image_type ) {

				if ( isset( $background_option['image_upload'] ) && !empty( $background_option['image_upload'] ) ) {
					$output .= 'background-image:url('.$background_option['image_upload'].');';
				}
				if ( isset( $background_option['repeat'] ) && !empty( $background_option['repeat'] ) ) {
					$output .= 'background-repeat:'.$background_option['repeat'].';';
				}
				if ( isset( $background_option['attachment'] ) && !empty( $background_option['attachment'] ) ) {
					$output .= 'background-attachment:'.$background_option['attachment'].';';
				}
				if ( isset( $background_option['position'] ) && !empty( $background_option['position'] ) ) {
					$output .= 'background-position:'.$background_option['position'].';';
				}
				if ( isset( $background_option['size'] ) && !empty( $background_option['size'] ) ) {
					$output .= 'background-size:'.$background_option['size'].';';
				}

			} else if ( 'gradient' == $background_image_type ) {

				$from	  = $background_option['gradient']['from'];
				$to		= $background_option['gradient']['to'];
				$direction = $background_option['gradient']['direction'];

				if ( !empty( $from ) && !empty( $to ) ) {

					$output .= 'background: '.$background_option['color'].';';

					if ( 'horizontal' == $direction ) {

						$output .= 'background: -moz-linear-gradient(left, '.$from.' 0%, '.$to.' 100%);';
						$output .= 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
						$output .= 'background: -webkit-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: -o-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: -ms-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: linear-gradient(to right, '.$from.' 0%,'.$to.' 100%);';
						$output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=1 );";

					} else {

						$output .= 'background: -moz-linear-gradient(top, '.$from.' 0%, '.$to.' 100%);';
						$output .= 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
						$output .= 'background: -webkit-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: -o-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: -ms-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
						$output .= 'background: linear-gradient(to bottom, '.$from.' 0%,'.$to.' 100%);';
						$output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=0 );";
					}
				}

			} else if ( 'pattern' == $background_image_type ) {

				$output .= 'background-image:url('.get_template_directory_uri().'/images/'.$background_option['image_pattern'].'.png'.');';
			}
		}

		return $output;
	}
}
/**
 * Add link to theme options panel inside admin bar
 */
function mts_admin_bar_link() {
	/** @var WP_Admin_bar $wp_admin_bar */
	global $wp_admin_bar;

	if( current_user_can( 'edit_theme_options' ) ) {
		$wp_admin_bar->add_menu( array(
			'id' => 'mts-theme-options',
			'title' => __( 'Theme Options', 'bloggingbox' ),
			'href' => admin_url( 'themes.php?page=theme_options' )
		) );
	}
}
add_action( 'admin_bar_menu', 'mts_admin_bar_link', 65 );


/**
 * Retrieves the attachment ID from the file URL
 *
 * @param $image_url
 *
 * @return string
 */
if( ! function_exists( 'mts_get_image_id_from_url' ) ) {
	function mts_get_image_id_from_url( $image_url ) {
		if ( is_numeric( $image_url ) ) return $image_url;
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
		if ( isset( $attachment[0] ) ) {
			return $attachment[0];
		} else {
			return false;
		}
	}
}

/**
 * Remove new line tags from string
 *
 * @param $text
 *
 * @return string
 */
function mts_escape_text_tags( $text ) {
	return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}

/**
 * Remove new line tags from string
 *
 * @return string
 */
if( ! function_exists( 'mts_single_post_schema' ) ) {
	function mts_single_post_schema() {

		if ( is_singular( 'post' ) ) {

			global $post, $mts_options;

			if ( has_post_thumbnail( $post->ID ) && !empty( $mts_options['mts_logo'] ) ) {

				$logo_id = mts_get_image_id_from_url( $mts_options['mts_logo'] );

				if ( $logo_id ) {

					$images  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$logo	= wp_get_attachment_image_src( $logo_id, 'full' );
					$excerpt = mts_escape_text_tags( $post->post_excerpt );
					$content = $excerpt === "" ? mb_substr( mts_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;

					$args = array(
						"@context" => "http://schema.org",
						"@type"	=> "BlogPosting",
						"mainEntityOfPage" => array(
							"@type" => "WebPage",
							"@id"   => get_permalink( $post->ID )
						),
						"headline" => ( function_exists( '_wp_render_title_tag' ) ? wp_get_document_title() : wp_title( '', false, 'right' ) ),
						"image"	=> array(
							"@type"  => "ImageObject",
							"url"	 => $images[0],
							"width"  => $images[1],
							"height" => $images[2]
						),
						"datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
						"dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
						"author" => array(
							"@type" => "Person",
							"name"  => mts_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
						),
						"publisher" => array(
							"@type" => "Organization",
							"name"  => get_bloginfo( 'name' ),
							"logo"  => array(
								"@type"  => "ImageObject",
								"url"	 => $logo[0],
								"width"  => $logo[1],
								"height" => $logo[2]
							)
						),
						"description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
					);

					echo '<script type="application/ld+json">' , PHP_EOL;
					echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
					echo '</script>' , PHP_EOL;
				}
			}
		}
	}
}
add_action( 'wp_head', 'mts_single_post_schema' );

if ( ! empty( $mts_options['mts_async_js'] ) ) {
	function mts_js_async_attr($tag){

		if (is_admin())
			return $tag;

		$async_files = apply_filters( 'mts_js_async_files', array(
			get_template_directory_uri() . '/js/ajax.js',
			get_template_directory_uri() . '/js/contact.js',
			get_template_directory_uri() . '/js/customscript.js',
			get_template_directory_uri() . '/js/jquery.magnific-popup.min.js',
			get_template_directory_uri() . '/js/layzr.min.js',
			get_template_directory_uri() . '/js/owl.carousel.min.js',
			get_template_directory_uri() . '/js/parallax.js',
			get_template_directory_uri() . '/js/sticky.js',
			get_template_directory_uri() . '/js/zoomout.js',
		 ) );

		$add_async = false;
		foreach ($async_files as $file) {
			if (strpos($tag, $file) !== false) {
				$add_async = true;
				break;
			}
		}

		if ($add_async)
			$tag = str_replace( ' src', ' async="async" src', $tag );

		return $tag;
	}
	add_filter( 'script_loader_tag', 'mts_js_async_attr', 10 );
}

if ( ! empty( $mts_options['mts_remove_ver_params'] ) ) {
	function mts_remove_script_version( $src ){

		if ( is_admin() )
			return $src;

		$parts = explode( '?ver', $src );
		return $parts[0];
	}
	add_filter( 'script_loader_src', 'mts_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'mts_remove_script_version', 15, 1 );
}

/*-----------------------------------------------------------------------------------*/
/*  Set $mts_latest_posts_layout global for paged home pages
/*-----------------------------------------------------------------------------------*/
function mts_home_posts_num() {

	if ( is_admin() || ! ( is_home() && is_paged() ) ) {
		return;
	}

	$mts_options = get_option(MTS_THEME_NAME);
	global $mts_latest_posts_layout;
	$mts_latest_posts_layout = 'magazinepost';
	if ( !empty( $mts_options['mts_featured_categories'] ) ) {
		foreach ( $mts_options['mts_featured_categories'] as $section ) {
			$category_id = $section['mts_featured_category'];
			if ( 'latest' == $category_id ) {
				$mts_latest_posts_layout = $section['mts_featured_category_layout'];
				break;
			}
		}
	}
}
add_filter('wp_head', 'mts_home_posts_num');


/*
 * Check if Latest Posts are being displayed on homepage and set posts_per_page accordingly
 */
function mts_home_posts_per_page($query) {
	global $mts_options;

	if ( ! $query->is_home() || ! $query->is_main_query() )
		return;

	$set_posts_per_page = 0;
	if ( ! empty( $mts_options['mts_featured_categories'] ) ) {
		foreach ( $mts_options['mts_featured_categories'] as $section ) {
			if ( $section['mts_featured_category'] == 'latest' ) {
				$set_posts_per_page = $section['mts_featured_category_postsnum'];
				break;
			}
		}
	}
	if ( ! empty( $set_posts_per_page ) ) {
		$query->set( 'posts_per_page', $set_posts_per_page );
	}
}
add_action( 'pre_get_posts', 'mts_home_posts_per_page' );

// Map images and categories in group field after demo content import
add_filter( 'mts_correct_single_import_option', 'mts_correct_homepage_sections_import', 10, 3 );
function mts_correct_homepage_sections_import( $item, $key, $data ) {

	if ( !in_array( $key, array( 'mts_custom_slider', 'mts_featured_categories' ) ) ) return $item;

	$new_item = $item;

	if ( 'mts_custom_slider' === $key ) {

		foreach( $item as $i => $image ) {

			$id = $image['mts_custom_slider_image'];

			if ( is_numeric( $id ) ) {

				if ( array_key_exists( $id, $data['posts'] ) ) {

					$new_item[ $i ]['mts_custom_slider_image'] = $data['posts'][ $id ];
				}

			} else {

				if ( array_key_exists( $id, $data['image_urls'] ) ) {

					$new_item[ $i ]['mts_custom_slider_image'] = $data['image_urls'][ $id ];
				}
			}
		}

	} else { // mts_featured_categories

		foreach( $item as $i => $category ) {

			$cat_id = $category['mts_featured_category'];

			if ( is_numeric( $cat_id ) && array_key_exists( $cat_id, $data['terms']['category'] ) ) {

				$new_item[ $i ]['mts_featured_category'] = $data['terms']['category'][ $cat_id ];
			}
		}
	}

	return $new_item;
}


if ( !function_exists('mts_like_dislike')) {
	function mts_like_dislike( $post_id = 0, $comment = false ) {
		if ($post_id == 0) $post_id = get_the_ID();
		$like_class = '';
		$dislike_class = '';
		if ( $rating = mts_has_rated( $post_id, $comment ) ) {
			if ( $rating == '1' ) {
				$like_class = 'active';
				$dislike_class = 'inactive';
			} else {
				$like_class = 'inactive';
				$dislike_class = 'active';
			}
		}
		if ( $comment ) {
			$likes = (int) get_comment_meta( $post_id, '_mts_likes', true );
			$dislikes = (int) get_comment_meta( $post_id, '_mts_dislikes', true );
			$comment_class = ' mts-comment-like-dislike';
		} else {
			$likes = (int) get_post_meta( $post_id, '_mts_likes', true );
			$dislikes = (int) get_post_meta( $post_id, '_mts_dislikes', true );
			$comment_class = '';
		}
		?>
		<div class="comment-likes-dislikes">
			<div class="post-like likeus <?php echo $like_class . $comment_class; ?>" data-postid="<?php echo $post_id; ?>">
			   <i class="fa fa-thumbs-up"></i>
			</div>
			<div class="like-count dislike-count"><?php echo ($likes - $dislikes); ?></div>
			<div class="post-dislike dislikeus <?php echo $dislike_class . $comment_class; ?>" data-postid="<?php echo $post_id; ?>">
				<i class="fa fa-thumbs-down"></i>
			</div>
		</div>
		<?php
	}
}

if( $mts_options['mts_like_dislike'] ) {
	add_action( 'wp_ajax_mts_rate', 'ajax_mts_rate' );
	add_action( 'wp_ajax_nopriv_mts_rate', 'ajax_mts_rate' );
	add_action( 'wp_ajax_mts_ratings', 'ajax_mts_ratings' );
	add_action( 'wp_ajax_nopriv_mts_ratings', 'ajax_mts_ratings' );
}
function ajax_mts_rate() {
	if (empty($_POST['post_id']) || empty($_POST['rating']))
		exit();
	$post_id = $_POST['post_id'];
	$comment = (bool) $_POST['comment_vote'];

	if ($_POST['rating'] == '1') {
		mts_like_post($post_id, $comment);
	} else if ($_POST['rating'] == '-1') {
		mts_dislike_post($post_id, $comment);
	}
	exit();
}
function ajax_mts_ratings() {
	if (empty($_POST['post_id']))
		exit();
	$post_id = $_POST['post_id'];
	$comment = (bool) $_POST['comment_vote'];
	if ( $comment ) {
		$likes = (int) get_comment_meta( $post_id, '_mts_likes', true );
		$dislikes = (int) get_comment_meta( $post_id, '_mts_dislikes', true );
	} else {
		$likes = (int) get_post_meta( $post_id, '_mts_likes', true );
		$dislikes = (int) get_post_meta( $post_id, '_mts_dislikes', true );
	}
	$has_rated = (int) mts_has_rated( $post_id, $comment ); // 0 / 1 / -1
	echo json_encode(
		array(
			'likes' => $likes,
			'dislikes' => $dislikes,
			'has_rated' => $has_rated
		)
	);
	exit();
}
function mts_like_post( $post_id, $comment = false ) {
	if (mts_has_rated($post_id, $comment))
		return;
	if ( $comment ) {
		$current_likes = get_comment_meta( $post_id, '_mts_likes', true );
		$current_likes++;
		update_comment_meta( $post_id, '_mts_likes', $current_likes );
	} else {
		$current_likes = get_post_meta( $post_id, '_mts_likes', true );
		$current_likes++;
		update_post_meta( $post_id, '_mts_likes', $current_likes );
	}

	mts_log_like($post_id, '1', $comment);
}

function mts_dislike_post( $post_id, $comment = false ) {
	if (mts_has_rated($post_id, $comment))
		return;

	if ( $comment ) {
		$current_dislikes = get_comment_meta( $post_id, '_mts_dislikes', true );
		$current_dislikes++;
		update_comment_meta( $post_id, '_mts_dislikes', $current_dislikes );
	} else {
		$current_dislikes = get_post_meta( $post_id, '_mts_dislikes', true );
		$current_dislikes++;
		update_post_meta( $post_id, '_mts_dislikes', $current_dislikes );
	}

	mts_log_like($post_id, '-1',$comment);
}
// check if current IP has liked/disliked post already
// returns false (didn't rate) or -1 (disliked) or 1 (liked)
function mts_has_rated( $post_id, $comment = false ) {
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$ip_list = $comment ? get_comment_meta( $post_id, '_mts_ips_rated', true ) : get_post_meta( $post_id, '_mts_ips_rated', true );
	if (empty($ip_list))
		return false;
	if (array_key_exists($user_ip, $ip_list)) {
		return $ip_list[$user_ip]; // 1 or -1
	}
	return false;
}
function mts_log_like($post_id, $rating = '1', $comment = false) {
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$ip_list = $comment ? get_comment_meta( $post_id, '_mts_ips_rated', true ) : get_post_meta( $post_id, '_mts_ips_rated', true );
	if (empty($ip_list))
		$ip_list = array();
	$ip_list[$user_ip] = $rating;
	if ( $comment ) {
		update_comment_meta( $post_id, '_mts_ips_rated', $ip_list );
	} else {
		update_post_meta( $post_id, '_mts_ips_rated', $ip_list );
	}
}

function mts_ajax_popular_posts() {
	$mts_options = get_option( MTS_THEME_NAME );
	$popular_class = 'page-magazine';
	foreach ( $mts_options['mts_featured_categories'] as $section ) {
		$category_id = $section['mts_featured_category'];
		$layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'magazinepost';
		if( $layout == 'gridpost' ) {
			$class = 'page-grid';
		} elseif( $layout == 'magazinepost' ) {
			$class = 'page-magazine';
		} elseif( $layout == 'fullpost' ) {
			$class = 'page-fullpost';
		}
		if ( 'latest' == $category_id ) {
			$popular_class = $class;
		}
	}
	?>
	<div class="article-wrap <?php echo $popular_class; ?>">
		<?php $j = 1; $cat_query = new WP_Query('posts_per_page='.$mts_options['mts_popular_tab_posts_num'].'&orderby=meta_value_num&meta_key=_mts_view_count&order=desc&date_query='.$mts_options['mts_popular_tab_posts_limit'].'&ignore_sticky_posts=1'); ?>
		<?php if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
			<?php mts_archive_post($layout, $j); ?>
		<?php endwhile; endif; wp_reset_postdata(); ?>
	</div>
	<?php
	exit();
}

add_action('wp_ajax_mts_popular_posts', 'mts_ajax_popular_posts');
add_action('wp_ajax_nopriv_mts_popular_posts','mts_ajax_popular_posts');

// Rank Math SEO.
if ( is_admin() && ! apply_filters( 'mts_disable_rmu', false ) ) {
    if ( ! defined( 'RMU_ACTIVE' ) ) {
        include_once( 'functions/rm-seo.php' );
    }
    $rm_upsell = MTS_RMU::init();
}

add_action( 'after_switch_theme', 'mts_theme_activation', 10, 2 );
