<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js'
	import { profile_icon } from '$lib/cmp/icons.svelte';

	let expiring = $derived( ctx.remaining < 3600 );

	function toggleMenu( event ) {
		ctx.menuOpen = !ctx.menuOpen;
	}

</script>


<!--a href='/' class='absolute -m-1' title='Das BDRG Zuchtbuch Logo'>
	<img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
</a-->

<div class='flex flex-row '>

	<a href='/' class='flex flex-row w-8 md:w-32 justify-start' title='Das BDRG Zuchtbuch Logo'>
		<img src='/assets/bdrg_logo_r.png' class='absolute h-16 lg:h-16 pt-0.5' alt='BDRG Rassegeflügelzuchtbuch Logo' />
	</a>

	<div class='grow font-bold text-sm text-center' in:fade>
		BDRG Zuchtbuch
	</div>

	<div class='hidden lg:flex flex-row w-32 pr-2 gap-x-2 text-sm justify-end' class:expiring in:fade>
		<span>{ ctx.user ? ctx.user.firstname : 'Gast' }</span>
		<span class='screen:hidden'>{ new Date().toLocaleDateString( 'de-DE' ) }</span>
		<a href='/user' title='An/abmelden'>{@render profile_icon()}</a>
	</div>

	<button class='lg:hidden bg-white text-black text-xl w-8 px-2 pb-0 text-right' onclick={toggleMenu}>
		≡
	</button>

</div>

<style>
	.expiring {
		@apply text-red-600;
	}
</style>
