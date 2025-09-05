<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import User from '$lib/cmp/user/User.svelte';

	let { children, data } = $props();

	let authorized = $state( ctx.user && ctx.user.admin );

	// layout protects role with authorized
</script>

{#if authorized }
	{@render children()}
{:else}
	<User />
{/if}


