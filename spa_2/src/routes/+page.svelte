<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js'

	import Home from '$lib/cmp/Home.svelte';


	console.log( 'H', ctx.header );

	$effect( () => {
		console.log( 'set header' );
		setHeader( page.url );
	})

	function setHeader( url ) {
		console.log( 'setting title' );
		const title = 'Das BDRG Zuchtbuch';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
			],
			options: [
				{name: 'Beiträge', href: '/article'},
				{name: 'Verbände', href: '/district'},
				{name: 'Standard', href: '/standard'},
				{name: 'Leistungen', href: '/report'},
			],
		};
		if ( ctx.user) { // restricted
			if ( ctx.user.moderator) menu.options.push({name: 'Obmann', href: '/moderator'});
			if ( ctx.user.admin) menu.options.push({name: 'Admin', href: '/admin'});
		}
		ctx.header.title = title;
		ctx.header.menu  = menu;
	}

</script>

Page
<Home />

