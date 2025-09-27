import "clsx";
class Context {
  //args       = $state( null);
  article = null;
  articles = null;
  breed = null;
  breeds = null;
  breeder = null;
  breeders = null;
  district = null;
  districts = null;
  federation = null;
  header = { title: null, menu: null };
  pair = null;
  pairs = null;
  report = null;
  result = null;
  results = null;
  resultsEdit = null;
  section = null;
  sections = null;
  standard = null;
  // whole standard with sections, breeds and colors
  user = null;
  year = null;
  // for managing page title and menu with crumbs
  title = null;
  menu = null;
  submenu = null;
  crumbs = null;
  initialMenustate = {
    // to allow for resetting on logout.
    "/article": "/article",
    "/federation": "/federation",
    "/standard": "/standard",
    "/report": "/report",
    "/tool": "/tool",
    "/breeder": "/breeder",
    "/moderator": "/moderator",
    "/admin": "/admin"
  };
  menustate = { ...this.initialMenustate };
  dialog = null;
}
class Config {
  //    aocSection = $state( { id:9999, name:'AOC-Klasse', breeds:[] } ); // TODO obsolete
  groups = ["I", "II", "III"];
  broodGroups = [1, 2, 3, 4];
  pigeons = 5;
  // note, not for subsections
  ringColors = [
    // start at 2022, then repeat every 6 years
    // get color by index = year % 6;
    { name: "schwarz", color: "#000" },
    { name: "gelb", color: "#FF0" },
    { name: "blau", color: "#00F" },
    { name: "grün", color: "#0F0" },
    { name: "grau", color: "#444" },
    { name: "weiß", color: "#FFF" }
  ];
  fadeIn = 500;
  // for fade in duration
  // rootSections defined in js/model/standard.js
}
class Dirty {
  // flag dirty, for forcing reloading in +page
  article = 1;
  articles = 1;
  breeder = 1;
  breeders = 1;
  district = 1;
  districts = 1;
  federation = 1;
  pair = 1;
  pairs = 1;
  report = 1;
  result = 1;
  results = 1;
  // list of entered results
  resultsEdit = 1;
  // list of editable results
  standard = 1;
}
let ctx = new Context();
let cfg = new Config();
let dirty = new Dirty();
export {
  cfg as a,
  ctx as c,
  dirty as d
};
