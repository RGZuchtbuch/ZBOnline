<script>
    import Help from '../common/Help.svelte';
    import { createEventDispatcher } from 'svelte';

    const dispatch = createEventDispatcher();

    function onHelp() {
        dispatch( 'help', false );
    }


    //
</script>


<Help title='Zucht Leistungen Anleitung' on:help>
    TODO

    <br>
    Dieser Anleitung kann mann wieder verschwinden lassen mit dem Kreuz oben rechts.
</Help>

<style>

    ol {
        @apply list-inside list-decimal;
    }

    ul {
        @apply list-inside list-disc;
    }

    li {
        @apply pl-4;
    }

    dl {
        @apply pl-4;
    }

    dt, dd {
        @apply pl-4;
    }

</style>