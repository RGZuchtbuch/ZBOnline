<script>
	import {getContext, onMount} from 'svelte';
    import store from '$lib/js/store.svelte.js';
    import { info_icon, test } from '$lib/cmp/icons.svelte';
	import Home from '$lib/cmp/Home.svelte';

	let page = getContext( 'page' );

    // every page should set these
    page.title = 'Das BDRG Rassegeflügelzuchtbuch';
	page.menu  = {
		trail: [
			{name: 'Start', href: '/'},
		],
		options: [
			{name: 'Info', href: '/article'},
			{name: 'Verbände', href: '/district'},
			{name: 'Standard', href: '/standard'},
			{name: 'Leistungen', href: '/result'},
			//         { name:'Züchter',    href:'/breeder' },
			{name: 'Obmann', href: '/moderator'},
			{name: 'Admin', href: '/admin'},
		]
	};

</script>

<Home />
