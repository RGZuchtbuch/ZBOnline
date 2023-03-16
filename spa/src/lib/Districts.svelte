<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import SelectTree from './select/SelectTree.svelte';

    export let districts = null;

    const route = meta();

    onMount( () => {
    })

    console.log( 'Districts', districts )
</script>


<div class='border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände</div>
<div class='bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 scrollbar'>
    {#if districts}
        <SelectTree children={districts} link={route.match+'/'}/>
    {/if}
</div>

<style>

</style>
