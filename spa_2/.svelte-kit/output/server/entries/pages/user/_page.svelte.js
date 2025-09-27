import "clsx";
import { v as pop, t as push } from "../../../chunks/index.js";
import { U as User } from "../../../chunks/User.js";
function _page($$payload, $$props) {
  push();
  let { data } = $$props;
  $$payload.out.push(`<main>`);
  User($$payload);
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
