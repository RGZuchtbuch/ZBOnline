<script>
    import {Route, router, meta} from 'tinro';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import ClubDetails from './ClubDetails.svelte';

    export let districtId;
    export let clubId;
    let club = null;
    let route = meta();

    function loadClub( id ) {
        if( id && id>0 ) { // valid id, else new
            api.district.get(id).then(response => {
                //breeder.set( response.breeder );
                club = response.district;
            });
        } else { // new
            club = {
                id:null, parentId:districtId,
                name:null, fullname:null, short:null,
                latitude:null, longitude:null,
                level:'OV',
                moderatorId:null
            };;
            router.goto( route.match+'/daten' );
        }
        console.log( 'Club', club );
    }

    $: loadClub( clubId );

</script>

{#if club}
    <Route path='/' redirect={route.match+'/daten'} />

    <Route path='/daten' let:meta>
        <ClubDetails {club} />
    </Route>
{/if}