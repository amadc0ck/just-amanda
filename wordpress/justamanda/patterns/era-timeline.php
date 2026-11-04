<?php
/**
 * Title: Reading timeline
 * Slug: justamanda/era-timeline
 * Categories: justamanda
 * Description: Eras down a rule, each with a handwritten label, a shelf of book cards, and a note. A card with no cover art gets a tinted plate with the title set on it.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"tagName":"ol","className":"era-line","layout":{"type":"default"}} -->
<ol class="wp-block-group era-line"><!-- wp:group {"tagName":"li","className":"era","layout":{"type":"default"}} -->
<li class="wp-block-group era"><!-- wp:group {"className":"era-rail","layout":{"type":"default"}} -->
<div class="wp-block-group era-rail"><!-- wp:paragraph {"className":"tl-label"} -->
<p class="tl-label">first era</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"era-body","layout":{"type":"default"}} -->
<div class="wp-block-group era-body"><!-- wp:group {"className":"bookrow","layout":{"type":"default"}} -->
<div class="wp-block-group bookrow"><!-- wp:group {"className":"bookcard is-1 has-cover","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-1 has-cover"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"bc-txt"} -->
<p class="bc-txt">Book title<strong>Author</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"bookcard is-2 no-cover","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-2 no-cover"><!-- wp:group {"className":"bc-plate","layout":{"type":"default"}} -->
<div class="wp-block-group bc-plate"><!-- wp:paragraph -->
<p>Book title</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"bc-txt"} -->
<p class="bc-txt"><strong>Author</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"tl-note"} -->
<p class="tl-note">A sentence or two about this stretch of reading.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></li>
<!-- /wp:group -->

<!-- wp:group {"tagName":"li","className":"era","layout":{"type":"default"}} -->
<li class="wp-block-group era"><!-- wp:group {"className":"era-rail","layout":{"type":"default"}} -->
<div class="wp-block-group era-rail"><!-- wp:paragraph {"className":"tl-label"} -->
<p class="tl-label">second era</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"era-body","layout":{"type":"default"}} -->
<div class="wp-block-group era-body"><!-- wp:group {"className":"bookrow","layout":{"type":"default"}} -->
<div class="wp-block-group bookrow"><!-- wp:group {"className":"bookcard is-3 has-cover","layout":{"type":"default"}} -->
<div class="wp-block-group bookcard is-3 has-cover"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/ornament-violets.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"bc-txt"} -->
<p class="bc-txt">Book title<strong>Author</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"tl-note"} -->
<p class="tl-note">A sentence or two about this stretch of reading.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"script tl-aside"} -->
<p class="script tl-aside">A handwritten aside.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></li>
<!-- /wp:group --></ol>
<!-- /wp:group -->
