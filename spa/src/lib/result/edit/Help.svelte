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
        <li>Wähle das Zuchtjahr der Leistungen</li>
        <li>Wähle für welche Sparte mann Daten eingeben möchte </li>
        <li>Wähle für welche Zuchtbuchgruppe mann Daten eingeben will ( nur für Leger, Tauben kennen keine Gruppen</li>
    </ol>
    <br>
    Mann sieht die Rasse der gewählten Sparte.<br>
    Wähle die Rasse für den mann die Leistungen eingeben will, durch klicken.<br>
    Die Farbenschläge zeigen sich zum eingeben der Daten. Für die Tauben werden die Farbenschläge zusammen genommen als einen Eingang.<br>
    <br>
    Jetzt kann mann von Links nach Rechts die Daten eingeben und mit 'tab' oder Maus zum nächsten springen.<br>
    Wenn mann von einem Feld wegspringt wird die eingabe kontroliert und es erscheint eine rotfarbige Warmung unter dem Feld wenn es ein Fehler gibt.<br>
    <br>
    Nach Änderungen sieht mann ganz recht die Schaltfläche erscheinen. Dieser hat drei verschiedene Farben und bedeutungen bei auswahl:
    <br>
    <ol>
        <li>Rot mit - : Felhler in den Daten, kann nicht speichern</li>
        <li>Gelb mit X: Das Feld 'Zuchten' is leer, dies löscht dieser Zeile</li>
        <li>Grün mit &#10003;: Daten Ok, speichern</li>
    </ol>
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
        @apply list-inside list-disc;
    }

</style>