<script>
    import { meta } from 'tinro';
    import { user } from './../../js/store.js'

    let route = meta();

</script>


<div class='flex flex-row gap-x-4'>
    <div class='w-6'></div>

    <div class='grow'>Name</div>

    {#if $user && $user.moderator}
        <div class='cursor-pointer' title='Neues Mitglied'>
            <a href={route.match+'/0/daten'}>[+]</a>
        </div>
    {/if}
</div>
