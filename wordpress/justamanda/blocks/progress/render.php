<?php
/**
 * A custom block because core has no way to edit a bar's width.
 *
 * @var array $attributes
 */
$value = max( 0, min( 100, (int) ( $attributes['value'] ?? 0 ) ) );
?>
<span <?php echo get_block_wrapper_attributes( array( 'class' => 'ja-progress' ) ); ?>><span class="bar"><i style="width:<?php echo esc_attr( $value ); ?>%;"></i></span><?php echo esc_html( $value ); ?>%</span>
