/* Plain JS against the editor's globals, so the theme needs no build step.
   The list itself is fetched on the page (view.js); the editor shows the
   search box and lets its two bits of wording be changed. */
(function (wp) {
  const el = wp.element.createElement;
  const { InspectorControls, useBlockProps } = wp.blockEditor;
  const { PanelBody, TextControl } = wp.components;
  const { __ } = wp.i18n;
  wp.blocks.registerBlockType("justamanda/plant-inventory", {
    edit({ attributes, setAttributes }) {
      return el("div", useBlockProps({ className: "ja-inventory" }),
        el(InspectorControls, null,
          el(PanelBody, { title: __("Wording", "justamanda") },
            el(TextControl, { label: __("Search box hint", "justamanda"), value: attributes.searchPlaceholder,
              onChange: (v) => setAttributes({ searchPlaceholder: v }) }),
            el(TextControl, { label: __("While loading", "justamanda"), value: attributes.loadingText,
              onChange: (v) => setAttributes({ loadingText: v }) }))),
        el("div", { className: "inv-controls" },
          el("input", { className: "search-input", type: "search", placeholder: attributes.searchPlaceholder, disabled: true }),
          el("p", { className: "muted" }, __("The live list of species from the garden catalogue appears here on the page.", "justamanda"))));
    },
    save() { return null; },
  });
})(window.wp);
