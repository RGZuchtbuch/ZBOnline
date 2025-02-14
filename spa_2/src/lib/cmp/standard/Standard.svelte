<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import { slide } from 'svelte/transition';
	import api from '$lib/js/api.js';
	import { email_icon, link_icon } from '$lib/cmp/snippets.svelte';
	import Section from '$lib/cmp/standard/Section.svelte';

	let { standard } = $props();

	//$inspect( 'Root', standard );

</script>

<h3>
	Standard, Sparten, Gruppen, Rassen und Farbenschläge
</h3>

{#if standard }
	<div in:slide>
		<Section section={ standard.root } unfold={true}/>
	</div>
{/if}

<style>


</style>