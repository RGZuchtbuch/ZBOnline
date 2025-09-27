import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
function _page($$payload, $$props) {
  push();
  let mounted = false;
  if (ctx.breeder && mounted) ;
  else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
