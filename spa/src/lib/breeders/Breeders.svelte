<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { breeder, district, user } from '../../js/store.js'

    import Page from "../common/Page.svelte";
    import BreedersHeader from "../breeders/BreedersHeader.svelte";
    import BreedersRow from "../breeders/BreedersRow.svelte";
    import Article from "../article/Article.svelte";

//    export let district = null;

    export let breeders = null;
    let showInactives = false; // should ex members be included ?

    const route = meta();

</script>

<!--div > UNUSED </div-->
<Page>
    <div slot='title'>{district.name} - Zuchtbuchmitglieder</div>
    <BreedersHeader slot='header' bind:showInactives />
    <div slot='body'>
        {#if breeders}
            {#each breeders as breeder (breeder.id) }
                <BreedersRow {breeder} {showInactives} />
            {/each}
        {/if}
    </div>
</Page>

<style>

</style>