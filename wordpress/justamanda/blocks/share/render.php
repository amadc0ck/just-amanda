<?php
/**
 * Share row for the post being shown. The service links are plain URLs built here,
 * so they work with no script and load nothing from those services (no buttons,
 * no tracking pixels). "Share" (the device's own share sheet) and "Copy link" need
 * a browser API, so they ship hidden and assets/js/share.js reveals them.
 *
 * @var array    $attributes
 * @var WP_Block $block
 */
$post_id = $block->context['postId'] ?? get_the_ID();
if ( ! $post_id ) {
	return;
}
$url   = get_permalink( $post_id );
$title = wp_strip_all_tags( get_the_title( $post_id ) );
// Pinterest pins the picture itself, so it gets the uploaded PNG/JPEG, not the AVIF (see inc/meta.php).
$thumb = get_post_thumbnail_id( $post_id );
$image = $thumb ? (string) ( wp_get_original_image_url( $thumb ) ?: wp_get_attachment_url( $thumb ) ) : '';
$e     = 'rawurlencode';
$links = array(
	'Facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $e( $url ),
	'Threads'   => 'https://www.threads.net/intent/post?text=' . $e( $title . ' ' . $url ),
	'LinkedIn'  => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $e( $url ),
	'Pinterest' => 'https://pinterest.com/pin/create/button/?url=' . $e( $url ) . ( $image ? '&media=' . $e( $image ) : '' ) . '&description=' . $e( $title ),
	'Email'     => 'mailto:?subject=' . $e( $title ) . '&body=' . $e( $url ),
);
$label = trim( (string) ( $attributes['label'] ?? '' ) );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'ja-share' ) ); ?> data-url="<?php echo esc_url( $url ); ?>" data-title="<?php echo esc_attr( $title ); ?>">
	<?php if ( '' !== $label ) : ?>
		<p class="ja-share-label"><?php echo esc_html( $label ); ?></p>
	<?php endif; ?>
	<div class="ja-share-row">
		<button type="button" class="ja-share-btn is-primary ja-share-native" hidden><?php esc_html_e( 'Share', 'justamanda' ); ?></button>
		<button type="button" class="ja-share-btn ja-share-copy" hidden><?php esc_html_e( 'Copy link', 'justamanda' ); ?></button>
		<?php foreach ( $links as $name => $href ) : ?>
			<a class="ja-share-btn" href="<?php echo esc_url( $href ); ?>"<?php echo 'Email' === $name ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>><?php echo esc_html( $name ); ?><?php if ( 'Email' !== $name ) : ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'justamanda' ); ?></span><?php endif; ?></a>
		<?php endforeach; ?>
	</div>
</div>
