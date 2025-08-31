<script>
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import {goto} from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';
	import User from '$lib/cmp/user/User.svelte';
	import model from '$lib/js/model.js';

	let { children, data } = $props();

	let authorized = $state( ctx.user !== null );//&& ( ctx.user.id === +page.params.breeder || ctx.user.admin ) );

	$effect( async () => {
		await loadBreeder( ctx.user.id );
	});

	$effect( async () => {
		ctx.district = ctx.breeder ? ctx.federation.districts[ ctx.breeder.districtId ] : null;
	});

	async function loadBreeder( id ) {
		console.log( 'Load Breeder', authorized )
		if( authorized ) {
			//dirty.breeder = false;
			//ctx.breeder = null;
			ctx.breeder = await model.Breeder.load( id );
		}
	}
	function loadDistrict( id ) {
		ctx.district = ctx.federation.districts[ id ];
	}

</script>

{#if ctx.user }
	{#if ctx.breeder && ctx.district && authorized }
		{@render children()}
	{/if}
{:else}
	<User /> <!-- login -->
{/if}


