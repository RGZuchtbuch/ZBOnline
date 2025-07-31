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
	<li class='row'>
		<button class='inline' type='button' title='Öffnen' onclick={toggle}>{unfold?'▽':'▷'} </button>
		<div class='grow font-bold' title='Sparte'>{section.name}</div><div></div>
	</li>

	{#if unfold}
		<ul class='pl-8' transition:slide={{duration:500}}>
			{#each section.children as child}
				<Section section={child} />
			{/each}
		</ul>

		<ul class='pl-8' transition:slide={{duration:500}}>
			{#each section.breeds as breed}
				<Breed {breed} />
			{/each}
		</ul>
	{/if}
{/if}

<style>
    li {
        @apply flex flex-row p-2 gap-x-1;
    }

    button {

    }


</style>