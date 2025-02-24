<script>
	import { getContext } from 'svelte';
	import { fade, fly, slide } from 'svelte/transition';

	let page = getContext( 'page' );
</script>

{#key page.title}
	<div class='flex flex-row justify-between items-center' in:fade>
		<a onclick={() => history.go(-1)} title='Zurück'>←</a>
		<h1 class='text-center whitespace-nowrap'> {page.title} </h1>
		<a onclick={() => history.go(+1)} title='Vorwärtz'>→</a>
	</div>
{/key}