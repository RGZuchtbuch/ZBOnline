<script>
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    let { report } = $props();
    let prodCanvas = null; // ref to canvas element
    let hatchCanvas = null;
    let prodChart = null; // showing chart
    let hatchChart = null; // showing chart

    Chart.register( BarController, BarElement, CategoryScale, Colors, LinearScale, Tooltip );

    $effect( () => {
        updateChartHatch( report );
        updateChartProd( report );
    });

    function updateChartHatch( result ) {
        let labels = [ 'Geschlüpft %' ];
        let datasets = [
            {
                data: [ 100*result.broodPigeonHatched ],
                backgroundColor: [ '#CEC8' ],
                borderColor: [ '#283' ],
                borderWidth: 1,
            }
        ];

        if( hatchChart ) {
            hatchChart.data.labels = labels;
            hatchChart.data.datasets = datasets;
            hatchChart.update();
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
            const context = hatchCanvas.getContext( '2d' );
            hatchChart = new Chart( context, config );
        }
    }

    function updateChartProd( result ) {
        let labels = [ 'Küken / Paar' ];
        let datasets = [
            {
                data: [ result.broodPigeonResult ],
                backgroundColor: [ '#CEC8' ],
                borderColor: [ '#283' ],
                borderWidth: 1,
            }
        ];

        if( prodChart ) {
            prodChart.data.labels = labels;
            prodChart.data.datasets = datasets;
            prodChart.update();
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
                        x: {
                            barPercentage: 0.5,
                        },
                        y: {
                            min: 0,
                        }
                    }
                }
            }
            const context = prodCanvas.getContext( '2d' );
            prodChart = new Chart( context, config );
        }
    }

</script>

<div class='flex flex-col' >
    <h5 title={`Brutleistung, kKüken pro Paar für Tauben von ${report.broodPigeonBreeders} Tieren`}>
        Bruten Tauben
        <sup>{report.broodPigeonBreeders} / {report.breeders}</sup>
    </h5>
    <div class='flex flex-row'>
        <canvas bind:this={hatchCanvas} width='96px' height='256px'></canvas>
        <canvas bind:this={prodCanvas} width='96px' height='256px'></canvas>
    </div>
</div>

<style></style>