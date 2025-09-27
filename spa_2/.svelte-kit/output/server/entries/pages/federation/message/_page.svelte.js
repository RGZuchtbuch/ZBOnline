import "clsx";
import { v as pop, t as push } from "../../../../chunks/index.js";
import { p as page } from "../../../../chunks/index2.js";
import { c as ctx } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import "../../../../chunks/client.js";
import "jwt-decode";
/* empty css                                                    */
function _page($$payload, $$props) {
  push();
  let { data } = $$props;
  let districtId = page.url.searchParams.has("district") ? +page.url.searchParams.get("district") : null;
  page.url.searchParams.has("to") ? +page.url.searchParams.get("to") : null;
  districtId ? ctx.federation.districts[districtId] : null;
  {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
