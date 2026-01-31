<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import {getContext} from 'svelte';
	import { ctx } from '$lib/js/store.svelte.js';

</script>


<div class='flex flex-row rounded-b-none justify-between border-header bg-header align-bottom text-header mt-2 px-2' in:fade>
	<a class='pt-2 cursor-pointer' title='Zurück' href='javascript:history.back()'> ← </a>
	<!--a-- class='print:hidden' onclick={() => history.go(-1)} title='Zurück'>←</a-->
	<h1 class='grow text-center whitespace-nowrap'> {ctx.title} </h1>
	<!--a class='print:hidden' onclick={() => history.go(+1)} title='Vorwärtz'>→</a-->
	<a class='pt-2 cursor-pointer' title='Vorwärts' href='javascript:history.forward()'> → </a>
</div>


<style>

</style>