<?php
// make sure to not include translations
$args['presets']['default'] = array(
	'title' => 'Default',
	'demo' => 'http://demo.mythemeshop.com/bloggingbox/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/default/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts' ),
);

$args['presets']['fitness'] = array(
	'title' => 'Fitness',
	'demo' => 'http://demo.mythemeshop.com/bloggingbox-fitness/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/fitness/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts' ),
);

$args['presets']['news'] = array(
	'title' => 'News',
	'demo' => 'http://demo.mythemeshop.com/bloggingbox-news/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/news/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts' ),
);

$args['presets']['travel'] = array(
	'title' => 'Travel',
	'demo' => 'http://demo.mythemeshop.com/bloggingbox-travel/',
	'thumbnail' => get_template_directory_uri().'/options/demo-importer/demo-files/travel/thumb.jpg',
	'menus' => array( 'primary-menu' => 'Menu' ), // menu location slug => Demo menu name
	'options' => array( 'show_on_front' => 'posts' ),
);

global $mts_presets;
$mts_presets = $args['presets'];
