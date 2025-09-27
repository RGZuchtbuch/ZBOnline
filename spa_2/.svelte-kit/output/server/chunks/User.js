import { K as copy_payload, N as assign_payload, v as pop, t as push, w as escape_html } from "./index.js";
import "clsx";
import { g as goto } from "./client.js";
import { c as ctx } from "./store.svelte.js";
import { m as model } from "./model.js";
import { F as Form_1, v as validator } from "./Form.js";
import { E as Email, P as Password, S as Submit } from "./Submit.js";
function User($$payload, $$props) {
  push();
  const State = {
    LOGIN: 10,
    LOGGEDIN: 11,
    FAILED: 12,
    FORGOT: 20,
    FORGOTTEN: 21,
    LOGOUT: 30,
    LOGGEDOUT: 31
  };
  let state = ctx.user ? State.LOGOUT : State.LOGIN;
  let email = null;
  let password = null;
  let disabled = false;
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
    //born:      v => validator(v).date().orNull().isValid(),
    //age:      v => validator(v).number().range( 1, 10 ).orNull().isValid(),
    email: (v) => validator(v).email().isValid(),
    password: (v) => validator(v).password().isValid()
  };
  async function onLogin(event) {
    console.log("Logging in", email);
    let success = await model.User.login(email, password);
    if (success) {
      state = State.LOGGEDIN;
      history.back();
    } else {
      state = State.FAILED;
      password = null;
      disabled = false;
    }
  }
  async function onForgot(event) {
    console.log("Send Reset", email);
    model.User.forgot(email);
    state = State.FORGOTTEN;
  }
  async function onLogout(event) {
    state = State.LOGGEDOUT;
    await model.User.logout();
    await goto();
  }
  let $$settled = true;
  let $$inner_payload;
  function $$render_inner($$payload2) {
    $$payload2.out.push(`<div class="flex flex-col py-4 gap-2 items-center">`);
    if (state === State.LOGIN || state === State.FAILED) {
      $$payload2.out.push("<!--[-->");
      if (state === State.LOGIN) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<h3>Sie sind nicht angemeldet !</h3>`);
      } else {
        $$payload2.out.push("<!--[!-->");
        $$payload2.out.push(`<h3>Das hat nicht geklappt, versuche es nochmals !</h3>`);
      }
      $$payload2.out.push(`<!--]--> `);
      Form_1($$payload2, {
        class: "w-96 flex flex-col gap-2",
        validateafter: 500,
        submitafter: 1e3,
        onsubmit: onLogin,
        disabled,
        children: ($$payload3) => {
          Email($$payload3, {
            class: "w-96",
            name: "email",
            label: "eMail adresse",
            error: "Emailadresse ungütig",
            validator: validate.email,
            autocomplete: "username",
            get value() {
              return email;
            },
            set value($$value) {
              email = $$value;
              $$settled = false;
            }
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
          $$payload3.out.push(`<!----> <button class="w-96 block text-right forgot svelte-6n4yrl" type="button">Passwort vergessen</button> `);
          Submit($$payload3, { class: "w-96", values });
          $$payload3.out.push(`<!---->`);
        },
        $$slots: { default: true }
      });
      $$payload2.out.push(`<!---->`);
    } else {
      $$payload2.out.push("<!--[!-->");
      if (state === State.LOGGEDIN) {
        $$payload2.out.push("<!--[-->");
        $$payload2.out.push(`<h3>Wunderbar, du bist drin :)</h3>`);
      } else {
        $$payload2.out.push("<!--[!-->");
        if (state === State.FORGOT) {
          $$payload2.out.push("<!--[-->");
          $$payload2.out.push(`<h3>Sie wissen ihr Passwort nicht oder nicht mehr, oder hatten noch keiner ?</h3> <p>Wenn man vom Obmann registriert ist kann man einen Reset Link über eMail beantragen.<br/> Über diesen Link können Sie ein neues Passwort eingeben.</p> `);
          Form_1($$payload2, {
            class: "w-96 flex flex-col gap-2",
            initialState: "valid",
            submitafter: 1e3,
            onsubmit: onForgot,
            disabled,
            children: ($$payload3) => {
              Email($$payload3, {
                class: "w-96",
                name: "email",
                label: "Ihre eMail adresse",
                error: "Emailadresse ungütig",
                validator: validate.email,
                autocomplete: "username",
                get value() {
                  return email;
                },
                set value($$value) {
                  email = $$value;
                  $$settled = false;
                }
              });
              $$payload3.out.push(`<!----> `);
              Submit($$payload3, { class: "w-96", values });
              $$payload3.out.push(`<!---->`);
            },
            $$slots: { default: true }
          });
          $$payload2.out.push(`<!---->`);
        } else {
          $$payload2.out.push("<!--[!-->");
          if (state === State.FORGOTTEN) {
            $$payload2.out.push("<!--[-->");
            $$payload2.out.push(`<h3>Wunderbar, du bekommst eine email mit Resetlink :)</h3>`);
          } else {
            $$payload2.out.push("<!--[!-->");
            if (state === State.LOGOUT) {
              $$payload2.out.push("<!--[-->");
              $$payload2.out.push(`<div>Züchter ${escape_html(ctx.user.firstname)} ${escape_html(ctx.user.infix)} ${escape_html(ctx.user.lastname)}</div> <div>Abmelden vom RGZuchtbuch</div> `);
              Form_1($$payload2, {
                initialState: "valid",
                onsubmit: onLogout,
                disabled,
                children: ($$payload3) => {
                  Submit($$payload3, { class: "w-96", values });
                },
                $$slots: { default: true }
              });
              $$payload2.out.push(`<!---->`);
            } else {
              $$payload2.out.push("<!--[!-->");
              if (state === State.LOGGEDOUT) {
                $$payload2.out.push("<!--[-->");
                $$payload2.out.push(`<h3>Wunderbar, du bist raus :)</h3>`);
              } else {
                $$payload2.out.push("<!--[!-->");
              }
              $$payload2.out.push(`<!--]-->`);
            }
            $$payload2.out.push(`<!--]-->`);
          }
          $$payload2.out.push(`<!--]-->`);
        }
        $$payload2.out.push(`<!--]-->`);
      }
      $$payload2.out.push(`<!--]-->`);
    }
    $$payload2.out.push(`<!--]--></div>`);
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
  User as U
};
