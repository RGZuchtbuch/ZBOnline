<script>
	import {page} from '$app/state';
	import {goto} from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';
	import User from '$lib/cmp/user/User.svelte';

	let { children, data } = $props();

	let authorized = $state( ctx.user && ( ctx.user.id === page.params.breeder || ctx.user.moderator.length > 0 || ctx.user.admin ) );
	console.log( 'cu', ctx.user );

</script>

{#if authorized }
	{@render children()}
{:else}
	<User />
{/if}


