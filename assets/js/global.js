/* ------------------------------------------------------------------------------------------------
# Debounce
------------------------------------------------------------------------------------------------ */
/**
 * Debouncing function by John Hann
 * @see http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
 */
(function($,sr){

	var debounce = function (func, threshold, execAsap) {
		
			var timeout;

		return function debounced () {

			var obj = this, args = arguments;
			
			function delayed () {
				if (!execAsap)
					func.apply(obj, args);
					timeout = null;
			}

			if (timeout)
				clearTimeout(timeout);
			else if (execAsap)
				func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 100);
		
		};

	}

	// smartresize 
	jQuery.fn[sr] = function(fn) { 

		return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); 
	
	};
	
})(jQuery,'smartresize');

/* ------------------------------------------------------------------------------------------------
# Print Component
------------------------------------------------------------------------------------------------ */

function print_component() {

	var re = new RegExp(/.*C.*O.*M.*P.*O.*N.*E.*N.*T.*\$/)
	
	while (true) {
    
    	let lst = ['C', 'O', 'M', 'P', 'O', 'N', 'E', 'N', 'T', '$'];
    	let word = '';
    
    	for (let i = 0; i < 40; i++) {
    
      		let choice = choose([lst[0], '$', '$', '$'])
      		word = word + choice
    
      		if (choice != '$') {
        		let index = lst.indexOf(choice)
        		lst.splice(index, 1)
      		}
    
    	}
    	
    	if (re.test(word)) {
      		return `${word.substring(0, 20)}\n${word.substring(20, 40)}`
    	}
  	}
  
  	function choose(choices) {
    	var index = Math.floor(Math.random() * choices.length);
    	return choices[index];
  	}
}

/* ------------------------------------------------------------------------------------------------
# Document Ready
------------------------------------------------------------------------------------------------ */
jQuery(document).ready(function($){

	/* --------------------------------------------------------------------------------------------
	> Printcomp
	-------------------------------------------------------------------------------------------- */

	const printCompVal 	= print_component();

	$('body').prepend( '<div class="wrap--printcomp"><h1 class="printcomp"><a href="' + cortila.siteUrl + '">' + printCompVal + '</a></h1></div>' );

	/* --------------------------------------------------------------------------------------------
	# Single Post/Page Min Height
	-------------------------------------------------------------------------------------------- */

	function pageMinHeight() {

		const headerHeight 	= $('.wrap--printcomp').outerHeight(),
			  contentHeight = $('#page').outerHeight(),
			  footerHeight 	= $('#colophon').outerHeight();

		let	newContentHeight = ( window.innerHeight - ( headerHeight + footerHeight ) );

	  	if ( $('body').hasClass('logged-in') ) {

			const window_width = window.innerWidth;

			if ( window_width > 768 ) 	{ newContentHeight = ( newContentHeight - 32 ); } 
			else 						{ newContentHeight = ( newContentHeight - 46 ); }

	  	}

	  	$('#page').css({ minHeight: newContentHeight, })

	}

	pageMinHeight();
	$(window).smartresize(function(){ pageMinHeight(); });

	/* --------------------------------------------------------------------------------------------
	> Chocolat
	-------------------------------------------------------------------------------------------- */

	$('.entry-content img').each( function() {

		const $this = $(this);

		if ( $this.parent().is('a') ) {
			$this.parent().addClass('chocolat-image');
		}

	})

	$(window).on('load', function() {

		$('.chocolat-parent').Chocolat();

	})

	/* --------------------------------------------------------------------------------------------
	> Image size manipulation
	-------------------------------------------------------------------------------------------- */	
	// We need to balance somehow the output code difference based on the image 
	// definition ( linked / not linked / captioned / not captioned ) - so let's
	// use some helper classes 

	if ( $('body').hasClass('single-type') ) {

		// If the image is not linked
		$('img.wide-right, img.wide-left, img.wide-center, img.wide-fullwidth').each( function() {

			const $this = $(this);

			if ( $this.parent().is('figure') ) {

				if ( $this.hasClass('wide-left') )
					$this.removeClass('wide-left').parent().addClass('wide-left').removeAttr('style');

				if ( $this.hasClass('wide-right') )
					$this.removeClass('wide-right').parent().addClass('wide-right').removeAttr('style');

				if ( $this.hasClass('wide-center') )
					$this.removeClass('wide-center').parent().addClass('wide-center').removeAttr('style');

				if ( $this.hasClass('wide-fullwidth') )
					$this.removeClass('wide-fullwidth').parent().addClass('wide-fullwidth').removeAttr('style');														
			}

		})

		// If the image is linked AND has a caption
		$('a.wide-right, a.wide-left, a.wide-center, a.wide-fullwidth').each( function() {

			const $this = $(this);

			if ( $this.next().is('figcaption') ) {

				if ( $this.hasClass('wide-left') )
					$this.removeClass('wide-left').parent().addClass('wide-left').removeAttr('style');

				if ( $this.hasClass('wide-right') )
					$this.removeClass('wide-right').parent().addClass('wide-right').removeAttr('style');

				if ( $this.hasClass('wide-center') )
					$this.removeClass('wide-center').parent().addClass('wide-center').removeAttr('style');

				if ( $this.hasClass('wide-fullwidth') )
					$this.removeClass('wide-fullwidth').parent().addClass('wide-fullwidth').removeAttr('style');														
			}			

		})

	}

	/* --------------------------------------------------------------------------------------------
	> Video size manipulation
	-------------------------------------------------------------------------------------------- */

	if ( $('body').hasClass('single-type') ) {

		$('.entry-content p:empty, .wrap--custom-video br, .wrap--custom-video p').remove();

		if ( 
			 	$('.entry-content > *:first-child').is('.wrap--custom-video') 	|| 
			 	$('.entry-content > *:first-child').is('.wrap--gif-for-ios') 	||
			 	$('.entry-content > *:first-child').is('figure') 				||
				$('.entry-content > p:first-child > *:first-child').is('[class*="wide-"]')
		   ) {

		   	// Add basic '1st child is media' class
			$('body').addClass('first-is-custom-media');

			// Filter the specific 'wide-' class from the media element class array 
			// and add it to the body

			let $wideBearer;
			if ( $('.entry-content > p:first-child > *:first-child').is('[class*="wide-"]') ) {
				$wideBearer = $('.entry-content > p:first-child > *:first-child');
			} else {
				$wideBearer = $('.entry-content > *:first-child');				
			}

			$wideBearer.filter(function(){
				
				const classes = $(this).attr('class').split(" ");
				let found = false;
			    
			    for ( var i = 0; i < classes.length; i++ ) {
					if ( classes[i].substr(0, 4) == "wide" ){
			        	found = classes[i];
			            break;
					}
				}
				
				$('body').addClass( 'first-media--' + found );
			
			}); 

		}

		// The main video resize function

		function video_resize() {

			let windowWidth 	= window.innerWidth,
				viewportWidth 	= $(window).innerWidth(),
				contentWidth 	= $('.entry-content').outerWidth();

			console.log( viewportWidth + ' / ' + contentWidth );

			// IMPORTANT!!!
			// window.innerWidth 		returns the width INCLUDING SCROLLBAR, while
			// $(window).innerWidth() 	returns the width WITHOUT SCROLLBAR!!!
			// 
			// !!! 	For media queries, as surprising as it is, the window.innerWidth 
			// 		method is the correct version !!! 

			$('.wrap--custom-video').each( function() {
	
				const $this 			= $(this),
					  $wpVideo 			= $(this).find('.custom-video'),
					  $mejsContainer 	= $this.find('.mejs-container'),
					  $video 			= $this.find('video'); 

				let thisVidWidth 	= $(this).find('.mejs-container').outerWidth(),
					thisVidHeight  	= $(this).find('.mejs-container').outerHeight(),
					vRatio 			= thisVidHeight / thisVidWidth,
					newVideoWidth 	= contentWidth + ( ( viewportWidth - contentWidth ) / 2 ) - 30;

				if ( ( windowWidth > 1140 ) || 
					 ( ( windowWidth <= 1140 ) && $('body').hasClass('desktop-view') ) 
				   ) {

					// Width & Height
					let vWidth,
						vHeight; 		

					if ( $this.hasClass('wide-none') )
						vWidth 	= contentWidth - 60; 
					
					if ( $this.hasClass('wide-fullwidth') )
						vWidth 	= $(window).innerWidth(); 
					
					if ( !$this.hasClass('wide-none') && !$this.hasClass('wide-fullwidth') )
						vWidth 	= newVideoWidth; 

					vHeight = vWidth * vRatio;

					$this.add( $wpVideo ).add( $mejsContainer ).add( $video ).css({
						width: 	vWidth,
						height: vHeight,
					});
					console.log( vWidth + ' ' + vHeight );
					// Margin
					let vMarginLeft = 0;

					if ( $(this).hasClass('wide-left') ||
						 $(this).hasClass('wide-fullwidth')  
					   )
						vMarginLeft = ( ( ( viewportWidth - contentWidth ) / -2 ) - 30 );

					if ( $(this).hasClass('wide-center') )
						vMarginLeft = viewportWidth / -2;

					$(this).css({
						marginLeft: vMarginLeft,
					});

				} else if ( ( windowWidth < 1140 ) && ( windowWidth > 740 ) ) {

					$(this).add( $wpVideo ).add( $mejsContainer ).add( $video ).css({
						width: 		( contentWidth - 60 ),
						height: 	( contentWidth - 60 ) * vRatio,
						marginLeft: 0,
					});

				} else {

					$(this).add( $wpVideo ).add( $mejsContainer ).add( $video ).css({
						width: 		windowWidth,
						height: 	windowWidth * vRatio,
						marginLeft: 0,
					});

				}

				$(this).not('.video-loaded').addClass('video-loaded');

			})

		}

		$(window).on('load', function(){ 
		
			setTimeout( function(){ 
				video_resize(); 
			}, 200); 
		
		});

		$(window).smartresize(function(){ video_resize(); });

		$('.wrap--custom-video').not(':first-child').addClass('video-not-first');

	}

	
})