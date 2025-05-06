<?php
class Telly_Shortcodes
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
        add_shortcode('telly_video', array($this, 'video_shortcode'));
        add_shortcode('telly_playlist', array($this, 'playlist_shortcode'));
        add_action('wp_ajax_telly_load_more', array($this, 'load_more_videos'));
        add_action('wp_ajax_nopriv_telly_load_more', array($this, 'load_more_videos'));
    }

    public function video_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'id' => '',
            'width' => '100%',
            'height' => '400'
        ), $atts, 'telly_video');

        if (empty($atts['id']))
        {
            return '<p>' . __('Please provide a YouTube video ID', 'telly') . '</p>';
        }

        $video_id = esc_attr($atts['id']);
        $width = esc_attr($atts['width']);
        $height = esc_attr($atts['height']);

        $video_data = Telly_API::get_video_data($video_id);

        if (!$video_data)
        {
            return '<p>' . __('Video not found or API error occurred', 'telly') . '</p>';
        }

        ob_start();
?>
        <div class="telly-video-container" style="width: <?php echo $width; ?>; max-width: 100%;">
            <div class="telly-video-wrapper" style="position: relative; padding-bottom: <?php echo ($height / $width) * 100; ?>%; height: 0; overflow: hidden;">
                <iframe
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>?rel=0"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>
            <div class="telly-video-info">
                <h3 class="telly-video-title"><?php echo esc_html($video_data['title']); ?></h3>
                <p class="telly-video-description"><?php echo esc_html($video_data['description']); ?></p>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }

    public function playlist_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'id' => '',
            'columns' => '1',
            'limit' => '10',
            'show_more' => 'true'
        ), $atts, 'telly_playlist');

        if (empty($atts['id']))
        {
            return '<p>' . __('Please provide a YouTube playlist ID', 'telly') . '</p>';
        }

        $playlist_id = esc_attr($atts['id']);
        $columns = in_array($atts['columns'], array('1', '2', '3')) ? (int)$atts['columns'] : 1;
        $limit = (int)$atts['limit'];
        $show_more = filter_var($atts['show_more'], FILTER_VALIDATE_BOOLEAN);
                
        $playlist_data = Telly_API::get_playlist_data($playlist_id, $limit);
        if (!$playlist_data || empty($playlist_data['videos']))
        {
            return '<p>' . __('Playlist not found or API error occurred', 'telly') . '</p>';
        }

        $videos = $playlist_data['videos'];
        $next_page_token = $playlist_data['next_page_token'];
        $total_videos = $playlist_data['total_videos'];

        ob_start();
    ?>
        <div class="telly-playlist-container"
            data-playlist-id="<?php echo $playlist_id; ?>"
            data-columns="<?php echo $columns; ?>"
            data-limit="<?php echo $limit; ?>"
            data-next-page="<?php echo esc_attr($next_page_token); ?>"
            data-total-videos="<?php echo $total_videos; ?>"
            data-loaded="<?php echo count($videos); ?>">

            <div class="telly-playlist-grid telly-columns-<?php echo $columns; ?>">
                <?php foreach ($videos as $video) : ?>
                    <div class="telly-playlist-item">
                        <div class="telly-video-thumbnail">
                            <a href="https://www.youtube.com/watch?v=<?php echo $video['video_id']; ?>" target="_blank">
                                <img src="<?php echo esc_url($video['thumbnail']); ?>" alt="<?php echo esc_attr($video['title']); ?>">
                                <div class="telly-play-icon">▶</div>
                            </a>
                        </div>
                        <h4 class="telly-video-title"><?php echo esc_html($video['title']); ?></h4>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($show_more && !empty($next_page_token) && count($videos) < $total_videos) : ?>
                <div class="telly-load-more-container">
                    <button class="telly-load-more"><?php _e('Load More', 'telly'); ?></button>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function load_more_videos()
    {
        check_ajax_referer('telly-nonce', 'nonce');

        $playlist_id = isset($_POST['playlist_id']) ? sanitize_text_field($_POST['playlist_id']) : '';
        $page_token = isset($_POST['page_token']) ? sanitize_text_field($_POST['page_token']) : '';
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
        $columns = isset($_POST['columns']) ? (int)$_POST['columns'] : 1;

        if (empty($playlist_id))
        {
            wp_send_json_error(__('Playlist ID is required', 'telly'));
        }

        $playlist_data = Telly_API::get_playlist_data($playlist_id, $limit, $page_token);

        if (!$playlist_data || empty($playlist_data['videos']))
        {
            wp_send_json_error(__('No more videos found', 'telly'));
        }

        $videos = $playlist_data['videos'];
        $next_page_token = $playlist_data['next_page_token'];
        $total_videos = $playlist_data['total_videos'];

        ob_start();
        foreach ($videos as $video) : ?>
            <div class="telly-playlist-item">
                <div class="telly-video-thumbnail">
                    <a href="https://www.youtube.com/watch?v=<?php echo $video['video_id']; ?>" target="_blank">
                        <img src="<?php echo esc_url($video['thumbnail']); ?>" alt="<?php echo esc_attr($video['title']); ?>">
                        <div class="telly-play-icon">▶</div>
                    </a>
                </div>
                <h4 class="telly-video-title"><?php echo esc_html($video['title']); ?></h4>
            </div>
<?php endforeach;

        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'next_page' => $next_page_token,
            'total_videos' => $total_videos,
            'loaded' => count($videos)
        ));
    }
}
