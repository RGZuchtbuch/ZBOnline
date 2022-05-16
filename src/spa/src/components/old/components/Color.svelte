<script>
    import { slide } from 'svelte/transition';
    import api from '../../../scripts/api.js';

    export let id;
    let promise = api.getColor( id );

    console.log( 'run color' )
</script>

{#await promise}
    loading
{:then color}
    <fieldset class='bordered w-256'>
        <legend> Farbenschlag </legend>
        {color.name}
    </fieldset>
{:catch error}
    oeps
{/await}



<style>

</style>