<script>
	import { getContext } from 'svelte';
	import { fade, fly, slide } from 'svelte/transition';

	const state = getContext( 'state' );
</script>



<div class='flex flex-row border border-teal-400 bg-teal-200 print:hidden' >
	{#if state.menu}
		<div class='grow flex flex-row pl-20 items-center' in:fade>
			{#each state.menu.trail as step, i}
				{#if i>0} / {/if}
				{#key step.name}
					<div class='flex flex-row'>
						{#if i < state.menu.trail.length-1}
							<a href={step.href} class='pr-1'>{step.name}</a>
						{:else}
							<a href={step.href} class='pr-1 font-bold'>{step.name}</a>
						{/if}
					</div>
				{/key}
			{/each} :
			<div class='flex flex-row px-4 items-center gap-x-2' in:slide={{axis:'x', duration:500}}>
				{#each state.menu.options as option, i}
					{#key option.name}
						<a href={option.href}>{option.name}</a>
					{/key}
				{/each}
			</div>
		</div>
	{/if}

</div>

