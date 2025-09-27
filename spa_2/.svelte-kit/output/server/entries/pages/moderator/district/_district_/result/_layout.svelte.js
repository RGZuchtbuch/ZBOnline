import "clsx";
import { v as pop, t as push } from "../../../../../../chunks/index.js";
import { c as ctx } from "../../../../../../chunks/store.svelte.js";
import "../../../../../../chunks/client.js";
import "@sveltejs/kit/internal";
import "../../../../../../chunks/exports.js";
import "../../../../../../chunks/utils.js";
function _layout($$payload, $$props) {
  push();
  let { children } = $$props;
  if (
    // const url = new URL( page.url );
    // url.searchParams.set( 'year', year );
    //goto( url.href );
    ctx.district && ctx.year
  ) {
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
