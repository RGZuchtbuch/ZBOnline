<script>
	import { fade } from 'svelte/transition';
    import { cfg, ctx } from '$lib/js/store.svelte.js';

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

<main class='' in:fade={{duration:cfg.fadeIn}}>
	<User />
</main>

