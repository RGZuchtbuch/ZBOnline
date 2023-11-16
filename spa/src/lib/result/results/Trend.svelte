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

    const datasetGenerators = {
        2 : ( years ) => [
            {label: 'Zuchten #', data: years.map( year => year.breeders ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw) + ' gemeldete Zuchten' }
        ],
        10 : ( years ) => [
            {label: 'Eier %',    data: years.map( year => 100 * year.layEggs ),   borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von ⌀ '+dec( data[ context.dataIndex ].layShould )+' Ei/J' },
            {label: 'Gewicht %', data: years.map( year => 100 * year.layWeight ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) =>  dec( context.raw, 1 )+'% von ⌀ '+dec( data[ context.dataIndex ].layWeightShould )+' g' }
        ],
        20 : ( years ) => [
            {label: 'Befruchtet %', data: years.map( year => 100 * year.broodLayerFertile ), borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von '+dec( data[ context.dataIndex ].broodLayerEggs )+' eingelegte Eier' },
            {label: 'Küken %',      data: years.map( year => 100 * year.broodLayerHatched ), borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von '+dec( data[ context.dataIndex ].broodLayerEggs )+' eingelegte Eier' }
        ],
        21 : ( years ) => [
            {label: 'Küken pro Paar', data: years.map( year => year.broodPigeonProduction ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw, 1) + ' mit '+dec( data[ context.dataIndex ].broodPigeonChicks )+' Küken aus '+dec( data[ context.dataIndex ].broodPigeonChicks / data[ context.dataIndex ].broodPigeonProduction )+' Paare in  '+data[ context.dataIndex ].broodPigeonBreeders+' Zuchten' }
        ],
        30 : ( years ) => [
            {label: '⌀ Bewertung', data: years.map( year => year.showScore ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw, 1) + ' auf '+dec( data[ context.dataIndex ].showCount )+' Tieren' }
        ],
    }

    const scales = {
        2:  {},
        10: { min:0, max:140 },
        20: { min:0, max:100 },
        21: {},
        30: { min: 89, max: 97 },
    }

    function extractDatasets( years, typeId ) {
        const generator = datasetGenerators[ typeId ];
        return generator( years );
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
                data: { labels: labels, datasets: datasets },
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
        const scale = scales[ typeId ];
        if( years ) {
            const context = canvas.getContext( '2d' );
            let labels = extractLabels( years, typeId );
            let datasets = extractDatasets( years, typeId );
            if( chart ) {
                refreshChart( labels, datasets, scale.min, scale.max, 2012 );
            } else {
                createChart( labels, datasets, scale.min, scale.max, 2012 );
            }
        }
    }

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
