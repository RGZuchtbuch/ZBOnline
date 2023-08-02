<script>
    import {Route, meta, router} from 'tinro';
    import {user} from '../../js/store.js';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import AdminMenu from "./AdminMenu.svelte";
    import DistrictMenu from "./DistrictMenu.svelte";
    import BreederMenu from "./BreederMenu.svelte";
    import ModeratorMenu from "./ModeratorMenu.svelte";
    import StandardMenu from "./StandardMenu.svelte";
    import InfoMenu from "./InfoMenu.svelte";

</script>

<div class='w-64 mt-32 p-4 border rounded flex flex-col no-print'>
    {#if $user}
        <h3>Hallo {$user.firstname}</h3>
    {:else}
        <h3>Hallo Besucher</h3>
    {/if}
    <hr>
    <Route path='/zuchtbuch/*'>
        <InfoMenu />
    </Route>
    <Route path='/standard/*'>
        <StandardMenu />

    </Route>
    <Route path='/leistungen/*'>
        <h4>Zuchtleistungen ←</h4>
    </Route>

    {#if $user && $user.moderator.length > 0 }
        <Route path='/obmann/*'>
            <ModeratorMenu />
            <Route path='/verband/:districtId/*' let:meta>
                <DistrictMenu districtId={meta.params.districtId} />

                <Route path='/zuechter/:breederId/*' let:meta>
                    <BreederMenu districtId={meta.params.districtId} breederId={meta.params.breederId} />
                </Route>
            </Route>
        </Route>
    {/if}
    {#if $user && $user.admin }
        <Route path='/admin/*' >
            <AdminMenu />

            <Route path='/verband/:districtId/*' let:meta>
                <DistrictMenu districtId={meta.params.districtId}/>

                <Route path='/zuechter/:breederId/*' let:meta>
                    <BreederMenu districtId={meta.params.districtId} breederId={meta.params.breederId} />
                </Route>
            </Route>
        </Route>
    {/if}

</div>

<style>

</style>