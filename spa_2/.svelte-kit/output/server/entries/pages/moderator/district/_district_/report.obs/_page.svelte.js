import { x as ensure_array_like, w as escape_html, y as attr, v as pop, t as push } from "../../../../../../chunks/index.js";
import "clsx";
import "../../../../../../chunks/client.js";
import { a as cfg, c as ctx } from "../../../../../../chunks/store.svelte.js";
import "@sveltejs/kit/internal";
import "../../../../../../chunks/exports.js";
import "../../../../../../chunks/utils.js";
import "jwt-decode";
import { d as dec, p as pct } from "../../../../../../chunks/tools.js";
/* empty css                                                           */
function Table($$payload, $$props) {
  push();
  let { table, district, year } = $$props;
  let totalledReport = null;
  calcTotals();
  function addTo(sum, result) {
    result.broods = result.broodEggs ? result.broodEggs / 2 : null;
    result.broodResult = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;
    sum.breeders += result.breeders;
    sum.pairs += result.pairs;
    sum.layDames += result.layDames;
    if (result.layEggs) {
      sum.layBreeders += result.layBreeders;
      sum.layShould += result.layBreeders * result.layShould;
      sum.layEggs += result.layBreeders * result.layEggs;
    }
    if (result.layWeight) {
      sum.layWeightBreeders += result.layWeightBreeders;
      sum.layWeightShould += result.layWeightBreeders * result.layWeightShould;
      sum.layWeight += result.layWeightBreeders * result.layWeight;
    }
    if (result.broodLayerEggs) {
      sum.broodBreeders += result.broodLayerBreeders;
      sum.broodLayerEggs += result.broodLayerEggs;
      sum.broodLayerFertile += result.broodLayerBreeders * result.broodLayerFertile;
      sum.broodLayerHatched += result.broodLayerBreeders * result.broodLayerHatched;
    }
    if (result.pairs && result.broodPigeonHatched) {
      sum.broodBreeders += result.broodPigeonBreeders;
      sum.broodPigeonEggs += result.broodPigeonEggs;
      sum.broodPigeonHatched += result.broodPigeonBreeders * result.broodPigeonHatched;
      sum.broodPigeonResult += result.broodPigeonBreeders * result.broodPigeonResult;
    }
    if (result.showCount && result.showScore) {
      sum.showBreeders += result.showBreeders;
      sum.showCount += result.showCount;
      sum.showScore += result.showBreeders * result.showScore;
    }
  }
  function avgTotal(sum) {
    const total = {};
    total.breeders = sum.breeders;
    total.pairs = sum.pairs;
    total.layDames = sum.layDames;
    total.layBreeders = sum.layBreeders;
    total.layShould = sum.layBreeders ? sum.layShould / sum.layBreeders : null;
    total.layEggs = sum.layBreeders ? sum.layEggs / sum.layBreeders : null;
    total.layWeightBreeders = sum.layWeightBreeders;
    total.layWeightShould = sum.layWeightBreeders ? sum.layWeightShould / sum.layWeightBreeders : null;
    total.layWeight = sum.layWeightBreeders ? sum.layWeight / sum.layWeightBreeders : null;
    total.broodBreeders = sum.broodBreeders;
    total.broodLayerEggs = sum.broodLayerEggs;
    total.broodLayerFertile = sum.broodBreeders ? sum.broodLayerFertile / sum.broodBreeders : null;
    total.broodLayerHatched = sum.broodBreeders ? sum.broodLayerHatched / sum.broodBreeders : null;
    total.broodPigeonEggs = sum.broodPigeonEggs;
    total.broodPigeonHatched = sum.broodBreeders ? sum.broodPigeonHatched / sum.broodBreeders : null;
    total.broodPigeonResult = sum.broodBreeders ? sum.broodPigeonResult / sum.broodBreeders : null;
    total.showBreeders = sum.showBreeders;
    total.showCount = sum.showCount;
    total.showScore = sum.showBreeders ? sum.showScore / sum.showBreeders : null;
    return total;
  }
  function createTotal() {
    return {
      breeders: 0,
      pairs: 0,
      layDames: 0,
      layShould: 0,
      layBreeders: 0,
      layEggs: 0,
      layWeightBreeders: 0,
      layWeightShould: 0,
      layWeight: 0,
      broodBreeders: 0,
      broodLayerEggs: null,
      broodLayerFertile: 0,
      broodLayerHatched: 0,
      broodPigeonEggs: null,
      broodPigeonHatched: null,
      broodPigeonResult: 0,
      showBreeders: 0,
      showCount: null,
      showScore: 0
    };
  }
  function calcTotals() {
    totalledReport = table;
    const resultsSum = createTotal();
    for (const section of totalledReport.sections) {
      const sectionSum = createTotal();
      for (const subsection of section.subsections) {
        const subsectionSum = createTotal();
        for (const breed of subsection.breeds) {
          if (breed.result) {
            addTo(resultsSum, breed.result);
            addTo(sectionSum, breed.result);
            addTo(subsectionSum, breed.result);
          }
          const breedSum = createTotal();
          for (const color of breed.colors) {
            if (color.result) {
              addTo(resultsSum, color.result);
              addTo(sectionSum, color.result);
              addTo(subsectionSum, color.result);
              addTo(breedSum, color.result);
            }
          }
          breed.total = avgTotal(breedSum);
        }
        subsection.total = avgTotal(subsectionSum);
      }
      section.total = avgTotal(sectionSum);
    }
    totalledReport.total = avgTotal(resultsSum);
  }
  if (totalledReport !== null && totalledReport.sections.length > 0) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<!---->`);
    {
      const each_array = ensure_array_like(totalledReport.sections);
      $$payload.out.push(`<div class="flex flex-col"><!--[-->`);
      for (let s = 0, $$length = each_array.length; s < $$length; s++) {
        let section = each_array[s];
        const each_array_1 = ensure_array_like(section.subsections);
        $$payload.out.push(`<table class="w-full px-2 break-after-page svelte-17g6mya"><thead><tr class="svelte-17g6mya"><th class="screen:sticky screen:top-1 svelte-17g6mya" colspan="14"><div class="flex flex-col"><div class="flex flex-row p-1 rounded-b-none border-header bg-header text-header"><small class="w-48 pr-2 text-left self-end">`);
        if (district) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`${escape_html(district.short)}`);
        } else {
          $$payload.out.push("<!--[!-->");
        }
        $$payload.out.push(`<!--]--></small> <div class="grow text-center text-xl italic">Sparte ${escape_html(section.name)}</div> <small class="w-48 pr-2 text-right self-end">`);
        if (district) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`${escape_html(year)}`);
        } else {
          $$payload.out.push("<!--[!-->");
        }
        $$payload.out.push(`<!--]--></small></div> <div class="rounded-none flex flex-row bg-gray-300 px-2 text-center"><div class="grow text-left">Gruppe, Rasse &amp; Farbe</div> <div class="w-1"></div> <div class="w-12">Zuchten</div> <div class="w-8"></div> <div class="w-5"></div> <div class="w-24 text-center">`);
        if (section.id === 5) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`Legeleistung`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`Legeleistung`);
        }
        $$payload.out.push(`<!--]--></div> <div class="w-8"></div> <div class="w-2"></div> <div class="w-48 text-center">Brutleistung</div> <div class="w-8"></div> <div class="w-4"></div> <div class="w-24 text-center">Schauleistung</div> <div class="w-4"></div></div> <div class="flex flex-row rounded-t-none border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-1"><div class="grow text-left">Rasse &amp; Farbe</div> <div class="w-12 th svelte-17g6mya">Zuchten</div> <div class="w-8 text-gray-400">|</div> `);
        if (section.id === 5) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<div class="w-12 th svelte-17g6mya">-</div> <div class="w-12 th svelte-17g6mya">-</div>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<div class="w-12 th svelte-17g6mya">Eier/J</div> <div class="w-12 th svelte-17g6mya">Gewicht</div>`);
        }
        $$payload.out.push(`<!--]--> <div class="w-8 text-gray-400">|</div> `);
        if (section.id === 5) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<div class="w-12 th svelte-17g6mya">Paare</div> <div class="w-12 th svelte-17g6mya">Bruten</div> <div class="w-12 th svelte-17g6mya">Schl %</div> <div class="w-12 th svelte-17g6mya">Kü/Pa</div>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<div class="w-12 th svelte-17g6mya">Eier</div> <div class="w-12 th svelte-17g6mya">Befr %</div> <div class="w-12 th svelte-17g6mya">Schl %</div> <div class="w-12">-</div>`);
        }
        $$payload.out.push(`<!--]--> <div class="w-8 text-gray-400">|</div> <div class="w-12 th svelte-17g6mya">Tiere</div> <div class="w-12 th svelte-17g6mya">Punkte</div> <div class="w-2"></div></div></div></th></tr></thead><!--[-->`);
        for (let $$index_2 = 0, $$length2 = each_array_1.length; $$index_2 < $$length2; $$index_2++) {
          let subsection = each_array_1[$$index_2];
          const each_array_2 = ensure_array_like(subsection.breeds);
          $$payload.out.push(`<tbody><tr class="svelte-17g6mya"><th class="svelte-17g6mya"><div class="flex flex-row mt-4 px-2 gap-x-4 font-bold text-xl text-left">Gruppe ${escape_html(subsection.name)}</div></th></tr><tr class="svelte-17g6mya"><td class="svelte-17g6mya"><div class="flex flex-row border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-1"><div class="grow text-left">Rasse &amp; Farbe</div> <div class="w-12 th svelte-17g6mya">Zuchten</div> <div class="w-8 text-gray-400">|</div> `);
          if (section.id === 5) {
            $$payload.out.push("<!--[-->");
            $$payload.out.push(`<div class="w-12 th svelte-17g6mya">-</div> <div class="w-12 th svelte-17g6mya">-</div>`);
          } else {
            $$payload.out.push("<!--[!-->");
            $$payload.out.push(`<div class="w-12 th svelte-17g6mya" title="Anteil von Soll">Legen</div> <div class="w-12 th svelte-17g6mya" title="Anteil von Soll">Gewicht</div>`);
          }
          $$payload.out.push(`<!--]--> <div class="w-8 text-gray-400">|</div> `);
          if (section.id === 5) {
            $$payload.out.push("<!--[-->");
            $$payload.out.push(`<div class="w-12 th svelte-17g6mya">Paare</div> <div class="w-12 th svelte-17g6mya">Bruten</div> <div class="w-12 th svelte-17g6mya">Schl %</div> <div class="w-12 th svelte-17g6mya">Kü/Pa</div>`);
          } else {
            $$payload.out.push("<!--[!-->");
            $$payload.out.push(`<div class="w-12 th svelte-17g6mya" title="Gesamtzahl eingelegten Eier">Eier</div> <div class="w-12 th svelte-17g6mya" title="Anteil befruchteten Eier">Befr %</div> <div class="w-12 th svelte-17g6mya" title="Anteil geschlüpften Eier">Schl %</div> <div class="w-12">-</div>`);
          }
          $$payload.out.push(`<!--]--> <div class="w-8 text-gray-400">|</div> <div class="w-12 th svelte-17g6mya">Tiere</div> <div class="w-12 th svelte-17g6mya">Punkte</div> <div class="w-2"></div></div></td></tr></tbody> <!--[-->`);
          for (let $$index_1 = 0, $$length3 = each_array_2.length; $$index_1 < $$length3; $$index_1++) {
            let breed = each_array_2[$$index_1];
            const each_array_3 = ensure_array_like(breed.colors);
            $$payload.out.push(`<tbody class="print-no-break"><tr class="svelte-17g6mya"><th class="svelte-17g6mya"><div class="flex flex-row px-2 py-1 text-right text-base font-semibold gap-x-1"><div class="grow text-left">${escape_html(breed.name)} `);
            if (section.id === cfg.pigeons) {
              $$payload.out.push("<!--[-->");
              $$payload.out.push(`<span class="text-xs">(Gesamt)</span>`);
            } else {
              $$payload.out.push("<!--[!-->");
            }
            $$payload.out.push(`<!--]--></div> `);
            if (breed.result) {
              $$payload.out.push("<!--[-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(dec(breed.result.breeders))}</div>`);
            } else {
              $$payload.out.push("<!--[!-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(dec(breed.total.breeders))}</div>`);
            }
            $$payload.out.push(`<!--]--> <div class="w-8"></div> `);
            if (section.id === 5 && breed.result) {
              $$payload.out.push("<!--[-->");
              $$payload.out.push(`<div class="w-12"></div> <div class="w-12 td svelte-17g6mya"></div>`);
            } else {
              $$payload.out.push("<!--[!-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya"${attr("title", `Legen ${pct(breed.total.layEggs, 1)} von ${dec(breed.layEggs)} ergibt ${dec(breed.total.layEggs * breed.layEggs)} Eier im Jahr`)}>${escape_html(pct(breed.total.layEggs, 1))}</div> <div class="w-12 td svelte-17g6mya"${attr("title", `Eigewicht ${pct(breed.total.layWeight, 1)} von ${dec(breed.layWeight)}g. ergibt ${dec(breed.total.layWeight * breed.layWeight)} g.`)}>${escape_html(pct(breed.total.layWeight, 1))}</div>`);
            }
            $$payload.out.push(`<!--]--> <div class="w-8"></div> `);
            if (section.id === 5 && breed.result) {
              $$payload.out.push("<!--[-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der Brutpaare">${escape_html(dec(breed.result.pairs))}</div> <div class="w-12 td svelte-17g6mya" title="Zahl der Bruten">${escape_html(dec(breed.result.broodPigeonEggs / 2))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil der geschlüpften Küken">${escape_html(breed.result.broodPigeonEggs ? pct(breed.result.broodPigeonHatched, 1) : "-")}</div> <div class="w-12 td svelte-17g6mya" title="Zahl der Küken pro Paar">${escape_html(dec(breed.result.broodPigeonResult, 1))}</div>`);
            } else {
              $$payload.out.push("<!--[!-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der Eingelegte Eier">${escape_html(dec(breed.total.broodLayerEggs))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil befruchteten Eier">${escape_html(breed.total.broodLayerEggs ? pct(breed.total.broodLayerFertile, 1) : "-")}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(breed.total.broodLayerEggs ? pct(breed.total.broodLayerHatched, 1) : "-")}</div> <div class="w-12"></div>`);
            }
            $$payload.out.push(`<!--]--> <div class="w-8"></div> `);
            if (section.id === 5 && breed.result) {
              $$payload.out.push("<!--[-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(breed.result.showCount))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(breed.result.showScore, 1))}</div>`);
            } else {
              $$payload.out.push("<!--[!-->");
              $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(breed.total.showCount))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(breed.total.showScore, 1))}</div>`);
            }
            $$payload.out.push(`<!--]--> <div class="w-2"></div></div></th></tr><!--[-->`);
            for (let $$index = 0, $$length4 = each_array_3.length; $$index < $$length4; $$index++) {
              let color = each_array_3[$$index];
              if (section.id !== 5 && color.result) {
                $$payload.out.push("<!--[-->");
                $$payload.out.push(`<tr class="svelte-17g6mya"><td class="svelte-17g6mya"><div class="flex flex-row px-2 py-1 text-right text-base gap-x-1"><div class="grow pl-4 text-left">⤷ ${escape_html(color.name || color.result.aocColor)}</div> <div class="w-12 td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(dec(color.result.breeders))}</div> <div class="w-8"></div> <div class="w-12 td svelte-17g6mya" title="Relative Legeleistung im Jahr zu Soll">${escape_html(pct(color.result.layEggs, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Relative Eiergewichtsleistung zu Soll">${escape_html(pct(color.result.layWeight, 1))}</div> <div class="w-8"></div> <div class="w-12 td svelte-17g6mya" title="Eingelegte Eier">${escape_html(dec(color.result.broodLayerEggs))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil befruchteten Eier">${escape_html(pct(color.result.broodLayerFertile, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(pct(color.result.broodLayerHatched, 1))}</div> <div class="w-12"></div> <div class="w-8"></div> <div class="w-12 td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(color.result.showCount))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(color.result.showScore, 1))}</div> <div class="w-2"></div></div></td></tr>`);
              } else {
                $$payload.out.push("<!--[!-->");
              }
              $$payload.out.push(`<!--]-->`);
            }
            $$payload.out.push(`<!--]--></tbody>`);
          }
          $$payload.out.push(`<!--]-->  <tbody><tr class="svelte-17g6mya"><td class="svelte-17g6mya"><div class="flex flex-row border-y border-gray-600 bg-gray-300 px-2 text-right text-base italic gap-x-1"><div class="grow pl-4 text-left">Gesamt ${escape_html(subsection.name)}</div> <div class="w-12 td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(dec(subsection.total.breeders))}</div> <div class="w-8"></div> `);
          if (section.id === 5) {
            $$payload.out.push("<!--[-->");
            $$payload.out.push(`<div class="w-12"></div> <div class="w-12"></div>`);
          } else {
            $$payload.out.push("<!--[!-->");
            $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Relative Legeleistung im Jahr zu Soll">${escape_html(pct(subsection.total.layEggs, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Relative Eiergewichtsleistung im Jahr zu Soll">${escape_html(pct(subsection.total.layWeight, 1))}</div>`);
          }
          $$payload.out.push(`<!--]--> <div class="w-8"></div> `);
          if (section.id === 5) {
            $$payload.out.push("<!--[-->");
            $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Anzahl der Paare">${escape_html(dec(subsection.total.pairs))}</div> <div class="w-12 td svelte-17g6mya" title="Anzahl Bruten, jeder mit 2 Eier">${escape_html(dec(subsection.total.broodPigeonEggs) / 2)}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(pct(subsection.total.broodPigeonHatched, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Küken pro Paar">${escape_html(dec(subsection.total.broodPigeonResult, 1))}</div>`);
          } else {
            $$payload.out.push("<!--[!-->");
            $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Eingelegte Eier">${escape_html(dec(subsection.total.broodLayerEggs))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil befruchteten Eier">${escape_html(subsection.total.broodLayerEggs ? pct(subsection.total.broodLayerFertile, 1) : "-")}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(subsection.total.broodLayerEggs ? pct(subsection.total.broodLayerHatched, 1) : "-")}</div> <div class="w-12"></div>`);
          }
          $$payload.out.push(`<!--]--> <div class="w-8"></div> <div class="w-12 td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(subsection.total.showCount))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(subsection.total.showScore, 1))}</div> <div class="w-2"></div></div></td></tr></tbody>`);
        }
        $$payload.out.push(`<!--]--><tbody><tr class="svelte-17g6mya"><th class="svelte-17g6mya"><div class="flex flex-row border-y border-header bg-header text-header px-2 py-1 text-right text-base italic gap-x-1"><div class="grow pl-4 text-left">Gesamt ${escape_html(section.name)}</div> <div class="w-12 td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(dec(section.total.breeders))}</div> <div class="w-8"></div> `);
        if (section.id === 5) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<div class="w-12">-</div> <div class="w-12">-</div>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Durchschnitt Legeleistung im Jahr">${escape_html(pct(section.total.layEggs, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Eiergewicht">${escape_html(pct(section.total.layWeight, 1))}</div>`);
        }
        $$payload.out.push(`<!--]--> <div class="w-8"></div> `);
        if (section.id === 5) {
          $$payload.out.push("<!--[-->");
          $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Anzal Paare">${escape_html(dec(section.total.pairs))}</div> <div class="w-12 td svelte-17g6mya" title="Anzahl Bruten, jeder mit 2 Eier">${escape_html(dec(section.total.broodPigeonEggs / 2))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil befruchteten Eier">${escape_html(pct(section.total.broodPigeonHatched, 1))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(dec(section.total.broodPigeonResult, 1))}</div>`);
        } else {
          $$payload.out.push("<!--[!-->");
          $$payload.out.push(`<div class="w-12 td svelte-17g6mya" title="Eingelegte Eier">${escape_html(dec(section.total.broodLayerEggs))}</div> <div class="w-12 td svelte-17g6mya" title="Anteil befruchteten Eier">${escape_html(section.total.broodLayerEggs ? pct(section.total.broodLayerFertile, 1) : "-")}</div> <div class="w-12 td svelte-17g6mya" title="Anteil geschlüpfte Küken">${escape_html(section.total.broodLayerEggs ? pct(section.total.broodLayerHatched, 1) : "-")}</div> <div class="w-12"></div>`);
        }
        $$payload.out.push(`<!--]--> <div class="w-8"></div> <div class="w-12 td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(section.total.showCount))}</div> <div class="w-12 td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(section.total.showScore, 1))}</div> <div class="w-2"></div></div></th></tr></tbody></table> <div class="text-center">-</div>`);
      }
      $$payload.out.push(`<!--]--> <table class="svelte-17g6mya"><thead><tr class="svelte-17g6mya"><th class="border-y border-gray-600 p-2 bg-header text-header text-center text-xl svelte-17g6mya" colspan="14">Gesammt Geflügel</th></tr><tr class="svelte-17g6mya"><th class="svelte-17g6mya"><div class="flex flex-row bg-gray-300 px-2 gap-x-1 font-bold"><div class="grow text-left">Alle Sparten, Gruppen, Rassen &amp; Farben</div> <div class="flex flex-row justify-evenly gap-x-6"><div class="w-14 text-center">Zuchten</div> <div class="w-28"></div> <div class="w-40"></div> <div class="w-12"></div> <div class="w-28 text-center">Schauleistung</div></div></div> <div class="flex flex-row bg-gray-300 px-2 gap-x-1 text-xs gap-x-1"><div class="grow text-left"></div> <div class="flex flex-row justify-evenly gap-x-6"><div class="flex w-14 justify-evenly"><div class="th svelte-17g6mya"></div></div> <div class="w-28"></div> <div class="w-40"></div> <div class="flex w-28 justify-evenly"><div class="th svelte-17g6mya">Tiere</div> <div class="th svelte-17g6mya">Punkte</div></div></div></div></th></tr></thead><tbody><tr class="svelte-17g6mya"><th class="svelte-17g6mya"><div class="flex flex-row bg-header text-header px-2 gap-x-1 justify-evenly font-bold text-base italic border-y border-gray-600"><div class="grow">Gesamt</div> <div class="flex justify-evenly text-base gap-x-6">`);
      if (table.total) {
        $$payload.out.push("<!--[-->");
        $$payload.out.push(`<div class="flex w-14 justify-evenly"><div class="td svelte-17g6mya" title="Zahl der Zuchten / Züchter">${escape_html(table.total.breeders)}</div></div> <div class="w-28"></div> <div class="w-40"></div> <div class="w-12"></div> <div class="flex w-28 justify-evenly"><div class="td svelte-17g6mya" title="Zahl der ausgestellten Tieren">${escape_html(dec(table.total.showCount))}</div> <div class="td svelte-17g6mya" title="Durchschnitt Bewertungsnote">${escape_html(dec(table.total.showScore, 1))}</div></div>`);
      } else {
        $$payload.out.push("<!--[!-->");
      }
      $$payload.out.push(`<!--]--></div></div></th></tr></tbody></table></div>`);
    }
    $$payload.out.push(`<!---->`);
  } else {
    $$payload.out.push("<!--[!-->");
    $$payload.out.push(`<h2 class="p-2 text-center text-xl">Leider keine Daten für dieses Jahr</h2>`);
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
function _page($$payload, $$props) {
  push();
  ctx.report = null;
  if (ctx.report !== null) {
    $$payload.out.push("<!--[-->");
    $$payload.out.push(`<main class="flex flex-col break-after-page"><h2 class="text-center">Leistungsdaten im ${escape_html(ctx.district.name)} für ${escape_html(ctx.report.args.year)}</h2> <p>Das Jahr past sich das Jahr der Eingaben an !</p> <div class="flex flex-col py-4">`);
    Table($$payload, {
      table: ctx.report,
      district: ctx.district,
      year: ctx.report.args.year
    });
    $$payload.out.push(`<!----></div></main>`);
  } else {
    $$payload.out.push("<!--[!-->");
  }
  $$payload.out.push(`<!--]-->`);
  pop();
}
export {
  _page as default
};
