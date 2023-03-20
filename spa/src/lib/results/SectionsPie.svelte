<script>

    import api from "../../js/api.js";
//    import {BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip} from "chart.js";
    import { Chart, ArcElement, DoughnutController, Tooltip} from 'chart.js';

    export let districtId = null;
    export let year = null;
    export let sectionId = null;
    export let breedId = null;
    export let colorId = null;

    let canvasFraction = null; // ref to canvas element
    let canvasB = null;
    let canvasC = null;
    let chart = null;

    let fractions = {
        3: 0, 4: 0, 5: 0, 6: 0
    };



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

    function handle( districtId, year ) {
        if( districtId ) {
            api.district.results.get( districtId, year ).then( response => {
                console.log( 'Pie Results', response.results );
                const results = response.results;
                countBreeders( results );
                let data = results.sections.map( section => section.count ); // get array of counts
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
                        }
                    }
                    const context = canvasFraction.getContext( '2d' );
                    chart = new Chart( context, config );
                }
            });
        }
    }

    Chart.register( ArcElement, DoughnutController, Tooltip );
    $: handle( districtId, year );



</script>
{#if fractions }
    <div class='flex flex-col' >
        <h5> Zuchten / Sparte</h5>
        <canvas id='fractions' bind:this={canvasFraction} width='128px' height='128px'></canvas>
    </div>
{/if}

<style></style>