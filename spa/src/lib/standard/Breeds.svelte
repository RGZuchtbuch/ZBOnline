<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";

    import DistrictsTree from "../districts/DistrictsTree.svelte";
    import SectionTree from "./SectionTree.svelte";
//    import {createEventDispatcher} from "svelte";

    let rootSection = null;

//    const dispatch = createEventDispatcher();
    const route = meta();

    function loadStandard() {
        api.standard.get().then( response => {
            rootSection = response.standard;
            rootSection.open = true;
            console.log( 'RS', rootSection );
        } );

    }

    loadStandard();
</script>


<List>
    <div slot='title'> BDRG Rassegeflügelstandard </div>
    <div slot='header' class='text-center'> Sparten / Gruppen / Rassen / Farbenschläge </div>
    <div slot='body'>
        {#if rootSection}
            <SectionTree {rootSection} />
        {/if}
    </div>
</List>

<style>

</style>