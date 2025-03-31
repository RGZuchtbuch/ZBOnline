<script>
	import { page } from '$app/state';
    import store from '$lib/js/store.svelte.js';
	import Home from '$lib/cmp/Home.svelte';

	let { data } = $props();

	$effect( () => {
		setHeader( page.url );
	})

	function setHeader( data ) {
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
		if ( store.user) { // restricted
			if ( store.user.moderator) menu.options.push({name: 'Obmann', href: '/moderator'});
			if ( store.user.admin) menu.options.push({name: 'Admin', href: '/admin'});
		}
		store.title = title;
		store.menu  = menu;
	}

	console.log( "P data", store.title, store.menu );


</script>

<Home />
