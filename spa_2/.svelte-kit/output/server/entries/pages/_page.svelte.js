import "clsx";
import { v as pop, t as push } from "../../chunks/index.js";
import "../../chunks/client.js";
import "@sveltejs/kit/internal";
import "../../chunks/exports.js";
import "../../chunks/utils.js";
import "jwt-decode";
import { A as Article } from "../../chunks/Article.js";
function Home($$payload, $$props) {
  push();
  let welcome = null;
  let news = null;
  $$payload.out.push(`<div class="p-6">`);
  Article($$payload, { article: welcome });
  $$payload.out.push(`<!----> <hr/> `);
  Article($$payload, { article: news });
  $$payload.out.push(`<!----></div>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  Home($$payload);
  pop();
}
export {
  _page as default
};
