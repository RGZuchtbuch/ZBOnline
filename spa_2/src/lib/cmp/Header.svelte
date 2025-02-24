<script>
	import { fade, fly, slide } from 'svelte/transition';
	import store from '$lib/js/store.svelte.js'
	import { profile_icon } from '$lib/cmp/icons.svelte';

</script>

<div class='flex flex-row'>
	<div class='w-32'></div>
	<div class='grow font-bold text-sm text-center' in:fade>
		BDRG Zuchtbuch
	</div>
	{#if store.data.user}
		<div class='w-32 flex flex-row text-sm justify-end' in:fade>
			<span>{store.data.user.firstname}</span>
			<a href='/user'>{@render profile_icon()}</a>
		</div>
	{/if}
</div>
