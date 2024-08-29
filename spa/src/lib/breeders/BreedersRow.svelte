<script>
    import {meta} from "tinro";
    import { dat, txt } from '../../js/util.js';

    export let breeder = null;
    export let showInactives = false;

    const route = meta();

    function activeMember() {
        if( breeder.end ) { // if end and end before now
            let now = new Date();
            let end = new Date( breeder.end );
            return end > now;
        }
        return true;
    }
</script>

{#if showInactives || activeMember() }
    <div class='flex flex-row gap-x-4 border-b border-gray-300 my-2'>
        <div class='w-4 text-xs'>{breeder.member}</div>

        <a class='w-56' href={route.match+'/'+breeder.id+'/meldung'}>
            { txt( breeder.lastname )+', '+txt( breeder.firstname )+' '+txt( breeder.infix ) }
        </a>

        <div class='w-36 whitespace-nowrap'>{txt( breeder.clubName )}</div>
        <div class='w-64 whitespace-nowrap'>{txt( breeder.districtName )}</div>
        <div class='w-24'> {dat(breeder.start)} </div>
        <div class='w-24'> {dat(breeder.end)} </div>
    </div>
{/if}
