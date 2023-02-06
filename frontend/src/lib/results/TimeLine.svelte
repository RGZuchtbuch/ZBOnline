<script>

    import api from "../../js/api.js";
    import { pct } from '../../js/util.js';
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Title, Tooltip } from 'chart.js';
    import Select from '../input/Select.svelte';

    export let type = null;
    export let districtId = 1;
    export let sectionId = 1;
    export let breedId = null;
    export let colorId = 0;
    export let year;

    let district = null;
    let rootDistrict = null;
    let years = null;

    let canvas = null;
    let chart = null;

    function loadDistrict( districtId ) {
        api.district.get( districtId ).then( response => {
            district = response.district;
        })
    }

    function loadDistricts( rootDistrictId ) {
        api.district.children.get( rootDistrictId ).then( response => {
            rootDistrict = response.rootDistrict;
        })
    }

    function loadYears( districtId, sectionId, breedId, colorId ) {
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
                let values = type.extract( row );

                for( let i=0; i<values.length; i++ ) {
                    if( datasets.length < i+1 ) {
                        datasets.push( { data:[], borderWidth:1, categoryPercentage:(0.4+i*0.2) } )
                    }
                    const dataset = datasets[i];
                    const data = dataset.data;
                    const value = values[i];
                    data.push( value );
                }
            })
//            console.log('TL datasets', datasets);
            if( chart ) {
                chart.data.labels = labels;
                chart.data.datasets = datasets;
                chart.update();
            } else {
                chart = new Chart( context, {
                    type:'bar',
                    data: {
                        labels:labels,
                        datasets:datasets,
                    },
                    options: {
                        indexAxis:'y',
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context, data) {
//                                        console.log( 'Context', context );
//                                        console.log( 'More', chart.data.datasets.length );
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
                                                    label += pct( value, max, 1);
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
                            }
                        },
                        scales: {
                            x: {
                                //stacked:true,
                                position:'top',
                            },
                            y: {
                                reverse:true,
                                stacked:true,
                            }
                        },
                        onClick: ( event, element ) => {
                            if( element && element.length > 0 ) {
                                const label = labels[ element[0].index ];
                                onClick( label );
                            }
                        }
                    }
                });
            }
        }
    }

    function onClick( label ) {
        year = Number( label ); // to parent by binding.
    }

    Chart.register( Colors, BarController, BarElement, CategoryScale, LinearScale, Tooltip );

    loadDistricts( 1 );

    $: loadDistrict( districtId );
    $: loadYears( districtId, sectionId, breedId, colorId );
    $: showChart( years, type );

</script>



<div class='flex flex-col'>
    {#if district}
        <h3 class='text-center'>Im {district.name}</h3>
    {/if}
    <Select bind:value={districtId} label='Landesverband'>
        {#if rootDistrict}
            <option value={rootDistrict.id}>{rootDistrict.name}</option>
            {#each rootDistrict.children as district}
                <option value={district.id}>{district.name}</option>
            {/each}
        {/if}
    </Select>

    <div class='border border-gray-600'>
        <canvas id='canvas' width='380' height='512' bind:this={canvas} ></canvas>
    </div>
</div>
