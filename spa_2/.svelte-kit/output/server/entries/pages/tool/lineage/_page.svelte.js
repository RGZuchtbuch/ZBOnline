import "clsx";
import { t as push, K as copy_payload, N as assign_payload, G as bind_props, v as pop, J as stringify, x as ensure_array_like, y as attr, Q as maybe_selected, w as escape_html } from "../../../../chunks/index.js";
import "../../../../chunks/client.js";
import { c as ctx, a as cfg } from "../../../../chunks/store.svelte.js";
import { t as toDate, a as toDateString, F as Form_1, v as validator } from "../../../../chunks/Form.js";
import { T as Text } from "../../../../chunks/Text.js";
import { S as Select } from "../../../../chunks/Select.js";
import { N as Number } from "../../../../chunks/Number.js";
function Date($$payload, $$props) {
  push();
  let {
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    name = null,
    placeholder = null,
    title = null,
    validator: validator2 = null,
    value = void 0
  } = $$props;
  let date = toDate(value);
  let localValue = toDateString(date);
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    Text($$payload2, {
      class: `w-28 ${stringify(classname)}`,
      label,
      placeholder,
      title,
      error,
      validator: validator2,
      disabled,
      get element() {
        return element;
      },
      set element($$value) {
        element = $$value;
        $$settled = false;
      },
      get value() {
        return localValue;
      },
      set value($$value) {
        localValue = $$value;
        $$settled = false;
      }
    });
  }
  do {
    $$settled = true;
    $$inner_payload = copy_payload($$payload);
    $$render_inner($$inner_payload);
  } while (!$$settled);
  assign_payload($$payload, $$inner_payload);
  bind_props($$props, { element, value });
  pop();
}
function Ring($$payload, $$props) {
  push();
  let {
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    name = null,
    oninput = null,
    placeholder = null,
    title = null,
    validator: validator2 = null,
    value = void 0
  } = $$props;
  let localValue = value;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    Text($$payload2, {
      class: `w-32 ${stringify(classname)}`,
      disabled,
      error,
      label,
      placeholder,
      title,
      validator: validator2,
      oninput,
      get value() {
        return localValue;
      },
      set value($$value) {
        localValue = $$value;
        $$settled = false;
      },
      get element() {
        return element;
      },
      set element($$value) {
        element = $$value;
        $$settled = false;
      }
    });
  }
  do {
    $$settled = true;
    $$inner_payload = copy_payload($$payload);
    $$render_inner($$inner_payload);
  } while (!$$settled);
  assign_payload($$payload, $$inner_payload);
  bind_props($$props, { element, value });
  pop();
}
function Lineage($$payload, $$props) {
  push();
  ctx.standard.rootSections;
  let pair = null;
  const validate = {
    layer: {
      lay: { eggs: (v) => validator(v).range(0, 366).orNull().isValid() },
      brood: {
        eggs: (v) => validator(v).range(0, 9999).orNull().isValid(),
        hatched: (brood) => (v) => validator(v).range(0, brood.eggs).orNull().isValid()
      }
    },
    pigeon: {
      brood: {
        count: (v) => validator(v).range(0, 99).orNull().isValid(),
        hatched: (brood) => (v) => validator(v).range(0, 2 * brood.count).orNull().isValid()
      }
    }
  };
  createData();
  function onSectionChange(event) {
    pair.breed = null;
    pair.color = null;
    clearResults();
  }
  function onBreedChange(event) {
    pair.color = null;
    clearResults();
  }
  function createData() {
    pair = {
      // only generic data, rest depends on section : layers vs pigeons
      number: null,
      date: null,
      show: null,
      breeder: null,
      section: null,
      breed: null,
      color: null,
      parents: [],
      grade: "?"
    };
    clearResults();
  }
  function clearResults() {
    const parents = [];
    if (pair.section) {
      const max = pair.section.id === cfg.pigeons ? 2 : 3;
      for (let i = 0; i < max; i++) {
        parents.push({
          sex: i === 0 ? "1.0" : "0.1",
          ring: null,
          grade: "?",
          parents: [
            // grandparents
            {
              sex: "1.0",
              ring: null,
              brood: { count: null, eggs: null, hatched: null, grade: "?" },
              grade: "?"
            },
            {
              sex: "0.1",
              ring: null,
              lay: { eggs: null, grade: "?" },
              brood: { count: null, eggs: null, hatched: null, grade: "?" },
              grade: "?"
            }
          ]
        });
      }
    }
    pair.parents = parents;
    pair.grade = "?";
  }
  function grade(value, dec = 1) {
    return value === 0 ? "0" : value === "?" ? "?" : value.toFixed(dec);
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<main class="text-xl transition:slide">`);
    Form_1($$payload2, {
      autoSave: false,
      children: ($$payload3) => {
        $$payload3.out.push(`<fieldset><div class="flex flex-row px-2 gap-x-2"><span class="w-28 mt-6">Ausstellung</span> `);
        Date($$payload3, { class: "w-32", label: "Datum" });
        $$payload3.out.push(`<!----> <span class="w-8 mt-6 text-center">in</span> `);
        Text($$payload3, { class: "grow", label: "Schau" });
        $$payload3.out.push(`<!----> `);
        Text($$payload3, { class: "w-24", label: "Käfig-Nr." });
        $$payload3.out.push(`<!----></div> <div class="flex flex-row px-2 gap-x-2"><span class="w-28 mt-6">Züchter</span> `);
        Text($$payload3, { class: "grow", label: "Züchter" });
        $$payload3.out.push(`<!----> `);
        Text($$payload3, { class: "w-32", label: "LV Zuchtbuch-Nr" });
        $$payload3.out.push(`<!----> `);
        Text($$payload3, { class: "w-24", label: "Gruppe" });
        $$payload3.out.push(`<!----></div> <div class="flex flex-row px-2 gap-x-2"><span class="w-28 mt-6">Adresse</span> `);
        Text($$payload3, { class: "grow", label: "Adresse" });
        $$payload3.out.push(`<!----> <span class="w-28 mt-6">Ortsverein</span> `);
        Text($$payload3, { class: "w-64", label: "Ortsverein" });
        $$payload3.out.push(`<!----></div> <div class="flex flex-col px-2"><div class="flex flex-row gap-x-2 print:hidden"><span class="w-28 mt-6">Sparte</span> `);
        Select($$payload3, {
          class: "80",
          label: "Sparte *",
          error: "Pflichtfeld",
          onchange: onSectionChange,
          get value() {
            return pair.section;
          },
          set value($$value) {
            pair.section = $$value;
            $$settled = false;
          },
          children: ($$payload4) => {
            const each_array = ensure_array_like(ctx.standard.rootSections);
            $$payload4.out.push(`<option${attr("value", null)}${maybe_selected($$payload4, null)}>?</option> <!--[-->`);
            for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
              let section = each_array[$$index];
              $$payload4.out.push(`<option${attr("value", section)}${maybe_selected($$payload4, section)}>${escape_html(section.name)}</option>`);
            }
            $$payload4.out.push(`<!--]-->`);
          },
          $$slots: { default: true }
        });
        $$payload3.out.push(`<!----></div> <div class="flex flex-row gap-x-2"><span class="w-28 mt-6">Rasse</span> `);
        Select($$payload3, {
          class: "w-80",
          label: "Rasse *",
          error: "Pflichtfeld",
          onchange: onBreedChange,
          get value() {
            return pair.breed;
          },
          set value($$value) {
            pair.breed = $$value;
            $$settled = false;
          },
          children: ($$payload4) => {
            $$payload4.out.push(`<option${attr("value", null)}${maybe_selected($$payload4, null)}>?</option> `);
            if (pair.section) {
              $$payload4.out.push("<!--[-->");
              const each_array_1 = ensure_array_like(pair.section.breeds);
              $$payload4.out.push(`<!--[-->`);
              for (let $$index_1 = 0, $$length = each_array_1.length; $$index_1 < $$length; $$index_1++) {
                let breed = each_array_1[$$index_1];
                $$payload4.out.push(`<option${attr("value", breed)}${maybe_selected($$payload4, breed)}>${escape_html(breed.name)}</option>`);
              }
              $$payload4.out.push(`<!--]-->`);
            } else {
              $$payload4.out.push("<!--[!-->");
            }
            $$payload4.out.push(`<!--]-->`);
          },
          $$slots: { default: true }
        });
        $$payload3.out.push(`<!----> `);
        Select($$payload3, {
          class: "grow",
          label: "Farbenschlag",
          error: "Pflichtfeld",
          get value() {
            return pair.color;
          },
          set value($$value) {
            pair.color = $$value;
            $$settled = false;
          },
          children: ($$payload4) => {
            $$payload4.out.push(`<option${attr("value", null)}${maybe_selected($$payload4, null)}>?</option> `);
            if (pair.breed) {
              $$payload4.out.push("<!--[-->");
              const each_array_2 = ensure_array_like(pair.breed.colors);
              $$payload4.out.push(`<!--[-->`);
              for (let $$index_2 = 0, $$length = each_array_2.length; $$index_2 < $$length; $$index_2++) {
                let color = each_array_2[$$index_2];
                $$payload4.out.push(`<option${attr("value", color)}${maybe_selected($$payload4, color)}>${escape_html(color.name)}</option>`);
              }
              $$payload4.out.push(`<!--]-->`);
            } else {
              $$payload4.out.push("<!--[!-->");
            }
            $$payload4.out.push(`<!--]-->`);
          },
          $$slots: { default: true }
        });
        $$payload3.out.push(`<!----></div></div></fieldset> `);
        if (pair.breed) {
          $$payload3.out.push("<!--[-->");
          const each_array_3 = ensure_array_like(pair.parents);
          $$payload3.out.push(`<div class="flex flex-col mt-4 gap-y-2"><div class="flex flex-row"><div class="w-48 mx-4">`);
          if (pair.section.id === cfg.pigeons) {
            $$payload3.out.push("<!--[-->");
            $$payload3.out.push(`Tauben Paar`);
          } else {
            $$payload3.out.push("<!--[!-->");
            $$payload3.out.push(`Hühner Stamm`);
          }
          $$payload3.out.push(`<!--]--></div> <div class="pr-4"><span>Leistungen der gemeldete Elterntiere</span> `);
          if (pair && pair.section && pair.section.id === cfg.pigeons && pair.breed) {
            $$payload3.out.push("<!--[-->");
            $$payload3.out.push(`in Brutgruppe ${escape_html(pair.breed.broodGroup)}`);
          } else {
            $$payload3.out.push("<!--[!-->");
          }
          $$payload3.out.push(`<!--]--></div></div> <!--[-->`);
          for (let $$index_4 = 0, $$length = each_array_3.length; $$index_4 < $$length; $$index_4++) {
            let parent = each_array_3[$$index_4];
            const each_array_4 = ensure_array_like(parent.parents);
            $$payload3.out.push(`<fieldset class="flex flex-col gap-y-0 border border-gray-400 rounded p-0"><div class="flex flex-row"><div class="flex flex-col"><div class="flex flex-row p-2"><span class="w-8 mt-6 mx-2">${escape_html(parent.sex)}</span> `);
            Ring($$payload3, {
              class: "w-36",
              label: `Bundesring ${stringify(parent.sex)}`
            });
            $$payload3.out.push(`<!----></div> <div class="text-center text-xs">Abstammungsnote</div> <output class="text-2xl text-center font-bold">${escape_html(grade(parent.grade, 1))}</output></div> <div class="grow lex flex-col gap-y-0"><!--[-->`);
            for (let $$index_3 = 0, $$length2 = each_array_4.length; $$index_3 < $$length2; $$index_3++) {
              let grandParent = each_array_4[$$index_3];
              if (pair.section.id === cfg.pigeons) {
                $$payload3.out.push("<!--[-->");
                $$payload3.out.push(`<div class="flex flex-row border border-gray-400 rounded pt-2 px-2 justify-evenly"><span class="w-8 my-6 mx-1">${escape_html(grandParent.sex)}</span> `);
                Ring($$payload3, {
                  class: "w-36",
                  label: `Bundesring ${stringify(grandParent.sex)}`
                });
                $$payload3.out.push(`<!----> <div class="grow border-0 flex flex-row gap-x-2 justify-evenly"><span class="w-36 mt-6 text-left">→ Bruten</span> `);
                Number($$payload3, {
                  class: "w-24",
                  label: "Bruten",
                  validator: validate.pigeon.brood.count,
                  get value() {
                    return grandParent.brood.count;
                  },
                  set value($$value) {
                    grandParent.brood.count = $$value;
                    $$settled = false;
                  }
                });
                $$payload3.out.push(`<!----> <span class="w-8 mt-6">mit</span> `);
                Number($$payload3, {
                  class: "w-32",
                  label: "Beringte Jungtauben",
                  validator: validate.pigeon.brood.hatched(grandParent.brood),
                  get value() {
                    return grandParent.brood.hatched;
                  },
                  set value($$value) {
                    grandParent.brood.hatched = $$value;
                    $$settled = false;
                  }
                });
                $$payload3.out.push(`<!----> <span class="w-4 mt-6">→</span> <output class="w-8 mt-6 mx-1 text-xl font-bold text-center">${escape_html(grade(grandParent.brood.grade, 0))}</output></div></div>`);
              } else {
                $$payload3.out.push("<!--[!-->");
                $$payload3.out.push(`<div class="flex flex-row border border-gray-400 rounded-none -m-px p-1 px-2 justify-evenly"><div class="flex flex-row pt-2"><span class="w-8 my-6 mx-1">${escape_html(grandParent.sex)}</span> `);
                Ring($$payload3, {
                  class: "w-36",
                  label: `Bundesring ${stringify(grandParent.sex)}`
                });
                $$payload3.out.push(`<!----></div> <div class="grow flex flex-col">`);
                if (grandParent.sex === "0.1") {
                  $$payload3.out.push("<!--[-->");
                  $$payload3.out.push(`<div class="grow border-0 flex flex-row justify-evenly pt-2"><div class="w-32 mt-6 mx-1 text-left">→ Legen</div> `);
                  Number($$payload3, {
                    class: "w-32",
                    label: "Legen e/j",
                    validator: validate.layer.lay.eggs,
                    get value() {
                      return grandParent.lay.eggs;
                    },
                    set value($$value) {
                      grandParent.lay.eggs = $$value;
                      $$settled = false;
                    }
                  });
                  $$payload3.out.push(`<!----> <div class="w-8 mt-6 mx-1 text-center">von</div> `);
                  Number($$payload3, {
                    class: "w-32",
                    label: "SOLL Legen",
                    value: pair.breed ? pair.breed.layEggs : "?",
                    disabled: true
                  });
                  $$payload3.out.push(`<!----> <div class="w-4 mt-6 mx-1 text-center">→</div> <output class="w-8 mt-6 mx-1 text-xl font-bold text-center">${escape_html(grade(grandParent.lay.grade, 0))}</output></div>`);
                } else {
                  $$payload3.out.push("<!--[!-->");
                }
                $$payload3.out.push(`<!--]--> <div class="grow border-0 flex flex-row justify-evenly pt-2"><span class="w-32 mt-6 mx-1 text-left">→ Brut</span> `);
                Number($$payload3, {
                  class: "w-32",
                  label: "Eingelegt",
                  validator: validate.layer.brood.eggs,
                  get value() {
                    return grandParent.brood.eggs;
                  },
                  set value($$value) {
                    grandParent.brood.eggs = $$value;
                    $$settled = false;
                  }
                });
                $$payload3.out.push(`<!----> <span class="w-8 mt-6 mx-1 text-center">mit</span> `);
                Number($$payload3, {
                  class: "w-32",
                  label: "Geschlüpft",
                  validator: validate.layer.brood.hatched(grandParent.brood),
                  get value() {
                    return grandParent.brood.hatched;
                  },
                  set value($$value) {
                    grandParent.brood.hatched = $$value;
                    $$settled = false;
                  }
                });
                $$payload3.out.push(`<!----> <span class="w-4 mt-6 mx-1 text-center">→</span> <output class="w-8 mt-6 mx-1 text-xl font-bold text-center">${escape_html(grade(grandParent.brood.grade, 0))}</output></div></div></div>`);
              }
              $$payload3.out.push(`<!--]-->`);
            }
            $$payload3.out.push(`<!--]--></div></div></fieldset>`);
          }
          $$payload3.out.push(`<!--]--> <footer class="flex flex-row align-stretch mt-2 h-32 gap-x-4"><div class="flex grow border border-gray-600 px-4">Datum / Unterschrift Züchter</div> <div class="grow border border-gray-600 px-4">Datum / Unterschrift Obmann</div> <div class="w-64 my-1 flex flex-col font-bold text-3xl"><div class="text-center">Leistungsnote</div> <strong class="m-2 text-center">${escape_html(grade(pair.grade, 0))}</strong></div></footer></div>`);
        } else {
          $$payload3.out.push("<!--[!-->");
          $$payload3.out.push(`<div class="text-center italic">Der Leistungsdatenteil erscheint sobalt die Rasse eingegeben ist</div>`);
        }
        $$payload3.out.push(`<!--]-->`);
      },
      $$slots: { default: true }
    });
    $$payload2.out.push(`<!----></main>`);
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
  Lineage($$payload);
  $$payload.out.push(`<!----></main>`);
  pop();
}
export {
  _page as default
};
