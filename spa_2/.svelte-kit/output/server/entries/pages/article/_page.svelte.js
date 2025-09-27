import "clsx";
import { x as ensure_array_like, y as attr, w as escape_html, v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../chunks/exports.js";
import "../../../chunks/utils.js";
import "jwt-decode";
import { p as page } from "../../../chunks/index2.js";
function Articles($$payload, $$props) {
  push();
  let { articles } = $$props;
  $$payload.out.push(`<section class="py-4 svelte-yvf4w7">`);
  if (articles) {
    $$payload.out.push("<!--[-->");
    const each_array = ensure_array_like(articles);
    $$payload.out.push(`<header class="flex flex-row px-2 gap-x-2 border-header bg-header text-header"><span class="w-8 text-right">#</span> <span class="grow">Titel</span> <span class="w-48">Von</span></header> <!--[-->`);
    for (let i = 0, $$length = each_array.length; i < $$length; i++) {
      let article = each_array[i];
      $$payload.out.push(`<!---->`);
      {
        if (article.level !== null) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<a class="grow flex flex-row gap-x-4 p-2"${attr("href", `${page.url.pathname}/${article.id}`)}><span class="w-8 text-right">${escape_html(i + 1)}</span> <span class="grow">${escape_html(article.title)}</span> <span class="w-48">${escape_html(article.author)}</span></a>`);
        } else {
          $$payload.out.push("<!--[!-->");
        }
        $$payload.out.push(`<!--]-->`);
      }
      $$payload.out.push(`<!---->`);
    }
    $$payload.out.push(`<!--]-->`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> `);
  if (articles && articles.length === 0) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`Keine Info's gefunden.....`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--></section>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  $$payload.out.push(`<main>`);
  Articles($$payload, { articles: ctx.articles });
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
