<?php

/* ------------------------------------------------------------------------------------------------
# Marginal Notes
------------------------------------------------------------------------------------------------ */

/**
 * Marginal Notes shortcode
 * 
 * @param  [type] $atts    the shortcode attributes array
 * @param  [type] $content the shortcode content (for enclosing shortcodes)
 * @return [type]          the returned shortcode output
 */
function cortila_marginal_note( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'align' => 'left',
		'color' => 'inherit',
		'style' => 'normal',
	), $atts ); 

	$output = "<div class='marginal-note {$a['align']}' style='color: {$a['color']}; font-style: {$a['style']}'>{$content}</div>";
	return $output;

}
add_shortcode( 'marginal-note', 'cortila_marginal_note' );

/* ------------------------------------------------------------------------------------------------
# Custom Video
------------------------------------------------------------------------------------------------ */

/**
 * Helper function - Unnamed (aka No-Value) WordPress shortcode attributes
 *
 * @see https://richjenks.com/unnamed-wordpress-shortcode-attributes/
 * 
 * @param  [type]  $flag The queried attrbiute
 * @param  [type]  $atts The attributes array
 * @return boolean       true if flag exists ( meaning the no-value 
 *                       attribute is specified )
 */
function is_flag( $flag, $atts ) {
	
	foreach ( $atts as $key => $value )
		if ( $value === $flag && is_int( $key ) ) return true;
	
	return false;

}

/**
 * Custom Video shortcode
 * 
 * @param  [type] $atts    the shortcode attributes array
 * @param  [type] $content the shortcode content (for enclosing shortcodes)
 * @return [type]          the returned shortcode output
 */
function cortila_custom_video( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'align'			=> 'none', 			// 'left' | 'right' | 'center' | 'fullwidth' | 'none'
		'align-gif'		=> '', 				// 'left' | 'right' | 'center' | 'fullwidth' | 'none'
		'controls' 		=> false,			// no-value
		'clickpause' 	=> false,			// no-value
		'loop' 			=> false, 			// no-value
		'autoplay' 		=> false, 			// no-value
		'muted' 		=> false, 			// no-value		
		'src-mp4' 		=> '', 				// (url)
		'src-webm' 		=> '', 				// (url)
		'src-ogg' 		=> '', 				// (url)
		'src-gif' 		=> '', 				// (url)
		'width' 		=> 1024, 			// (int)
		'height' 		=> 512, 			// (int)
		'width-gif' 	=> '', 				// (int)
		'height-gif' 	=> '',	 			// (int)		
		'caption'		=> '',				// (Str)
		'caption-gif' 	=> '', 				// (Str)
	), $atts ); 

	$a['autoplay']		= is_flag( 'autoplay', $atts ) 		? true : false;
	$a['clickpause'] 	= is_flag( 'clickpause', $atts ) 	? true : false;
	$a['controls'] 		= is_flag( 'controls', $atts ) 		? true : false;	
	$a['loop'] 			= is_flag( 'loop', $atts ) 			? true : false;
	$a['muted']			= is_flag( 'muted', $atts ) 		? true : false;		

	$control_class 		= ( true == $a['controls'] ) 	? 'control-freak' : 'no-control';
	$clickpause_class 	= ( true == $a['clickpause'] ) 	? 'youClick-iPause' : 'youClick-iLaugh'; 
	$mute_class 		= ( true == $a['muted'] ) 		? 'unsound' : 'sound'; 
	$margin_class 		= ( '' == $a['caption'] ) 		? 'has-margin' : 'no-margin';	

	$features 				= ( true == $a['controls'] ) 
								? ['playpause', 'current', 'progress', 'duration', 'tracks', 'volume', 'fullscreen']
								: [];

	$playerConfig 			= array(
								'clickToPlayPause'			=> ( true == $a['clickpause'] ) 
																? true : false, 
								'loop'						=> ( true == $a['loop'] ) 
																? true : false,
								'muted' 					=> true,
								'alwaysShowControls'		=> false, 
								'pauseOtherPlayers'			=> false, 
								'enableProgressTooltip'		=> false, 
								'hideVideoControlsOnLoad'	=> true, 
								'features'					=> $features
							  );

	$detect = new Mobile_Detect;

	ob_start(); 

	// If the src-mp4 attribute value is a valid url
	if ( filter_var( $a['src-mp4'], FILTER_VALIDATE_URL ) ) :

		// If not on iOS
		if ( !$detect->isiOS() || 
			 ( $detect->isiOS() && ( '' == $a['src-gif'] ) )
		   ) :
		?>

			<div class="wrap--custom-video <?php echo 'wide-' . $a['align'] . ' ' . $margin_class; ?>">

				<div class="custom-video <?php echo $control_class . ' ' . $clickpause_class; ?>">	

					<video 
						poster="" preload="none" 
						<?php if ( true == $a['autoplay'] ) echo 'autoplay'; ?> 
						<?php if ( true == $a['muted'] ) echo 'muted'; ?> 
						class="mejs__player " 
						data-mejsoptions='<?php echo json_encode( $playerConfig ); ?>' 
						width="<?php echo $a['width']; ?>" 
						height="<?php echo $a['height']; ?>" 
						style="width: 100%; height: 100%; background-color: #fff!important;">
	 
						<source type='video/mp4' src="<?php echo $a['src-mp4']; ?>" />
						
						<?php 
						if ( '' !== $a['src-webm'] )	
							echo "<source type='video/webm' src='{$a['src-webm']}' />";
							
						if ( '' !== $a['src-ogg'] )
							echo "<source type='video/ogg' src='{$a['src-ogg']}' />";
						
						?>

					</video>

				</div>

			</div>
			
			<?php 
			if ( '' !== $a['caption'] )
				echo "<div class='custom-caption'>{$a['caption']}</div>";			

		endif;

		// If on iOS AND the src-gif attribute has a value AND the value is a valid url
		if ( $detect->isiOS() && filter_var( $a['src-gif'], FILTER_VALIDATE_URL ) ) :

			$gif_caption 		= ( '' == $a['caption-gif'] ) 
									? $a['caption']
									: $a['caption-gif'];	

			$gif_class_Str 		= '';
			
			// Add alignment class
			$gif_class_Str 		.= ( '' == $a['align-gif'] ) 
									? 'wide-' . $a['align']
									: 'wide-' . $a['align-gif'];
			// Add margin class
			$gif_class_Str 		.= ( '' == $gif_caption )
									? ' has-margin'
									: ' no-margin';	
			// Add size class
			$gif_class_Str 		.= ( ( '' !== $a['width-gif'] ) && ( '' !== $a['height-gif'] ) )
									? ' has-size'
									: ' no-size';									
			?>

			<div class="wrap--gif-for-ios <?php echo $gif_class_Str; ?>">

				<div class="gif-for-ios">
					
					<img src="<?php echo $a['src-gif']; ?>" width="<?php echo $a['width-gif']; ?>" height="<?php echo $a['height-gif']; ?>" alt="<?php echo $gif_caption; ?>">

				</div>

			</div>

			<?php 
			if ( ( '' !== $gif_caption ) )
				echo "<div class='custom-caption'>{$gif_caption}</div>";			

		endif;

	endif;

	$output = ob_get_clean();
	return $output;


}
add_shortcode( 'custom-video', 'cortila_custom_video' );