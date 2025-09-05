<script>
	import { slide } from 'svelte/transition';
	import Color from '$lib/cmp/standard/Color.svelte';
	import {cfg, ctx} from '$lib/js/store.svelte.js';

	let { breed, unfold=false } = $props();
	let authorized = $state( ctx.user && ctx.user.admin );
	function toggle() {
		unfold = ! unfold;
	}
</script>

{#if breed}
	<button class='w-full flex flex-row py-2 gap-x-2' type='button' title='Farben' onclick={toggle}>
		<span class='w-4'>{unfold?'▽':'▷'} </span>
		<span class='grow text-left' title='Rasse'>{breed.name}</span>
		<span class='w-16 text-center' title='Brutgruppe'>
			{#if breed.broodGroup}{breed.broodGroup}{/if}
		</span>
		<span class='w-16 text-right' title='Legeleistung'>{breed.layEggs}</span>
		<span class='w-16 text-right' title='Bruteigewicht'>{breed.layWeight}</span>
		<span class='w-32 text-center' title='Zielgewicht der Hahne.Henne'>{breed.sireWeight}.{breed.dameWeight}</span>
		<span class='w-24 text-center' title='Ringgröße Hahn.Henne'>{breed.sireRing}.{breed.dameRing}</span>
	</button>

	{#if unfold}
		<div class='pl-12' transition:slide={{duration:breed.colors.length*25}}>
			{#each breed.colors as color}
				<Color {color} />
			{/each}
		</div>
	{/if}
{/if}


<style>
    li {
        @apply flex flex-row p-2 gap-x-1;
    }

    button {
        @apply bg-inherit text-black;
    }
</style>