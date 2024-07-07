<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';
    import {breeder, district, user} from '../../js/store.js';

    export let id = null;

    let route = meta();


    function loadBreeder( id ) {
        console.log( 'Breeder load', id );
        breeder.set( null );
        if( id ) { // valid id, else new
            api.breeder.get( id )
                .then( response => {
                    //breeder.set( response.breeder );
                    breeder.set( response.breeder );
                    console.log( 'Load breeder', $breeder);
                })
                .catch( e => {
                    alert( 'Oops loading breeder' );
                });

        }
/*        else { // new
            breeder.set( {
                id:null, firstname:null, infix: null, lastname:null, active:true,
                districtId: forDistrict.id,
                district: forDistrict, // needed ?
                club:null,
                start: null, end: null,
                email: null,
                info: null
            } );
            //router.goto( route.match+'/daten' );
        }
*/
    }

    $: loadBreeder( id );
</script>

{#if $breeder}
    <slot>Hier sollte man nur enden wenn mann angemeldet und Obmann ist</slot>
{/if}