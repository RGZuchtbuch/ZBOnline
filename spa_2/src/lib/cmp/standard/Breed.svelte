<script>
	import { slide } from 'svelte/transition';
	import Color from '$lib/cmp/standard/Color.svelte';

	let { breed, unfold=false } = $props();

	function toggle() {
		unfold = ! unfold;
	}
</script>

{#if breed}
	<li class='row'>
		<div class='flex flex-row'>
			<button class='inline' type='button' title='Farben' onclick={toggle}>{unfold?'▽':'▷'}</button>
			<div class='grow'>{breed.name}</div>
			<div class='w-16'></div>
			<div class='w-16'></div>
			<div class='w-16'></div>
			<div class='w-16'></div>
		</div>
		{#if unfold}
			<ul class='pl-12' transition:slide={{duration:breed.colors.length*25}}>
				{#each breed.colors as color}
					<Color {color} />
				{/each}
			</ul>
		{/if}
	</li>
{/if}


<style>
    li {
        @apply p-1 flex flex-col;
    }

    button {
        @apply bg-inherit text-black;
    }
</style>