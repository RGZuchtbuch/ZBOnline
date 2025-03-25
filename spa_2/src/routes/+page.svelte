<script>
	import {getContext, onMount} from 'svelte';
	import { page } from '$app/state';
    import store, { user } from '$lib/js/store.svelte.js';
    import { info_icon, test } from '$lib/cmp/icons.svelte';
	import Home from '$lib/cmp/Home.svelte';

	let { data } = $props();

	$effect( () => {
		setHeader( page );
	})

	function setHeader( data ) {
		console.log( 'setting title' );
		store.title.update(() => 'Info zum BDRG Zuchtbuch');
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
			],
			options: [
				{name: 'Info', href: '/article'},
				{name: 'Verbände', href: '/district'},
				{name: 'Standard', href: '/standard'},
				{name: 'Leistungen', href: '/report'},
			],
		};
		if ($user) { // restricted
			//page.menu.options.push( { name:'Züchter',    href:'/breeder' } );
			if ($user.moderator) menu.options.push({name: 'Obmann', href: '/moderator'});
			if ($user.admin) menu.options.push({name: 'Admin', href: '/admin'});
		}
		store.menu.update(() => menu);
	}

	console.log( "P data", data );


</script>

<Home />
