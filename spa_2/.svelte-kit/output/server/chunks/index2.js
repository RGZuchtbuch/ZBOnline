import "./client.js";
import { z as getContext } from "./index.js";
function context() {
  return getContext("__request__");
}
const page$1 = {
  get params() {
    return context().page.params;
  },
  get url() {
    return context().page.url;
  }
};
const page = page$1;
export {
  page as p
};
