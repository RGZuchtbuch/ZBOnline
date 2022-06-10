<script>
    import { active, meta, router, Route } from 'tinro';
    import api from '../scripts/api.js';
    import { user } from '../scripts/store.js'

    import Box from '../components/Box.svelte';
    import District from '../components/District.svelte';
    import Districts from '../components/Districts.svelte';


    if( ! $user ) router.goto( '/login')

    console.log( 'User', $user );

</script>


<Box legend='Admin'>
    <div class='w-128'>Admin stuff</div>
    <Districts promise={api.district.tree(1)} legend='Verbände und Vereine'/>
</Box>