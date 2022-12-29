<script>
    import {user} from '../js/store.js';
</script>

<div class='border bg-gray-200 flex flex-row justify-between px-48 border rounded'>
    <div class='flex flex-row gap-x-4'>
        <a href='/'>Das Zuchtbuch</a>
        <a href='/leistungen'>Leistungen</a>
        <a href='/zuechter'>Mein Zuchtbuch</a>
        <a href='/obmann'>Obmann</a>
        <a href='/admin'>Admin</a>
    </div>
    {#if $user}
        <a href='/abmelden'>Abmelden</a>
    {:else}
        <a href='/anmelden'>Anmelden</a>
    {/if}
</div>