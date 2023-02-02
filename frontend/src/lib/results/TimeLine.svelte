<script>

    import api from "../../js/api.js";
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Title, Tooltip } from 'chart.js';
    import Select from '../input/Select.svelte';

    export let type = null;
    export let districtId = 1;
    export let sectionId = 1;
    export let breedId = null;
    export let colorId = 0;
    export let year;

    let rootDistrict = null;
    let years = null;

    let canvas = null;
    let chart = null;

    console.log( 'TimeLine', year, districtId, type.label);

    function loadDistricts( rootDistrictId ) {
        api.district.children.get( rootDistrictId ).then( response => {
            rootDistrict = response.rootDistrict;
        })
    }

    function loadYears( districtId, sectionId, breedId, colorId ) {
        let promise;
        if( districtId ) {
            console.log( 'Loading years ' );
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
                    console.log( 'Renewed years', years );
                });
            }
        }
    }

    function showChart( years, type ) {

        if( years ) {
            console.log( 'Show', years, type );
            const context = canvas.getContext( '2d' );
            let labels = [];
            let datasets = [];

            // fill label;s and datasets depending on rows
            years.forEach( row => {
                labels.push( row.year );
                let values = type.extract( row );
                for( let i=0; i<values.length; i++ ) {
                    if( datasets.length < i+1 ) {
                        datasets.push( { data:[] } )
                    }
                    datasets[i].data.push( values[ i ] );
                }
            })
            console.log( 'Datasets', datasets );

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
                        },
                        scales: {
                            x: {
                                stacked:true,
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

    $: loadYears( districtId, sectionId, breedId, colorId );
    $: showChart( years, type );


</script>



<div class='flex flex-col'>
    <h3 class='text-center'>Values over the years {type.label}</h3>
    <Select bind:value={districtId} label='Landesverband'>
        {#if rootDistrict}
            <option value={rootDistrict.id}>{rootDistrict.name}</option>
            {#each rootDistrict.children as district}
                <option value={district.id}>{district.name}</option>
            {/each}
        {/if}
    </Select>
    <div>{districtId} {sectionId} {breedId} {colorId} {type.label}</div>
    <div class='border border-gray-600 p-4'>
        <canvas id='canvas' width='256' height='512' bind:this={canvas} ></canvas>
    </div>
</div>
