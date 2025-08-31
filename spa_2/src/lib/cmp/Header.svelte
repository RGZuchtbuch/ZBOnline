<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js'
	import { profile_icon } from '$lib/cmp/icons.svelte';
</script>


<a href='/' class='absolute -m-1' title='Das BDRG Zuchtbuch Logo'>
	<img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
</a>

<div class='flex flex-row'>
	<!--div class='flex flex-row pl-16'>
		<span>Crumbs</span>
		{#each ctx.crumbs as crumb }
			<a href={crumb.url.href}>{crumb.name}</a> .
		{/each}
	</div-->
	<div class='grow font-bold pl-32 text-sm text-center' onclick={ () => ctx.dialog='test' } in:fade>
		BDRG Zuchtbuch
	</div>

	<div class='w-32 flex flex-row pr-2 gap-x-2 text-sm justify-end' in:fade>
		<span>{ ctx.user ? ctx.user.firstname : 'Gast' }</span>
		<span class='screen:hidden'>{ new Date().toLocaleDateString( 'de-DE' ) }</span>
		<a href='/user' title='An/abmelden'>{@render profile_icon()}</a>
	</div>
</div>
