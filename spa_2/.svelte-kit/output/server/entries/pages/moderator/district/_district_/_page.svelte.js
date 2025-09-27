import "clsx";
import { v as pop, t as push } from "../../../../../chunks/index.js";
import { p as page } from "../../../../../chunks/index2.js";
import { c as ctx } from "../../../../../chunks/store.svelte.js";
function _page($$payload, $$props) {
  push();
  let mounted = false;
  ctx.federation.districts[+page.params.district];
  if (ctx.district && mounted) ;
  else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
