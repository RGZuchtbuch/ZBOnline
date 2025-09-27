<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	let logs = $state( null );

	onMount( async () => {
		logs = await model.Log.query( { from:0, count:500} );
	} )

</script>

<section class='mt-32 flex flex-col items-center'>
	<h2>RGZuchtbuch Logs</h2>
	<p>Die Logs zeigen wann gewisse aufrufe im systeem gewesen sind. Zur Hacker entdeckung und Fehler suche.</p>

	<section>
		{#if logs}
			{#each logs as log}
				<div class='flex'><span class='w-48'>{log.modified}</span>  <span class='w-80'>{log.message}</span>  <span class='w-32'>{log.uri}</span> <span class='w-32'>{log.query}</span></div>
			{/each}
		{/if}

	</section>

</section>
