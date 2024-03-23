<script>
    import { meta } from 'tinro';
    import { standard } from '../../js/store.js';

    import List from "../common/List.svelte";
    import SectionRow from "./SectionRow.svelte";

    let section = null;

    const route = meta();

    $: section = $standard;
</script>


<List>
    <div slot='title'> BDRG Rassegeflügelstandard </div>
    <div slot='header' class='text-center'> Sparten / Gruppen / Rassen / Farbenschläge </div>
    <div slot='body'>
        {#if section}
            <SectionRow {section} open={true}/>
        {/if}
    </div>
</List>

<style>

</style>