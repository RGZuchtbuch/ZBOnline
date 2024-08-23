<script>
    import { standard, user } from './js/store.js'


    import Router from './Router.svelte';
    import api from './js/api.js';
    import Standard from './lib/standard/Standard.svelte';

    api.standard.get().then( response => { // load standard here async into store
        standard.set( response.standard );
    } );

</script>

<main class='h-full flex flex-col'>
    <Router />
</main>


<style>


</style>
