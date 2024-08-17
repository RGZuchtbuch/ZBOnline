<script>
    import { meta } from 'tinro';
    import { standard } from '../../js/store.js';

    import Page from "../common/Page.svelte";
    import SectionRow from "./SectionRow.svelte";

    let section = null;

    const route = meta();

    $: section = $standard;
</script>


<Page>
    <div slot='title'> BDRG Rassegeflügelstandard</div>
    <div slot='header'> Sparten / Gruppen / Rassen / Farbenschläge </div>
    <div slot='body' class='p-2'>
        {#if section}
            <SectionRow {section} open={true}/>
        {/if}
    </div>
</Page>

<style>

</style>