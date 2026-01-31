<script>
	import model from '$lib/js/model.js';
	import { dateToInt } from '$lib/js/tools.js';

	function onClearCache() {
		console.log( 'Clearing cache' );
		model.Cache.delete();
	}

	function onClearLogs() {
		// let date = new Date();
		// date.setDate( date.getDate() );
		// let until = dateToInt( date );
		// console.log( until );
		console.log( 'Clearing logs' );
		model.Log.delete();
	}

	function onBackup() {
		console.log( 'Todo' );
	}
</script>

<h3 class='text-center'>Verschiedene System bearbeitungen</h3>
<p class='italic text-center'>Mit Vorsicht zu genießen</p>
<div class='flex flex-col mx-auto p-4 gap-y-2'>
	<button class='' onclick={onClearCache}>System Cache löschen</button>
	<button class='' onclick={onClearLogs}>System Logs löschen</button>
	<!--button class='p-2' onclick={onBackup}>System Daten Backup herunterladen</button-->
</div>

<style>
	button {
		@apply bg-orange-300 bg-opacity-40 text-black px-8 py-4;
	}
</style>