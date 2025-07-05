<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js'

	import Home from '$lib/cmp/Home.svelte';

	$effect( () => {
		const header = {
			title: 'Das BDRG Zuchtbuch',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		};
		if ( ctx.user ) { // restricted
			if ( ctx.user.moderator) header.menu.options.push({name: 'Obmann', href: '/moderator'});
			if ( ctx.user.admin) header.menu.options.push({name: 'Admin', href: '/admin'});
		}
		ctx.header = header; // single trigger
	})

</script>

<Home />

