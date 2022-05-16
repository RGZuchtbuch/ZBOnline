<script>
    import { slide } from 'svelte/transition';
    import { Router, Route, Link, navigate } from 'svelte-navigator';
    import api from '../../../scripts/api.js';
    import { user } from '../../../scripts/store.js'
    import Districts from '../components/Districts.svelte';
    import BreederPage from './BreederPage.svelte';

    export let id;

    function onDistrictSelect( districtId ) {
        console.log( 'Moderator:onDistrictSelect', districtId);
        navigate( '/moderator/'+id+'/district/'+districtId );
    }

    console.log( 'run homepage' )

</script>

<fieldset class='bordered w-256'>
    <legend> Obmann </legend>
    <div class='flex flex-col'>
        <Route path='/'>
            <Districts promise={api.getDistricts( $user.id )} {onDistrictSelect} />
        </Route>
        <Route path='district/:districtId/*' let:params>
            District breeders <Link to='breeder/:10'>Tester</Link>
            <Route path='breeder/:breederId' let:params> <BreederPage id={params.breederId} /> </Route>
        </Route>
    </div>
</fieldset>


<style>

</style>