<script>
	import { title } from '$lib/js/store.svelte.js';
	import { fade, fly, slide } from 'svelte/transition';

	//let state = getContext( 'state' );
</script>

{#key $title}
	<div class='flex flex-row justify-between' in:fade>
		<!--a-- class='print:hidden' onclick={() => history.go(-1)} title='Zurück'>←</a-->
		<h1 class='grow text-center whitespace-nowrap'> {$title} </h1>
		<!--a class='print:hidden' onclick={() => history.go(+1)} title='Vorwärtz'>→</a-->
	</div>
{/key}

<style>

</style>