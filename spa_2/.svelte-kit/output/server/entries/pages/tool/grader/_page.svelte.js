import "clsx";
import { K as copy_payload, N as assign_payload, v as pop, t as push, x as ensure_array_like, y as attr, Q as maybe_selected, w as escape_html } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx, a as cfg } from "../../../../chunks/store.svelte.js";
import { F as Form_1, v as validator } from "../../../../chunks/Form.js";
import { S as Select } from "../../../../chunks/Select.js";
import { N as Number } from "../../../../chunks/Number.js";
function Grader($$payload, $$props) {
  push();
  const sections = ctx.standard.rootSections;
  let section = null;
  let breed = null;
  let lay = { eggs: null };
  let brood = { count: null, eggs: null, hatched: null, grade: null };
  const validate = {
    layer: {
      lay: { eggs: (v) => validator(v).range(0, 366).orNull().isValid() },
      brood: {
        eggs: (v) => validator(v).range(0, 9999).orNull().isValid(),
        hatched: (brood2) => (v) => validator(v).range(0, brood2.eggs).orNull().isValid()
      }
    },
    pigeon: {
      brood: {
        count: (v) => validator(v).range(0, 99).orNull().isValid(),
        hatched: (brood2) => (v) => validator(v).range(0, 2 * brood2.count).orNull().isValid()
      }
    }
  };
  function grade(value, dec = 0) {
    return "?";
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div><div class="px-4">`);
    Form_1($$payload2, {
      autoSave: false,
      children: ($$payload3) => {
        $$payload3.out.push(`<section><fieldset class="flex flex-col"><h2>Wähle Sparte und Rasse</h2> <div class="flex flex-row gap-x-4"><span class="hidden md:block w-16 mt-6">Sparte</span> `);
        Select($$payload3, {
          class: "w-80",
          label: "Sparte *",
          error: "Pflichtfeld",
          get value() {
            return section;
          },
          set value($$value) {
            section = $$value;
            $$settled = false;
          },
          children: ($$payload4) => {
            const each_array = ensure_array_like(sections);
            $$payload4.out.push(`<option${attr("value", null)}${maybe_selected($$payload4, null)}>?</option> <!--[-->`);
            for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
              let section2 = each_array[$$index];
              $$payload4.out.push(`<option${attr("value", section2)}${maybe_selected($$payload4, section2)}>${escape_html(section2.name)}</option>`);
            }
            $$payload4.out.push(`<!--]-->`);
          },
          $$slots: { default: true }
        });
        $$payload3.out.push(`<!----></div> <div class="flex flex-row gap-x-4"><span class="hidden md:block w-16 mt-6">Rasse</span> `);
        Select($$payload3, {
          class: "w-80",
          label: "Rasse *",
          error: "Pflichtfeld",
          get value() {
            return breed;
          },
          set value($$value) {
            breed = $$value;
            $$settled = false;
          },
          children: ($$payload4) => {
            $$payload4.out.push(`<option${attr("value", null)}${maybe_selected($$payload4, null)}>?</option> `);
            if (section && section.breeds) {
              $$payload4.out.push("<!--[-->");
              const each_array_1 = ensure_array_like(section.breeds);
              $$payload4.out.push(`<!--[-->`);
              for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
                let breed2 = each_array_1[$$index_1];
                $$payload4.out.push(`<option${attr("value", breed2)}${maybe_selected($$payload4, breed2)}>${escape_html(breed2.name)}</option>`);
              }
              $$payload4.out.push(`<!--]-->`);
            } else {
              $$payload4.out.push("<!--[!-->");
            }
            $$payload4.out.push(`<!--]-->`);
          },
          $$slots: { default: true }
        });
        $$payload3.out.push(`<!----></div></fieldset></section> <hr/> `);
        if (section && breed) {
          $$payload3.out.push("<!--[-->");
          $$payload3.out.push(`<div class="flex flex-col">`);
          if (section.id === cfg.pigeons) {
            $$payload3.out.push("<!--[-->");
            $$payload3.out.push(`<div class="text-center">Brutgruppe ${escape_html(breed.broodGroup)}</div> <fieldset class="flex flex-row gap-x-4 justify-evenly"><span class="hidden md:block w-40 mt-6 text-left">Brutleistung</span> `);
            Number($$payload3, {
              class: "w-24",
              label: "Bruten",
              validator: validate.pigeon.brood.count,
              get value() {
                return brood.count;
              },
              set value($$value) {
                brood.count = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <span class="w-2 mt-6 text-center">:</span> `);
            Number($$payload3, {
              class: "w-24",
              label: "Ber. Jungtauben",
              validator: validate.pigeon.brood.hatched(brood),
              get value() {
                return brood.hatched;
              },
              set value($$value) {
                brood.hatched = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <span class="w-2 mt-6 text-center">→</span> <output class="w-8 mt-6 text-xl font-bold text-center">${escape_html(grade())}</output></fieldset>`);
          } else {
            $$payload3.out.push("<!--[!-->");
            $$payload3.out.push(`<fieldset class="flex flex-col md:flex-row gap-x-4"><div class="w-40 mt-6 text-left">Legeleistung</div> <div class="flex flex-row">`);
            Number($$payload3, {
              class: "w-32",
              label: "Legen e/j",
              validator: validate.layer.lay.eggs,
              get value() {
                return lay.eggs;
              },
              set value($$value) {
                lay.eggs = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <div class="w-8 mt-6 text-center">von</div> `);
            Number($$payload3, {
              class: "w-32",
              label: "SOLL Legen",
              value: breed ? breed.layEggs : "?",
              disabled: true
            });
            $$payload3.out.push(`<!----> <div class="w-8 mt-6 text-center">→</div> <output class="w-8 mt-6 text-xl font-bold text-center whitespace-nowrap">${escape_html(grade())} Punkte</output></div></fieldset> <fieldset class="flex flex-col md:flex-row p-4 gap-x-4"><span class="w-40 mt-6 text-left">Brutleistung</span> <div class="flex flex-row">`);
            Number($$payload3, {
              class: "w-32",
              label: "Eingelegt",
              validator: validate.layer.brood.eggs,
              get value() {
                return brood.eggs;
              },
              set value($$value) {
                brood.eggs = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <span class="w-8 mt-6 text-center">mit</span> `);
            Number($$payload3, {
              class: "w-32",
              label: "Geschüpft",
              validator: validate.layer.brood.hatched(brood),
              get value() {
                return brood.hatched;
              },
              set value($$value) {
                brood.hatched = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> <span class="w-8 mt-6 text-center">→</span> <output class="w-8 mt-6 text-xl font-bold text-center whitespace-nowrap">${escape_html(grade())} Punkte</output></div></fieldset>`);
          }
          $$payload3.out.push(`<!--]--></div>`);
        } else {
          $$payload3.out.push("<!--[!-->");
          $$payload3.out.push(`<p class="m-16 text-center italic">Der Leistungsdatenteil erscheint sobalt die Rasse ausgewählt worden ist.<br/> Beachte: diese Daten werden nicht gespeichert !</p>`);
        }
        $$payload3.out.push(`<!--]-->`);
      },
      $$slots: { default: true }
    });
    $$payload2.out.push(`<!----></div></div>`);
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
  $$payload.out.push(`<main>`);
  Grader($$payload);
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
