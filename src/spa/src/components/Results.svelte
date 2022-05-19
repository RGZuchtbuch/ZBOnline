<script>
    import { active, meta, router, Route } from 'tinro';
    const route = meta();

    console.log( 'Results' );

    export let promise;
</script>

{#await promise}
    loading results...
{:then data}
    {#if data && data.results }
        <div>
            {#each data.results as result }
                <div>{result.id} : {result.lay_dames}</div>
            {/each}
        </div>
    {/if}
{:catch error}
    Oeps {error}
{/await}