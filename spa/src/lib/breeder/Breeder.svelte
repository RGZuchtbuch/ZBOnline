<script>
    import {getContext} from 'svelte';
    import { meta, router } from 'tinro';
    import api from '../../js/api.js';

    export let id = null;

    const district = getContext( 'district' ); // store
    const breeder  = getContext( 'breeder' ); // store

    //let route = meta();


    function updateBreeder(  ) {
        breeder.set( null );

        if( id ) { // valid id, else new
            api.breeder.get( id )
                .then( response => {
                    //breeder.set( response.breeder );
                    breeder.set( response.breeder );
                })
                .catch( e => {
                    alert( 'Oops loading breeder' );
                });

        } else if( $district ) { // new
            breeder.set( {
                id:null, firstname:null, infix: null, lastname:null, active:true,
                districtId: $district.id,
                district: $district, // needed ?
                club:null,
                start: null, end: null,
                email: null,
                info: null
            } );
            //router.goto( route.match+'/daten' );
        }
    }

    $: updateBreeder( $district, id );

    console.log( 'Breeder', id, breeder );

</script>

{#if $breeder}
    <slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{/if}