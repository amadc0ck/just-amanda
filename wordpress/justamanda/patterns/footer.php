<?php
/**
 * Title: Footer
 * Slug: justamanda/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 * Description: Badge, tagline, links, socials, and the legal line with the Admin and Garden links.
 *
 * @package justamanda
 */
?>
<!-- wp:group {"className":"wrap foot-main","layout":{"type":"default"}} -->
<div class="wp-block-group wrap foot-main"><!-- wp:group {"className":"brand-block","layout":{"type":"default"}} -->
<div class="wp-block-group brand-block"><!-- wp:image {"sizeSlug":"full","linkDestination":"custom","className":"logo"} -->
<figure class="wp-block-image size-full logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-badge.png' ) ); ?>" alt="just amanda"/></a></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"tagline"} -->
<p class="tagline">A creative life,<br>thoughtfully collected.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:navigation {"overlayMenu":"never","className":"foot-nav","layout":{"type":"flex","flexWrap":"wrap"},"ariaLabel":"Footer"} -->
<!-- wp:navigation-link {"label":"Home","url":"/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"About","url":"/about/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Succulents","url":"/plants/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Chickens","url":"/chickens/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Crochet","url":"/crochet/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Books","url":"/books/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Tech","url":"/tech/","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Journal","url":"/journal/","kind":"custom"} /-->
<!-- /wp:navigation -->

<!-- wp:social-links {"className":"social","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<ul class="wp-block-social-links social"><!-- wp:social-link {"url":"https://www.instagram.com/justamanda_net","service":"instagram","label":"Instagram","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://www.facebook.com/justamanda.net","service":"facebook","label":"Facebook","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://www.threads.com/@justamanda_net","service":"threads","label":"Threads","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://www.linkedin.com/in/amadcock","service":"linkedin","label":"LinkedIn","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://github.com/amadc0ck","service":"github","label":"GitHub","rel":"me noopener"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"legal","layout":{"type":"default"}} -->
<div class="wp-block-group legal"><!-- wp:paragraph -->
<p>© 2026 just amanda. &nbsp;·&nbsp; Words and photographs are my own; please don’t reuse them without asking. &nbsp;·&nbsp; Illustrations were made with AI, from my direction.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"legal-tools"} -->
<p class="legal-tools"><a href="/wp-admin/">Admin</a><a href="https://garden.justamanda.net">Garden app</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
