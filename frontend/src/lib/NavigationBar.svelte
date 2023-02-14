<script>
    import {user} from '../js/store.js';
</script>

<div class='border border-gray-400 bg-header px-2 py-1 flex flex-row gap-2 no-print'>
    <div class='w-48'></div>
    <div class='grow gap-x-4 pl-8'>
        <a href='/seite/1'>Das Zuchtbuch</a>
        <a href='/standard'>Standard</a>
        <a href='/leistungen'>Leistungen</a>
        {#if $user } <a href='/zuechter'>Mein Zuchtbuch</a> {/if}
        {#if $user && $user.moderator.length > 0 } <a href='/obmann'>Obmann</a> {/if}
        {#if $user && $user.admin } <a href='/admin'>Admin</a> {/if}
    </div>
    <div class='w-48'>
        {#if $user}
            <a href='/abmelden'>Abmelden</a>
        {:else}
            <a href='/anmelden'>Anmelden</a>
        {/if}
    </div>
</div>

<style>
    a {
        @apply px-2 border border-gray-600 rounded bg-button;
    }
</style>