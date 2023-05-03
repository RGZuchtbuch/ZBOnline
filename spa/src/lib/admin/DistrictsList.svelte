<script>
    import {createEventDispatcher, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { user } from '../../js/store.js'
    import DistrictRow from '../district/DistrictListRow.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte'
    import api from '../../js/api.js';


    let district = null;

    const route = meta();
    const dispatch = createEventDispatcher();

    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
            district = filter( response.district );
            console.log("Admin districts root", district );
        } );
    }


    function filter( source ) {
        let district = { id:source.id, name:source.name, moderated:true, children:[] };
        for( let child of source.children ) {
            district.children.push( filter( child ) );
        }
        return district;
    }

    onMount( () => {})

    loadDistricts();
</script>

{#if $user}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Admin {$user.fullname} </h2>
    {#if district}
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <DistrictRow district={district} open={true}/>
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}

<style></style>