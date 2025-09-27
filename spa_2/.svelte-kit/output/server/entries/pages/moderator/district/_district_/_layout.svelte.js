import "clsx";
import { v as pop, t as push } from "../../../../../chunks/index.js";
import { c as ctx } from "../../../../../chunks/store.svelte.js";
import "../../../../../chunks/client.js";
function _layout($$payload, $$props) {
  push();
  let { children } = $$props;
  if (ctx.district) {
    $$payload.out.push("<!--[-->");
    children($$payload);
    $$payload.out.push(`<!---->`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _layout as default
};
