import "clsx";
import { t as push, z as getContext, F as attr_class, w as escape_html, y as attr, G as bind_props, v as pop, J as stringify, K as copy_payload, N as assign_payload } from "../../../../../chunks/index.js";
import { g as goto } from "../../../../../chunks/client.js";
import { c as ctx, d as dirty } from "../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../chunks/exports.js";
import "../../../../../chunks/utils.js";
import "jwt-decode";
import { m as model } from "../../../../../chunks/model.js";
import { o as onDestroy, F as Form_1, v as validator } from "../../../../../chunks/Form.js";
import { C as CheckBox, S as Status } from "../../../../../chunks/Status.js";
import { N as Number } from "../../../../../chunks/Number.js";
import { T as Text } from "../../../../../chunks/Text.js";
/* empty css                                                       */
import { h as html } from "../../../../../chunks/html.js";
function TextArea($$payload, $$props) {
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
  let valid = true;
  const form = getContext("form");
  function validate() {
    if (validator2) valid = validator2(value);
    return valid;
  }
  onDestroy(() => {
    let index = form.validators.indexOf(validate);
    if (index >= 0) form.validators.splice(index, 1);
  });
  $$payload.out.push(`<div${attr_class(`wrapper ${stringify(classname)}`)}>`);
  if (label) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<label class="label" for="number">${escape_html(label)}</label>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> <textarea${attr_class("input left svelte-12gq7ue", void 0, { "valid": valid })} style="height:100%"${attr("placeholder", placeholder)}${attr("title", title)}${attr("disabled", disabled, true)}>`);
  const $$body = escape_html(value);
  if ($$body) {
    $$payload.out.push(`${$$body}`);
  }
  $$payload.out.push(`</textarea> <label${attr_class("error", void 0, { "valid": valid })} for="number">${escape_html(error)}</label></div>`);
  bind_props($$props, { element, value });
  pop();
}
function Article($$payload, $$props) {
  push();
  let { article = void 0 } = $$props;
  let edit = article && article.id === 0;
  let remove = false;
  let authorized = ctx.user && ctx.user.admin;
  const validate = {
    level: (v) => validator(v).number().range(1, 999).orNull().isValid(),
    author: (v) => validator(v).string().length(3, 64).orNull().isValid(),
    title: (v) => validator(v).string().length(3, 96).orNullIf(remove).isValid(),
    html: (v) => validator(v).string().length(3, 99999).isValid()
  };
  async function onSubmit(event) {
    console.log("Submit article");
    if (article.title) {
      let ok = await model.Article.save(article);
      dirty.articles++;
      return ok;
    } else if (!article.titel && remove && confirm("Lösschen?")) {
      await model.Article.delete(article.id);
      await goto();
    }
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<section>`);
    if (article) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<div class="flex flex-row items-center justify-end gap-x-2 p-2"><span class="meta svelte-5dol5e">${escape_html(article.author)}, ${escape_html(article.modified)}</span> `);
      if (authorized) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<span class="print:hidden">`);
        CheckBox($$payload2, {
          label: "Ändern",
          error: "",
          get value() {
            return edit;
          },
          set value($$value) {
            edit = $$value;
            $$settled = false;
          }
        });
        $$payload2.out.push(`<!----></span>`);
      } else {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--></div> `);
      if (authorized && edit) {
        $$payload2.out.push("<!--[-->");
        Form_1($$payload2, {
          class: "p-4",
          autosubmit: true,
          onsubmit: onSubmit,
          submitafter: "2500",
          children: ($$payload3) => {
            $$payload3.out.push(`<div></div> <div class="flex flex-row gap-x-4 justify-between">`);
            Number($$payload3, {
              class: "w-16",
              label: "Folge",
              validator: validate.level,
              get value() {
                return article.level;
              },
              set value($$value) {
                article.level = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> `);
            Text($$payload3, {
              class: "grow",
              label: "Titel",
              validator: validate.title,
              get value() {
                return article.title;
              },
              set value($$value) {
                article.title = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----> `);
            CheckBox($$payload3, {
              label: "Löschen",
              title: article.id > 1 ? "Nur wenn alles leer!" : "Hauptartikel nicht Löschbar",
              disabled: article.id === 1 || article.title,
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
            $$payload3.out.push(`<!----></div> <div class="flex flex-row">`);
            Text($$payload3, {
              class: "w-96",
              label: "Autor",
              validator: validate.author,
              get value() {
                return article.author;
              },
              set value($$value) {
                article.author = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!----></div> `);
            TextArea($$payload3, {
              class: "h-64",
              label: "Beitrag",
              validator: validate.html,
              get value() {
                return article.html;
              },
              set value($$value) {
                article.html = $$value;
                $$settled = false;
              }
            });
            $$payload3.out.push(`<!---->`);
          },
          $$slots: { default: true }
        });
      } else {
        $$payload2.out.push("<!--[!-->");
      }
      $$payload2.out.push(`<!--]--> <p class="px-16 py-2">${html(article.html)}</p>`);
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
  bind_props($$props, { article });
  pop();
}
function _page($$payload, $$props) {
  push();
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    if (ctx.article) {
      $$payload2.out.push("<!--[-->");
      Article($$payload2, {
        get article() {
          return ctx.article;
        },
        set article($$value) {
          ctx.article = $$value;
          $$settled = false;
        }
      });
    } else {
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
