<script>
//    import { createEventDispatcher } from 'svelte';
    import api from "../../../js/api.js";
    import { pct } from '../../../js/util.js';
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    export let type = null;
    export let districtId = null;
    export let sectionId = null;
    export let breedId = null;
    export let colorId = null;
    export let year;

    let district = null;
    let years = null;

    let canvas = null;
    let chart = null;

    function loadDistrict( districtId ) {
        api.district.get( districtId ).then( response => {
            district = response.district;
        })
    }


    function onChange( districtId, sectionId, breedId, colorId ) {
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
                    years = response.years;
                });
            }
        }
    }

    function showChart( years, type ) {

        if( years ) {
            const context = canvas.getContext( '2d' );
            let labels = [];
            let datasets = [];

            // fill label;s and datasets depending on rows
            years.forEach( row => {
                labels.push( row.year );
                let values = type.time( row );

                for( let i=0; i<values.length; i++ ) {
                    if( datasets.length < i+1 ) {
                        datasets.push( { data:[], borderWidth:1, categoryPercentage:(0.75) } )
                    }
                    const value = values[i];
                    datasets[i].data.push( value );
                }
            })
            if( chart ) {
                chart.data.labels = labels;
                chart.data.datasets = datasets;
                chart.options.scales.x.min = type.min;
                chart.options.scales.x.max = type.max;
                chart.update();
            } else {
                chart = new Chart( context, {
                    type:'bar',
                    data: {
                        labels:labels,
                        datasets:datasets,
                    },
                    options: {
                        responsive: false,
                        //maintainAspectRatio: false, // added due to shrinking problem
                        indexAxis:'y',
                        plugins: {
                            tooltip: {
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
                                position:'top',
                                min:type.min,
                                max:type.max,
                            },
                            y: {
                                reverse:true, // last to first year
                                stacked:true,
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
            }
        }
    }

//    const dispatch = createEventDispatcher();

    Chart.register( Colors, BarController, BarElement, CategoryScale, LinearScale, Tooltip );

    $: loadDistrict( districtId );
    $: onChange( districtId, sectionId, breedId, colorId );
    $: showChart( years, type );

</script>



<div class='flex flex-col'>

    <h3 class='text-center'>Im {#if district}{district.name}{/if}</h3>

    <div class='border border-gray-600'>
        <canvas id='canvas' bind:this={canvas} width='380px' height='512px'></canvas>
    </div>
</div>
