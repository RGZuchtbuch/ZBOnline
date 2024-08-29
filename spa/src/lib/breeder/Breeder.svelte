<script>
    import {getContext} from 'svelte';
    import { meta, router } from 'tinro';
    import api from '../../js/api.js';

    export let id = null;

    const district = getContext( 'district' ); // store
    const breeder  = getContext( 'breeder' ); // store

    //let route = meta();


    function update( districtId, breederId ) {
        breeder.set( null );

        if( breederId ) { // valid id, else new
            api.breeder.get( breederId )
                .then( response => {
                    breeder.set( response.breeder );
                })
                .catch( e => {
                    alert( 'Oops loading breeder' );
                });

        } else if( districtId ) { // new
            breeder.set( {
                id:null, firstname:null, infix: null, lastname:null, active:true,
                districtId: districtId,
//                district: $district, // needed ?
                club:null,
                start: null, end: null,
                email: null,
                info: null
            } );
            //router.goto( route.match+'/daten' );
        }
    }

    $: update( $district.id, id );

</script>

{#if $breeder}
    <slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{/if}