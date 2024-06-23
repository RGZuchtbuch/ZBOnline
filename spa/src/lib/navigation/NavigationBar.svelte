<script>
    import {meta, router} from 'tinro';
    import {user} from '../../js/store.js';

    let remainingMessage = '?';
    let blink = true;

    let route = meta();

    function toTimeString( millis ) {
        const hours = Math.floor( millis / (60*60*1000) );
        millis %= 60*60*1000;
        const minutes = Math.floor( millis / (60*1000) );
        millis %= 60*1000;
        const seconds = Math.floor( millis / 1000 );
        return hours+':'+(minutes < 10 ? '0':'')+minutes+':'+(seconds<10?'0':'')+seconds;
//        return hours+':'+minutes+':'+seconds;
    }

    // ensures attention if login is timing out
    function expired() {
        if( $user ) {
            const exp = new Date( $user.exp * 1000 ); // needs millis io secs
            const now = new Date(); // millis
            const millis = exp - now;
            remainingMessage = toTimeString( millis );
            if( millis < 1 * 60 * 60 * 1000 ) { // last hour
                blink = ! blink;
            }
            if( millis < 1 ) {
                router.goto('/anmelden');
            }
        }
    }

    setInterval( expired, 1000 );

</script>

<div class='w-full border border-gray-400 bg-header px-2 py-1 flex flex-row gap-2 text-white no-print'>
    <div class='w-48'></div>
    <div class='grow gap-x-4 pl-8'>
        <a class:active={ $route.url.startsWith('/zuchtbuch') } href='/zuchtbuch'>Das Zuchtbuch</a>
        <a class:active={ $route.url.startsWith('/verband') } href='/verband'>Verbände</a>
        {#if STANDARDENABLED}<a class:active={ $route.url.startsWith('/standard') } href='/standard'>Standard</a>{/if}

        {#if RESULTSENABLED}<a class:active={ $route.url.startsWith('/leistungen') } href='/leistungen'>Leistungen</a>{/if}
        {#if BREEDERENABLED && $user } <a class:active={ $route.url.startsWith('/zuechter') } href='/zuechter'>Mein Zuchtbuch</a> {/if}
        {#if MODERATORENABLED && $user && $user.moderator.length > 0 } <a class:active={ $route.url.startsWith('/obmann') } href='/obmann'>Obmann</a> {/if}
        {#if ADMINENABLED && $user && $user.admin } <a class:active={ $route.url.startsWith('/admin') } href='/admin'>Admin</a> {/if}
    </div>
    <div class='w-64'>
        {#if $user}
            <a href='/abmelden'>Abmelden</a> {#if blink}({remainingMessage}){/if}
        {:else}
            <a href='/anmelden'>Anmelden</a>
        {/if}
    </div>
</div>

<style>
    a {
        @apply px-2 border-b border-b-gray-600 font-semibold text-white;
    }
    .active {
        @apply border-b-2 border-b-white;
    }
</style>