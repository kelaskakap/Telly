<?php

/**
 * Plugin Name: Telly
 * Plugin URI: https://example.com/telly
 * Description: A plugin to display YouTube videos and playlists with various options.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: telly
 * Domain Path: /languages
 */

defined('ABSPATH') or die('No direct access allowed!');

// Define plugin constants
define('TELLY_VERSION', '1.0.0');
define('TELLY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TELLY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once TELLY_PLUGIN_DIR . 'includes/class-telly-settings.php';
require_once TELLY_PLUGIN_DIR . 'includes/class-telly-api.php';
require_once TELLY_PLUGIN_DIR . 'includes/class-telly-cache.php';
require_once TELLY_PLUGIN_DIR . 'includes/class-telly-shortcodes.php';

class Telly
{

    private static $instance;

    public static function get_instance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        // Initialize classes
        Telly_Settings::get_instance();
        Telly_Shortcodes::get_instance();

        // Load assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets()
    {
        // CSS
        wp_enqueue_style(
            'telly-style',
            TELLY_PLUGIN_URL . 'assets/css/telly.css',
            array(),
            TELLY_VERSION
        );

        // JS
        wp_enqueue_script(
            'telly-script',
            TELLY_PLUGIN_URL . 'assets/js/telly.js',
            array('jquery'),
            TELLY_VERSION,
            true
        );

        wp_localize_script(
            'telly-script',
            'telly_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('telly-nonce')
            )
        );
    }
}

// Initialize the plugin
Telly::get_instance();
