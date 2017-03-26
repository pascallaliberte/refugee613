<?php

the_content();

global $r613_services;
$r613_services->html_list_services( $post );

wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']);

?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
jQuery(document).ready( function( $ ) {
	r613_get_services( '<?php echo $r613_services->get_service_type( $post ); ?>' );
} );
</script>
