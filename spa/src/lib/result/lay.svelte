<script>

    import api from "../../js/api.js";
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    export let districtId = null;
    export let year = null;
    export let sectionId = null;
    export let breedId = null;
    export let colorId = null;
    export let type = null; // what to display

    let canvas = null; // ref to canvas element

    let chart = null;

    function update(districtId, year, sectionId, breedId, colorId, type ) {
        if( districtId &&  year && sectionId ) { // at least
            let promise;
            if( colorId ) {
                promise = api.map.color.get( year, colorId )
            } else if( breedId ) {
                promise = api.map.breed.get( year, breedId )
            } else { // sectionId
                promise = api.map.section.get( year, sectionId )
            }
            if( promise ) {
                promise.then(response => {
                    const districts = response.districts;
//                    let data = districts.map( district => district.breeders ); // get array of breeders
                    let data = districts.map( district => type.pie( district)[0] );
                    let datasets = [ { data:data } ];
                    let labels = districts.map( district => district.name ); // get array of names

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
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'right',
                                    }
                                }                            }
                        }
                        const context = canvas.getContext( '2d' );
                        chart = new Chart( context, config );
                    }
                });
            }
        }
    }

    function  getData( districtId, year, sectionId, breedId, colorId, type ) {
        return data;
    }

    Chart.register( BarController, BarElement, CategoryScale, Colors, LinearScale, Tooltip );
    $: update( districtId, year, sectionId, breedId, colorId, type );



</script>

<div class='flex flex-col' >
    <h5> {type.label} / LVx</h5>
    <canvas id='districtspie' bind:this={canvas} width='256px' height='128px'></canvas>
</div>

<style></style>