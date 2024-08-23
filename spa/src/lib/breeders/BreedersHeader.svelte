<script>
    import { meta } from 'tinro';
    import { user } from './../../js/store.js'

    export let showInactives = false;

    let route = meta();

</script>


<div class='flex flex-row gap-x-4'>
    <div class='w-4'>#</div>
    <div class='w-56'>Namen</div>
    <div class='w-36'>Ortsverein</div>
    <div class='w-64'>ZB Verband</div>
    <div class='w-24'>Seit</div>
    <div class='w-16'>Inaktief</div> <div class='w-6 h-6 text-center'> <input class='cursor-pointer' type='checkbox' bind:checked={showInactives}> </div>
    <div class='grow'></div>
    {#if $user && $user.moderator}
        <div class='cursor-pointer' title='Neues Mitglied'>
            <a href={route.match+'/0/details'}>[+]</a>
        </div>
    {/if}
</div>
