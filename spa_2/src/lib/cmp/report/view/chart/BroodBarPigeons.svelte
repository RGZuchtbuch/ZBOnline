<script>
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    let { report } = $props();
    let canvas = null; // ref to canvas element
    let chart = null; // showing chart

    Chart.register( BarController, BarElement, CategoryScale, Colors, LinearScale, Tooltip );

    $effect( () => {
        updateChart( report );
    });

    function updateChart( result ) {
        let labels = [ 'Küken / Paar' ];
        let datasets = [
            {
                data: [ result.broodPigeonResult ],
                backgroundColor: [ '#CEC8' ],
                borderColor: [ '#283' ],
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
                        x: {
                            barPercentage: 0.5,
                        },
                        y: {
                            min: 0,
                        }
                    }
                }
            }
            const context = canvas.getContext( '2d' );
            chart = new Chart( context, config );
        }
    }



</script>

<div class='flex flex-col' >
    <h5 title={`Brutleistung, kKüken pro Paar für Tauben von ${report.broodPigeonBreeders} Tieren`}>
        Brutleistung Tauben
        <sup>{report.broodPigeonBreeders} / {report.broodBreeders}</sup>
    </h5>
    <canvas bind:this={canvas} width='128px' height='256px'></canvas>
</div>

<style></style>