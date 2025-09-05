<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';

	let { district } = $props();

</script>


{#if ctx.user }
	<section class='p-6'>
		<h3 class=''>
			Hallo {ctx.user.firstname}, hier kannst du, als Obmann, was schaffen. ;)
		</h3>
		<p>
			über diese Seiten können die Zuchtleistungen und Zuchtbuchmitglieder und deren Meldungen verwaltet werden.
		</p>

		<h3>Kurzanleitung</h3>
		<p>
			Das Menü enthält die öffentliche Zugänge sowie Zugänge für den Angemeldeten Besucher als Züchter, Obmann oder Admin. <br />
			Der Züchter hat Zugang zu seine Meldungen und Daten, Obleute können für ihren Verband die Leistungsdaten und Züchter verwalten.<br />
			In der zweiten Menüzeile wird gezeigt wo mann im Programm ist, die 'Breadcrumbs', und die Möglichkeiten die man von hier aus hat (Submenü).<br />
		</p>
		<img src='/assets/menu_1.png' alt='Menü vorbild wie im Programm gezeigt, wenn Angemeldet' />
		<p>
			Wie das Blit zeigt, hat sich Eelco angemeldet am 2.9.2025.<br />
			Das Menu sagt aus das er als Züchter, als Obmann und als Admin im Programm arbeiten kann.<br />
			Er ist jetzt als Obmann in seinem Verband und hat Kann weiterfahren mit 'Leistungen' oder 'Züchter'
		</p>
		<img src='/assets/menu_2.jpg' alt='Menü vorbild für "Leistungen"' />
		<p>
			Nachdem jetzt 'Leistungen' ausgewählt worden ist, sieht man alle meldungen für das Ausgewählte Jahr, als Vervandsleistung oder einzelner Stammmeldung. , hat mann die Wahl um Verbandleistungen ein zu geben oder der einzelne Stamm meldung.<br />
			Eine Stammeldung erkennt man an die Züchter Initialen am ende der Zeile. Wenn man dieser Zugang auswähl, kommt man zur Stammmeldung.
			Eine rote Stammeldung heist das dieser Stamm noch nicht vom Obmann begutachtet ist.
		</p>
		<p>
			Wenn mann als Obmann tätig ist, und kurz den Standard durchsehen will, und danach wieder weitermachen will, dann geht das weil man, nachdem mann wieder 'Obmann' wählt, dort weiter geht wo man als Obmann tätig war. Wenn mann wieder zum Obmann Hauptmenü gehen will, wählt man in der 2. Menuzeile wieder 'Obmann'.
		</p>


		<h3>Leistungdaten eingeben</h3>
		<ol class =''>
			Mann hat Zwei möglichkeiten zum eingeben der Leistungsdaten :
			<li class=''>Verbandseingabe pro Rasse und Farbenschlag ( nicht bei Tauben ). Mann mus selbst die gesamtzahlen aus den Meldungen berechnen.</li>
			<li class=''>Einzelne Meldungen als Stamm/Paar eingeben. Hier mus man mert Daten eingeben, braucht dafür aber nichts zu berechnen, was auch von Vorteil ist wenn Nachmeldungen kommen. Dies ist auch nützling für die Bewertung der Zuchtbuchschaustämme.
				Aber, jeder Meldung nur auf die eine ODER die andere Art verarbeiten um doppelte Meldungen zu vermeiden.</li>
		</ol>

		<h3>Züchter verwalten</h3>
		<p>
			Für den Meldenden Züchter kann man hier die Kontaktdaten erfassen.<br />
			Damit können die einzelne Meldungen der Züchter eingeben werden. Oder der Züchter gibt selbst seine Meldungen ein, brauch dan aber ein Passwort.<br />
			Eine Meldung, vom Züchter eingegeben, braucht ein Ok vom Obmann damit die in den Berichten mitgezählt wird, dies zur FehlerKontrolle.
		</p>

		<p>
			Wähle dazu in der 2. Menüzeile 'Leistungen' oder 'Züchter'.
		</p>


	</section>
{/if}


<style>
    img {
        @apply border border-teal-400 rounded p-4;
    }
</style>