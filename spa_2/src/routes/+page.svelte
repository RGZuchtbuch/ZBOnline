<script>
	import {getContext, onMount} from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import { info_icon, test } from '$lib/cmp/icons.svelte';
	import Home from '$lib/cmp/Home.svelte';

	let state = getContext( 'state' );

    // every page should set these
	state.title = 'Das BDRG Rassegeflügelzuchtbuch';
	state.menu  = {
		trail: [
			{name: 'Start', href: '/'},
		],
		options: [ // all public
			{name: 'Info', href: '/article'},
			{name: 'Verbände', href: '/district'},
			{name: 'Standard', href: '/standard'},
			{name: 'Leistungen', href: '/report'},
		]
	};
	if( store.user ) { // restricted
		//page.menu.options.push( { name:'Züchter',    href:'/breeder' } );
		if( store.user.moderator ) state.menu.options.push( {name: 'Obmann', href: '/moderator'} );
		//if( store.user.admin )	 page.menu.options.push( {name: 'Admin', href: '/admin'} );
	}


</script>

<Home />
