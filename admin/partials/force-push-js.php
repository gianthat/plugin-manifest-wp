<script>
jQuery(document).ready(function(){
	jQuery('.sendmailsuccess').hide();
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	jQuery('#plugin_manifest_wp_force_push').click(function(){
		var email_id = jQuery('#plugin_manifest_wp_email_address').val();
		jQuery.ajax({
							url : ajaxurl,
							type : 'post',
							data : {
								action : 'get_all_items',
								email_id : email_id,
							},
							success : function( response ) {
								jQuery('.sendmailsuccess').show();
							}
		});
	});
	
});
</script>