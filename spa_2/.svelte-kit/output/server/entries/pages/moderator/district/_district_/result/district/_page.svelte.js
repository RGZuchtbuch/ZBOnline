import "clsx";
import { K as copy_payload, N as assign_payload, v as pop, t as push } from "../../../../../../../chunks/index.js";
import { p as page } from "../../../../../../../chunks/index2.js";
import { c as ctx } from "../../../../../../../chunks/store.svelte.js";
import { A as ArgsBuilder, a as activeYear } from "../../../../../../../chunks/tools.js";
import "@sveltejs/kit/internal";
import "../../../../../../../chunks/exports.js";
import "../../../../../../../chunks/utils.js";
import "../../../../../../../chunks/client.js";
import "jwt-decode";
/* empty css                                                             */
function _page($$payload, $$props) {
  push();
  let mounted = false;
  let args = getArgs(page);
  function getArgs(page2) {
    let query = page2.url.searchParams;
    const args2 = ArgsBuilder.init();
    args2.district = +page2.params.district;
    ArgsBuilder.setNumber(args2, query, "year", activeYear());
    ArgsBuilder.setNumber(args2, query, "section", 3);
    ArgsBuilder.setNumber(args2, query, "breed", null);
    ArgsBuilder.setNumber(args2, query, "color", null);
    ArgsBuilder.setString(args2, query, "group", "I");
    return args2;
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    if (ctx.district && args && ctx.resultsEdit && mounted) ;
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
