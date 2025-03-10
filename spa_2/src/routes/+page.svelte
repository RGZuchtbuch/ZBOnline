<script>
	import {getContext, onMount} from 'svelte';
    import store, { user } from '$lib/js/store.svelte.js';
    import { info_icon, test } from '$lib/cmp/icons.svelte';
	import Home from '$lib/cmp/Home.svelte';

	const title = 'Info zum BDRG Zuchtbuch';
	const menu = {
		trail : [
			{name: 'Start', href: '/'},
		],
		options : [
			{name: 'Info', href: '/article'},
			{name: 'Verbände', href: '/district'},
			{name: 'Standard', href: '/standard'},
			{name: 'Leistungen', href: '/report'},
		],
	};
	if( $user ) { // restricted
		//page.menu.options.push( { name:'Züchter',    href:'/breeder' } );
		if( $user.moderator ) menu.options.push( {name: 'Obmann', href: '/moderator'} );
		if( $user.admin )	   menu.options.push( {name: 'Admin', href: '/admin'} );
	}

	store.title.update( () => title );
	store.menu.update( () => menu );





</script>

<Home />
