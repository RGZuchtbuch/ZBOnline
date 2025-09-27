import "clsx";
import { x as ensure_array_like, w as escape_html, y as attr, v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../chunks/exports.js";
import "../../../chunks/utils.js";
/* empty css                                                      */
import { A as Article } from "../../../chunks/Article.js";
import "jwt-decode";
/* empty css                                                      */
function Moderator($$payload, $$props) {
  push();
  let manual = null;
  if (ctx.user) {
    $$payload.out.push("<!--[-->");
    const each_array = ensure_array_like(ctx.districts);
    $$payload.out.push(`<h3 class="text-center">Hallo ${escape_html(ctx.user.firstname)}, hier kannst du, als Obmann, was schaffen. ;)</h3> <div class="mx-16 my-8"><h3>Wähle dein Verband zum verwalten</h3> <ul><!--[-->`);
    for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
      let district = each_array[$$index];
      $$payload.out.push(`<li><a${attr("href", `/moderator/district/${district.id}`)}>${escape_html(district.name)}</a></li>`);
    }
    $$payload.out.push(`<!--]--></ul></div> <hr/> `);
    Article($$payload, { article: manual });
    $$payload.out.push(`<!---->`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
function _page($$payload, $$props) {
  push();
  if (ctx.districts) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<main>`);
    Moderator($$payload);
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
