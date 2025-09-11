<script>
    import Help from '../../common/Help.svelte';
    import { createEventDispatcher } from 'svelte';

    const dispatch = createEventDispatcher();

    function onHelp() {
        dispatch( 'help', false );
    }


    //
</script>


<Help title='Eingabe Leistungen Anleitung' on:help>
    Das eingeben der Leistungdaten ist eine Aufgabe für der Obmann.<br>
    Dies kann auf BDRG, Landerverband oder Kreisverband ( im LV Weser-Ems ) ebene.<br>
    Alle Leistungsmeldungen auf jeder Ebene und die darunter liegende, werden zusammen gerechnet. Leistungsdaten sollen also nur einmahl auf eine ebene eingegeben werden.
    <hr>
    In dieser Seite gibt mann die Leistungsdaten für den jeweiligen Verband ein in einige Schritte:<br>
    <ol>
        <li>Wähle das Zuchtjahr der Leistungen (NICHT VERGESSEN!)</li>
        <li>Wähle für welche Sparte mann Daten eingeben möchte </li>
        <li>Wähle für welche Zuchtbuchgruppe mann Daten eingeben will ( nur für Leger, Tauben kennen keine Gruppen</li>
    </ol>
    Für die AOC-Klasse kann mann die AOC Klasse als Sparte Wählen und dar die Sparte mit Rasse wählen und den Farbenschlag als Tekst eingeben. Dieser neuer Farbenschlag wird and die unterstehende Liste hinzugefügt wo mann die Leistungsdaten dann eingeben kann.<br>
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
                <li>Paare: Zahle der Tauben Paare.</li>
            </ul>
        </li>
        <br>
        <li>Legeleistung (Nicht bei Tauben ):
            <ul>
                <li>Eier / Jahr: Durchschnitt Legeleistung im Jahr<br>Oft werden nur einige Monate am Anfang des Jahres die Eier gesammelt und gezählt. Darum wird dieser Formel genützt: <br>
                    Legeleistung = 274 * Eier / Tage / Hennen
                </li>
                <li>Gewicht: Durchschnitt Eiergewicht der gesammelten Eier, Ausreißer (zb. obere 5% und untere 5%) kann mann evt. weglassen</li>
            </ul>
        </li>
        <br>
        <li>Brutleistung:
            <ul>
                <li>Alle außer Tauben:
                    <ul>
                        <li>Eingelegt: Zahl der eingelegten Eier.</li>
                        <li>Befruchtet: Zahl der befruchteten Eier, kann nicht mehr sein als Eingelegt!</li>
                        <li>Geschlüpft: Zahl der geschlüpften Küken, kann nicht mehr sein als Befruchtet oder Eingelegt!</li>
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
    Um das zusammenrechnen zu vereinfachen is das "<strong>Hinzufügen</strong>" dar mittels den [+] Knopf.<br>
    Da kan man pro Züchter die Daten eingeben und wenn Komplet, zum gesamten zählen.<br>
    Aber aufgepasst, bei Fehleingaben kan mann dies nicht rückgängig machen.<br>
    Zusätzlich kann mann auch die Legeleistung berechnen über den zweiten [+] Knopf im "Hinzufügen". Hier kann man die sammelzeit eingeben, die zahl der Hennen und gesammelten Eier und an das "Hinzufügen" einfügen.
    <br>
    Während mann einem Feld bearbeitet wird die eingabe validiert und es erscheint eine rotfarbige Warnung unter dem Feld wenn es ein Fehler gibt.<br>
    <br>
    Die Daten werden automatisch gespeichert, etwa eine sekunde nach der letzte Änderung, wenn valide, die Flagge hinter der zeile zeigt dies an: Rot Fehler, Orange geändert, Grün Gespeichert
    <br>
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