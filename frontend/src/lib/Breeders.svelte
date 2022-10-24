<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let breeders = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            breeders = data.breeders;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if breeders}
        {#each breeders as breeder}
            <div><a href={route.match+'/'+breeder.id}>{breeder.name}</a></div>
        {/each}
    {/if}
</main>

<style>

</style>