<script>
    import { active, meta, router, Route } from 'tinro';
    const route = meta();

    console.log( 'Districts' );

    export let promise;
</script>

{#await promise}
    loading districts...
{:then data}
    {#if data && data.districts }
        <div>
            {#each data.districts as district }
                <div>{district.id} : {district.name}</div>
            {/each}
        </div>
    {/if}
{:catch error}
    Oeps {error}
{/await}