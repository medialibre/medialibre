jQuery(document).ready(function($) {
	//get variables
	var $wrap = $('#wtr-content');
	if($wrap.length > 0) {
		var $touch = $wrap.data('touch');
		if((!$touch && !isTouchDevice()) || $touch) {
			var $fg = $wrap.data('fg'),
				$bg = $wrap.data('bg'),
				$placement = $wrap.data('placement'),
				$placement_offset = $wrap.data('placement-offset'),
				$placement_touch = $wrap.data('placement-touch'),
				$placement_offset_touch = $wrap.data('placement-offset-touch'),
				$width = $wrap.data('width'),
				$transparent = $wrap.data('transparent'),
				$comments = $wrap.data('comments'),
				$comments_bg = $wrap.data('commentsbg'),
				$enable = $wrap.data('enable'),
				$mute = $wrap.data('mute'),
				$fgopacity = $wrap.data('fgopacity'),
				$mutedopacity = $wrap.data('mutedopacity'),
				$loc = $wrap.data('location');

			//set some defaults
			if($fg === null || $fg === '') $fg = '#ef490f';
			if($bg === null || $bg === '') $bg = '#CCCCCC';
			if($width === null || $width === '') $width = 4;
			if($comments_bg === null || $comments_bg === '') $comments_bg = '#999999';
			if($fgopacity === null || $fgopacity === '') $fgopacity = '.6';
			if($mutedopacity === null || $mutedopacity === '') $mutedopacity = '.5';
			if($transparent) {
				$bg = 'none';
				$comments_bg = 'none';
			}
			//get touch vs non-touch options
			$placement = isTouchDevice() ? $placement_touch : $placement;
			$placement_offset = isTouchDevice() ? $placement_offset_touch : $placement_offset;
			var $placement_offset_admin = $placement_offset + getTopOffset();
			//setup dom elements and add to page
			var $progress_bg = $('<div>', {id: 'wtr-progress', class: $placement});
			var $progress_fg = $('<div>', {id: 'wtr-slider'});
			$progress_bg.addClass('mute');

			$progress_bg.css('background', $bg);
			if($mute) $progress_bg.css('opacity', $mutedopacity);

			$progress_fg.css('background', $fg);
			$progress_fg.css('opacity', $fgopacity);

			if($comments) {
				var $progress_comments = $('<div>', {id: 'wtr-progress-comments'}),
					$commentsEnd = $('#wtr-comments-end'),
					$progress = $('#wtr-progress'),
					$wrap = $loc==='home' ? $('body') : $('#wtr-content'),
					winHeight = $(window).height(),
					totalHeight = $commentsEnd.offset().top - $wrap.offset().top,
					commentsHeight = totalHeight - $wrap.outerHeight(true) - (winHeight * .25),
					orientation = 'left',
					percentage = (100 - ((commentsHeight / totalHeight) * 100));

				//if position is right or left then our relative orientation is from the top
				if($placement=='left' || $placement=='right') orientation = 'top';
				//console.log('orientation=' + orientation);
				$progress_comments.css(orientation, percentage + '%');
				$progress_comments.css('background', $comments_bg);
				$progress_bg.append($progress_comments);
			}
			$progress_bg.append($progress_fg);
			if($transparent) $progress_bg.addClass('transparent');
			$('body').append($progress_bg);

			//adjust style after it's loaded to the DOM
			$hidden_by = $width + 6;
			$hidden_by_admin = -$hidden_by + getTopOffset();
			$width = $width + 'px';
			$hidden_by = '-' + $hidden_by + 'px';
			$hidden_by_admin = '-' + $hidden_by_admin + 'px';

			$('#wtr-progress.left').css({'width': $width, 'left': $hidden_by});
			$('#wtr-progress.right').css({'width': $width, 'right': $hidden_by});
			$('#wtr-progress.bottom').css({'height': $width, 'bottom': $hidden_by});
			$('#wtr-progress.top').css({'height': $width, 'top': $hidden_by});
			$('.admin-bar #wtr-progress.top').css({'height': $width, 'top': $hidden_by_admin});

			//if there is an offset, update the "shown" class
			if($placement_offset) {
				var $showncss = '<style>' +
									'#wtr-progress.top.shown {top:' + $placement_offset + 'px!important;}' +
									'.admin-bar #wtr-progress.top.shown {top:' + $placement_offset_admin + 'px!important;}' +
									'#wtr-progress.bottom.shown {bottom:' + $placement_offset + 'px!important;}' +
									'#wtr-progress.left.shown {left:' + $placement_offset + 'px!important;}' +
									'#wtr-progress.right.shown {right:' + $placement_offset + 'px!important;}' +
								'</style>';
				$($showncss).appendTo('body');
			}
		}
	}
	wtrProgress();
});

//utility for determining touch devices
function isTouchDevice() {
  return 'ontouchstart' in window // works on most browsers 
      || window.navigator.msMaxTouchPoints > 0; // works on ie10
}

//finds whether the bottom of the element is in the viewport
function bottomVisible(obj,offset){
    var a = obj.offset().top + offset,
        b = obj.outerHeight(true),
        c = jQuery(window).height(),
        d = jQuery(window).scrollTop();
    return ((c+d) >= (a+b));
}
//finds whether the top of the element is in the viewport
function topVisible(obj,offset){  
    var viewportHeight = jQuery(window).height(),
        documentScrollTop = jQuery(document).scrollTop(),
        minTop = documentScrollTop + offset,
        maxTop = documentScrollTop + viewportHeight,
        objOffset = obj.offset().top;
    return (objOffset >= minTop && objOffset <= maxTop);
}
// check how much admin bar is showing
function getTopOffset() {
	topOffset = 0;
	var $win = jQuery(window).width();
	if(jQuery('#wpadminbar').length > 0) {
	    if($win < 601) {
	    	topOffset += 0;
	    } else if($win < 783) {
	        topOffset += 46;
	    } else {
	        topOffset += 32;
	    }
	}
	return topOffset;
}

function wtrProgress() {
	var $win = jQuery(window),
		$wrap = jQuery('#wtr-content');
		$progress = jQuery('#wtr-progress'),
		$progress_comments = jQuery('#wtr-progress-comments'),
		$slider = jQuery('#wtr-slider');

	if($wrap.length > 0) {

		var $touch = $wrap.data('touch');
		var $mutedfg = $wrap.data('mutedfg');
		var $fg = $wrap.data('fg');

		if((!$touch && !isTouchDevice()) || $touch) {

			var $loc = $wrap.data('location'),
				$wrap = $loc==='home' ? jQuery('body') : jQuery('#wtr-content'),
				wrapHeight = $wrap.outerHeight(true),
				winHeight = $win.height();

			//we only want to do this if the content is greater than the window height
			//thus necessitating a visual progress indicator
			if(wrapHeight > winHeight) {
				var winScroll = $win.scrollTop(),
					wrapOffset = $wrap.offset().top,
					beyond = winScroll - wrapOffset + winHeight * .75,
					percentage = 0,
					topOffset = getTopOffset(),
					orientation = 'left',
					$rel = $wrap;
				//if position is right or left then our relative orientation is from the top
				if($progress.hasClass('left') || $progress.hasClass('right')) orientation = 'top';
				//do we need to account for comments?
				if($progress_comments.length > 0) {
					var $commentsEnd = jQuery('#wtr-comments-end'),
						totalHeight = $commentsEnd.offset().top - $wrap.offset().top,
						commentsHeight = totalHeight - $wrap.outerHeight(true);
					wrapHeight = totalHeight;
					$rel = $commentsEnd;
				}
				//we are into the content
				if(bottomVisible($rel, winHeight * .25)) {
					$slider.css(orientation, '0');
					$progress.addClass('mute');
					$slider.css('background', $mutedfg);
				} else if((topVisible($wrap, topOffset) || (winScroll + topOffset) > wrapOffset) && winScroll > 0 ) {
					$progress.addClass('shown').removeClass('mute');
					$slider.css('background', $fg);
					//how far past the start are we?
					percentage = -(100 - ((beyond / wrapHeight) * 100)); //get a negative percentage
					$slider.css(orientation, percentage + '%');
				} else {
					//console.log('winScroll=' + winScroll + '\nwrapOffset=' + wrapOffset);
					$slider.css(orientation, '-100%');
					$progress.removeClass('shown').removeClass('mute');
					$slider.css('background', $fg);
				}

				//mute progress bar after inactivity
				clearTimeout(jQuery.data(this, 'scrollTimer'));
			    jQuery.data(this, 'scrollTimer', setTimeout(function() {
			        $progress.addClass('mute');
			        $slider.delay(200).queue(function (next) {
			        	jQuery(this).css('background', $mutedfg);
			        	next();
			        });
			    }, 200));
			}
		}
	}
}

jQuery(window).scroll(function() {
	wtrProgress();
});