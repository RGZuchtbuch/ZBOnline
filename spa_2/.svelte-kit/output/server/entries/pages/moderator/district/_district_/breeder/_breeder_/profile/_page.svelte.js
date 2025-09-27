import "clsx";
import { K as copy_payload, N as assign_payload, v as pop, t as push } from "../../../../../../../../chunks/index.js";
import "../../../../../../../../chunks/client.js";
import { c as ctx } from "../../../../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../../../../chunks/exports.js";
import "../../../../../../../../chunks/utils.js";
import "jwt-decode";
/* empty css                                                                */
function _page($$payload, $$props) {
  push();
  let mounted = false;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    if (ctx.breeder && ctx.district && mounted) ;
    else {
      $$payload2.out.push("<!--[!-->");
    }
    $$payload2.out.push(`<!--]-->`);
  }
  do {
    $$settled = true;
    $$inner_payload = copy_payload($$payload);
    $$render_inner($$inner_payload);
  } while (!$$settled);
  assign_payload($$payload, $$inner_payload);
  pop();
}
export {
  _page as default
};
