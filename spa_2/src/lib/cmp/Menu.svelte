<script>
	import { getContext } from 'svelte';
	import { fade, fly, slide } from 'svelte/transition';

	const page = getContext( 'page' );
</script>

<div class='flex flex-row border border-teal-400 bg-teal-200 print:hidden' >
	<a href='/' class='-mt-5' title='Das BDRG Zuchtbuch Logo'>
		<img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
	</a>

	{#if page.menu}
		<div class='grow flex flex-row items-center' in:fade>
			{#each page.menu.trail as step, i}
				{#if i>0} / {/if}
				{#key step.name}
					<div class='flex flex-row'>
						{#if i < page.menu.trail.length-1}
							<a href={step.href} class='pr-1'>{step.name}</a>
						{:else}
							<a href={step.href} class='pr-1 font-bold'>{step.name}</a>
						{/if}
					</div>
				{/key}
			{/each} :
			<div class='flex flex-row px-4 items-center gap-x-2' in:slide={{axis:'x', duration:500}}>
				{#each page.menu.options as option, i}
					{#key option.name}
						<a href={option.href}>{option.name}</a>
					{/key}
				{/each}
			</div>
		</div>
	{/if}

</div>

