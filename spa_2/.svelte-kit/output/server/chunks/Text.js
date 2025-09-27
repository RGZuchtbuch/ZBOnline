import { t as push, z as getContext, F as attr_class, w as escape_html, y as attr, P as clsx, G as bind_props, v as pop, J as stringify } from "./index.js";
import { o as onDestroy } from "./Form.js";
/* empty css                                   */
function Text($$payload, $$props) {
  push();
  let {
    align = "left",
    autocomplete = null,
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    maxlength = null,
    name = null,
    oninput = null,
    placeholder = null,
    title = null,
    type = "text",
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
    let index = form.validators.indexOf(validate);
    if (index >= 0) form.validators.splice(index, 1);
  });
  $$payload.out.push(`<div${attr_class(`wrapper ${stringify(classname)}`, "svelte-19ixaws")}>`);
  if (label) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<label class="label" for="number">${escape_html(label)}</label>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> <input${attr("type", type)}${attr_class(clsx(align), "svelte-19ixaws", { "valid": valid })}${attr("value", value)}${attr("placeholder", placeholder)}${attr("title", title)}${attr("maxlength", maxlength)}${attr("disabled", disabled, true)}${attr("autocomplete", autocomplete)}/> <label${attr_class("error", void 0, { "valid": valid })} for="number">${escape_html(error)}</label></div>`);
  bind_props($$props, { element, value });
  pop();
}
export {
  Text as T
};
