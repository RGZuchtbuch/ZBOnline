<script>
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    let { report } = $props();

    let canvas = null; // ref to canvas element
    let chart = null; // showing chart

    Chart.register( BarController, BarElement, CategoryScale, Colors, LinearScale, Tooltip );

    $effect( () => {
        updateChart( report );
    });

    function updateChart( report ) {
        let labels = [ 'Befruchtet %', 'Geschlupft %' ];
        let datasets = [
            {
                data: [ 100 * report.broodLayerFertile, 100 * report.broodLayerHatched ],
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
                    animation: {
                        delay: 500,
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
                            max: 100,
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
    <h5 title={`Brutleistung, Befruchtung und Schlupf von alle Sparten ausser Tauben von ${report.broodLayerBreeders} Tieren`}>
        Bruten Geflügel
        <sup>{report.broodHatchedBreeders} / {report.breeders}</sup>
    </h5>
    <canvas bind:this={canvas} width='192px' height='256px'></canvas>
</div>

<style></style>