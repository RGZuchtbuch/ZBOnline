<script>
	//import { getContext } from 'svelte';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { fade, fly, slide } from 'svelte/transition';

</script>


<div class='flex flex-row border border-teal-400 bg-teal-200 print:hidden' >
	{#if store.menu}
		<nav class='grow flex flex-row pl-20 items-center' in:fade>
			{#each store.menu.trail as step, i}
				{#if i>0} / {/if}
				{#key step.name}
					<div class='flex flex-row'>
						{#if i < store.menu.trail.length-1}
							<a href={step.href} class='pr-1'>{step.name}</a>
						{:else}
							<a href={step.href} class='pr-1 font-bold'>{step.name}</a>
						{/if}
					</div>
				{/key}
			{/each} :
			<div class='flex flex-row px-4 items-center gap-x-2' in:slide={{axis:'x', duration:500}}>
				{#each store.menu.options as option, i}
					{#key option.name}
						<a href={option.href}>{option.name}</a>
					{/key}
				{/each}
			</div>
		</nav>
	{/if}

</div>

