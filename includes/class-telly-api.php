<?php
class Telly_API
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

    public static function get_video_data($video_id)
    {
        $cache_key = 'telly_video_' . $video_id;
        $cached_data = Telly_Cache::get($cache_key);

        if ($cached_data !== false)
        {
            return $cached_data;
        }

        $api_key = Telly_Settings::get_api_key();
        $api_url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$video_id}&key={$api_key}";

        $response = wp_remote_get($api_url);

        if (is_wp_error($response))
        {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data['items']))
        {
            return false;
        }

        $video_data = array(
            'title' => $data['items'][0]['snippet']['title'],
            'description' => $data['items'][0]['snippet']['description'],
            'thumbnail' => $data['items'][0]['snippet']['thumbnails']['high']['url'],
            'video_id' => $video_id
        );

        // Cache the data
        $cache_duration = Telly_Settings::get_cache_duration() * 60; // Convert to seconds
        Telly_Cache::set($cache_key, $video_data, $cache_duration);

        return $video_data;
    }

    public static function get_playlist_data($playlist_id, $max_results = 10, $page_token = '')
    {
        $cache_key = 'telly_playlist_' . $playlist_id . '_' . $max_results . '_' . $page_token;
        $cached_data = Telly_Cache::get($cache_key);

        if ($cached_data !== false)
        {
            return $cached_data;
        }

        $api_key = Telly_Settings::get_api_key();
        $api_url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&maxResults={$max_results}&key={$api_key}";

        if (!empty($page_token))
        {
            $api_url .= "&pageToken={$page_token}";
        }

        $response = wp_remote_get($api_url);
        
        file_put_contents('o:\susu.txt', var_export($response, true));
        if (is_wp_error($response))
        {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data['items']))
        {
            return false;
        }

        $videos = array();
        foreach ($data['items'] as $item)
        {
            $videos[] = array(
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                'video_id' => $item['snippet']['resourceId']['videoId']
            );
        }

        $playlist_data = array(
            'videos' => $videos,
            'next_page_token' => isset($data['nextPageToken']) ? $data['nextPageToken'] : '',
            'total_videos' => isset($data['pageInfo']['totalResults']) ? $data['pageInfo']['totalResults'] : 0
        );

        // Cache the data
        $cache_duration = Telly_Settings::get_cache_duration() * 60; // Convert to seconds
        Telly_Cache::set($cache_key, $playlist_data, $cache_duration);

        return $playlist_data;
    }
}
