<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import {jwtDecode} from 'jwt-decode';
    import { ctx } from '$lib/js/store.svelte.js';
    import Reset from '$lib/cmp/user/Reset.svelte';

	//let { data } = $props();

	const token = page.url.searchParams.get('token');

	$effect( () => {
		setHeader();
	})

	function setHeader() {
		ctx.header = {
			title: 'Benutzer im RGZuchtbuch, Passwort setzen',
			menu: {
				trail: [
					//{name: 'Start', href: '/'},
					{name: 'Anmeldung'},
				],
				options: [],
			},
		}
	}
</script>

{#if token}
	<Reset {token}/>
{/if}


