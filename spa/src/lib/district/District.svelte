<script>
    import {Route, meta} from 'tinro';
    import { getContext } from 'svelte';
    import api from '../../js/api.js';
    //import { district } from '../../js/store.js';

    export let id = null;

    let route = meta();

    const district = getContext( 'district' );


    function loadDistrict( id ) {
        district.set( null );
        console.log( 'District load', id );
        api.district.get( id )
            .then( response => {
                district.set( response.district );
                console.log( 'got District', response.district );
            })
            .catch( e => {
                alert('Houston we have got a problem loading district');
                district.set( null );
            } );
    }

    console.log( 'District route', route );

    $: loadDistrict( id );
</script>

{#if $district}
    <slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{/if}




