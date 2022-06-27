<script>
    import {active, meta, router, Route} from 'tinro';
    import dic from '../scripts/dic.js';
    import SelectTree from './SelectTree.svelte';

    const route = meta();

    export let breeders;
    export let legend = '';
    export let link='';

</script>

<main>
    <div>{legend}</div>
    <SelectTree children={breeders} {link}/>
</main>

<style>

</style>