<script>

    import api from "../../js/api.js";
//    import {BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip} from "chart.js";
    import { Chart, ArcElement, DoughnutController, Legend, Tooltip} from 'chart.js';
    import {dec, pct} from "../../js/util.js";

    export let districtId = null;
    export let year = null;
//    export let sectionId = null;
    //export let breedId = null;
//    export let colorId = null;
    export let typeId = null;

    let canvasFraction = null; // ref to canvas element
    let canvasB = null;
    let canvasC = null;
    let chart = null;

    let fractions = {
        3: 0, 4: 0, 5: 0, 6: 0
    };
    let type = null;
    const types = { // what options to show
        2: {
            id: 2,
            label: 'Zuchten',
            pie: (result) => [result.breeders],
            title: (result) => result.breeders ? ` ${dec(result.breeders)} demeldete Zuchten` : ' hat keine Daten',
            tooltip: 'Meldende Mitglieder',
        },
        10: {
            id: 10,
            label: 'Legeleistung',
            pie: (result) => [result.layEggs],
            title: (result) =>  result.layEggs ? ` Legeleistung ⌀ ${dec(result.layEggs)} Eier im Jahr` : ' hat keine Daten',
            tooltip: 'Nur für Leger',
        },

        20: {
            id: 20,
            label: 'Brutleistung Leger',
            pie: (result) => [ 100 * result.broodLayerHatched / result.broodLayerEggs], // for pie
            title: (result) => result.broodEggs ?
                ` Eingelegt ${dec(result.broodEggs)} Eier, ${pct(result.broodFertile, result.broodEggs, 0)} waren befruchtet und es schlüpften ${pct(result.broodHatched, result.broodEggs, 0)}` :
                ' hat keine Daten',
            tooltip: 'Nur für Leger',
        },
        21: {
            id: 21,
            label: 'Brutleistung Tauben',
            pie: (result) => [ result.nonLayerPairs ? result.chicks / result.nonLayerPairs : 0 ], // for pie
            title: (result) => result.nonLayerPairs ?
                ` Bei ${dec(result.nonLayerPairs)} Paare schlüpften ${dec(result.chicks)} Küken also ${dec(result.chicks/result.nonLayerPairs,1)} Küken / Paar` :
                ` hat keine Daten`,
            tooltip: 'Nur für Tauben',
        },

        30: {
            id: 30,
            label: 'Schauleistung',
            min: 89,
            max: 97,
            pie: (result) => [result.showScore ? result.showScore : 89 ],
            title: (result) => result.showCount ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte` : ' hat keine Daten',
            tooltip: 'Bewertungen der Tiere (u), 90 (b) .. 97 (v) Punkte',
        },
    }



    function countBreeders( results ) {
        //const keys = Object.keys( fractions );
        //let currentId = -1;
        for( let section of results.sections ) {
            section.count = 0;
            for( let subsection of section.subsections ) {
                subsection.count = 0;
                for (let breed of subsection.breeds) {
                    if( breed.result ) {
                        section.count += breed.result.breeders;
                        subsection.count += breed.result.breeders;
                    }
                    for (let color of breed.colors ) {
                        if( color.result ) {
                            section.count += color.result.breeders;
                            subsection.count += color.result.breeders;
                        }
                    }
                }
            }
        }
    }

    function handle( districtId, year, typeId ) {
        type = types[ typeId ];
        if( districtId ) {
            api.district.results.get( districtId, year ).then( response => {
                const results = response.results;
                countBreeders( results );
                //console.log( "Pie results", results );
                let data = results.sections.map( section => section.count ); // get array of counts
                //let data = [1,2,3];
                let datasets = [ { data:data } ];
                let labels = results.sections.map( section => section.name ); // get array of names

                if( chart ) {
                    chart.data.labels = labels;
                    chart.data.datasets = datasets;
                    chart.update();
                } else {
                    const config = {
                        type: 'doughnut',
                        data: {
                            datasets:datasets,
                            labels:labels,
                        },
                        options: {
                            responsive:false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'right',
                                }
                            }
                        }
                    }
                    const context = canvasFraction.getContext( '2d' );
                    chart = new Chart( context, config );
                }
            });
        }
    }

    Chart.register( ArcElement, DoughnutController, Legend, Tooltip );
    $: handle( districtId, year, typeId );



</script>
{#if fractions }
    <div class='flex flex-col' >
        <h5> Zuchten / Sparte</h5>
        <canvas id='fractions' bind:this={canvasFraction} width='256px' height='128px'></canvas>
    </div>
{/if}

<style></style>