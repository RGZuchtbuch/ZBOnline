import "clsx";
import "./client.js";
function dec(value, dec2 = 0) {
  value = Number(value);
  if (value) {
    return value.toFixed(dec2);
  }
  return "";
}
function pct(a, b, decimals = 0) {
  if (a != null && b != null && b !== 0) {
    return (100 * a / b).toFixed(decimals) + "﹪";
  }
  return "";
}
function txt(text) {
  if (text === null) {
    return "";
  }
  return text;
}
let ArgsBuilder = {
  init: () => {
    return {};
  },
  setNumber: (args, searchParams, key, init) => {
    if (searchParams.has(key)) {
      args[key] = +searchParams.get(key);
    } else if (init) {
      args[key] = +init;
    }
  },
  setString: (args, searchParams, key, init) => {
    if (searchParams.has(key)) {
      args[key] = searchParams.get(key);
    } else if (init) {
      args[key] = init;
    }
  }
};
function fullName(person) {
  if (person) {
    return `${txt(person.firstname)} ${txt(person.infix)} ${txt(person.lastname)}`;
  }
  return "-";
}
function activeYear() {
  const now = /* @__PURE__ */ new Date();
  const year = now.getFullYear();
  return now.getMonth() < 2 ? year - 1 : year;
}
export {
  ArgsBuilder as A,
  activeYear as a,
  dec as d,
  fullName as f,
  pct as p
};
