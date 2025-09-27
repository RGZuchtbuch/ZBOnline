import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
import { U as User } from "../../../chunks/User.js";
function _layout($$payload, $$props) {
  push();
  let { children, data } = $$props;
  let authorized = ctx.user && ctx.user.admin;
  if (
    // layout protects role with authorized
    authorized
  ) {
    $$payload.out.push("<!--[-->");
    children($$payload);
    $$payload.out.push(`<!---->`);
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
