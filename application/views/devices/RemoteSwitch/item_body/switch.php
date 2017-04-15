<?php

$classes = array( 'fa' );

// Get item last value
if( !empty($last_value_info) ){
	if( $last_value_info->value == 0 ){
		$classes[] = 'fa-toggle-off text-muted';
	}else{
		$classes[] = 'fa-toggle-on';
	}
}else{
	$classes[] = 'fa-toggle-off text-muted';
}

?>
<i class="<?php echo implode( ' ', $classes ); ?>" aria-hidden="true"></i>