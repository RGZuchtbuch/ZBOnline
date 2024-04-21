<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";
    import BreedersHeader from "./BreedersHeader.svelte";
    import BreedersRow from "./BreedersRow.svelte";
    import Article from "../article/Article.svelte";
    import Page from "../common/Page.svelte";

    export let district = null;

    let breeders = null;
    let showInactives = false; // should ex members be included ?

    const route = meta();

    function loadBreeders( district ) {
        api.district.breeders.get( district.id ).then( response => {
            breeders = response.breeders;
        });
    }

    $: loadBreeders( district ); // each time district changes in url
</script>


<List>
    <div slot='title'>{district.name} - Zuchtbuchmitglieder</div>
    <BreedersHeader slot='header' bind:showInactives />
    <div slot='body'>
        {#if breeders}
            {#each breeders as breeder (breeder.id) }
                <BreedersRow {breeder} {showInactives} />
            {/each}
        {/if}
    </div>
</List>

<style>

</style>