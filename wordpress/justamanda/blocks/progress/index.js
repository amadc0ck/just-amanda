/* Plain JS against the editor's globals, so the theme needs no build step. */
(function (wp) {
  const el = wp.element.createElement;
  const { InspectorControls, useBlockProps } = wp.blockEditor;
  const { PanelBody, RangeControl } = wp.components;
  const { __ } = wp.i18n;
  wp.blocks.registerBlockType("justamanda/progress", {
    edit({ attributes, setAttributes }) {
      const value = attributes.value;
      return el("span", useBlockProps({ className: "ja-progress" }),
        el(InspectorControls, null,
          el(PanelBody, { title: __("Progress", "justamanda") },
            el(RangeControl, {
              label: __("Percent done", "justamanda"),
              value, min: 0, max: 100,
              onChange: (v) => setAttributes({ value: v == null ? 0 : v }),
            }))),
        el("span", { className: "bar" }, el("i", { style: { width: value + "%" } })),
        value + "%");
    },
    save() { return null; },
  });
})(window.wp);
