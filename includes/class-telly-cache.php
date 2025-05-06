<?php
class Telly_Cache
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
        // Constructor can be used for initialization if needed
    }

    public static function get($key)
    {
        $transient = get_transient($key);
        return $transient !== false ? $transient : false;
    }

    public static function set($key, $data, $expiration)
    {
        set_transient($key, $data, $expiration);
    }

    public static function delete($key)
    {
        delete_transient($key);
    }

    public static function clear_all()
    {
        // Note: This is a simple implementation. In a production environment, 
        // you might want a more sophisticated way to track and delete all Telly cache.
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
                '_transient_telly_%',
                '_transient_timeout_telly_%'
            )
        );
    }
}
