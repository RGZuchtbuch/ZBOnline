<script>
    import {getContext} from 'svelte';
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js';

    import Page from '../common/Page.svelte';
    import {dat, txt} from '../../js/util.js';

    const district = getContext( 'district' );
    const breeder  = getContext( 'breeder' );



    let breeders = null;
    let showInactives = false; // should ex members be included ?

    const route = meta();

    function loadBreeders( forDistrict ) {
        console.log( 'DistrictBreeders load', forDistrict );
        if( forDistrict ) {
            console.log( 'DistrictBreeders load', forDistrict );
            api.district.breeders.get( forDistrict.id )
                .then( response => {
                    breeders = response.breeders;
                    breeder.set( null );
                })
                .catch( e => {
                    alert('Oeps loading breeders for district');
                })
        }
    }

    function activeMember( breeder ) {
        console.log( 'Breeder', breeder );
        if( breeder.end ) { // if end and end before now
            let now = new Date();
            let end = new Date( breeder.end );
            return end > now;
        }
        return true;
    }

    function addBreeder() {
        breeders = [         {
            id: null, zbnr:null,
            firstname: null, infix: null, lastname: null,
            districtId: district.id, districtName: null, clubName: null,
            start: null, end: null
        }, ...breeders ];
    }

    $: loadBreeders( $district ); // each time district changes in url, could be null !
    console.log( 'DistrictBreeders', $district);
</script>

<Page>
    <div slot='title'> Zuchtbuchmitglieder in Verband </div>
    <div slot='header' class='flex flex-row gap-x-4 px-2'>
        <div class='w-12'>ZbNr</div>
        <div class='w-56'>Name</div>
        <div class='w-36'>Ortsverein</div>
        <div class='w-64'>ZB Verband</div>
        <div class='w-24'>Seit</div>
        <div class='w-16'>Inaktief</div> <div class='w-6 h-6 text-center'> <input class='cursor-pointer' type='checkbox' bind:checked={showInactives}> </div>
        <div class='grow'></div>
        {#if $user && $user.moderator}
            <div class='cursor-pointer' title='Neues Mitglied'>
                <button type='button' on:click={addBreeder}> + </button>
            </div>
        {/if}
    </div>
    <div slot='body' class=''>
        {#if breeders}
            {#each breeders as breeder (breeder.id) }
                {#if showInactives || activeMember( breeder ) }
                    <div class='flex flex-row border-b border-gray-400 gap-x-4 px-2 my-2'>
                        <div class='w-12 text-2xs'>[{breeder.id}]</div>

                        <a class='w-56' href={route.match+'/'+breeder.id+'/meldung'}>
                            { txt( breeder.lastname )+', '+txt( breeder.firstname )+' '+txt( breeder.infix ) }
                        </a>

                        <div class='w-36 whitespace-nowrap'>{txt( breeder.clubName )}</div>
                        <div class='w-64 whitespace-nowrap'>{txt( breeder.districtName )}</div>
                        <div class='w-24'> {dat(breeder.start)} </div>
                        <div class='w-24'> {dat(breeder.end)} </div>
                    </div>
                {/if}
            {/each}
        {/if}
    </div>
</Page>


<style>

</style>