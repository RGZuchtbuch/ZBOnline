<script>
//    import { createEventDispatcher } from 'svelte';
    import api from "../../../js/api.js";
    import { dec, pct } from '../../../js/util.js';
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    export let typeId = null;
    export let districtId = null;
    export let sectionId = null;
    export let breedId = null;
    export let colorId = null;
    export let year;

    let district = null;
    let data = null;

    let canvas = null;
    let chart = null;

const types = { // what options to show
    2: {
        label: 'Zuchten',
        values: (year) => [year.breeders],
        title: (year) => year.breeders ? ` Insgesammt ${dec(year.breeders)} gemeldete Zuchten` : ' hat keine Daten',
//        tooltip: 'Meldende Mitglieder',
        tooltip: ( context ) => dec( context.raw ),

    },
    10: {
        label: 'Legeleistung %',
        values: (year) => [ 100 * year.layEggs ],
        title: (year) =>  year.layBreeders ? ` Legeleistung SOLL ⌀ ${dec(year.layShould)} und IST ⌀ ${dec(year.layEggs)} Eier im Jahr` : ' hat keine Daten',
//        tooltip: 'Nur für Leger',
        tooltip: ( context ) => dec( context.raw, 1 )+'% von ⌀ '+dec( data[ context.dataIndex ].layShould )+' Eiern im Jahr',
    },

    20: {
        id: 20,
        label: 'Brutleistung Leger',
        values: (result) => [result.broodLayerHatched, result.broodLayerFertile, result.broodLayerEggs],
        title: (result) => result.broodEggs ?
            ` Eingelegt ${dec(result.broodEggs)} Eier, ${pct(result.broodFertile, result.broodEggs, 0)} waren befruchtet und es schlüpften ${pct(result.broodHatched, result.broodEggs, 0)}` :
            ' hat keine Daten',
        tooltip: ( context ) => 'Nur für Leger',
    },
    21: {
        id: 21,
        label: 'Brutleistung Tauben',
        values: (result) => [  result.nonLayerPairs ? result.chicks / result.nonLayerPairs : 0  ], // for map and chart
        title: (result) => result.nonLayerPairs ?
            ` Bei ${dec(result.nonLayerPairs)} Paare schlüpften ${dec(result.chicks)} Küken also ${dec(result.chicks/result.nonLayerPairs,1)} Küken / Paar` :
            ` hat keine Daten`,
//        tooltip: ( context ) => dec( context.raw, 1 )+'% of '+dec( data[ context.dataIndex ].layShould );'Nur für Tauben',
    },

    30: {
        id: 30, label: 'Schauleistung', min: 89, max: 97,
        values: (result) => [result.showScore ? result.showScore : 89 ],
        title: (result) => result.showCount ? ` ${result.showCount} Tiere erhielten ⌀ ${dec(result.showScore, 1)} Punkte` : ' hat keine Daten',
        tooltip: 'Bewertungen der Tiere (u), 90 (b) .. 97 (v) Punkte',
    },
}

    function updateDistrict(districtId ) { // get district info
        api.district.get( districtId ).then( response => {
            district = response.district;
        })
    }


    function updateData(districtId, sectionId, breedId, colorId ) {
        let promise;
        if( districtId ) {
            if (colorId) {
                promise = api.trend.color.get(districtId, colorId)
            } else if (breedId) {
                promise = api.trend.breed.get(districtId, breedId)
            } else if (sectionId) {
                promise = api.trend.section.get(districtId, sectionId)
            }
            if( promise ) {
                promise.then(response => {
                    data = response.years;
                    console.log('Years', data );
                });
            }
        }
    }

    function extractLabels( years, type ) {
        let labels = [];
        years.forEach( year => {
            labels.push( year.year );
        })
        return labels;
    }

    function extractDatasets( years, type ) { // 2,3,10,20,21,30
        let datasets = [];
        years.forEach( year => {
            let label = type.label;
            let values = type.values( year ); // could be multiple values to show per year

            for( let i=0; i<values.length; i++ ) { // for each value
                const value = values[i];
                if( datasets.length < i+1 ) { // dataset not available yet, create it
                    console.log( 'Type', type );
                    datasets.push( { label:label, data:[], borderWidth:1, categoryPercentage:(0.75), tooltip:type.tooltip } )
                }
                datasets[i].data.push( value );
            }
        } );
        console.log( 'Datasets', datasets );
        return datasets;
    }

    function refreshChart( labels, datasets, min, max, start ) {
        chart.data.labels = labels;
        chart.data.datasets = datasets;
        chart.options.scales.y.min = min;
        chart.options.scales.y.max = max;
        chart.options.scales.x.min = start;
        chart.update();
    }

    function createChart( labels, datasets, min, max, start ) {
        const context = canvas.getContext( '2d' );

        chart = new Chart(
            context,
            {
                type: 'bar',
                data: {labels: labels, datasets: datasets},
                options : {
                    responsive : false, // not dyn change
                    indexAxis : 'x', // years labels along x axis
                    plugins : {
                        tooltip : {
                            callbacks : {
                                label : ( context ) => context.dataset.tooltip( context ),
                            }
                        }
                    },
                    scales: {
                        x: { position:'bottom', min:start },
                        y: { min:min, max:max },
                    },
                }
            }
        )
    }

    function updateChart(years, typeId ) {
        const type = types[ typeId ];
        if( years ) {
            const context = canvas.getContext( '2d' );

            let labels = extractLabels( years, type );
            let datasets = extractDatasets( years, type );

            if( chart ) {
                refreshChart( labels, datasets, type.min, type.max, 2012 );
            } else {
                createChart( labels, datasets, type.min, type.max, 2012 );
/*
                chart = new Chart( context, {
                    type:'bar',
                    data: {
                        labels:labels,
                        datasets:datasets,
                    },
                    options: {
                        responsive: false,
                        //maintainAspectRatio: false, // added due to shrinking problem
                        indexAxis:'x',
                        plugins: {
                            tooltip: { // on mouse hover
                                callbacks: {
                                    label: function(context, data) {
                                        const value = context.parsed.x; // mind x as we swapped x and y
                                        let label = context.dataset.label || '';

                                        if (label) {
                                            label += ': ';
                                        }

                                        if (context.parsed.x !== null) {
                                            const datasetIndex = context.datasetIndex;
                                            const lastDatasetIndex = chart.data.datasets.length -1;
                                            if( datasetIndex < lastDatasetIndex ) {
                                                const max = chart.data.datasets[ lastDatasetIndex ].data[context.dataIndex];
                                                if( max > 0 ) {
                                                    label += pct( value, max, 1)+' of '+max;
                                                } else {
                                                    label += '?'
                                                }
                                            } else {
                                                label += datasetIndex === 0 ? value : 'Total '+value;
                                            }
                                        }
                                        return label;
                                    }
                                }
                            },
                            legend: {
                                display: false,
                            }
                        },
                        scales: {
                            x: {
                                //stacked:true,
                                position:'bottom',
                                min:2012,
                            },
                            y: {
                                min:type.min,
                                max:type.max,
                                reverse:false, // last to first year
                                stacked:false,
                                onClick: ( event, elements ) => console.log( 'Click', elements )
                            },


                        },
                        onClick: ( event, elements ) => {
                            console.log( 'Click', event, elements );
                            if( elements && elements.length > 0 ) {
                                const label = labels[ elements[0].index ];
                                year = Number( label );
                            }
                        }
                    }
                });

 */
            }
        }
    }

//    const dispatch = createEventDispatcher();

    Chart.register( Colors, BarController, BarElement, CategoryScale, LinearScale, Tooltip );

    $: updateDistrict( districtId );
    $: updateData( districtId, sectionId, breedId, colorId );
    $: updateChart( data, typeId );

</script>



<div class='flex flex-col'>

    <h3 class='text-center'>Im {#if district}{district.name}{/if}</h3>

    <div class='border border-gray-600'>
        <canvas id='canvas' bind:this={canvas} width='380px' height='512px'></canvas>
    </div>
</div>
