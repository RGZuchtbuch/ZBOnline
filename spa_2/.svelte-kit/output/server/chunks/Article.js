import { v as pop, t as push, w as escape_html } from "./index.js";
import "clsx";
import { h as html } from "./html.js";
function Article($$payload, $$props) {
  push();
  let { article } = $$props;
  if (article) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<section class="px-16"><div class="flex flex-row items-center justify-end gap-x-2 p-2 text-xs italic">${escape_html(article.author)}, ${escape_html(article.modified)}</div> <p>${html(article.html)}</p></section>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  Article as A
};
