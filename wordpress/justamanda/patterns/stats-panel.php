<?php
/**
 * Title: Stats panel
 * Slug: justamanda/stats-panel
 * Categories: justamanda
 * Description: A white panel with three big numbers, a note on where they come from, and a link.
 *
 * @package justamanda
 */
?>
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
