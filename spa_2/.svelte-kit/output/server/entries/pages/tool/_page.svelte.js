import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
function Tools($$payload) {
  $$payload.out.push(`<section class="flex flex-row justify-center mt-16"><ul>Hier gibt es einige Hilfsmittel für den Züchter und Obmann <li><a href="/tool/grader">Leistungsrechner</a></li> <li><a href="/tool/lineage">Abstammungsnachweis</a></li></ul></section>`);
}
function _page($$payload, $$props) {
  push();
  $$payload.out.push(`<main>`);
  Tools($$payload);
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
