import "clsx";
import { v as pop, t as push } from "../../../../../../../../chunks/index.js";
import "../../../../../../../../chunks/client.js";
import { c as ctx } from "../../../../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../../../../chunks/exports.js";
import "../../../../../../../../chunks/utils.js";
import "jwt-decode";
function _layout($$payload, $$props) {
  push();
  let { children } = $$props;
  if (ctx.breeder) {
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
