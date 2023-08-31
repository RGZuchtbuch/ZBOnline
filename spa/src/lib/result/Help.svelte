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
    <p>
        Hier werden in Grafiken, Karten und Tabellen die verschiedenste Leistungen gezeigt.<br>
        Man kann sich die Leistung wählen und der Verband und das Jahr.
        Die kann mann eingrenzen damit mann die Sparte und optional die Rasse und Farbenschlag wählt. Eine '*' heist alle Rassen und alle Farbenschläge.
    </p>
    <p>
        Nachdem mann seine Wahl getroffen had zeigen sich die verschiedene Grafiken, mitunter eine Timeline mit Leistung pro Jahr und eine Karte mit die Leistung pro Landesverband.<br>
        Darunter ist die Tabelle mit alle Leistungsdaten im Landesverband für das gewähle Jahr.
    </p>

    <p>
        Dieser Anleitung kann mann wieder verschwinden lassen mit dem Kreuz oben rechts.
    </p>
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