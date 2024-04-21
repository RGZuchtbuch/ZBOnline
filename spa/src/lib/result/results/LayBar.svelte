<script>

    import api from "../../../js/api.js";

    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    export let districtId = null;
    export let year = null;
    export let sectionId = null;
    export let breedId = null;
    export let colorId = null;
    export let group = null;


    let canvas = null; // ref to canvas element

    let chart = null; // showing chart

    function update( districtId, year, sectionId, breedId, colorId, group ) {
        updateData( districtId, year, sectionId, breedId, colorId, group ).then( data => {
            updateChart( data.result );
        });
    }

    function updateData( districtId, year, sectionId, breedId, colorId, group ) {
        if( districtId && year ) { // at least
            let a =  api.result.districtYear(districtId, year, sectionId, breedId, colorId, group);
            return a;
        }
        return null;

    }

    function updateChart( result ) {
        let labels = [ 'Eier %', 'Gewicht %' ];
        let datasets = [
            {
                data: [ 100*result.layEggs, 100*result.layWeight ],
                backgroundColor: [ '#FFCD5680' ],
                borderColor: [ '#c62' ], // FF9F40 f94
                borderWidth: 1,
            }
        ];
        if( chart ) {
            chart.data.labels = labels;
            chart.data.datasets = datasets;
            chart.update();
        } else {
            const config = {
                type: 'bar',
                data: {
                    labels:labels,
                    datasets:datasets,
                },
                options: {
                    responsive:false,
                    plugins: {
                        legend: {
                            display: false,
                            position: 'right',
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 160,
                        }
                    }
                }
            }
            const context = canvas.getContext( '2d' );
            chart = new Chart( context, config );
        }
    }


    Chart.register( BarController, BarElement, CategoryScale, Colors, LinearScale, Tooltip );

    $: update( districtId, year, sectionId, breedId, colorId, group );
</script>

<div class='flex flex-col' >
    <h5>Legeleistung  Geflügel</h5>
    <canvas bind:this={canvas} width='192px' height='256px'></canvas>
    <div></div>
</div>

<style></style>