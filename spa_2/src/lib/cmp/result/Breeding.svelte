<script>
	import { fade, slide } from 'svelte/transition';
	import { goto, invalidate } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { dec, selectName } from '$lib/js/tools.js';
	import BreederDetails from '$lib/cmp/result/BreedingDetails.svelte';


	let { district, breeders=$bindable() } = $props();

	let years = []; // create years array for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year ); // Todo, move to tools as function ?

</script>



{#if false && ctx.year }
	<div class='flex flex-row border-header bg-header text-header text-xl p-2 pt-1 gap-x-2 justify-center sticky top-1' in:fade>
		<span class='pt-5 font-bold'>Zuchtleistungen eingeben für {ctx.year}</span>
	</div>

{/if}


<h2 class='text-center'>Eingabe pro Zucht vom Züchter</h2>

	<header class='flex flex-row pl-2 gap-x-4  border-header bg-header'>
		<span class='w-2'></span>
		<span class='w-12 text-center'>Mitg.</span>
		<span class='w-128'>Züchter</span>
		<span class='w-12 text-center'>Zuchten</span>
	</header>

	{#if breeders}
		{#each breeders as breeder, i }
			{#key breeder.id}
				<BreederDetails bind:breeder={breeders[i]}></BreederDetails>
			{/key}
		{/each}
	{/if}

<style>


</style>