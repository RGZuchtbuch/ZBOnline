import "clsx";
import { K as copy_payload, N as assign_payload, v as pop, t as push, w as escape_html, y as attr, x as ensure_array_like } from "../../../../chunks/index.js";
import { c as ctx } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import "../../../../chunks/client.js";
import "jwt-decode";
import { f as fullName } from "../../../../chunks/tools.js";
/* empty css                                                    */
function District_1($$payload, $$props) {
  push();
  let { district } = $$props;
  ctx.user && ctx.user.admin;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div class="ml-6 flex flex-col py-2"><div class="flex flex-row grow py-2 border-b rounded-b-none"><span class="grow">${escape_html(district.name)}</span> <span class="w-64">${escape_html(fullName(district.moderator))}</span> <div class="w-16 text-center">`);
    if (district.moderator && district.moderator.email) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<a class="px-2 border-button bg-button text-button text-center print:hidden"${attr("href", `/federation/message?district=${district.id}&to=${district.moderator.id}`)} title="Email schicken">✉</a>`);
    } else {
      $$payload2.out.push("<!--[!-->");
    }
    $$payload2.out.push(`<!--]--></div> <span class="w-16 text-center"><a class="px-2 border-button bg-button text-button text-center"${attr("href", `/moderator/district/${district.id}`)} title="Verband verwalten als Obmann">⚙</a></span> <div class="w-16 text-center"><button class="px-2 border-button bg-button text-button" title="Daten bearbeiten">`);
    {
      $$payload2.out.push("<!--[!-->");
      $$payload2.out.push(`▶`);
    }
    $$payload2.out.push(`<!--]--></button></div></div> `);
    {
      $$payload2.out.push("<!--[!-->");
    }
    $$payload2.out.push(`<!--]--> `);
    if (district.children) {
      $$payload2.out.push("<!--[-->");
      const each_array_1 = ensure_array_like(district.children);
      $$payload2.out.push(`<!--[-->`);
      for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
        let child = each_array_1[$$index_1];
        $$payload2.out.push(`<!---->`);
        {
          District_1($$payload2, { district: child });
        }
        $$payload2.out.push(`<!---->`);
      }
      $$payload2.out.push(`<!--]-->`);
    } else {
      $$payload2.out.push("<!--[!-->");
    }
    $$payload2.out.push(`<!--]--></div>`);
  }
  do {
    $$settled = true;
    $$inner_payload = copy_payload($$payload);
    $$render_inner($$inner_payload);
  } while (!$$settled);
  assign_payload($$payload, $$inner_payload);
  pop();
}
function DistrictTree($$payload, $$props) {
  push();
  let { root } = $$props;
  let authorized = ctx.user && ctx.user.admin;
  $$payload.out.push(`<section class="flex flex-col pl-4">`);
  if (authorized && root) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<header class="flex flex-row border-header bg-header text-header px-2 sticky top-1"><span class="grow">Verbände</span> <span class="w-64">Obmann</span> <span class="w-16 text-center" title="Email schicken">Email</span> <span class="w-16 text-center" title="Als Obmann bearbeiten">Verw.</span> <span class="w-16 text-center" title="Daten bearbeiten">Bearb.</span></header> `);
    District_1($$payload, { district: root });
    $$payload.out.push(`<!---->`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--></section>`);
  pop();
}
function _page($$payload, $$props) {
  push();
  $$payload.out.push(`<!---->Districts to moderate as obmann `);
  if (ctx.federation) {
    $$payload.out.push("<!--[-->");
    DistrictTree($$payload, { root: ctx.federation });
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
