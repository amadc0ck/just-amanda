<?php
/**
 * Title: Links profile
 * Slug: justamanda/links-profile
 * Categories: justamanda
 * Description: The top of the links page: share button, round avatar, name, handwritten bio, topics and social icons. Place it inside the page column group (class lt).
 *
 * @package justamanda
 */
?>
<!-- wp:html -->
<div class="lt-share-wrap"><button type="button" class="lt-share" id="lt-share" aria-label="Share this page"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11A2.99 2.99 0 0 0 21 5a3 3 0 1 0-5.91.7L8.04 9.81A3 3 0 1 0 6 15a2.99 2.99 0 0 0 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65A2.92 2.92 0 1 0 18 16.08z"/></svg></button></div>
<!-- /wp:html -->

<!-- wp:group {"className":"lt-avatar","layout":{"type":"default"}} -->
<div class="wp-block-group lt-avatar"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-badge.png' ) ); ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":1,"className":"lt-name"} -->
<h1 class="wp-block-heading lt-name">Name</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lt-bio"} -->
<p class="lt-bio">a handwritten line</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"lt-topics"} -->
<p class="lt-topics">one · two · three</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"className":"social","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<ul class="wp-block-social-links social"><!-- wp:social-link {"url":"https://www.instagram.com/justamanda_net","service":"instagram","label":"Instagram","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://www.threads.com/@justamanda_net","service":"threads","label":"Threads","rel":"me noopener"} /-->

<!-- wp:social-link {"url":"https://www.facebook.com/justamanda.net","service":"facebook","label":"Facebook","rel":"me noopener"} /--></ul>
<!-- /wp:social-links -->
