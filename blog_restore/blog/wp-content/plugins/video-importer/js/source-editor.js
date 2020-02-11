jQuery(document).ready(function($) {

	var current_provider = false;
	var post_id = $('#post_ID').val();

	//	Load provider in details meta box
	function load_provider_box( provider_slug ) {
		var data = {
			action: 'rf_video_source_details',
			nonce: $('input[name="source_information_nonce"]').val(),
			provider: provider_slug,
			id: post_id
		};
		$.post(ajaxurl, data, function(response) {
			$('#source-details #details-box').html(response);
			$('#source-details').removeClass('loading');
		});
	}

	//	Make provider buttons clickable
	$('#rf-video-provider-selector a').click(function(e) {
		e.preventDefault();
		$this = $(this);
		provider_slug = $this.data('provider-slug');
		if ( provider_slug == current_provider ) return false;
		current_provider = provider_slug;
		$('#source-details').addClass('loading');
		$('#rf-video-provider-selector a').removeClass('selected');
		$this.addClass('selected');
		// $('#source-details .handlediv').click();
		$('#rf_video_importer_provider').val(provider_slug);
		load_provider_box(provider_slug);
	});

	//	Import source now AJAX
	$('.rfvi-import-now').click(function(e) {
		e.preventDefault();
		$this = $(this);
		source_id = $this.data('source-id');
		var data = {
			action: 'rfvi_import_source',
			nonce: $this.data('nonce'),
			source_id: source_id
		};
		$this.closest('tr').addClass('rfvi-source-importing');
		$.post(ajaxurl, data, function(response) {

			var el = $('#post-'+response.source_id);
			var newone = el.clone(true)
				.removeClass('rfvi-source-importing')
				.addClass('rfvi-source-imported');
			el.before(newone);
			el.remove();

			$('#post-'+response.source_id+' > td.column-videos > strong')
				.text(response.total_videos+' ('+response.new_videos+' new)')
			$('#post-'+response.source_id+' > td.column-last_checked > strong')
				.text(response.last_checked)
			$('#post-'+response.source_id+' > td.column-last_checked > small')
				.text('(just now)')
		}, 'json');
	});

	//	Delete all from source now AJAX
	$('.rfvi-delete-all').click(function(e) {
		e.preventDefault();
		var confirmation = confirm("Are you sure you want to move this source's videos to the trash? Videos will not be reimported until removed from the trash.");
		if (confirmation == true) {
			$this = $(this);
			source_id = $this.data('source-id');
			var data = {
				action: 'rfvi_delete_videos_from_source',
				nonce: $this.data('nonce'),
				source_id: source_id
			};
			$.post(ajaxurl, data, function(response) {
				alert( response + ' posts deleted' );
			});
		}
	});

});