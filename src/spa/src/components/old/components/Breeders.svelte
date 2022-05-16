<script>
    import { slide } from 'svelte/transition';

    export let promise;
    export let onBreederSelect;

    function onClick( event ) { onBreederSelect( this.id ) }

    console.log( 'run breeders' )
</script>


{#await promise}
    loading breeders
{:then breeders}
    <fieldset class='bordered w-256'>
        <legend> Züchter </legend>
        <ul>
            {#each breeders as breeder}
                <li on:click={onClick.bind( breeder )}>{breeder.name}</li>
            {/each}
        </ul>
    </fieldset>
{:catch error}
    oeps
{/await}


<style>

</style>