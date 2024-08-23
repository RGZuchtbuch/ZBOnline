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
        api.district.get( id )
            .then( response => {
                district.set( response.district );
            })
            .catch( e => {
                alert('Houston we have got a problem loading district');
                district.set( null );
            } );
    }

    $: loadDistrict( id );
</script>

{#if $district}
    <slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{/if}




