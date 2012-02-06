<br/><br/>
<div class="ad-gallery" id="binnash-gallery-<?php echo $albumId.'-'.time();?>">
</div>
<script type="text/javascript">
jQuery(function ($) {
    'use strict';
    $.ajax({
        url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
        data: {
            action: 'binnash_get_album_links',
            album_id: '<?php echo $albumId;?>'
        },
        dataType: 'json',
        success: function (data) {
            var $container = $('#binnash-gallery-<?php echo $albumId.'-'.time();?>');
            var ulContainer = $('<ul class="ad-thumb-list" />');            
            var alt = '';
            $.each(data, function (index, photo) {
                alt = (photo.name != null)? photo.name:'';
                $('<li />')
                .append($('<a rel="binnash-gallery-<?php echo $albumId.'-'.time();?>" ></a>')
                .append($('<img height=60 alt="'+alt+'" />').prop('src', photo.t))
                .prop('href', photo.n))
                .appendTo(ulContainer);
            });
            $('<div class="ad-image-wrapper" />')
            .appendTo($container);
            $('<div class="ad-controls" />')
            .appendTo($container);
            $('<div class="ad-nav" />')
            .append($('<div class="ad-thumbs" />')
            .append(ulContainer))
            .appendTo($container);
            $container.adGallery({
                loader_image:'<?php echo FBALBUM4WP_URL . '/images/loading.gif'?>'
            });            
        }        
    });
});
</script>