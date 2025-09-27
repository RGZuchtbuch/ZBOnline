import { O as current_component, t as push, u as setContext, F as attr_class, y as attr, w as escape_html, G as bind_props, v as pop } from "./index.js";
/* empty css                                   */
function onDestroy(fn) {
  var context = (
    /** @type {Component} */
    current_component
  );
  (context.d ??= []).push(fn);
}
function isEmail(value) {
  if (value) {
    const regex = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
    return regex.test(value);
  }
  return false;
}
function isPassword(value) {
  if (value) {
    const regex = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/;
    return regex.test(value);
  }
  return false;
}
function isURL(value) {
  if (value) {
    const regex = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
    return regex.test(value);
  }
  return false;
}
function toDate(input, yearsAhead = 10) {
  if (input) {
    let match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.\-](1[0-2]|0[1-9]|[1-9])[\.\-]([0-9]{2})$/);
    if (match) {
      const year = toFullYear(match[3]);
      return new Date(year, match[2] - 1, match[1]);
    } else {
      match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.\-](1[0-2]|0[1-9]|[1-9])[\.\-]([0-9]{4})$/);
      if (match) {
        return new Date(match[3], match[2] - 1, match[1]);
      } else {
        match = input.match(/^([0-9]{4})[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.](3[0-1]|[12][0-9]|0[1-9]|[1-9])$/);
        if (match) {
          return new Date(match[1], match[2] - 1, match[3]);
        }
      }
    }
  }
  return null;
}
function toDateString(date) {
  return date ? date.getDate().toString().padStart(2, "0") + "." + (date.getMonth() + 1).toString().padStart(2, "0") + "." + date.getFullYear().toString().padStart(4, "0") : null;
}
function toFullYear(shortYear) {
  const thisYear = (/* @__PURE__ */ new Date()).getFullYear();
  const currentCentury = Math.floor(thisYear / 100);
  const tryYear = 100 * currentCentury + Number(shortYear % 100);
  return tryYear > thisYear + 1 ? tryYear - 100 : tryYear;
}
function toNumber(value) {
  return value !== void 0 && value !== null && !isNaN(value) ? Number(value) : null;
}
function toRing(value) {
  if (value) {
    let match = value.match(/^(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/);
    if (match) {
      return { country: "D", year: toFullYear(match[1]), code: match[2].toUpperCase(), number: match[3] };
    } else {
      match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/);
      if (match) {
        return { country: match[1].toUpperCase(), year: toFullYear(match[2]), code: match[3].toUpperCase(), number: match[4] };
      } else {
        match = value.match(/^(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/);
        if (match) {
          return { country: "D", year: match[1], code: match[2].toUpperCase(), number: match[3] };
        }
        match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/);
        if (match) {
          return { country: match[1].toUpperCase(), year: match[2], code: match[3].toUpperCase(), number: match[4] };
        }
      }
    }
  }
  return null;
}
function toString(value) {
  return value !== void 0 && value !== null ? String(value) : null;
}
function validator(value) {
  let valid = true;
  const worker = {
    // to stream
    boolean: () => {
    },
    string: () => {
      const stringValue = toString(value);
      if (stringValue !== null) {
        value = stringValue;
      } else {
        valid = false;
      }
      return worker;
    },
    number: () => {
      const numberValue = toNumber(value);
      if (numberValue !== null) {
        value = numberValue;
      } else {
        valid = false;
      }
      return worker;
    },
    date: () => {
      const dateValue = toDate(value);
      if (dateValue !== null) {
        value = dateValue;
      } else {
        valid = false;
      }
      return worker;
    },
    ring: () => {
      const ringValue = toRing(value);
      if (ringValue !== null) {
        value = ringValue;
      } else {
        valid = false;
      }
      return worker;
    },
    email: () => {
      valid &&= isEmail(value);
      return worker;
    },
    password: () => {
      valid &&= isPassword(value);
      return worker;
    },
    url: () => {
      valid &&= isURL(value);
      return worker;
    },
    true: () => {
      valid &&= value;
      return worker;
    },
    range: (min, max) => {
      valid &&= min <= value && value <= max;
      return worker;
    },
    after: (date) => {
      const other = toDate(date);
      if (value && other) {
        valid &&= value.getTime() > other.getTime();
      } else {
        valid = false;
      }
      return worker;
    },
    before: (date) => {
      const other = toDate(date);
      if (value && other) {
        valid &&= value.getTime() < other.getTime();
      } else {
        valid = false;
      }
      return worker;
    },
    between: (after, before) => {
      const afterDate = toDate(after);
      const beforeDate = toDate(before);
      if (value && afterDate && beforeDate) {
        valid &&= value.getTime() >= afterDate.getTime() && value.getTime() <= beforeDate.getTime();
      } else {
        valid = false;
      }
      return worker;
    },
    length: (min, max) => {
      valid &&= min <= value.length && value.length <= max;
      return worker;
    },
    if: (condition) => {
      valid = condition ? valid : false;
      return worker;
    },
    notNull: () => {
      valid = value != null;
      return worker;
    },
    orNull: () => {
      valid = value === null ? true : valid;
      return worker;
    },
    orNullIf: (condition) => {
      valid = condition && value === null ? true : valid;
      return worker;
    },
    isValid: () => {
      return valid;
    },
    isInvalid: () => {
      return !valid;
    }
  };
  return worker;
}
function Form_1($$payload, $$props) {
  push();
  let {
    autosubmit = false,
    children,
    class: classname = "",
    disabled = false,
    initialState = "stored",
    legend = null,
    onsubmit = null,
    valid = void 0,
    validateafter = 500,
    submitafter = 1500
  } = $$props;
  const states = {
    changed: "changed",
    invalid: "invalid",
    valid: "valid",
    stored: "stored"
  };
  const form = { state: states.stored, validators: [] };
  setContext("form", form);
  function validate() {
    let valid2 = true;
    for (const validator2 of form.validators) {
      valid2 = validator2() && valid2;
    }
    form.state = valid2 ? states.valid : states.invalid;
  }
  onDestroy(() => {
    if (form.state === states.changed) {
      validate();
      if (form.state === states.valid) ;
    }
  });
  $$payload.out.push(`<form${attr_class("svelte-xiib6x", void 0, { "valid": valid })}><fieldset${attr_class(classname, "svelte-xiib6x")}${attr("disabled", disabled, true)}>`);
  if (legend) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<legend>${escape_html(legend)}</legend>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]--> `);
  children($$payload);
  $$payload.out.push(`<!----></fieldset></form>`);
  bind_props($$props, { valid });
  pop();
}
export {
  Form_1 as F,
  toDateString as a,
  onDestroy as o,
  toDate as t,
  validator as v
};
