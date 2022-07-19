<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let promise;
    export let legend = '';
    export let link='';

    let districts = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            districts = data;
        });
    })


</script>

<main>
    <div>{legend}</div>
    {#if districts}
        <SelectTree children={districts} {link}/>
    {/if}
</main>

<style>

</style>