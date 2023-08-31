<script>
    import {createEventDispatcher, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { user } from '../../../js/store.js'
    import api from '../../../js/api.js';

    import Entry from './Entry.svelte';

    let logs = [];
    let from = 0;
    let count = 100; // entries per page

    const route = meta();
    const dispatch = createEventDispatcher();

    function loadLog() {
        api.log.getNext( from, count ).then( response => {
            logs = response.logs;
        })
    }
    function onBegin() {
        if( from > 0 ) {
            from = 0;
            loadLog();
        }
    }
    function onPrev() {
        if( from > 0 ) {
            from -= count;
            from = from < 0 ? 0 : from;
            loadLog();
        }
    }
    function onNext() {
        if( logs.length === count ) {
            from += count;
            from = from < 0 ? 0 : from;
            loadLog();
        }
    }
    function onEnd() {
        loadLog();
    }

    onMount( () => {})

    loadLog();
</script>

{#if $user && $user.admin}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-white text-center text-xl print'>System Log</h2>
    <div class='w-256 flex border-gray-600 bg-header gap-x-4 text-xl text-white font-semibold justify-center'>
        <div on:click={onBegin} title='To begin, the most recent entry'>&larrb;</div>
        <div on:click={onPrev} title='Previous entries'>&larr;</div>
        <div on:click={onNext} title='Previous entries'>&rarr;</div>
    </div>
    <div class='w-256 flex bg-header text-white font-semibold p-2'>
        <div class='w-12'>#</div>
        <div class='w-36'>Time</div>
        <div class='w-16'>Method</div>
        <div class='w-72'>URI</div>
        <div class='w-48'>Query</div>
        <div class='w-48'>Body</div>
        <div class='w-12'>UserId</div>
    </div>
    <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 scrollbar p-2'>
        {#each logs as entry}
            <Entry {entry} />
        {/each}
    </div>
{:else}
    Geht nicht, Sollte nicht
{/if}

<style></style>