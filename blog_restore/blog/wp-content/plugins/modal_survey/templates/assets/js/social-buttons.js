/*============================================================================
  Social Icon Buttons v1.0
  Author:
    Carson Shold | @cshold
    http://www.carsonshold.com
  MIT License
==============================================================================*/
window.CSbuttons = window.CSbuttons || {};
if ( document.referrer ) {
   if ( document.referrer.indexOf( "facebook.com" ) > 0 && window.location.search.indexOf( 'post_id' ) == 1 ) {
	   window.close();
   }
}

jQuery(function() {
  CSbuttons.cache = {
    $shareButtons: jQuery('.social-sharing')
  }
});

CSbuttons.init = function () {
  CSbuttons.socialSharing();
}

CSbuttons.socialSharing = function () {
  var $buttons = CSbuttons.cache.$shareButtons,
      $shareLinks = $buttons.find('a'),
      permalink = $buttons.attr('data-permalink');

  // Get share stats from respective APIs
  var $fbLink = jQuery('.share-facebook'),
      $twitLink = jQuery('.share-twitter'),
      $pinLink = jQuery('.share-pinterest'),
      $googleLink = jQuery('.share-google');
      $linkedinLink = jQuery('.share-linkedin');

  if ( $fbLink.length && $fbLink.find('.share-count').length ) {
    jQuery.getJSON('https://graph.facebook.com/?id=' + permalink + '&callback=?')
      .done(function(data) {
        if (data.shares) {
          $fbLink.find('.share-count').text(data.shares).addClass('is-loaded');
        } else {
          $fbLink.find('.share-count').remove();
        }
      })
      .fail(function(data) {
        $fbLink.find('.share-count').remove();
      });
  };

  if ( $twitLink.length && $twitLink.find('.share-count').length ) {
    jQuery.getJSON('https://cdn.api.twitter.com/1/urls/count.json?url=' + permalink + '&callback=?')
      .done(function(data) {
        if (data.count > 0) {
          $twitLink.find('.share-count').text(data.count).addClass('is-loaded');
        } else {
          $twitLink.find('.share-count').remove();
        }
      })
      .fail(function(data) {
        $twitLink.find('.share-count').remove();
      });
  };

  if ( $pinLink.length && $pinLink.find('.share-count').length ) {
    jQuery.getJSON('https://api.pinterest.com/v1/urls/count.json?url=' + permalink + '&callback=?')
      .done(function(data) {
        if (data.count > 0) {
          $pinLink.find('.share-count').text(data.count).addClass('is-loaded');
        } else {
          $pinLink.find('.share-count').remove();
        }
      })
      .fail(function(data) {
        $pinLink.find('.share-count').remove();
      });
  };

  if ( $linkedinLink.length && $linkedinLink.find('.share-count').length ) {
    jQuery.getJSON('http://www.linkedin.com/countserv/count/share?url=' + permalink + '&format=json')
      .done(function(data) {
        if (data.count) {
          $linkedinLink.find('.share-count').text(data.count).addClass('is-loaded');
        } else {
          $linkedinLink.find('.share-count').remove();
        }
      })
      .fail(function(data) {
        $linkedinLink.find('.share-count').remove();
      });
  };

  if ( $googleLink.length && $googleLink.find('.share-count').length ) {
    // Can't currently get Google+ count with JS, so just pretend it loaded
    $googleLink.find('.share-count').addClass('is-loaded');
  }

  // Share popups
  jQuery( document ).on( 'click', '.ms-social-share', function( e ) {
    e.preventDefault();
    var el = jQuery( this );
    var popup = el.attr( 'class' ).replace( 'ms-social-share', '' ).replace( '-','_' );
    var link = el.attr( 'href' );
    var w = 700, h = 400;

    // Set popup sizes
    switch ( popup.trim() ) {
      case 'share_twitter':
        h = 300;
        break;
      case 'share_google':
        w = 500;
        break;
    }

    window.open(link, popup, 'width=' + w + ', height=' + h);
  });
}
jQuery(function() {
  window.CSbuttons.init();
});