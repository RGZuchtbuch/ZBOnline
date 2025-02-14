<script>
	import { slide } from 'svelte/transition';
	import Color from '$lib/cmp/standard/Color.svelte';



	let { breed, unfold=false } = $props();

	function toggle() {
		unfold = ! unfold;
	}
</script>

{#if breed}
	<div class='row'><button type='button' onclick={toggle}>o</button><div class='grow'>{breed.name}</div><div>a</div></div>
	{#if unfold}
		<div class='pl-8' transition:slide={{duration:breed.colors.length*100}}>
			{#each breed.colors as color}
				<Color {color} />
			{/each}
		</div>
	{/if}
{/if}


<style>
    div.row {
        @apply flex flex-row border border-y-amber-400;
    }

</style>