<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import User from '$lib/cmp/user/User.svelte';


	let { children } = $props();

	let authorized = $state( ctx.user && ( ctx.user.moderator.length > 0 || ctx.user.admin ) );

	$effect( () => {
		if( dirty.districts || ctx.federation || ctx.user ) setDistricts();
	})


	function setDistricts() {
		//dirty.districts = false;
		let districts = [];
		if( ctx.federation && ctx.user ) {
			ctx.user.moderator.forEach(id => {
				districts.push( ctx.federation.districts[id] );
			});
		}
		ctx.districts = districts;
	}
</script>


{#if authorized }
	{@render children()}
{:else}
	<User />
{/if}


