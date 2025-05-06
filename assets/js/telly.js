jQuery(document).ready(function ($)
{
    // Load more videos for playlists
    $(document).on('click', '.telly-load-more', function (e)
    {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.closest('.telly-playlist-container');
        var playlistId = $container.data('playlist-id');
        var nextPage = $container.data('next-page');
        var limit = $container.data('limit');
        var columns = $container.data('columns');
        var loaded = $container.data('loaded');
        var total = $container.data('total-videos');

        $button.prop('disabled', true).text('Loading...');

        $.ajax({
            url: telly_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'telly_load_more',
                nonce: telly_ajax.nonce,
                playlist_id: playlistId,
                page_token: nextPage,
                limit: limit,
                columns: columns
            },
            success: function (response)
            {
                if (response.success) {
                    $container.find('.telly-playlist-grid').append(response.data.html);
                    $container.data('next-page', response.data.next_page);
                    $container.data('loaded', loaded + response.data.loaded);

                    if (!response.data.next_page || (loaded + response.data.loaded) >= total) {
                        $button.closest('.telly-load-more-container').remove();
                    } else {
                        $button.prop('disabled', false).text('Load More');
                    }
                } else {
                    alert(response.data);
                    $button.prop('disabled', false).text('Load More');
                }
            },
            error: function ()
            {
                alert('Error loading more videos');
                $button.prop('disabled', false).text('Load More');
            }
        });
    });
});