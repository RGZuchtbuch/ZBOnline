import "clsx";
import { x as ensure_array_like, y as attr, w as escape_html, v as pop, t as push } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import "jwt-decode";
import { p as page } from "../../../../chunks/index2.js";
/* empty css                                                    */
import "../../../../chunks/District.svelte_svelte_type_style_lang.js";
function Articles($$payload, $$props) {
  push();
  let { articles } = $$props;
  let canEdit = ctx.user && ctx.user.admin;
  $$payload.out.push(`<section class="svelte-1enjr68">`);
  if (canEdit) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<div class="flex flex-row justify-end p-2"><a class="border-button bg-button text-button py-0 px-2" href="/admin/article/0" title="Neuer Beitrag">+</a></div>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> `);
  if (articles) {
    $$payload.out.push("<!--[-->");
    const each_array = ensure_array_like(articles);
    $$payload.out.push(`<header class="flex flex-row px-2 gap-x-2 border-header bg-header text-header"><span class="w-8 text-right">#</span> <span class="w-12 text-right">Folge</span> <span class="grow">Titel</span> <span class="w-48">Von</span> <span class="w-48">Geändert</span></header> <!--[-->`);
    for (let i = 0, $$length = each_array.length; i < $$length; i++) {
      let article = each_array[i];
      $$payload.out.push(`<!---->`);
      {
        $$payload.out.push(`<a class="grow flex flex-row gap-x-2 p-2"${attr("href", `${page.url.pathname}/${article.id}`)}><div class="w-8 text-right">${escape_html(i)}</div> <div class="w-12 text-right">${escape_html(article.level)}</div> <div class="grow">${escape_html(article.title)}</div> <div class="w-48">${escape_html(article.author)}</div> <div class="w-48">${escape_html(article.modified)}</div></a>`);
      }
      $$payload.out.push(`<!---->`);
    }
    $$payload.out.push(`<!--]-->`);
  } else {
    $$payload.out.push("<!--[!-->");
    $$payload.out.push(`<h2 class="mt-32 text-center text-xl">Keine Beiträge gefunden</h2>`);
  }
  $$payload.out.push(`<!--]--></section>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  Articles($$payload, { articles: ctx.articles });
  pop();
}
export {
  _page as default
};
