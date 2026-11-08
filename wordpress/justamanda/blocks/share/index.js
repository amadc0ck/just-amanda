/* Plain JS against the editor's globals, so the theme needs no build step.
   The links are built from the post on the server (render.php); the editor only
   shows a stand-in row and lets the label be changed. */
(function (wp) {
  const el = wp.element.createElement;
  const { InspectorControls, useBlockProps, RichText } = wp.blockEditor;
  const { PanelBody } = wp.components;
  const { __ } = wp.i18n;
  const names = ["Share", "Copy link", "Facebook", "Threads", "LinkedIn", "Pinterest", "Email"];
  wp.blocks.registerBlockType("justamanda/share", {
    edit({ attributes, setAttributes }) {
      return el("div", useBlockProps({ className: "ja-share" }),
        el(InspectorControls, null,
          el(PanelBody, { title: __("Share links", "justamanda") },
            el("p", null, __("Links are filled in with this post's address when the page is shown.", "justamanda")))),
        el(RichText, {
          tagName: "p", className: "ja-share-label", value: attributes.label,
          allowedFormats: [], onChange: (v) => setAttributes({ label: v }),
        }),
        el("div", { className: "ja-share-row" },
          names.map((n) => el("span", { key: n, className: "ja-share-btn" }, n))));
    },
    save() { return null; },
  });
})(window.wp);
