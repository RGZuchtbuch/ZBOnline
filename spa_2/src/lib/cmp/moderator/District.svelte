<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<p class='content'>
		Uber diese Seiten können die Meldungen der Zuchten vom Obmann eingegeben werden.<br>
		Mann hat Zwei möglichkeiten: Man gibt zu daten ein pro Rasse und evt. den Farbenschlag, wobei mann selbst dus Zahlen zusammen rechnet.
		Oder man gibt jeder vom Züchter gemeldeter Stamm ein, wobei mann einige Daten mehr eingeben muss, braucht dafür aber selbst nichts zusammen zu rechnen.

	</p>
	<p>Wähle jetzt
		<a href=''>Eingaben</a> für eine Tabelle und Eingabe der Leistungen, oder <a href=''>Züchter</a> um Züchter ein zu geben und deren Meldungen.</p>
	<p>
		Hier kommen statistiken
	</p>

{/if}


<style>
	p {
		@apply px-6 pt-4;
	}
</style>