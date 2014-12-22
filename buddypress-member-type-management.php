<?php
/**
 * Plugin Name: BuddyPress Member Type Management
 * Plugin URI: http://dustyf.com
 * Description: Easily manage member types in BuddyPress
 * Author: Dustin Filippini
 * Author URI: http://dustyf.com
 * Version: 0.0.1
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if wp-content exists, only run code if it does not
if ( ! class_exists( 'BP_MTM' ) ) {

	/**
	 * Main Sample Plugin Class
	 *
	 * @since 1.0.0
	 */
	class BP_MTM {

		/**
		 * Construct function to get things started
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			/**
			 * Setup some base variables for the plugin
			 */
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );

			/**
			 * Include any required files
			 */
			add_action( 'init', array( $this, 'includes' ) );

			/**
			 * Load Textdomain
			 */
			load_plugin_textdomain( 'buddypress-member-type-management', false, dirname( $this->basename ) . '/languages' );

			/**
			 * Activation/Deactivation Hooks
			 */
			register_activation_hook(   __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			/**
			 * Make sure we have our requirements, and disable the plugin if we do not have them.
			 */
			add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		}


		/**
		 * Include our plugin dependencies
		 *
		 * @since 1.0.0
		 */
		public function includes() {

			if( $this->meets_requirements() ) {

			}

		} /* includes() */

		/**
		 * Register CPTs & taxonomies
		 *
		 * @since 1.0.0
		 */
		public function do_hooks() {

			add_action( 'bp_register_admin_settings', array( $this, 'register_admin_settings' ), 20 );

		} /* do_hooks() */

		/**
		 * Activation hook for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function activate() {

			if ( $this->meets_requirements() ) {

			}

		} /* activate() */

		/**
		 * Deactivation hook for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function deactivate() {

		} /* deactivate() */

		/**
		 * Check that all plugin requirements are met
		 *
		 * @since  1.0.0
		 *
		 * @return bool
		 */
		public static function meets_requirements() {

			/**
			 * We have met all requirements
			 */
			return true;

		} /* meets_requirements() */

		/**
		 * Check if the plugin meets requirements and disable it if they are not present.
		 *
		 * @since 1.0.0
		 */
		public function maybe_disable_plugin() {

			if ( ! $this->meets_requirements() ) {
				// Display our error
				echo '<div id="message" class="error">';
				echo '<p>' . sprintf( __( 'BuddyPress Member Type Management is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', '_s' ), admin_url( 'plugins.php' ) ) . '</p>';
				echo '</div>';

				// Deactivate our plugin
				deactivate_plugins( $this->basename );
			}

		} /* maybe_disable_plugin() */

		public function register_admin_settings() {

			/** Member Type Section **************************************************/
			// Add the main section
			add_settings_section( 'bp_member_type', _x( 'Member Type Settings', 'BuddyPress setting tab', 'buddypress-member-type-management' ), array( $this, 'settings_section' ), 'buddypress' );

			// Profile sync setting
			//add_settings_field( 'bp-disable-profile-sync',   __( 'Profile Syncing',  'buddypress' ), 'bp_admin_setting_callback_profile_sync',     'buddypress', 'bp_xprofile' );
			//register_setting  ( 'buddypress',         'bp-disable-profile-sync',     'intval'                                                                                  );

		}

		public function settings_section() {
			
			echo '<p>The following member types are registered:</p>';
			$member_types = bp_get_member_types();
			echo '<ul>';
			foreach ( $member_types as $member_type ) {
				echo '<li>' . esc_html( $member_type ) . '</li>';
			}
			echo '</ul>';

		}

	}

	$_GLOBALS['bp_mtm'] = new BP_MTM;
	$_GLOBALS['bp_mtm']->do_hooks();
}