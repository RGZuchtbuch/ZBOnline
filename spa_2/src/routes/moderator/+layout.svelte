<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import User from '$lib/cmp/user/User.svelte';

	let { children, data } = $props();
	let authorized = $state( ctx.user && ( ctx.user.moderator.length > 0 || ctx.user.admin ) );
</script>

{#if authorized }
	{@render children()}
{:else}
	<User />
{/if}


