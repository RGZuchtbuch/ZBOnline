<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js'
	import { profile_icon } from '$lib/cmp/icons.svelte';
</script>


<!--a href='/' class='absolute -m-1' title='Das BDRG Zuchtbuch Logo'>
	<img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
</a-->

<div class='flex flex-row '>

	<a href='/' class='flex flex-row w-8 justify-start border' title='Das BDRG Zuchtbuch Logo'>
		<img src='/assets/bdrg_logo_r.png' class='absolute h-6 lg:h-16 pt-0.5' alt='BDRG Rassegeflügelzuchtbuch Logo' />
	</a>

	<div class='grow font-bold text-sm text-center border' in:fade>
		BDRG Zuchtbuch
	</div>

	<div class='hidden lg:flex flex-row pr-2 gap-x-2 text-sm justify-end border' in:fade>
		<span>{ ctx.user ? ctx.user.firstname : 'Gast' }</span>
		<span class='screen:hidden'>{ new Date().toLocaleDateString( 'de-DE' ) }</span>
		<a href='/user' title='An/abmelden'>{@render profile_icon()}</a>
	</div>

	<div class='lg:hidden w-8 px-1 pb-0 text-right border'>
		≡
	</div>
</div>
