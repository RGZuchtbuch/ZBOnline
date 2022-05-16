<script>
    import { slide } from 'svelte/transition';

    export let promise;
    export let onPairSelect;

    function onClick( event ) { onPairSelect( this.id ) }


    console.log( 'run pairs' )
</script>


{#await promise}
    loading
{:then pairs}

    <fieldset class='bordered w-256'>
        <legend> Stämme </legend>
        <ul>
            {#each pairs as pair}
                <li on:click={onClick.bind( pair )}>{pair.name}</li>
            {/each}
        </ul>
    </fieldset>

{:catch error}
    oeps
{/await}


<style>

</style>

