<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import {getContext} from 'svelte';
	import { ctx } from '$lib/js/store.svelte.js';

</script>


<div class='flex flex-row rounded-b-none justify-between border-header bg-header text-header mt-2' in:fade>
	<!--a-- class='print:hidden' onclick={() => history.go(-1)} title='Zurück'>←</a-->
	<h1 class='grow text-center whitespace-nowrap'> {ctx.title} </h1>
	<!--a class='print:hidden' onclick={() => history.go(+1)} title='Vorwärtz'>→</a-->
</div>


<style>

</style>