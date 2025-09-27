import "clsx";
import "@sveltejs/kit/internal";
import "./exports.js";
import "./utils.js";
import "./client.js";
import { c as ctx } from "./store.svelte.js";
import { D as DEV } from "./false.js";
import { jwtDecode } from "jwt-decode";
const browser = DEV;
const API_BASE = "";
function headers() {
  return {
    "Accept": "application/json",
    // response
    "Content-Type": "application/json",
    // body
    "Authorization": `Bearer ${ctx.user ? ctx.user.token : ""}`
  };
}
async function get(url, query2 = null) {
  url += query2 ? "?" + new URLSearchParams(query2).toString() : "";
  const response = await fetch(`${API_BASE}${url}`, { method: "GET", headers: headers() });
  return response.ok ? await response.json() : null;
}
async function query(url, query2) {
  url += query2 ? "?" + new URLSearchParams(query2).toString() : "";
  const response = await fetch(`${API_BASE}${url}`, { method: "GET", headers: headers() });
  return response.ok ? await response.json() : null;
}
async function post(url, body) {
  const response = await fetch(`${API_BASE}${url}`, { method: "POST", headers: headers(), body: JSON.stringify(body) });
  return response.ok ? await response.json() : null;
}
async function put(url, body) {
  const response = await fetch(`${API_BASE}${url}`, { method: "PUT", headers: headers(), body: JSON.stringify(body) });
  return response.ok ? await response.json() : null;
}
async function del(url) {
  const response = await fetch(`${API_BASE}${url}`, { method: "DELETE", headers: headers() });
  return response.ok ? await response.json() : null;
}
const api = {
  get,
  query,
  post,
  put,
  delete: del
};
class Article {
  static invalid = true;
  static async load(id) {
    if (id === 0) {
      return { id: 0, level: 1, author: null, title: null, html: null };
    } else {
      const data = await api.get(`/api/2/article/${id}`);
      return data && data.article ? data.article : null;
    }
  }
  static async query(args) {
    const data = await api.query(`/api/2/article`, args);
    return data && data.articles ? data.articles : null;
  }
  static async save(article) {
    if (article.id === 0) {
      const data = await api.post(`/api/2/article`, article);
      if (data && data.id > 0) {
        article.id = data.id;
        return true;
      }
    } else {
      const data = await api.put(`/api/2/article/${article.id}`, article);
      if (data && data.id > 0) {
        return true;
      }
    }
    return false;
  }
  static async delete(id) {
    return await api.delete(`/api/2/article/${id}`);
  }
}
let standard = null;
class Standard {
  static async load() {
    standard = null;
    const data = await api.get(`/api/2/standard`);
    if (data && data.standard) {
      standard = structuredStandardTree(data.standard);
      standard.rootSections = [
        standard.sections[3],
        standard.sections[11],
        standard.sections[12],
        standard.sections[13],
        standard.sections[5],
        standard.sections[6]
      ];
    }
    return standard;
  }
  static createBreed(sectionId) {
    return {
      id: 0,
      name: "Neu !",
      sectionId,
      broodGroup: null,
      layEggs: null,
      layWeight: null,
      sireWeight: null,
      dameWeight: null,
      sireRing: null,
      dameRing: null,
      colors: []
      // info:null,
    };
  }
  static createColor(breedId) {
    return {
      id: 0,
      name: "Neu !",
      breedId
      // info:null,
    };
  }
  static async saveBreed(breed) {
    if (breed.id === 0) {
      const data = await api.post(`/api/2/standard/breed`, breed);
      if (data && data.id > 0) {
        breed.id = data.id;
        return true;
      }
    } else {
      const data = await api.put(`/api/2/standard/breed/${breed.id}`, breed);
      if (data && data.id > 0) {
        return true;
      }
    }
    return false;
  }
  static async deleteBreed(id) {
    console.log("Delete breed", id);
    let ok = false;
    if (id > 0) {
      ok = await api.delete(`/api/2/standard/breed/${id}`);
    }
    return ok;
  }
  static async saveColor(color) {
    if (color.id === 0) {
      const data = await api.post(`/api/2/standard/color`, color);
      if (data && data.id > 0) {
        color.id = data.id;
        return true;
      }
    } else {
      const data = await api.put(`/api/2/standard/color/${color.id}`, color);
      if (data && data.id > 0) {
        return true;
      }
    }
    return false;
  }
  static async deleteColor(id) {
    console.log("Delete color", id);
    let ok = false;
    if (id > 0) {
      ok = await api.delete(`/api/2/standard/color/${id}`);
    }
    return ok;
  }
}
function structuredStandardTree(standard2) {
  standard2.sections = {};
  standard2.breeds = {};
  standard2.colors = {};
  addSection(standard2.root, standard2);
  return standard2;
}
function addSection(section, standard2) {
  standard2.sections[section.id] = section;
  for (const breed of section.breeds) {
    standard2.breeds[breed.id] = breed;
    for (const color of breed.colors) {
      standard2.colors[color.id] = color;
    }
  }
  for (const child of section.children) {
    addSection(child, standard2);
    addBreeds(child, section.breeds);
  }
  section.breeds.sort((a, b) => a.name.localeCompare(b.name));
}
function addBreeds(section, breeds) {
  for (const breed of section.breeds) {
    breeds.push(breed);
  }
  for (const child of section.children) {
    addBreeds(child, breeds);
  }
}
class User {
  static async load() {
    let token = browser;
    let user = tokenToUser(token);
    return user;
  }
  static async login(email, password) {
    const response = await api.post("/api/2/user/login", { email, password });
    if (response && response.token) {
      console.log("Got Token");
      ctx.user = tokenToUser(response.token);
    } else {
      ctx.user = null;
    }
    return ctx.user;
  }
  static async forgot(email) {
    const response = await api.post("/api/2/user/forgot", { email });
    console.log(response);
    return true;
  }
  static async reset(token, password) {
    const response = await api.post("/api/2/user/reset", { token, password });
    console.log("Reset", response);
    if (response && response.token) {
      console.log("Got login token");
      ctx.user = tokenToUser(response.token);
      return true;
    } else {
      ctx.user = null;
      return false;
    }
  }
  static async logout() {
    ctx.user = null;
    window.sessionStorage.removeItem("token");
    ctx.menustate = { ...ctx.initialMenustate };
    return true;
  }
}
function tokenToUser(token) {
  let user = null;
  try {
    if (token) {
      const decoded = jwtDecode(token);
      user = decoded.user;
      user.exp = decoded.exp;
      user.token = token;
    }
  } catch (error) {
    console.error(error);
  }
  return user;
}
const model = {
  Article,
  Standard,
  User
};
export {
  model as m
};
