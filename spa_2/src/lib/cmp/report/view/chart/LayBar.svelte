<script>
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    // export let d

    let { report } = $props();
    let canvas = null; // ref to canvas element
    let chart = null; // showing chart

    $effect( () => {
        updateChart( report );
    });

    function updateChart( report ) {
        let labels = [ 'Eier %', 'Gewicht %' ];
        let datasets = [
            {
                data: [ 100*report.layEggs, 100*report.layWeight ],
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
                    animation: {
                        delay: 250,
                    },
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

</script>

<div class='flex flex-col' >
    <h5 title={`Legeleistung durchschnitt von alle Sparten von ${report.layBreeders} Tieren`}>
        Legen  Geflügel
        <sup>{report.layBreeders} / {report.breeders}</sup>
    </h5>
    <canvas bind:this={canvas} width='192px' height='256px'></canvas>
</div>

<style></style>