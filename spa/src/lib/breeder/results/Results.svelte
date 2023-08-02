<script>
    import api from '../../../js/api.js';

    export let breeder = null;
    let results = null;

    function loadResults( breeder ) {
        api.breeder.results.get( breeder.id ).then( response => {
            results = response.results;
        });
    }


    $: loadResults( breeder );

</script>


{#if results}
    <div>Results header</div>
    {#each results as result}
        <div> {result.id} </div>
    {/each}
{/if}


