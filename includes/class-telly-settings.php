<?php
class Telly_Settings
{

    private static $instance;
    private $options;

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
        $this->options = get_option('telly_settings', array());
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }

    public function add_admin_menu()
    {
        add_options_page(
            'Telly Settings',
            'Telly',
            'manage_options',
            'telly',
            array($this, 'options_page')
        );
    }

    public function settings_init()
    {
        register_setting('telly', 'telly_settings');

        add_settings_section(
            'telly_api_section',
            __('YouTube API Settings', 'telly'),
            array($this, 'settings_section_callback'),
            'telly'
        );

        add_settings_field(
            'youtube_api_key',
            __('YouTube API Key', 'telly'),
            array($this, 'api_key_render'),
            'telly',
            'telly_api_section'
        );

        add_settings_field(
            'cache_duration',
            __('Cache Duration (minutes)', 'telly'),
            array($this, 'cache_duration_render'),
            'telly',
            'telly_api_section'
        );
    }

    public function api_key_render()
    {
        $api_key = isset($this->options['youtube_api_key']) ? $this->options['youtube_api_key'] : '';
?>
        <input type='text' name='telly_settings[youtube_api_key]' value='<?php echo esc_attr($api_key); ?>' class='regular-text'>
        <p class='description'><?php _e('Enter your YouTube Data API v3 key', 'telly'); ?></p>
    <?php
    }

    public function cache_duration_render()
    {
        $duration = isset($this->options['cache_duration']) ? $this->options['cache_duration'] : 60;
    ?>
        <input type='number' name='telly_settings[cache_duration]' value='<?php echo esc_attr($duration); ?>' min='1'>
        <p class='description'><?php _e('How long to cache API responses (in minutes)', 'telly'); ?></p>
    <?php
    }

    public function settings_section_callback()
    {
        echo __('Configure your YouTube API settings below.', 'telly');
    }

    public function options_page()
    {
    ?>
        <div class="wrap">
            <h1>Telly Settings</h1>
            <form action='options.php' method='post'>
                <?php
                settings_fields('telly');
                do_settings_sections('telly');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }

    public static function get_api_key()
    {
        $instance = self::get_instance();
        return isset($instance->options['youtube_api_key']) ? $instance->options['youtube_api_key'] : '';
    }

    public static function get_cache_duration()
    {
        $instance = self::get_instance();
        return isset($instance->options['cache_duration']) ? $instance->options['cache_duration'] : 60;
    }
}
