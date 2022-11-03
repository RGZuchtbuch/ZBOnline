<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let breeders = null;

    function handle( promise ) {
        if( promise ) promise.then( data => {
            breeders = data.breeders;
        });
    }

    onMount( () => {
    })

    $: handle( promise );


</script>

<main>
    <h2>{legend}</h2>
    {#if breeders}
        {#each breeders as breeder}
            <div><a href={route.match+'/'+breeder.id}>{breeder.name}</a></div>
        {/each}
    {/if}
</main>

<style>

</style>