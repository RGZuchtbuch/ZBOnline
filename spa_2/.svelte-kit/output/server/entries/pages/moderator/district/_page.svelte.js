import "clsx";
import { x as ensure_array_like, y as attr, w as escape_html, v as pop, t as push } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
/* empty css                                                         */
import "jwt-decode";
/* empty css                                                         */
function Districts($$payload, $$props) {
  push();
  let { districts } = $$props;
  (/* @__PURE__ */ new Date()).getFullYear() - 1;
  $$payload.out.push(`<section class="svelte-105wvnr">`);
  if (
    //let dists = getContext( 'districts' );
    districts
  ) {
    $$payload.out.push("<!--[-->");
    const each_array = ensure_array_like(districts);
    $$payload.out.push(`<ol class="svelte-105wvnr"><!--[-->`);
    for (let i = 0, $$length = each_array.length; i < $$length; i++) {
      let district = each_array[i];
      $$payload.out.push(`<!---->`);
      {
        $$payload.out.push(`<li class="flex flex-row items-center"><a${attr("href", `/moderator/district/${district.id}`)} title="Verband verwalten" class="svelte-105wvnr"><div class="w-6 text-right">${escape_html(i + 1)}.</div> <div>${escape_html(district.name)}</div></a></li>`);
      }
      $$payload.out.push(`<!---->`);
    }
    $$payload.out.push(`<!--]--></ol>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--></section>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  if (ctx.districts) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<main>`);
    Districts($$payload, { districts: ctx.districts });
    $$payload.out.push(`<!----></main>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
