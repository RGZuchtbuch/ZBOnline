import { t as push, z as getContext, F as attr_class, w as escape_html, y as attr, G as bind_props, v as pop } from "./index.js";
import { o as onDestroy } from "./Form.js";
/* empty css                                   */
function Number($$payload, $$props) {
  push();
  let {
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    max = null,
    maxlength = null,
    min = null,
    name = null,
    oninput = null,
    placeholder = null,
    step = 1,
    title = null,
    type = "text",
    validator = null,
    value = void 0
  } = $$props;
  let valid = true;
  const form = getContext("form");
  function validate() {
    if (validator) valid = validator(value);
    return valid;
  }
  onDestroy(() => {
    let index = form.validators.indexOf(validate);
    if (index >= 0) form.validators.splice(index, 1);
  });
  $$payload.out.push(`<div${attr_class(classname, "svelte-1jofz7r")}>`);
  if (label) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<label class="label" for="name">${escape_html(label)}</label>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> <input type="number"${attr_class("input number right svelte-1jofz7r", void 0, { "valid": valid })}${attr("value", value)}${attr("disabled", disabled, true)}${attr("min", min)}${attr("max", max)}${attr("name", name)}${attr("step", step)}${attr("title", title)}/> <label${attr_class("error svelte-1jofz7r", void 0, { "valid": valid })} for="number">${escape_html(error)}</label></div>`);
  bind_props($$props, { element, value });
  pop();
}
export {
  Number as N
};
