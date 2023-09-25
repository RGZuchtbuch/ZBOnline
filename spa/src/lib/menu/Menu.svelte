<script>
    import {Route, meta, router} from 'tinro';
    import {user} from '../../js/store.js';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import AdminMenu from "./AdminMenu.svelte";
    import BreederMenu from "./BreederMenu.svelte";
    import ClubMenu from "./ClubMenu.svelte";
    import DistrictMenu from "./DistrictMenu.svelte";
    import ModeratorMenu from "./ModeratorMenu.svelte";
    import StandardMenu from "./StandardMenu.svelte";
    import InfoMenu from "./InfoMenu.svelte";

    // w-64 border rounded flex flex-col
</script>


<div class='w-64 mt-12 flex flex-col border rounded no-print'>


    <div class='header'> Menü für {$user ? $user.firstname : 'Besucher' } </div>
    <div class='body scrollbar'>

        <Route path='/zuchtbuch/*'> <InfoMenu /> </Route>
        <Route path='/standard/*'> <StandardMenu /> </Route>
        <Route path='/leistungen/*'> <h4>Zuchtleistungen ←</h4> </Route>

        <Route path='/obmann/*'>
            {#if $user && $user.moderator.length > 0 }
                <ModeratorMenu />
                <Route path='/verband/:districtId/*' let:meta>
                    <DistrictMenu districtId={meta.params.districtId} />

                    <Route path='/zuechter/:breederId/*' let:meta>
                        <BreederMenu districtId={meta.params.districtId} breederId={meta.params.breederId} />
                    </Route>

                    <Route path='/verein/:clubId/*' let:meta>
                        <ClubMenu districtId={meta.params.districtId} clubId={meta.params.clubId} />
                    </Route>
                </Route>
            {/if}
        </Route>

        <Route path='/admin/*' >
            {#if $user && $user.admin }
                <AdminMenu />

                <Route path='/verband/:districtId/*' let:meta>
                    <DistrictMenu districtId={meta.params.districtId}/>

                    <Route path='/zuechter/:breederId/*' let:meta>
                        <BreederMenu districtId={meta.params.districtId} breederId={meta.params.breederId} />
                    </Route>
                </Route>
            {/if}
        </Route>

    </div>
</div>


<style>
    .title {
        @apply text-center;
    }
    .header {
        @apply border rounded-t border-gray-400 px-4 py-2 bg-header font-bold text-white text-center;
    }
    .body {
        @apply border rounded-b border-gray-400 bg-gray-100 p-2 text-black overflow-y-scroll;
    }
</style>