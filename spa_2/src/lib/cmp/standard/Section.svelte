<script>
	import { slide } from 'svelte/transition';
	import Section from '$lib/cmp/standard/Section.svelte';
	import Breed from '$lib/cmp/standard/Breed.svelte';

	let { section=null, unfold=false } = $props();

	function toggle() {
		unfold = ! unfold;
	}

</script>

{#if section}
	<div class='row'><button type='button' onclick={toggle}>o</button><div class='grow'>{section.name}</div><div>{unfold}</div></div>

	{#if unfold}
		<div class='pl-8' transition:slide={{duration:section.children.length*20}}>
			{#each section.children as child}
				<Section section={child} />
			{/each}
		</div>

		<div class='pl-8' transition:slide={{duration:section.breeds.length*25}}>
			{#each section.breeds as breed}
				<Breed {breed} />
			{/each}
		</div>
	{/if}
{/if}

<style>
    div.row {
        @apply flex flex-row border border-y-amber-400;
    }


</style>