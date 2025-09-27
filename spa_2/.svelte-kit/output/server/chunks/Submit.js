import { t as push, K as copy_payload, N as assign_payload, G as bind_props, v as pop, z as getContext, F as attr_class, y as attr, J as stringify } from "./index.js";
import { T as Text } from "./Text.js";
import { o as onDestroy } from "./Form.js";
/* empty css                                   */
function Email($$payload, $$props) {
  push();
  let {
    autocomplete = null,
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "Fehler",
    label = null,
    name = null,
    placeholder = null,
    title = null,
    validator = null,
    value = void 0
  } = $$props;
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    Text($$payload2, {
      class: classname,
      type: "email",
      disabled,
      error,
      label,
      name,
      placeholder,
      title,
      validator,
      oninput,
      onchange,
      onfocus,
      onblur,
      autocomplete,
      get element() {
        return element;
      },
      set element($$value) {
        element = $$value;
        $$settled = false;
      },
      get value() {
        return value;
      },
      set value($$value) {
        value = $$value;
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
function Password($$payload, $$props) {
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
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    Text($$payload2, {
      class: classname,
      type: "password",
      disabled,
      error,
      label,
      name,
      placeholder,
      title,
      validator,
      oninput,
      onchange,
      onfocus,
      onblur,
      autocomplete: "current-password",
      get element() {
        return element;
      },
      set element($$value) {
        element = $$value;
        $$settled = false;
      },
      get value() {
        return value;
      },
      set value($$value) {
        value = $$value;
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
function Submit($$payload, $$props) {
  push();
  const defaultValue = {
    waiting: "...",
    changed: "...",
    invalid: "X",
    valid: "Ok",
    disabled: "x",
    stored: "Fertig"
  };
  let {
    class: classname = "",
    disabled = false,
    element = void 0,
    error = "!",
    label = null,
    name = null,
    placeholder = null,
    title = null,
    validator = null,
    values = defaultValue
  } = $$props;
  const form = getContext("form");
  $$payload.out.push(`<input${attr_class(`submit ${stringify(classname)} ${stringify(form.state)}`, "svelte-m3qmun")} type="submit"${attr("value", values[form.state])}${attr("disabled", form.state !== "valid", true)}${attr("title", form.state)}/>`);
  bind_props($$props, { element, values });
  pop();
}
export {
  Email as E,
  Password as P,
  Submit as S
};
