<script>
    import { active, meta, router, Route } from 'tinro';
    import Box from './Box.svelte';

    const route = meta();

    export let promise
    console.log( 'Section' );
</script>

{#await promise}
    loading...
{:then section}
    <Box legend='Rassen der {section.name}'>
        <div class='flex flex-row'>
            <div>Section <a href='{route.match}/breeds'>breeds</a></div>
            <div>Section <a href='{route.match}/results/trend'>Trend</a></div>
            <div>Section <a href='{route.match}/results/map'>Karte</a></div>
            <div>Section <a href='{route.match}/results/table'>Tabelle</a></div>
        </div>
    </Box>
{:catch error}
    oeps
{/await}



