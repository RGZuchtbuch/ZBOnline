import { t as push, z as getContext, F as attr_class, w as escape_html, y as attr, G as bind_props, v as pop } from "./index.js";
import { o as onDestroy } from "./Form.js";
/* empty css                                   */
function Select($$payload, $$props) {
  push();
  let {
    children,
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    name = null,
    onchange = null,
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
    if (form) {
      let index = form.validators.indexOf(validate);
      if (index >= 0) form.validators.splice(index, 1);
    }
  });
  $$payload.out.push(`<div${attr_class(classname, "svelte-yoovd4")}>`);
  if (label) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<label class="label" for="number">${escape_html(label)}</label>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> <select${attr_class("input", void 0, { "valid": valid })}${attr("disabled", disabled, true)}${attr("name", name)}${attr("title", title)}>`);
  $$payload.select_value = value;
  children($$payload);
  $$payload.out.push(`<!---->`);
  $$payload.select_value = void 0;
  $$payload.out.push(`</select> <label${attr_class("error", void 0, { "valid": valid })} for="number">${escape_html(error)}</label></div>`);
  bind_props($$props, { element, value });
  pop();
}
export {
  Select as S
};
