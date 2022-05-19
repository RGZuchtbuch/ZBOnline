<script>
    import { active, meta, router, Route } from 'tinro';

    export let promise;

//    const route = meta();

    console.log( 'Breeder' );

</script>

{#await promise}
    loading breeder page...
{:then data}
    {#if data && data.user}
        <ul>
            <li>{data.user.name} ({data.user.id}) </li>

        </ul>
    {/if}
{:catch error}
    Oeps {error}
{/await}