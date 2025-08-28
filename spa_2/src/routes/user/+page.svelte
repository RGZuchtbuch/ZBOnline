<script>
	import { getContext } from 'svelte';
    import { ctx } from '$lib/js/store.svelte.js';
    import User from '$lib/cmp/user/User.svelte';

	let { data } = $props();

	$effect( () => {
		setHeader();
	})

	function setHeader() {
		ctx.header = {
			title: 'Benutzer im RGZuchtbuch',
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

<User />


