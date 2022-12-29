<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    import SelectTree from './select/SelectTree.svelte';

    export let districts = [];

    const route = meta();
    onMount( () => {
    })
    console.log( 'Districts', districts )
</script>

<div class='border border-gray-600 rounded-t bg-gray-300 px-4 font-bold'>Verband</div>
<div class='bg-gray-100 overflow-y-scroll border border-t-0 rounded-b border-gray-600 scrollbar'>
    {#if districts}
        Districts {districts.length}
        <SelectTree children={districts} link={route.match+'/'}/>
    {/if}
</div>

<style>

</style>
