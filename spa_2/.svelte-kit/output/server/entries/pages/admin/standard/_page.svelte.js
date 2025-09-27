import "clsx";
import { t as push, K as copy_payload, N as assign_payload, G as bind_props, v as pop, w as escape_html, x as ensure_array_like } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx, a as cfg } from "../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../chunks/exports.js";
import "../../../../chunks/utils.js";
import "jwt-decode";
import { F as Form_1, v as validator } from "../../../../chunks/Form.js";
import { m as model } from "../../../../chunks/model.js";
import { T as Text } from "../../../../chunks/Text.js";
import { C as CheckBox, S as Status } from "../../../../chunks/Status.js";
/* empty css                                                    */
function Color($$payload, $$props) {
  push();
  let { breed, color = void 0 } = $$props;
  console.log("Color cmp");
  let authorized = ctx.user && ctx.user.admin;
  let edit = false;
  let remove = false;
  if (color.id === 0) edit = true;
  const validate = {
    name: (v) => validator(v).string().length(2, 64).orNullIf(remove).isValid()
  };
  async function onSubmit(event) {
    console.log("Submit color");
    if (color.name) {
      let response = await model.Standard.saveColor(color);
      return response;
    } else if (!color.name && remove && confirm("Lösschen?")) {
      console.log("A");
      let response = await model.Standard.deleteColor(color.id);
      if (response) {
        let index = breed.colors.findIndex((item) => item.id === color.id);
        console.log("B", index);
        breed.colors.splice(index, 1);
      }
      return response;
    }
    return true;
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div class="flex flex-col">`);
    if (color) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<div class="flex flex-rowflex flex-row p-2 gap-x-1" title="Farbenschlag"><div class="grow italic">• ${escape_html(color.name)}</div> `);
      if (authorized) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<button class="w-8 border-button bg-button text-button">`);
        if (edit) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`⯇`);
        } else {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`▶`);
        }
        $$payload2.out.push(`<!--]--></button>`);
      } else {
        $$payload2.out.push("<!--[!-->");
        $$payload2.out.push(`<div class="w-8"></div>`);
      }
      $$payload2.out.push(`<!--]--> <div class="w-8"></div></div> `);
      if (authorized && edit) {
        $$payload2.out.push("<!--[-->");
        Form_1($$payload2, {
          autosubmit: true,
          onsubmit: onSubmit,
          children: ($$payload3) => {
            $$payload3.out.push(`<fieldset class="ml-8 flex flex-col px-2"><div class="flex flex-row gap-x-2">`);
            Text($$payload3, { class: "w-8", label: "Id", value: color.id, disabled: true });
            $$payload3.out.push(`<!----> `);
            Text($$payload3, {
              class: "w-128",
              label: "Name",
              validator: validate.name,
              get value() {
                return color.name;
              },
              set value($$value) {
                color.name = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <div class="grow"></div> `);
            CheckBox($$payload3, {
              label: "Löschen",
              title: "Nur wenn Name leer und keine meldungen",
              disabled: color.name,
              get value() {
                return remove;
              },
              set value($$value) {
                remove = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> `);
            Status($$payload3, {});
            $$payload3.out.push(`<!----></div></fieldset>`);
          },
          $$slots: { default: true }
        });
      } else {
        $$payload2.out.push("<!--[!-->");
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
  bind_props($$props, { color });
  pop();
}
function Breed($$payload, $$props) {
  push();
  let { section, breed = void 0, unfold = false } = $$props;
  let authorized = ctx.user && ctx.user.admin;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div class="flex flex-col">`);
    if (breed) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<div class="flex flex-row p-2 gap-x-1"><button class="inline" type="button" title="Farben">${escape_html(unfold ? "▽" : "▷")}</button> <div class="grow" title="Rasse">${escape_html(breed.name)}</div> <div class="w-10 text-right" title="Brutgruppe">`);
      if (section.parentId === cfg.pigeons) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`${escape_html(breed.broodGroup)}`);
      } else {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--></div> <div class="w-10 text-right" title="Legeleistung">${escape_html(breed.layEggs)}</div> <div class="w-10 text-right" title="Bruteigewicht">${escape_html(breed.layWeight)}</div> <div class="w-12 text-right" title="Zielgewicht der Hähne">${escape_html(breed.sireWeight)}</div> <div class="w-12 text-left" title="Zielgewicht der Hennen">.${escape_html(breed.dameWeight)}</div> <div class="w-10 text-right" title="Ringgröße Hahn">${escape_html(breed.sireRing)}</div> <div class="w-10 text-left" title="Ringgröße Henne">.${escape_html(breed.dameRing)}</div> `);
      if (authorized) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<button class="w-8 border-button bg-button text-button" title="Bearbeiten als Admin">`);
        {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`▶`);
        }
        $$payload2.out.push(`<!--]--></button> `);
        if (unfold) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`<button class="w-8 border-button bg-button text-button" title="Farbe hinzufügen">✙</button>`);
        } else {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`<div class="w-8"></div>`);
        }
        $$payload2.out.push(`<!--]-->`);
      } else {
        $$payload2.out.push("<!--[!-->");
        $$payload2.out.push(`<div class="w-8"></div>`);
      }
      $$payload2.out.push(`<!--]--></div> `);
      {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--> `);
      if (unfold) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<div class="pl-12">`);
        if (breed.colors.length > 0) {
          $$payload2.out.push("<!--[-->");
          const each_array_1 = ensure_array_like(breed.colors);
          $$payload2.out.push(`<!--[-->`);
          for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
            each_array_1[i];
            $$payload2.out.push(`<!---->`);
            {
              Color($$payload2, {
                breed,
                get color() {
                  return breed.colors[i];
                },
                set color($$value) {
                  breed.colors[i] = $$value;
                  $$settled = false;
                }
              });
            }
            $$payload2.out.push(`<!---->`);
          }
          $$payload2.out.push(`<!--]-->`);
        } else {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`<div>Noch keine farbenschläge eingegeben</div>`);
        }
        $$payload2.out.push(`<!--]--></div>`);
      } else {
        $$payload2.out.push("<!--[!-->");
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
  bind_props($$props, { breed });
  pop();
}
function Section_1($$payload, $$props) {
  push();
  let { section = void 0, unfold = false } = $$props;
  let authorized = ctx.user && ctx.user.admin;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div class="flex flex-col">`);
    if (section) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<div class="flex flex-row p-2 gap-x-1"><button class="inline" type="button" title="Öffnen">${escape_html(unfold ? "▽" : "▷")}</button> <div class="grow font-bold" title="Sparte">${escape_html(section.name)}</div> `);
      if (unfold && section.breeds.length > 0) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<div class="w-10 text-center" title="Brutgruppe">`);
        if (section.parentId === cfg.pigeons) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`B.G.`);
        } else {
          $$payload2.out.push("<!--[!-->");
        }
        $$payload2.out.push(`<!--]--></div> <div class="w-10 text-center" title="Legeleistung">`);
        if (section.parentId !== cfg.pigeons) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`Eier`);
        } else {
          $$payload2.out.push("<!--[!-->");
        }
        $$payload2.out.push(`<!--]--></div> <div class="w-10 text-center" title="Bruteigewicht">`);
        if (section.parentId !== cfg.pigeons) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`Gew.`);
        } else {
          $$payload2.out.push("<!--[!-->");
        }
        $$payload2.out.push(`<!--]--></div> <div class="w-24 text-center" title="Zielgewicht der Hähne">Gewicht</div> <div class="w-20 text-center" title="Ringgröße Hahn">Ring</div>`);
      } else {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--> `);
      if (authorized) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<button class="w-8 border-button bg-button text-button" title="Bearbeiten als Admin">`);
        {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`▶`);
        }
        $$payload2.out.push(`<!--]--></button> `);
        if (unfold && section.children.length === 0) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`<button class="w-8 border-button bg-button text-button" title="Rasse hinzufügen">✙</button>`);
        } else {
          $$payload2.out.push("<!--[!-->");
          $$payload2.out.push(`<div class="w-8"></div>`);
        }
        $$payload2.out.push(`<!--]-->`);
      } else {
        $$payload2.out.push("<!--[!-->");
        $$payload2.out.push(`<div class="w-8"></div>`);
      }
      $$payload2.out.push(`<!--]--></div> `);
      {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--> `);
      if (unfold) {
        $$payload2.out.push("<!--[-->");
        const each_array = ensure_array_like(section.children);
        $$payload2.out.push(`<ul class="pl-8"><!--[-->`);
        for (let i = 0, $$length = each_array.length; i < $$length; i++) {
          each_array[i];
          $$payload2.out.push(`<!---->`);
          {
            Section_1($$payload2, {
              get section() {
                return section.children[i];
              },
              set section($$value) {
                section.children[i] = $$value;
                $$settled = false;
              }
            });
          }
          $$payload2.out.push(`<!---->`);
        }
        $$payload2.out.push(`<!--]--></ul> `);
        if (section.children.length === 0) {
          $$payload2.out.push("<!--[-->");
          const each_array_1 = ensure_array_like(section.breeds);
          $$payload2.out.push(`<ul class="pl-8"><!--[-->`);
          for (let i = 0, $$length = each_array_1.length; i < $$length; i++) {
            each_array_1[i];
            $$payload2.out.push(`<!---->`);
            {
              Breed($$payload2, {
                section,
                get breed() {
                  return section.breeds[i];
                },
                set breed($$value) {
                  section.breeds[i] = $$value;
                  $$settled = false;
                }
              });
            }
            $$payload2.out.push(`<!---->`);
          }
          $$payload2.out.push(`<!--]--></ul>`);
        } else {
          $$payload2.out.push("<!--[!-->");
        }
        $$payload2.out.push(`<!--]-->`);
      } else {
        $$payload2.out.push("<!--[!-->");
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
  bind_props($$props, { section });
  pop();
}
function Standard($$payload, $$props) {
  push();
  let { standard } = $$props;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<section class="svelte-12vbc5"><header class="border-header bg-header text-header text-center">Standard, Sparten, Rassen und Farbenschläge</header> `);
    if (standard) {
      $$payload2.out.push("<!--[-->");
      Section_1($$payload2, {
        unfold: true,
        get section() {
          return ctx.standard.root;
        },
        set section($$value) {
          ctx.standard.root = $$value;
          $$settled = false;
        }
      });
    } else {
      $$payload2.out.push("<!--[!-->");
    }
    $$payload2.out.push(`<!--]--></section>`);
  }
  do {
    $$settled = true;
    $$inner_payload = copy_payload($$payload);
    $$render_inner($$inner_payload);
  } while (!$$settled);
  assign_payload($$payload, $$inner_payload);
  pop();
}
function _page($$payload, $$props) {
  push();
  if (ctx.standard) {
    $$payload.out.push("<!--[-->");
    Standard($$payload, { standard: ctx.standard });
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
