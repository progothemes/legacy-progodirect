<?php

add_action( 'after_setup_theme', 'progodirect_setup', 20 );
function progodirect_setup() {
	add_image_size( 'fullw', 925, 400, true );
	
	add_action( 'init', 'progodirect_init' );
	add_action( 'progo_frontend_scripts', 'progodirect_scripts', 20 );
	add_action( 'progo_direct_after_arrow', 'progodirect_contenttop', 20 );
	add_action( 'progo_pre_gateways', 'progodirect_gatewaycleanup' );
	add_filter( 'progo_display_easysecure', 'progodirect_easyoverride', 10, 3);
	add_filter( 'progo_checkout_btn', 'progodirect_checkoutbtn' );
}

function progodirect_init() {
	if(class_exists('MultiPostThumbnails')) {
	$thumb = new MultiPostThumbnails(array(
		'label' => 'Full-width image',
		'id' => 'fw-image',
		'post_type' => 'page'
	));
	}
}

function progodirect_scripts() {
	wp_enqueue_script( 'cufon', dirname(get_stylesheet_uri()) .'/cufon-yui.js', array('jquery'), '1.09i', true );
	wp_enqueue_script( 'cufon-Titillium', dirname(get_stylesheet_uri()) .'/TitilliumText_400-TitilliumText_800.font.js', array('cufon'), '1.09i', true );
	wp_enqueue_script( 'progodirect', dirname(get_stylesheet_uri()) .'/progo-frontend.js', array('jquery'), '1.0', true );
}

function progodirect_contenttop() {
	global $post;
	
	if ( class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail('page', 'fw-image', $post->ID) ) {
		MultiPostThumbnails::the_post_thumbnail('page', 'fw-image', $post->ID, 'fullw', array( 'class' => 'fw' ) );
	}
	
	echo nggShow_JS_Slideshow(2, 408, 255, 'ngg-slideshow lapcolors');
	echo '<div id="directprice"></div>';
}

function progo_direct_charcutoff($field) {
	$cut = 0;
	switch($field) {
		case 'arrowd':
		case 'getyours':
		case 'toparr':
			$cut = 35;
			break;
		case 'buynow':
			$cut = 16;
			break;
		case 'rightheadline':
			$cut = 60;
			break;
	}
	return $cut;
}

function progo_colorschemes() {
	return array();
}

function progo_direct_submitbtn( $pid=0, $btxt = 'BUY NOW' ) {
	$btxt = esc_html($btxt);
	switch(strtoupper($btxt)) {
		case 'DOWNLOAD NOW':
			return '<input type="image" id="product_'. absint($pid) .'_submit_button" name="Buy" value="submit" class="buynow sbtn img" src="'. dirname(get_stylesheet_uri()) .'/images/download.jpg" />';
			break;
	}
	return '<input type="submit" id="product_'. absint($pid) .'_submit_button" name="Buy" value="'. esc_html($btxt) .'" class="buynow sbtn" />';
}

function progodirect_checkoutbtn( $html ) {
	//<input type='submit' value='$options[button]' name='submit' class='make_purchase wpsc_buy_button sbtn buynow' />
	return '<input type="image" name="submit" value="submit" class="make_purchase wpsc_buy_button buynow sbtn img" src="'. dirname(get_stylesheet_uri()) .'/images/download.jpg" />';
}

function progo_sitelogo() {
	$options = get_option( 'progo_options' );
	$progo_logo = $options['logo'];
	$upload_dir = wp_upload_dir();
	$dir = trailingslashit($upload_dir['baseurl']);
	$imagepath = $dir . $progo_logo;
	if($progo_logo) {
		echo '<a href="'. esc_url( get_bloginfo( 'url' ) ) .'/"><img src="'. esc_attr( $imagepath ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" id="logo" /></a>';
	} else {
		echo '<div id="logo">'. esc_html( get_bloginfo( 'name' ) ) .'<span class="g"></span></div>';
	}
}

function progodirect_easyoverride($content, $args, $instance) {
	extract($args);
	$options = get_option('progo_options');
	$content = '<img src="'. get_bloginfo('url') .'/wp-content/themes/progodirect/images/weaccept.jpg" alt="We Accept..." />'. $after_widget;
	$content .= $before_widget . $before_title .'Talk with a Pro'. $after_title;
	$content .= '<a href="#contact">Contact ProGo Themes</a>';
	return $content;
}

function progodirect_gatewaycleanup() {
	// custoom cleanup of Paypal Pro gateway fields...
	$years = $months = '';
	$curryear = date( 'Y' );
	//generate year options
	for ( $i = 0; $i < 10; $i++ ) {
		$years .= "<option value='" . $curryear . "'>" . $curryear . "</option>\r\n";
		$curryear++;
	}
	$oot = "<tr><td><label>" . __( 'Card Type *', 'wpsc' ) . "</label><select class='wpsc_ccBox' name='cctype'>
			<option value='Visa'>" . __( 'Visa', 'wpsc' ) . "</option>
			<option value='Mastercard'>" . __( 'MasterCard', 'wpsc' ) . "</option>
			<option value='Discover'>" . __( 'Discover', 'wpsc' ) . "</option>
			<option value='Amex'>" . __( 'Amex', 'wpsc' ) . "</option>
		</select><label>" . __( 'Card Number *', 'wpsc' ) . "</label><input type='text' value='' name='card_number' class='text txt req' />
		<label>" . __( 'Expiration *', 'wpsc' ) . "</label>
		<select class='wpsc_ccBox' name='expiry[month]'>
			" . $months . "
			<option value='01'>01</option>
			<option value='02'>02</option>
			<option value='03'>03</option>
			<option value='04'>04</option>
			<option value='05'>05</option>						
			<option value='06'>06</option>						
			<option value='07'>07</option>					
			<option value='08'>08</option>						
			<option value='09'>09</option>						
			<option value='10'>10</option>						
			<option value='11'>11</option>																			
			<option value='12'>12</option>																			
			</select><select class='wpsc_ccBox' name='expiry[year]'>
			" . $years . "
			</select>
		<label>" . __( 'CVV *', 'wpsc' ) . "</label><input type='text' size='4' value='' maxlength='4' name='card_code' class='txt text req cvv' />
		</td>
	</tr>";
	global $gateway_checkout_form_fields;
	$gateway_checkout_form_fields[wpsc_merchant_paypal_pro] = $oot;
}