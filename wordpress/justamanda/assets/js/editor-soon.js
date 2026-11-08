/* The "Coming soon" box in a page's settings sidebar (inc/coming-soon.php).
   Ticked: readers see only the page's coming-soon panel, links to it get the soon
   badge, and pages nested under it are curtained too. Unticked: the page is open.
   Plain JS against the editor's globals, so the theme needs no build step. */
(function (wp) {
  const el = wp.element.createElement;
  const Panel = (wp.editor && wp.editor.PluginDocumentSettingPanel) || (wp.editPost && wp.editPost.PluginDocumentSettingPanel);
  if (!Panel) return;
  const { CheckboxControl } = wp.components;
  const { useSelect, useDispatch } = wp.data;
  const { __ } = wp.i18n;
  const KEY = "_justamanda_coming_soon";

  function SoonPanel() {
    const { type, meta, template } = useSelect((select) => {
      const editor = select("core/editor");
      return {
        type: editor.getCurrentPostType(),
        meta: editor.getEditedPostAttribute("meta") || {},
        template: editor.getEditedPostAttribute("template"),
      };
    }, []);
    const { editPost } = useDispatch("core/editor");
    if (type !== "page") return null;
    const legacy = template === "coming-soon";
    const checked = !!meta[KEY] || legacy;
    return el(Panel, { name: "justamanda-coming-soon", title: __("Coming soon", "justamanda"), icon: "hidden" },
      el(CheckboxControl, {
        label: __("Coming soon (hidden from the public)", "justamanda"),
        help: checked
          ? __("Readers see only this page's coming-soon panel; menus and cards mark it SOON. Untick and Update to release it.", "justamanda")
          : __("This page is open to everyone. Tick to hide it behind its coming-soon panel.", "justamanda"),
        checked,
        onChange: (value) => {
          const edits = { meta: Object.assign({}, meta, { [KEY]: value }) };
          // Pages from before the box used the "Coming soon" template; unticking moves them to the default one.
          if (!value && legacy) edits.template = "";
          editPost(edits);
        },
      }));
  }
  wp.plugins.registerPlugin("justamanda-coming-soon", { render: SoonPanel });
})(window.wp);
