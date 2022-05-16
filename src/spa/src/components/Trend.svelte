<script>
    import Line from "svelte-chartjs/src/Line.svelte";

    export let promise;

    let data = {
        labels:[ 2001, 2002, 2003, 2004 ],
        datasets:[
            { label: 'laying', data: [1,2,3,2 ] },
            { label: 'hatching', data: [10, 9, 8, 9 ] }
        ]
    };
    let options = {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }


</script>
Trend showing
{#await promise}
    loading...
{:then years}
        <Line data width=640 height=480 options />
{:catch error}
    {error}
{/await}