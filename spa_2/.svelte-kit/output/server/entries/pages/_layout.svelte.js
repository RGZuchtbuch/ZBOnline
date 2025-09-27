import "clsx";
import { w as escape_html, v as pop, t as push, x as ensure_array_like, y as attr } from "../../chunks/index.js";
import { g as goto } from "../../chunks/client.js";
import { c as ctx } from "../../chunks/store.svelte.js";
import { p as page } from "../../chunks/index2.js";
import { m as model } from "../../chunks/model.js";
function profile_icon($$payload) {
  $$payload.out.push(`<span><svg viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" width="20" height="20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" fill="none"></circle><circle cx="12" cy="10" r="4" fill="none" stroke="currentColor"></circle><path d="M 6 18 A 8 8 0 0 1 18 18" fill="none" stroke="currentColor"></path></svg></span>`);
}
function Header($$payload, $$props) {
  push();
  $$payload.out.push(`<a href="/" class="absolute -m-1" title="Das BDRG Zuchtbuch Logo"><img src="/assets/bdrg_logo_r.png" class="h-16 p-1 mx-2" alt="BDRG Rassegeflügelzuchtbuch Logo"/></a> <div class="flex flex-row"><div class="grow font-bold pl-32 text-sm text-center">BDRG Zuchtbuch</div> <div class="w-32 flex flex-row pr-2 gap-x-2 text-sm justify-end"><span>${escape_html(ctx.user ? ctx.user.firstname : "Gast")}</span> <span class="screen:hidden">${escape_html((/* @__PURE__ */ new Date()).toLocaleDateString("de-DE"))}</span> <a href="/user" title="An/abmelden">`);
  profile_icon($$payload);
  $$payload.out.push(`<!----></a></div></div>`);
  pop();
}
function Menu($$payload, $$props) {
  push();
  if (
    // single trigger
    // ➭
    ctx && ctx.title && ctx.menu
  ) {
    $$payload.out.push("<!--[-->");
    const each_array = ensure_array_like(ctx.menu.options);
    const each_array_2 = ensure_array_like(ctx.crumbs);
    const each_array_3 = ensure_array_like(ctx.submenu);
    $$payload.out.push(`<div class="flex flex-col border-header bg-header text-header print:hidden"><div class="grow flex flex-row justify-end gap-x-2 py-1 pr-8"><nav class="flex flex-row gap-x-2"><!--[-->`);
    for (let i = 0, $$length = each_array.length; i < $$length; i++) {
      let option = each_array[i];
      if (i > 0) {
        $$payload.out.push("<!--[-->");
        $$payload.out.push(`•`);
      } else {
        $$payload.out.push("<!--[!-->");
      }
      $$payload.out.push(`<!--]--> `);
      if (page.url.pathname.startsWith(option.href)) {
        $$payload.out.push("<!--[-->");
        $$payload.out.push(`<a class="underline"${attr("href", ctx.menustate[option.href])}${attr("title", "Zum " + option.name)}>${escape_html(option.name)}</a>`);
      } else {
        $$payload.out.push("<!--[!-->");
        $$payload.out.push(`<a${attr("href", ctx.menustate[option.href])}${attr("title", "Zum " + option.name)}>${escape_html(option.name)}</a>`);
      }
      $$payload.out.push(`<!--]-->`);
    }
    $$payload.out.push(`<!--]--></nav> `);
    if (ctx.menu.roles.length > 0) {
      $$payload.out.push("<!--[-->");
      const each_array_1 = ensure_array_like(ctx.menu.roles);
      $$payload.out.push(`: <nav class="flex flex-row gap-x-2"><!--[-->`);
      for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
        let role = each_array_1[i];
        if (i > 0) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`•`);
        } else {
          $$payload.out.push("<!--[!-->");
        }
        $$payload.out.push(`<!--]--> `);
        if (page.url.pathname.startsWith(role.href)) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<a class="underline font-bold"${attr("href", ctx.menustate[role.href])}${attr("title", "Zum " + role.name)}>${escape_html(role.name)}</a>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<a${attr("href", ctx.menustate[role.href])}${attr("title", "Zum " + role.name)}>${escape_html(role.name)}</a>`);
        }
        $$payload.out.push(`<!--]-->`);
      }
      $$payload.out.push(`<!--]--></nav>`);
    } else {
      $$payload.out.push("<!--[!-->");
    }
    $$payload.out.push(`<!--]--></div> <div class="grow flex flex-row text-sm gap-x-2"><nav class="grow flex flex-row justify-end gap-x-1 italic"><!--[-->`);
    for (let i = 0, $$length = each_array_2.length; i < $$length; i++) {
      let crumb = each_array_2[i];
      $$payload.out.push(`<!---->`);
      {
        if (i > 0) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`/`);
        } else {
          $$payload.out.push("<!--[!-->");
        }
        $$payload.out.push(`<!--]--> `);
        if (i < ctx.crumbs.length - 1) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<a${attr("href", crumb.href)} class="pr-1" title="Zurück">${escape_html(crumb.name)}</a>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<span class="pr-1 underline font-bold cursor-default" title="Hier bist du">${escape_html(crumb.name)}</span>`);
        }
        $$payload.out.push(`<!--]-->`);
      }
      $$payload.out.push(`<!---->`);
    }
    $$payload.out.push(`<!--]--></nav> `);
    if (ctx.submenu.length > 0) {
      $$payload.out.push("<!--[-->");
      $$payload.out.push(`:`);
    } else {
      $$payload.out.push("<!--[!-->");
    }
    $$payload.out.push(`<!--]--> <nav class="flex flex-row pr-20 gap-x-1"><!--[-->`);
    for (let i = 0, $$length = each_array_3.length; i < $$length; i++) {
      let option = each_array_3[i];
      if (i > 0) {
        $$payload.out.push("<!--[-->");
        $$payload.out.push(`•`);
      } else {
        $$payload.out.push("<!--[!-->");
      }
      $$payload.out.push(`<!--]--> <!---->`);
      {
        if (option.href) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<a${attr("href", option.href)}${attr("title", "Zum " + option.name)}>${escape_html(option.name)}</a>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<span title="Jetzige Wahl">${escape_html(option.name)}</span>`);
        }
        $$payload.out.push(`<!--]-->`);
      }
      $$payload.out.push(`<!---->`);
    }
    $$payload.out.push(`<!--]--></nav></div></div>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
function Title($$payload, $$props) {
  push();
  $$payload.out.push(`<div class="flex flex-row rounded-b-none justify-between border-header bg-header text-header mt-2"><h1 class="grow text-center whitespace-nowrap">${escape_html(ctx.title)}</h1></div>`);
  pop();
}
function _layout($$payload, $$props) {
  push();
  let { children } = $$props;
  loadUser();
  setInterval(
    () => {
      const now = Date.now() / 1e3;
      if (ctx.user !== null && now - ctx.user.exp > -10) {
        console.log("Threw user out, expired");
        model.User.logout();
        goto();
      }
    },
    5e3
  );
  async function loadUser() {
    ctx.user = await model.User.load();
  }
  Header($$payload);
  $$payload.out.push(`<!----> `);
  Menu($$payload);
  $$payload.out.push(`<!----> `);
  Title($$payload);
  $$payload.out.push(`<!----> <dialog><div class="w-128 h-64 flex flex-col gap-y-4 justify-center items-center border-header bg-header text-header"><div>Willkommen im RGZuchtbuch</div> <button>OK</button></div></dialog> `);
  if (ctx.federation !== null && ctx.standard !== null) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<div class="screen-scroll-y content svelte-canp5v">`);
    children($$payload);
    $$payload.out.push(`<!----></div>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _layout as default
};
