<?php
/**
 * Plugin Name: To Do List
 * Plugin URI: 
 * Description: A WordPress plugin that helps you keep track of the things you need to to.
 * Author: Kevin Carwile
 * Author URI: 
 * Version: 0.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

/* Load Only Once */
if ( class_exists( 'WPRXTodoListPlugin' ) ) {
	return;
}

/* Autoloaders */
include_once __DIR__ . '/includes/plugin-bootstrap.php';

/**
 * This plugin uses the MWP Application Framework to init.
 *
 * @return void
 */
add_action( 'mwp_framework_init', function() 
{
	/* Framework */
	$framework = MWP\Framework\Framework::instance();
	
	/**
	 * Plugin Core 
	 *
	 * Grab the main plugin instance and attach its annotated
	 * callbacks to WordPress core.
	 */
	$plugin	= WPRX\TodoList\Plugin::instance();
	$framework->attach( $plugin );
	
	/**
	 * Plugin Settings 
	 *
	 * Register a settings storage to the plugin which can be
	 * used to get/set/save settings to the wp_options table.
	 */
	$settings = WPRX\TodoList\Settings::instance();
	$plugin->addSettings( $settings );
	
	/* Register settings to a WP Admin page */
	// $framework->attach( $settings );
	
} );
