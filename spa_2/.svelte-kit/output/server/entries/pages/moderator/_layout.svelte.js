import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
import { U as User } from "../../../chunks/User.js";
function _layout($$payload, $$props) {
  push();
  let { children } = $$props;
  let authorized = ctx.user && (ctx.user.moderator.length > 0 || ctx.user.admin);
  if (authorized) {
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
