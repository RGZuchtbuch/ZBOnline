import "clsx";
import { v as pop, t as push } from "../../chunks/index.js";
import { c as ctx } from "../../chunks/store.svelte.js";
function _error($$payload, $$props) {
  push();
  setHeader();
  function setHeader() {
    const title = null;
    const menu = { trail: [{ name: "Start", href: "/" }], options: [] };
    ctx.header.title = title;
    ctx.header.menu = menu;
  }
  $$payload.out.push(`<h2 class="m-8 border-header bg-header text-header text-center">Fehler 404, Seite nicht gefunden</h2> <div class="m-4 flex flex-col items-center justify-center"><div>Du hast eine Adresse / URL eingegeben der nicht gefunden werden kann...</div> <a class="m-8 w-24 border-button bg-button text-button p-2" href="/">Neustart...</a></div>`);
  pop();
}
export {
  _error as default
};
