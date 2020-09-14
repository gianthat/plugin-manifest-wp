<script type="text/javascript">
	jQuery(document).ready(function(){
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		jQuery('.button-force').click(function(){
			var email_id = jQuery('#plugin_manifest_wp_email_address').val();
			jQuery.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					action : 'get_all_items',
					email_id : email_id,
				},
				success : function( response ) {
					jQuery('.send-mail-result.success').css("display", "block");
				},
				error : function( response ) {
					jQuery('.send-mail-result.error').css("display", "block");
				}
			});
		});
	});
</script>