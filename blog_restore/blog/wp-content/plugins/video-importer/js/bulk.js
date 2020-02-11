jQuery(function ($) {

	function RFVIBulkImporter( sourceID ) {
		this.sourceID       = sourceID;
		this.currentItem    = 0;
		this.videos         = new Array();
		this.paused         = false;
		this.newVideos      = 0;
		this.existingVideos = 0;
		this.delay          = 500;
		this.delayTimer     = false;
		this.nextSourcePage = 0;
		this.pageDelay      = 1000;
		this.pageDelayTimer = false;
		this.logList        = $('#rfvi-bulk-results .log');
		this.progressBar    = $('#rfvi-bulk-results .progress-bar');
	}

	RFVIBulkImporter.prototype.log = function(text) {
		d = new Date();
		$('<li>'+text+'</li>').prependTo(this.logList).hide().slideDown(200);
		console.log(text);
	};

	RFVIBulkImporter.prototype.startProcessing = function() {
		this.paused = false;
		if ( this.currentItem == 0 ) {
			this.log( 'Started processing ' + this.videos.length + ' videos' );
		} else {
			this.log( 'Resumed processing' );
		}
		this.processCurrentItem();
	};

	RFVIBulkImporter.prototype.pauseProcessing = function() {
		this.clearSchedule();
		this.paused = true;
		this.log( 'Paused Processing' );
	};

	RFVIBulkImporter.prototype.toggleProcessing = function() {
		if ( this.paused ) {
			this.startProcessing();
		} else {
			this.pauseProcessing();
		}
	};

	RFVIBulkImporter.prototype.processingCompleted = function() {
		this.log( 'Done! Processed ' + this.videos.length + ' videos' );
	};

	RFVIBulkImporter.prototype.resetProgressBar = function() {
		$('#rfvi-bulk-results .percentage').html('0%');
		this.progressBar
			.addClass('disable-animation')
			.css('width','0')
		this.progressBar.height();
		this.progressBar.removeClass('disable-animation');
	};

	RFVIBulkImporter.prototype.updateProgressBar = function() {
		console.log( percentage = ( this.currentItem + 1 ) / this.videos.length );
		if ( percentage == 1 ) {
			progressText = 'Done!';
			this.processingCompleted();
		} else {
			progressText = Math.round(percentage*100)+'%';
		}
		$('#rfvi-bulk-results .percentage').html(progressText);
		this.progressBar.css('width',(percentage*100)+'%');
	};

	RFVIBulkImporter.prototype.updateCounter = function() {
		$('#rfvi-bulk-results .stats').html( 'Imported ' + this.newVideos + ' / Skipped ' + this.existingVideos + ' / Processing ' + (this.currentItem+1) + ' of ' + this.videos.length );
	}

	RFVIBulkImporter.prototype.updateStats = function() {
		this.updateProgressBar();
		this.updateCounter();
	}

	RFVIBulkImporter.prototype.scheduleNextItem = function() {
		if ( ( this.currentItem + 1 ) < this.videos.length ) {
			var self = this;
			self.currentItem++;
			this.delayTimer = setTimeout(function() {
				self.processCurrentItem();
			}, this.delay);
		}
	}

	RFVIBulkImporter.prototype.clearSchedule = function() {
		clearTimeout( this.delayTimer );
	}

	RFVIBulkImporter.prototype.processCurrentItem = function() {

		if ( this.paused ) return false;

		if ( this.currentItem < this.videos.length ) {

			this.log( 'Processing "' + this.videos[this.currentItem].title + '" (' + (this.currentItem+1) + ' of ' + this.videos.length + ')' );
			this.updateCounter();

			var data = {
				action: 'rfvi_import_video_from_bulk',
				source_id: this.sourceID,
				video_array: this.videos[this.currentItem]
			};
			var self = this;
			$.post(ajaxurl, data, function(response) {
				if ( response == 'exists' ) {
					self.existingVideos++;
					self.log( 'Skipping "' + self.videos[self.currentItem].title + '", already exists' );
				} else if ( response == 'new' ) {
					self.newVideos++;
					self.log( 'Imported "' + self.videos[self.currentItem].title + '" successfully' );
				} else {
					self.log( 'Error while importing "' + self.videos[self.currentItem].title + '": ' + response );
				}
				self.updateStats();
				self.scheduleNextItem();
			});

		} else {
			this.updateStats();
			this.currentItem = 0;
		}

	};

	RFVIBulkImporter.prototype.import = function() {
		// Confirm
		var r = confirm("This import all videos from this source. It may take awhile. Continue?");
		if ( r == true ) {
			this.progressBar.show();
			this.resetProgressBar();
			$('form#rfvi-bulk-process').slideUp();
			this.log('Getting videos from source ID ' + this.sourceID);
			x.getNextSourcePage();
		} else {
			x.log('Cancelled');
		}
	}

	RFVIBulkImporter.prototype.getNextSourcePage = function() {
		var data = {
			action: 'rfvi_get_source_page',
			source_id: this.sourceID,
			page: this.nextSourcePage
		};
		this.log('Getting next page (' + this.nextSourcePage + ') from source...');
		var self = this;
		$.post(ajaxurl, data, function(response) {
			self.videos = self.videos.concat(response.videos);
			self.log( response.videos.length + ' videos added to queue' );
			if ( response.next_page != false ) {
				self.nextSourcePage = response.next_page;
				self.pageDelayTimer = setTimeout(function() {
					self.getNextSourcePage();
				}, self.pageDelay);
			} else {
				self.log( 'Reached last page' );
				self.startProcessing();
			}
		}, 'json');
	}

	$('form#rfvi-bulk-process').on('submit',function(e){
		// Ready...
		e.preventDefault();
		// Set...
		x = new RFVIBulkImporter( $(this).find('select[name=source-id]').val() );
		// Go!
		x.import();
	});

});