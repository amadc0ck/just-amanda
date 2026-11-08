<?php
/**
 * The collection, read live in the reader's browser from the garden catalogue's
 * public_plant_inventory view (Supabase), as the static inventory.html did. The
 * view exposes only species-level columns: locations, health, notes and accession
 * numbers are not in it and cannot be reached from here.
 *
 * The URL and key are the garden project's publishable key, public by design
 * (RLS decides what it can read), the same pair the static site's site.js carried.
 * Filterable so a later move of the catalogue needs no template change.
 *
 * @var array $attributes
 */
$sb = apply_filters(
	'justamanda_catalogue',
	array(
		'url' => 'https://fsckwgicmvviefuivgza.supabase.co',
		'key' => 'sb_publishable_60U_SBa3qnwU2jhWzyylnA_-6A3LMDt',
	)
);
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'ja-inventory' ) ); ?> data-sb-url="<?php echo esc_url( $sb['url'] ); ?>" data-sb-key="<?php echo esc_attr( $sb['key'] ); ?>">
	<div class="inv-controls">
		<input class="search-input" type="search" autocomplete="off" placeholder="<?php echo esc_attr( $attributes['searchPlaceholder'] ?? '' ); ?>" aria-label="<?php esc_attr_e( 'Search the collection', 'justamanda' ); ?>">
		<p class="muted inv-count" aria-live="polite"><?php echo esc_html( $attributes['loadingText'] ?? '' ); ?></p>
	</div>
	<div class="inv-list"></div>
	<noscript><p class="muted"><?php esc_html_e( 'The collection list needs JavaScript; it is read live from the garden catalogue.', 'justamanda' ); ?></p></noscript>
</div>
