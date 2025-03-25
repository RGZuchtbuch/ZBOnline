<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import { title } from '$lib/js/store.svelte.js';

</script>

{#key $title}
	<div class='flex flex-row rounded-b-none justify-between header mt-2' in:fade>
		<!--a-- class='print:hidden' onclick={() => history.go(-1)} title='Zurück'>←</a-->
		<h1 class='grow text-center whitespace-nowrap'> {$title} </h1>
		<!--a class='print:hidden' onclick={() => history.go(+1)} title='Vorwärtz'>→</a-->
	</div>
{/key}


<style>

</style>