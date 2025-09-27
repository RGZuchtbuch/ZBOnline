import "clsx";
import { w as escape_html, y as attr, v as pop, t as push } from "../../../chunks/index.js";
import "../../../chunks/client.js";
import { c as ctx } from "../../../chunks/store.svelte.js";
function Admin($$payload, $$props) {
  push();
  $$payload.out.push(`<section class="mt-32 flex flex-col items-center"><h2>Hallo ${escape_html(
    //let { breeder } = $props();
    ctx.user.firstname
  )}, Willkommen in das Zuchtbuch</h2> <p>Als Admin kannst du Info's, Verbände, den Standard usw Verwalten.</p> <ul class="mr-16"><li><a${attr("href", `/admin/article`)}>Infos</a></li> <li><a${attr("href", `/admin/district`)}>Verbände</a></li> <li><a${attr("href", `/admin/standard`)}>Standard</a></li> <li><a${attr("href", `/admin/setting`)}>Settings</a></li> <li><a${attr("href", `/admin/log`)}>Logs</a></li></ul></section>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  Admin($$payload);
  pop();
}
export {
  _page as default
};
