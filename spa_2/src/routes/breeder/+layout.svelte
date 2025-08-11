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
		if ( dirty.breeder || page.url ) {
//			await loadBreeder( +page.params.breeder);
			await loadBreeder( ctx.user.id );
			loadDistrict( ctx.breeder.districtId );
		}
	});

	async function loadBreeder( id ) {
		console.log( 'Load Breeder', authorized )
		if( authorized ) {
			dirty.breeder = false;
			ctx.breeder = null;
			ctx.breeder = await model.Breeder.load( id );
		}
	}
	function loadDistrict( id ) {
		ctx.district = ctx.federation.districts[ id ];
	}

</script>

{#if ctx.user }
	{#if ctx.breeder && authorized }
		<div in:fade>
			{@render children()}
		</div>
	{/if}
{:else}
	<User />
{/if}


