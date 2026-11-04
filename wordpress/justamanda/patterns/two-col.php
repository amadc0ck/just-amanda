<?php
/**
 * Title: Two columns, story and stats
 * Slug: justamanda/two-col
 * Categories: justamanda
 * Description: A story panel on the left, beside the numbers and a captioned photograph on the right. Stacks below 900px.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"section","layout":{"type":"default"}} -->
<section class="wp-block-group"><!-- wp:group {"className":"wrap two-col","layout":{"type":"default"}} -->
<div class="wp-block-group wrap two-col"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:heading -->
<h2 class="wp-block-heading">How this started</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"panel","layout":{"type":"default"}} -->
<div class="wp-block-group panel"><!-- wp:group {"className":"prose","layout":{"type":"default"}} -->
<div class="wp-block-group prose"><!-- wp:paragraph -->
<p>The first part of the story.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>And how it went from there.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Today</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"panel","layout":{"type":"default"}} -->
<div class="wp-block-group panel"><!-- wp:group {"className":"stat-row","layout":{"type":"default"}} -->
<div class="wp-block-group stat-row"><!-- wp:group {"className":"stat","layout":{"type":"default"}} -->
<div class="wp-block-group stat"><!-- wp:paragraph -->
<p><b>100</b><span>things</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"stat","layout":{"type":"default"}} -->
<div class="wp-block-group stat"><!-- wp:paragraph -->
<p><b>50</b><span>kinds</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"stat","layout":{"type":"default"}} -->
<div class="wp-block-group stat"><!-- wp:paragraph -->
<p><b>25</b><span>places</span></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"stat-note"} -->
<p class="stat-note">A line about where the numbers come from.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"stat-link"} -->
<p class="stat-link"><a href="/inventory/">Browse the whole collection →</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"origin-photo"} -->
<figure class="wp-block-image size-full origin-photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-succulent.png' ) ); ?>" alt="Describe the photograph"/><figcaption class="wp-element-caption">A caption for the photograph.</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
