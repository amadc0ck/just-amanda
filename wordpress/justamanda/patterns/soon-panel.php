<?php
/**
 * Title: Coming soon panel
 * Slug: justamanda/soon-panel
 * Categories: justamanda
 * Description: What readers see on a page using the Coming soon template. Keep it as the first block on the page; everything after it is shown only to you.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"soon-panel","layout":{"type":"default"}} -->
<div class="wp-block-group soon-panel"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Coming soon</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Page name</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This page is still being written. In the meantime, the succulents and the journal are open.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"btn-row"} -->
<div class="wp-block-buttons btn-row"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button" href="/plants/">See the succulents →</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-read"} -->
<div class="wp-block-button is-style-read"><a class="wp-block-button__link wp-element-button" href="/journal/">Read the journal</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
