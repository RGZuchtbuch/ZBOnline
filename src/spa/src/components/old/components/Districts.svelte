<script>

    export let promise;
    export let onDistrictSelect;

    function onClick( event ) { onDistrictSelect( this ) }

    let districts = [{ id:42, name:'Jan'}, { id:43, name:'Piet'}]

    console.log( 'run districts' )
</script>


<div class='text-4xl'>Districts</div>
{#await promise}
    loading
{:then breed}
    <ul>
        {#each districts as district}
            <li on:click={onClick.bind( district.id )}> {district.name} </li>
        {/each}
    </ul>

    got districts
{:catch error}
    oeps
{/await}


<style>

</style>