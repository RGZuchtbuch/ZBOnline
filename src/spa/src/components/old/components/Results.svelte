<script>
    import { slide } from 'svelte/transition';
    export let promise;
    export let onSelect;

    function onClick( event ) { onSelect( this ) }

    console.log( 'run results' )
</script>

{#await promise}
    loading results
{:then results}
    <fieldset class='bordered w-256'>
        <legend> Leistungen </legend>
        <ul>
            {#each results as result}
                <li on:click={onClick.bind( result )}>{result.year} - {result.layed} - {result.fertilized} - {result.hatched} </li>
            {/each}
        </ul>
    </fieldset>
{:catch error}
    oeps
{/await}

<style>

</style>