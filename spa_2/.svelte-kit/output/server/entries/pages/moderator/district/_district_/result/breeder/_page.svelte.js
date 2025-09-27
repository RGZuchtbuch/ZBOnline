import "clsx";
import { x as ensure_array_like, y as attr, w as escape_html, v as pop, t as push } from "../../../../../../../chunks/index.js";
import "../../../../../../../chunks/client.js";
import { c as ctx } from "../../../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../../../chunks/exports.js";
import "../../../../../../../chunks/utils.js";
import "jwt-decode";
import { p as page } from "../../../../../../../chunks/index2.js";
function Breeders($$payload, $$props) {
  push();
  let { breeders } = $$props;
  const each_array = ensure_array_like(breeders);
  $$payload.out.push(`<h2 class="text-center">Welcher Züchter hat gemeldet</h2> <header class="flex flex-row border-header bg-header text-header section items-end px-2 py-0 pl-6 gap-x-2 screen:sticky screen:top-1"><span class="w-12" title="Sortieren" role="button">ZbNr</span> <span class="w-80" title="Sortieren" role="button">Name</span> <span class="w-48" title="Sortieren" role="button">Ortverein</span> <span class="w-12" title="Sortieren" role="button">Online</span></header> <!--[-->`);
  for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
    let breeder = each_array[$$index];
    $$payload.out.push(`<a class="flex flex-row svelte-q0v9n3"${attr("href", page.url.pathname + "/" + breeder.id + "/pair?year=" + ctx.year)}><span class="w-12">${escape_html(breeder.member)}</span> <span class="w-80">${escape_html(breeder.lastname)}, ${escape_html(breeder.firstname)} ${escape_html(breeder.infix)}</span> <span class="w-48">${escape_html(breeder.club)}</span> <span class="w-12 text-green-600">${escape_html(breeder.active ? "✓" : ".")}</span></a>`);
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
function _page($$payload, $$props) {
  push();
  if (ctx.breeders && ctx.district) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<main>`);
    Breeders($$payload, { breeders: ctx.breeders, district: ctx.district });
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
