import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import "@sveltejs/kit/internal";
import "../../../chunks/exports.js";
import "../../../chunks/utils.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
/* empty css                                                      */
import { U as User } from "../../../chunks/User.js";
import "jwt-decode";
function _layout($$payload, $$props) {
  push();
  let { children, data } = $$props;
  let authorized = ctx.user !== null;
  if (
    // function loadDistrict( id ) {
    // 	ctx.district = ctx.federation.districts[ id ];
    // }
    ctx.user
  ) {
    $$payload.out.push("<!--[-->");
    if (ctx.breeder && ctx.district && authorized) {
      $$payload.out.push("<!--[-->");
      children($$payload);
      $$payload.out.push(`<!---->`);
    } else {
      $$payload.out.push("<!--[!-->");
    }
    $$payload.out.push(`<!--]-->`);
  } else {
    $$payload.out.push("<!--[!-->");
    User($$payload);
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _layout as default
};
