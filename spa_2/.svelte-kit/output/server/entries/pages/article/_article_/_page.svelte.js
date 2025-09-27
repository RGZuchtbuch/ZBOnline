import "clsx";
import { v as pop, t as push } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import "jwt-decode";
import { A as Article } from "../../../../chunks/Article.js";
function _page($$payload, $$props) {
  push();
  $$payload.out.push(`<main>`);
  Article($$payload, { article: ctx.article });
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
