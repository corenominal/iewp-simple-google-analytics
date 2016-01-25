<?php

/**
 * Plugin Name: IEWP Simple Google Analytics 
 * Plugin URI: https://github.com/corenominal/iewp-google-analytics
 * Description: A simple plugin to allow site owners to apply their Google Analytics tracking code to their pages. Once activated, see the "Settings -> Analytics" menu item to configure.
 * Author: Philip Newborough
 * Version: 0.0.1
 * Author URI: https://corenominal.org
 */

/**
 * Plugin settings link
 */
function iewp_google_analytics_action_links( $actions, $plugin_file ) 
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file)
	{
		$settings = array('settings' => '<a href="options-general.php?page=options-iewp-google-analytics">' . __('Settings', 'General') . '</a>');
		//$site_link = array('support' => '<a href="http://corenominal.org" target="_blank">Support</a>');
	
		$actions = array_merge($settings, $actions);
		//$actions = array_merge($site_link, $actions);	
	}
	return $actions;
}
add_filter( 'plugin_action_links', 'iewp_google_analytics_action_links', 10, 5 );

/**
 * Output the tracking code.
 * Only outputs code if user is not logged in to WordPress.
 */
function iewp_google_analytics_output_tracking_code()
{
	if ( is_user_logged_in() )
	{
		echo "<!-- You are logged in, Google Analytics code has been disabled  -->\n";
	}
	else
	{
		$tracking_code = trim( get_option( 'iewp_google_analytics_code', '' ) );
		if($tracking_code != '')
		{
			echo $tracking_code . "\n";
		}
		else
		{
			echo "<!-- Google Analytics tracking code not available  -->\n";
		}
	}
}
add_action( 'wp_head', 'iewp_google_analytics_output_tracking_code' );

/**
 * Add submenu item to the default WordPress "Settings" menu.
 */
function iewp_google_analytics()
{
	add_submenu_page( 
		'options-general.php', // parent slug to attach to
		'Google Analytics', // page title
		'Analytics', // menu title
		'manage_options', // capability
		'options-iewp-google-analytics', // slug
		'iewp_google_analytics_callback' // callback function
		);

	// Activate custom settings
	add_action( 'admin_init', 'iewp_google_analytics_register' );
}
add_action( 'admin_menu', 'iewp_google_analytics' );

/**
 * iewp_google_analytics callback function.
 */
function iewp_google_analytics_callback()
{
	?>
	
		<div class="wrap">
			<h1>Google Analytics</h1>

			<p><a href="http://www.google.co.uk/analytics/">Google Analytics</a> is a freemium web analytics service offered by Google that tracks and reports website traffic. Google launched the service in November 2005 after acquiring Urchin. Google Analytics is now the most widely used web analytics service on the Internet.</p>

			<?php
			/**
			 * Test if tracking code is active.
			 */
			$tracking_code = trim( get_option( 'iewp_google_analytics_code', '' ) );
			if( $tracking_code == '' ):
			?>
				
				<hr>

				<p><a href="https://analytics.google.com/" target="_blank">Sign-in to Google Analytics</a>, copy your site's tracking code and paste it into the box below:</p>
			
			<?php else: ?>

				<hr>

				<p>Your Google Analytics tracking code is active, it will be inserted into the <code>&lt;HEAD&gt;</code> element of your site.
				<br><strong>Note:</strong> users who are logged in to WordPress will not be tracked.</p>

			<?php endif; ?>
			
			<form method="POST" action="options.php">
		
				<?php settings_fields( 'iewp_google_analytics_group' ); ?>
				<?php do_settings_sections( 'iewp_google_analytics_options' ); ?>
				<?php submit_button(); ?>

			</form>

		</div>

	<?php
}

/**
 * Register a custom option to hold tracking code.
 */
function iewp_google_analytics_register()
{
	register_setting( 'iewp_google_analytics_group', 'iewp_google_analytics_code');
	
	add_settings_section( 'iewp-google-analytics-options', '', 'iewp_google_analytics_options', 'iewp_google_analytics_options' );
	
	add_settings_field( 'iewp-google-analytics-code', 'Tracking Code', 'iewp_google_analytics_code', 'iewp_google_analytics_options', 'iewp-google-analytics-options' );
}

/**
 * Produce the form element/input field.
 */
function iewp_google_analytics_code()
{
	$setting = get_option( 'iewp_google_analytics_code' );
	echo '<textarea rows="14" class="widefat" name="iewp_google_analytics_code" placeholder="**** Paste your Google Analytics tracking code here ****">'.$setting.'</textarea>';
}

/**
 * Call back function for settings section. Do nothing.
 */
function iewp_google_analytics_options()
{
	return;
}