<script>
    import Box from '../components/Box.svelte';
    import {user} from "../scripts/store";
    import api from '../scripts/api.js';

    import Districts from '../components/Districts.svelte';

    let currentUser = null;
    user.subscribe( value => {
        currentUser = value;
        console.log( currentUser );
    });

    console.log( 'Obmann' );

</script>



<Box legend='Obmann'>
    <div class='flex flex-col'>
        <Districts promise={api.moderator.districts( currentUser.id )} legend='Verbände und Vereine'/>
        <div class='w-128'>Bereich: district list, select</div>
        <div class='w-128'>Züchter: needs district: list, new, disable/remove</div>
        <div class='w-128'>Meldungen: needs district & year: list new, remove</div>
        <div class='w-128'>Stalplaketten: needs district: list per year</div>
        <div class='w-128'>Auswertungen: need district: list per year</div>
    </div>
</Box>