<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

</script>

{#if ctx && ctx.header && ctx.header.title && ctx.header.menu }
	<div class='flex flex-row border-header bg-header text-header print:hidden' >
		<nav class='grow flex flex-row'>
			<nav class='flex flex-row px-4 items-center gap-x-0.0 pl-24'>
				{#each ctx.header.menu.trail as step, i}
					{#key step.name}
						{#if i>0}➭{/if}
						{#if i < ctx.header.menu.trail.length-1}
							<a href={step.href} class='pr-1' title='Zurück'> {step.name}</a>
						{:else}
							<span class='pr-1 font-bold cursor-default' title='Hier bist du' in:fade={{axis:'x', duration:500}}> {step.name}</span>
						{/if}
					{/key}
				{/each} :
			</nav>
			<span class='grow'></span>
			<nav class='flex flex-row px-4 items-center gap-x-2 pr-24' in:slide={{axis:'x', duration:500}}>
				{#each ctx.header.menu.options as option, i}
					{#key option.name}
						<a href={option.href} title='Wählen'>{option.name}</a>
					{/key}
				{/each}
			</nav>
		</nav>

	</div>
{/if}
