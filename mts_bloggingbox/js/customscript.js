jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
	callback.call(this, args);
  }
  return this;
};

/*----------------------------------------------------
/* Make all anchor links smooth scrolling
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
 // scroll handler
  var scrollToAnchor = function( id, event ) {
	// grab the element to scroll to based on the name
	var elem = $("a[name='"+ id +"']");
	// if that didn't work, look for an element with our ID
	if ( typeof( elem.offset() ) === "undefined" ) {
	  elem = $("#"+id);
	}
	// if the destination element exists
	if ( typeof( elem.offset() ) !== "undefined" ) {
	  // cancel default event propagation
	  event.preventDefault();

	  // do the scroll
	  // also hide mobile menu
	  var scroll_to = elem.offset().top;
	  $('html, body').removeClass('mobile-menu-active').animate({
			  scrollTop: scroll_to
	  }, 600, 'swing', function() { if (scroll_to > 46) window.location.hash = id; } );
	}
  };
  // bind to click event
  $("a").click(function( event ) {
	// only do this if it's an anchor link
	  var href = $(this).attr("href");
	  var exclude = ['#tab-description', '#tab-additional_information', '#tab-reviews'];
	  if (exclude.includes(href)) {
		  return;
	  }
	  if (href && href.match("#") && href !== '#' && !$(this).hasClass('comment-reply-link')) {
	  // scroll to the location
	  var parts = href.split('#'),
		url = parts[0],
		target = parts[1];
	  if ((!url || url == window.location.href.split('#')[0]) && target)
		scrollToAnchor( target, event );
	}
  });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
	jQuery(document).ready(function($){
		$('#primary-navigation').append('<div id="mobile-menu-overlay" />');
		// merge if two menus exist
		if (mts_customscript.nav_menu == 'both' && !$('.navigation.mobile-only').length) {
			$('.navigation').not('.mobile-menu-wrapper').find('.menu').clone().appendTo('.mobile-menu-wrapper').hide();
		}
	
		$('.toggle-mobile-menu').click(function(e) {
			e.preventDefault();
			e.stopPropagation();
			$('body').toggleClass('mobile-menu-active');

			if ( $('body').hasClass('mobile-menu-active') ) {
				if ( $(document).height() > $(window).height() ) {
					var scrollTop = ( $('html').scrollTop() ) ? $('html').scrollTop() : $('body').scrollTop();
					$('html').addClass('noscroll').css( 'top', -scrollTop );
				}
				$('#mobile-menu-overlay').fadeIn();
			} else {
				var scrollTop = parseInt( $('html').css('top') );
				$('html').removeClass('noscroll');
				$('html,body').scrollTop( -scrollTop );
				$('#mobile-menu-overlay').fadeOut();
			}
		});
	}).on('click', function(event) {

		var $target = jQuery(event.target);
		if ( ( $target.hasClass("fa") && $target.parent().hasClass("toggle-caret") ) ||  $target.hasClass("toggle-caret") ) {// allow clicking on menu toggles
			return;
		}
		jQuery('body').removeClass('mobile-menu-active');
		jQuery('html').removeClass('noscroll');
		jQuery('#mobile-menu-overlay').fadeOut();
	});
}

/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) {
	
	function mtsDropdownMenu() {
		var wWidth = $(window).width();
		if(wWidth > 865) {
			$('.navigation ul.sub-menu, .navigation ul.children').hide();
			var timer;
			var delay = 100;
			$('.navigation li').hover( 
			  function() {
				var $this = $(this);
				timer = setTimeout(function() {
					$this.children('ul.sub-menu, ul.children').slideDown('fast');
				}, delay);
				
			  },
			  function() {
				$(this).children('ul.sub-menu, ul.children').hide();
				clearTimeout(timer);
			  }
			);
		} else {
			$('.navigation li').unbind('hover');
			$('.navigation li.active > ul.sub-menu, .navigation li.active > ul.children').show();
		}
	}

	mtsDropdownMenu();

	$(window).resize(function() {
		mtsDropdownMenu();
	});
});

/*---------------------------------------------------
/*  Vertical menus toggles
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

	$('.widget_nav_menu, .navigation .menu').addClass('toggle-menu');
	$('.toggle-menu ul.sub-menu, .toggle-menu ul.children').addClass('toggle-submenu');
	$('.toggle-menu ul.sub-menu').parent().addClass('toggle-menu-item-parent');

	$('.toggle-menu .toggle-menu-item-parent').append('<span class="toggle-caret"><i class="fa fa-plus"></i></span>');

	$('.toggle-caret').click(function(e) {
		e.preventDefault();
		$(this).parent().toggleClass('active').children('.toggle-submenu').slideToggle('fast');
	});
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('span.facebookbtn, span.facebooksharebtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1&version=v2.8', 'fbjssdk');
	});
	jQuery('span.gplusbtn').exists(function() {
	  load('https://apis.google.com/js/plusone.js', 'gplus1js');
	});
	jQuery('span.twitterbtn').exists(function() {
	  load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
	  load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
	  load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	jQuery('span.stumblebtn').exists(function() {
	  load('//platform.stumbleupon.com/1/widgets.js', 'stumbleuponjs');
	});
	}(document, 'script'));
});

/*----------------------------------------------------
/* Lazy load avatars
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	var lazyloadAvatar = function(){
		$('.comment-author .avatar').each(function(){
			var distanceToTop = $(this).offset().top;
			var scroll = $(window).scrollTop();
			var windowHeight = $(window).height();
			var isVisible = distanceToTop - scroll < windowHeight;
			if( isVisible ){
				var hashedUrl = $(this).attr('data-src');
				if ( hashedUrl ) {
					$(this).attr('src',hashedUrl).removeClass('loading');
				}
			}
		});
	};
	if ( $('.comment-author .avatar').length > 0 ) {
		$('.comment-author .avatar').each(function(i,el){
			$(el).attr('data-src', el.src).removeAttr('src').addClass('loading');
		});
		$(function(){
			$(window).scroll(function(){
				lazyloadAvatar();
			});
		});
		lazyloadAvatar();
	}
});

/*----------------------------------------------------
/* Popular / Latest Post Tabs
/*---------------------------------------------------*/

function mts_loadTabContent( tab_name, page ) {
	
	var container = jQuery('#tab-content');
	var tab_content = jQuery(tab_name);
	if (!page) page = 1;
		
	// only load content if it wasn't already loaded
	var isLoaded = parseInt(tab_content.data('loaded')) || 0;
	if ( isLoaded < page ) {
		if (!container.hasClass('mts-loading') && !container.hasClass('mts-loading-page')) {
			
			if (page > 1) {
				container.addClass('mts-loading-page');
				tab_content = tab_content.append('<div class="more-page-'+page+'"></div>').find('.more-page-'+page);
			} else {
				container.addClass('mts-loading');
			}
			tab_content.load(mts_customscript.ajax_url, {
					action: 'mts_home_tabs_content',
					tab: tab_name,
					page: page
				}, function() {
					container.removeClass('mts-loading mts-loading-page');
					if (page == 1) {
						tab_content.data('loaded', page).hide().fadeIn().siblings().hide();
					} else {
						tab_content.hide().fadeIn().parent().data('loaded', page);
						tab_content.parent().find('[id="load-posts"]').hide().last().show();
					}
				}
			);
		}
	} else {
		tab_content.fadeIn().siblings().hide();
	}
}

/*----------------------------------------------------
/* Like / Dislike
/*---------------------------------------------------*/
if (mts_customscript.like) {
	jQuery(document).ready(function($) {
		if ($('.post-like').length) {
			$(document).on('click', '.post-like', function() {
				var $this = $(this),
					postid = $this.data('postid');
				if ($this.hasClass('active') || $this.hasClass('inactive')) {
					return false;
				}
				var comment = '0';
			if ( $this.hasClass('mts-comment-like-dislike')) {
			  comment = '1';
			}
				// ajax
				$.ajax({
					url: mts_customscript.ajax_url,
					type: 'POST',
					data: {action: 'mts_rate', post_id: postid, rating: '1', comment_vote: comment},
				})
				.always(function() {
					$this.addClass('active').parent().find('.like-count').text(function() { return parseInt($(this).text())+1; });
					$this.parent().find('.post-dislike').addClass('inactive');
				});
			});
			$(document).on('click', '.post-dislike', function() {
				var $this = $(this),
					postid = $this.data('postid');
				if ($this.hasClass('active') || $this.hasClass('inactive')) {
					return false;
				}
				var comment = '0';
			if ( $this.hasClass('mts-comment-like-dislike')) {
			  comment = '1';
			}
				// ajax
				$.ajax({
					url: mts_customscript.ajax_url,
					type: 'POST',
					data: {action: 'mts_rate', post_id: postid, rating: '-1', comment_vote: comment},
				})
				.always(function() {
					$this.addClass('active').parent().find('.like-count').text(function() { return parseInt($(this).text())-1; });
					$this.parent().find('.post-like').addClass('inactive');
				});
			});

			// Retreive ratings via JS to prevent caching
			$(window).load(function() {
			  $('.post-like').each(function() {
				var $this = $(this);
				var comment = '0';
				if ( $this.hasClass('mts-comment-like-dislike')) {
				  comment = '1';
				}
				  $.ajax({
					  url: mts_customscript.ajax_url,
					  type: 'POST',
					  dataType: 'json',
					  data: {action: 'mts_ratings', post_id: $this.data('postid'), comment_vote: comment}
				  })
				  .done(function(data) {
					  var $like = $this,
						  $dislike = $this.parent().find('.post-dislike');

					  $like.parent().find('.like-count').text(function() { return parseInt(data.likes)-parseInt(data.dislikes); });
					  var rated = parseInt(data.has_rated);
					  if (rated == 1) {
						  $like.addClass('active').removeClass('inactive');
						  $dislike.removeClass('active').addClass('inactive');
					  } else if (rated == -1) {
						  $dislike.addClass('active').removeClass('inactive');
						  $like.removeClass('active').addClass('inactive');
					  } else { // data.rated == 0
						  $like.removeClass('active inactive');
						  $dislike.removeClass('active inactive');
					  }
				  });
				});
			});
		}	 
	});
}
