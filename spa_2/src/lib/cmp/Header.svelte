<script>
	import { fade, fly, slide } from 'svelte/transition';
	import store from '$lib/js/store.svelte.js'
	import { profile_icon } from '$lib/cmp/icons.svelte';

</script>


<a href='/' class='absolute -m-1' title='Das BDRG Zuchtbuch Logo'>
	<img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
</a>

<div class='flex flex-row'>

	<div class='grow font-bold pl-32 text-sm text-center' in:fade>
		BDRG Zuchtbuch
	</div>

	<div class='w-32 flex flex-row pr-2 text-sm justify-end print:hidden' in:fade>
		<span>{ store.user ? store.user.firstname : 'Gast' }</span>
		<a href='/user' title='An/abmelden'>{@render profile_icon()}</a>
	</div>
</div>
