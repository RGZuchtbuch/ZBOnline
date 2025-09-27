import "clsx";
import { v as pop, t as push } from "../../../../../../chunks/index.js";
import "../../../../../../chunks/client.js";
import { c as ctx } from "../../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../../chunks/exports.js";
import "../../../../../../chunks/utils.js";
import "jwt-decode";
function _page($$payload, $$props) {
  push();
  let mounted = false;
  if (ctx.district && ctx.results && mounted) ;
  else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
