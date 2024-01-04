<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";

    import DistrictsTree from "../districts/DistrictsTree.svelte";
    import SectionRow from "./SectionRow.svelte";
//    import {createEventDispatcher} from "svelte";

    let section = null;

//    const dispatch = createEventDispatcher();
    const route = meta();

    function loadStandard() {
        api.standard.get().then( response => {
            section = response.standard;
            section.open = true;
        } );

    }

    loadStandard();
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