<script>
    import { active, meta, router, Route } from 'tinro';
    const route = meta();

    console.log( 'Breed' );

    export let promise;
</script>

{#await promise}
    loading...
{:then breed}
    <ul>
        <li>{breed.name} ({breed.id}) </li>

    </ul>
    <a href='{route.match}/colors/10'>To color</a>
    <a href='{route.match}/results/trend'>To trend</a>
{:catch error}
    Oeps
{/await}
