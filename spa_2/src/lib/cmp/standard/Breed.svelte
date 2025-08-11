<script>
	import { slide } from 'svelte/transition';
	import Color from '$lib/cmp/standard/Color.svelte';
	import {ctx} from '$lib/js/store.svelte.js';

	let { breed, unfold=false } = $props();
	let authorized = $state( ctx.user && ctx.user.admin );
	function toggle() {
		unfold = ! unfold;
	}
</script>

{#if breed}
	<li class=''>
		<button class='inline' type='button' title='Farben' onclick={toggle}>{unfold?'▽':'▷'}</button>
		<div class='grow' title='Rasse'>{breed.name}</div>
		<div class='w-16 text-right' title='Brutgruppe'>{breed.broodGroup}</div>
		<div class='w-16 text-right' title='Legeleistung'>{breed.layEggs}</div>
		<div class='w-16 text-right' title='Bruteigewicht'>{breed.layWeight}</div>
		<div class='w-16 text-right' title='Zielgewicht der Hähne'>{breed.sireWeight}</div>
		<div class='w-16 text-left' title='Zielgewicht der Hennen'>.{breed.dameWeight}</div>
		<div class='w-12 text-right' title='Ringgröße Hahn'>{breed.sireRing}</div>
		<div class='w-12 text-left' title='Ringgröße Henne'>.{breed.dameRing}</div>
	</li>
	{#if unfold}
		<ul class='pl-12' transition:slide={{duration:breed.colors.length*25}}>
			{#each breed.colors as color}
				<Color {color} />
			{/each}
		</ul>
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