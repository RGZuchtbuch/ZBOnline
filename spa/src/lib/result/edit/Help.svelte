<script>
    import Help from '../../common/Help.svelte';
    import { createEventDispatcher } from 'svelte';

    const dispatch = createEventDispatcher();

    function onHelp() {
        dispatch( 'help', false );
    }


    //
</script>


<Help title='Eingabe Leistungen' on:help>
    Das eingeben der Leistungdaten ist eine Aufgabe für der Obman. Dies kann auf Landesverband- oder Kreisverbandsebene, aber auch auf BDRG ebene.
    Wichtig ist es zu wissen das für den LV Bericht die LV und die KV Datendaten zusammen gefügt werden. Für den Bericht Mann muss also zuvor wissen wer welche Daten eingibt um doppelte Daten zu vermeiden.<br>
    <hr>
    In dieser Seite gibt mann die Leistungsdaten für den jeweiligen Verband ein in einige Schritte:<br>
    <ol>
        <li>Wähle das Zuchtjahr der Leistungen (NICHT VERGESSEN!)</li>
        <li>Wähle für welche Sparte mann Daten eingeben möchte </li>
        <li>Wähle für welche Zuchtbuchgruppe mann Daten eingeben will ( nur für Leger, Tauben kennen keine Gruppen</li>
    </ol>
    <br>
    Mann sieht die Rasse der gewählten Sparte.<br>
    Wähle die Rasse für den mann die Leistungen eingeben will, durch klicken der Name.<br>
    Die Farbenschläge zeigen sich zum eingeben der Daten. Für die Tauben werden die Farbenschläge zusammen genommen als einen Eingang.<br>
    <br>
    Es gibt die Daten für:

    <ul>
        <li>Zucht:
            <ul>
                <li>Zuchten: Zahle der Zuchten oder Züchter.</li>
                <li>Stämme / Paare: Zahle der Stämme oder Paare (Tauben).</li>

            </ul>
        </li>
        <br>
        <li>Legeleistung (Nicht bei Tauben ):
            <ul>
                <li>Hennen: Zahl der legenden Hennen.</li>
                <li>Eier / Jahr: Durchschnitt Legeleistung im Jahr (Eier / Tage / Hennen * 274)</li>
                <li>Gewicht: Durchschnitt Eiergewicht der gelegten Eier</li>
            </ul>
        </li>
        <br>
        <li>Brutleistung:
            <ul>
                <li>Alle außer Tauben:
                    <ul>
                        <li>Eingelegt: Zahl der eingelegten Eier.</li>
                        <li>Befruchtet: Zahl der befruchteten Eier, kann nicht mehr sein als Eingelegt!</li>
                        <li>Geschlüpft: Zahl der geschlüpften Küken, kann nicht mehr sein als Befruchtet!</li>
                    </ul>
                </li>
                <li>Tauben:
                    <ul>
                        <li>Küken: Zahle der geschlupften Küken, gibt die Küken / Paar wenn auch Paare eingegeben sind.</li>
                    </ul>
                </li>
            </ul>
        </li>
        <br>
        <li>Schauleistung:
            <ul>
                <li>Tiere: Zahl der ausgestellten Tieren.</li>
                <li>Note: Durchschnitt Note, U oder O gibt nicht 0 sondern 89 Punkte damit der Durchschnitt Sinn macht!</li>
            </ul>
        </li>

    </ul>
    <br>
    Jetzt kann mann von Links nach Rechts die Daten eingeben und mit 'tab' oder Maus zum nächsten Felt springen.<br>
    <br>
    Während mann einem Feld bearbeitet wird die eingabe kontroliert und es erscheint eine rotfarbige Warmung unter dem Feld wenn es ein Fehler gibt.<br>
    <br>
    Nach Änderungen sieht mann ganz recht die Schaltfläche erscheinen. Dieser hat drei verschiedene Farben und bedeutungen bei auswahl:
    <br>
    <ul>
        <li>Rot mit "-" : Felhler in den Daten, kann nicht speichern</li>
        <li>Gelb mit "X": Das Feld 'Zuchten' is leer, dies löscht dieser Zeile</li>
        <li>Grün mit "&#10003;": Daten Ok, speichern</li>
    </ul>
    <br>
    Mit 'tab' kan mann zu der Schaltfläche springen und mit der Leertaste oder Enter auswählen. Mit der Maus gehts natürlich auch.<br>
    Nachdem mann gespeichert oder gelöscht hat, verschwindet die Schaltfläche wieder (grau), da er keine Änderungen meht gibt.<br>
    <br>
    Jetzt geht es weiter zur nächten Farbenschlag oder Rasse.<br>
    <br>
    Wenn mann nicht sofort speichert, besteht das Risiko das mann eine andere Seite wählt und die Eingaben verloren gehen. Daher ist geraten jeder Zeile direct zu speichern.<br>
    Als Warnung zeigt der Tabellenkopf ganz rechts eine rote Zahle wenn er noch Eingaben zum speichern gibt.
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