import { t as push, z as getContext, F as attr_class, w as escape_html, y as attr, G as bind_props, v as pop, J as stringify } from "./index.js";
import { o as onDestroy } from "./Form.js";
/* empty css                                   */
function CheckBox($$payload, $$props) {
  push();
  let {
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    name = null,
    onchange = null,
    placeholder = null,
    title = null,
    validator = null,
    value = void 0
  } = $$props;
  const form = getContext("form");
  let valid = true;
  function validate() {
    if (validator) valid = validator(value);
    return valid;
  }
  onDestroy(() => {
    if (form && form.validators) {
      let index = form.validators.indexOf(validate);
      if (index >= 0) form.validators.splice(index, 1);
    }
  });
  $$payload.out.push(`<div${attr_class(`wrapper ${stringify(classname)} items-center`)}>`);
  if (label) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<label class="label svelte-1y0swg9" for="number">${escape_html(label)}</label>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> <input type="checkbox"${attr_class("input w-4 h-4 mt-2", void 0, { "valid": valid })}${attr("checked", value, true)}${attr("disabled", disabled, true)}${attr("name", name)}${attr("placeholder", placeholder)}${attr("title", title)}/> <label${attr_class("error", void 0, { "valid": valid })} for="number">${escape_html(error)}</label></div>`);
  bind_props($$props, { element, value });
  pop();
}
function Status($$payload, $$props) {
  push();
  let {
    class: classname = "",
    element = void 0,
    title = null,
    validator = null,
    value = "⚑"
  } = $$props;
  let form = getContext("form");
  const titles = {
    waiting: "Warten auf Antwort...",
    changed: "Geändert",
    invalid: "Fehler",
    valid: "Gültig",
    disabled: "Disabled",
    stored: "Gespeichert"
  };
  $$payload.out.push(`<span${attr_class(
    `status ${stringify(
      // ☑ ⚑ ⚿ ⛔ ⏳ 😊 😲😕😪😈⚫
      classname
    )} ${stringify(form.state)} print:hidden`,
    "svelte-17mtevp"
  )}${attr("title", titles[form.state])}>${escape_html(value)}</span>`);
  bind_props($$props, { element, value });
  pop();
}
export {
  CheckBox as C,
  Status as S
};
