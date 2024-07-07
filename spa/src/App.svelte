<script>
    //  import logo from './assets/svelte.png'
    import {meta, Route} from 'tinro';

    import { breeder, district, standard, user } from './js/store.js'


    import Router from './Router.svelte';
    import api from './js/api.js';
    import Standard from './lib/standard/Standard.svelte';

    api.standard.get().then( response => { // load standard here async into store
        standard.set( response.standard );
        console.log( 'Standard loaded', response.standard );
    } );

</script>

<main class='h-full flex flex-col'>
    <Router />
</main>




<style>


</style>
