<script>
    import {onMount} from 'svelte';
    import { goto } from '$app/navigation';
    import { page } from '$app/state';
    import {activeYear, dec, pct} from '$lib/js/tools.js';
    import { BarController, BarElement, CategoryScale, Chart, Colors, LinearScale, Tooltip } from 'chart.js';

    let { title, data, scale={}, unit, factor=1.0, color={fill:'#ADF', border:'#48A'}, width=1.0 } = $props();

    let canvas = null;
    let chart = $state( null );

    $effect( () => {
        if( chart ) {
            updateChart();
        } else {
            chart = getChart();
        }
    })


    function getChart() {
        const context = canvas.getContext('2d');
        return new Chart( context, getConfig() );
    }

    function updateChart() {
        chart.data.labels = data.map( item => item.year );
        chart.data.datasets = [
            { data:data.map( item => factor*item[ unit ] ), backgroundColor:color.fill, borderColor:color.border, borderWidth:1  }
        ]
        chart.options.scales = {
            x : { min:activeYear()-10 },
            y : scale,
        }
        chart.update();
    }

    function getConfig() {
        return {
            type: 'bar',
            data: {
                labels:data.map( item => item.year ),
                datasets:[
                    {
                        //label:label,
                        data :data.map( item => factor*item[ unit ] ),
                        backgroundColor: color.fill,
                        borderColor: color.border,
                        borderWidth: 1,
                    }
                ],
            },
            options : {
                animation: {
                    delay: 250,
                    duration:500,
                    easing:'linear',
                },
                plugins: {
                    // title: {
                    //     text: title,
                    //     display: false,
                    // }
                },
                responsive : false, // otherwise shrinking
                scales : {
                    x : { min:activeYear()-10 },
                    y : scale,
                }
            },
        }
    }

    Chart.register( Colors, BarController, BarElement, CategoryScale, LinearScale, Tooltip );


</script>



<div class='flex flex-col'>
    <div class='text-center font-bold'>{title}</div>
    <!--h3 class='bg-header text-center text-white'>{#if type && district } {type.name } im {district.name}{/if}</h3-->

    <div class='m-0 border rounded p-2'>
        <canvas width={width*288} height='192' bind:this={canvas} ></canvas>
    </div>
</div>
