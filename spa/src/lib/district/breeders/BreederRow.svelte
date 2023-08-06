<script>
    import {meta} from "tinro";
    import api from '../../../js/api.js';
    import { dat, txt } from '../../../js/util.js';

    import Button from '../../common/input/Button.svelte';
    import DateInput from '../../common/input/Date.svelte';
    import Select from '../../common/input/Select.svelte';
    import Text from '../../common/input/Text.svelte';

    export let breeder = null;
    export let showInactive = false;

    let showDetails = false;
    let edit = false;
    let changed = false;
    let details = null;
    let clubs = null;
    let rootDistrict = null;

    const route = meta();

    function onToggleDetails() {
        console.log( 'Toggle Details' );
        showDetails = ! showDetails;
        if( showDetails ) {
            loadDetails(breeder.id);
        }
    }

    function onToggleEdit() {
        edit = ! edit;
        if( edit ) {
            if( ! clubs ) loadClubs();
            if( ! rootDistrict ) loadDistricts();
        }
    }


    function onChange(event) {
        console.log( 'changed' );
        changed = true;
    }

    function onSubmit(event) {
        console.log( 'Submit Breeder details');
        changed = false;
        api.breeder.post( details ).then( response => {
            const id = response.id;
            details.id = id;
            breeder.id = id;
            breeder.name = details.name;
            breeder.clubId = details.clubId;
        })
    }

    function loadDetails( id ) {
        console.log( 'Breeder', breeder );
        if( id === null ) { // new breeder has id null
            details = Object.assign( {}, breeder );
            console.log( 'New Details', details );
        } else {
            api.breeder.get( id ).then( response => {
                details = response.breeder;
                console.log( 'Details', details );
            })
        }
        loadClubs( breeder.districtId );
    }

    function loadClubs( districtId ) {
        api.district.clubs.get( districtId ).then( response => {
            clubs = response.clubs;
        });
    }

    function loadDistricts( district ) {
        console.log( 'Load Clubs' );
        api.district.children.get( district.id ).then( response => {
            rootDistrict = response.district;
            console.log('District', rootDistrict );
        });
    }

    function activeMember( member ) {
        if( member.end ) {
            let now = new Date();
            let end = new Date( member.end );
            return end > now;
        }
        return true;
    }
</script>

{#if showInactive || activeMember( breeder ) }
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>
        <div class='w-4'>{breeder.id}</div>

        <a class='w-56' href={route.match+'/'+breeder.id}>
            { txt( breeder.lastname )+', '+txt( breeder.firstname )+' '+txt( breeder.infix ) }
        </a>

        <div class='w-36 whitespace-nowrap'>{txt( breeder.clubName )}</div>
        <div class='w-64 whitespace-nowrap'>{txt( breeder.districtName )}</div>
        <div class='w-24'> {dat(breeder.start)} </div>
        <div class='w-24'> {dat(breeder.end)} </div>
        <div class='grow'> </div>
        {#if showDetails}
            <div class='cursor-pointer text-red-600 px-2' on:click={onToggleDetails} title='schließen'>&#8505;</div>
        {:else}
            <div class='cursor-pointer text-green-600 px-2' on:click={onToggleDetails} title='bearbeiten'>&#8505;</div>
        {/if}

    </div>

{/if}

