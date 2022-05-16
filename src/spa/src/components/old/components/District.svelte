<script>
    export let promise;
    export let onBreeders;
    export let onResults;

    function showBreeders( event ) { onBreeders( this.id ) }
    function showResults( event ) { onResults( this.id ) }

    console.log( 'run breed' )

</script>


<div class='text-4xl'>District</div>
{#await promise}
    loading
{:then district}
    got district
    <div>District {district.name} info</div>

    <div class='flex flex-row'>
        <button on:click={ showBreeders.bind( district ) }> Breeders </button>
        <button on:click={ showResults.bind( district, 2021 ) }> Results </button>
    </div>
{:catch error}
    oeps
{/await}


<style>

</style>