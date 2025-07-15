<script>
//    import { createEventDispatcher } from 'svelte';
    import { goto } from '$app/navigation';
    import { page } from '$app/state';
    import { dec, pct } from '$lib/js/tools.js';
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    let { report, typeId=2 } = $props();

    let canvas = null;
    let chart = null;

    $effect( () => {
        updateTrend( report, typeId );
    });

    function extractLabels( years, type ) {
        let labels = [];
        years.forEach( year => {
            labels.push( year.year );
        })
        return labels;
    }

    const datasetGenerators = { // for type, prepare data
        2 : ( years ) => [
            {label: 'Zuchten #', data: years.map( year => year.breeders ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw) + ' gemeldete Zuchten' }
        ],
        10 : ( years ) => [
            {label: 'Eier %',    data: years.map( year => 100 * year.layEggs ),   borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von ⌀ '+dec( report.years[ context.dataIndex ].layShould )+' Ei/J' },
            {label: 'Gewicht %', data: years.map( year => 100 * year.layWeight ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) =>  dec( context.raw, 1 )+'% von ⌀ '+dec( report.years[ context.dataIndex ].layWeightShould )+' g' }
        ],
        20 : ( years ) => [
            {label: 'Befruchtet %', data: years.map( year => 100 * year.broodLayerFertile ), borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von '+dec( report.years[ context.dataIndex ].broodLayerEggs )+' eingelegte Eier' },
            {label: 'Küken %',      data: years.map( year => 100 * year.broodLayerHatched ), borderWidth: 1, categoryPercentage: (0.75), tooltip: (context) => dec( context.raw, 1 )+'% von '+dec( report.years[ context.dataIndex ].broodLayerEggs )+' eingelegte Eier' }
        ],
        21 : ( years ) => [
            {label: 'Küken pro Paar', data: years.map( year => year.broodPigeonResult ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw, 1) + ' mit '+dec( report.years[ context.dataIndex ].broodPigeonHatched )+' Küken aus '+dec( report.years[ context.dataIndex ].broodPigeonHatched / report.years[ context.dataIndex ].broodPigeonResult )+' Paare in  '+report.years[ context.dataIndex ].broodPigeonBreeders+' Zuchten' }
        ],
        30 : ( years ) => [
            {label: '⌀ Bewertung', data: years.map( year => year.showScore ), borderWidth: 1, categoryPercentage: (0.75), tooltip:(context) => dec(context.raw, 1) + ' auf '+dec( report.years[ context.dataIndex ].showCount )+' Tieren' }
        ],
    }

    const scales = {
        2:  {},
        10: { min:0, max:160 },
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
                    onClick: ( event, elements ) => { // change year on bar click
                        if( elements && elements.length > 0 ) {
                            const label = labels[ elements[0].index ];
                            const url = new URL( page.url );
                            url.searchParams.set( 'year', label );
                            goto( url );
                            //year = Number( label );
                        }
                    }
                }
            }
        )
    }

    function updateTrend( report, typeId ) {
        const scale = scales[ typeId ];
        if( report.years ) {
            //const context = canvas.getContext( '2d' );
            let labels = extractLabels( report.years, typeId );
            let datasets = extractDatasets( report.years, typeId );
            if( chart ) {
                refreshChart( labels, datasets, scale.min, scale.max, 2012 );
            } else {
                createChart( labels, datasets, scale.min, scale.max, 2012 );
            }
        }
    }

    Chart.register( Colors, BarController, BarElement, CategoryScale, LinearScale, Tooltip );


</script>



<div class='flex flex-col'>

    <!--h3 class='bg-header text-center text-white'>{#if type && district } {type.name } im {district.name}{/if}</h3-->

    <div class='border rounded-t-none'>
        <canvas id='canvas' width='448' height='576' bind:this={canvas} ></canvas>
    </div>
</div>
