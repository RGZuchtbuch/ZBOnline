import "clsx";
import { K as copy_payload, N as assign_payload, v as pop, t as push, w as escape_html } from "../../../../chunks/index.js";
import { p as page } from "../../../../chunks/index2.js";
import { g as goto } from "../../../../chunks/client.js";
import { m as model } from "../../../../chunks/model.js";
import { F as Form_1, v as validator } from "../../../../chunks/Form.js";
import { E as Email, P as Password, S as Submit } from "../../../../chunks/Submit.js";
import { jwtDecode } from "jwt-decode";
function Reset($$payload, $$props) {
  push();
  let { token } = $$props;
  let decoded = null;
  let user = null;
  let email = null;
  let password = null;
  try {
    if (token) {
      decoded = jwtDecode(token);
      if (decoded) {
        user = decoded.user;
        if (user) {
          email = user.email;
        }
      }
      console.log("Reset email from token", email);
    }
  } catch (error) {
    console.error(error);
  }
  const values = {
    initial: null,
    waiting: "Warten..",
    changed: "Controlle",
    invalid: "Fehler",
    valid: "Ok",
    disabled: "Geht nicht",
    stored: "Abmelden",
    error: "Server Fehler :("
  };
  const validate = {
    password: (v) => validator(v).password().isValid()
  };
  async function onSubmit(event) {
    console.log("Submit");
    const response = await model.User.reset(token, password);
    console.log("Response", response);
    await goto();
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    if (decoded && email) {
      $$payload2.out.push("<!--[-->");
      $$payload2.out.push(`<div class="flex flex-col py-4 gap-2 items-center"><h3>Geben Sie hier Ihr neues Passwort ein für</h3> <h3>${escape_html(email)}</h3> <ul>Ein Passwort braucht wenigstens 8 Zeichen mit <li>Kleine [a..z] Buchstaben</li> <li>Große [A..Z] Buchstaben</li> <li>Ziffern [0..9]</li> <li>Sonderzeichen [!@#$%^&amp;*()-] !</li></ul> `);
      Form_1($$payload2, {
        class: "w-96 flex flex-col gap-2",
        validateafter: 500,
        onsubmit: onSubmit,
        children: ($$payload3) => {
          Email($$payload3, {
            class: "w-96",
            name: "email",
            label: "eMail adresse",
            value: email,
            disabled: true
          });
          $$payload3.out.push(`<!----> `);
          Password($$payload3, {
            class: "w-96",
            name: "password",
            label: "Passwort",
            error: "Passwort nich volständig",
            validator: validate.password,
            get value() {
              return password;
            },
            set value($$value) {
              password = $$value;
              $$settled = false;
            }
          });
          $$payload3.out.push(`<!----> `);
          Submit($$payload3, { class: "w-96", values });
          $$payload3.out.push(`<!---->`);
        },
        $$slots: { default: true }
      });
      $$payload2.out.push(`<!----></div>`);
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
function _page($$payload, $$props) {
  push();
  const token = page.url.searchParams.get("token");
  if (token) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<main>`);
    Reset($$payload, { token });
    $$payload.out.push(`<!----></main>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
