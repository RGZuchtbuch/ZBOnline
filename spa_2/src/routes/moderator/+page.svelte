<script>
	import { getContext, setContext } from 'svelte';
    import Districts from '$lib/cmp/moderator/Districts.svelte';
	import {goto} from '$app/navigation';
	import store from '$lib/js/store.svelte.js';
	import {page} from '$app/state';

	let districts   = $state( null );

	$effect( () => {
		load( page.url );
	})

	async function load( url ) {
		let list = [];
		store.user.moderator.forEach( id => {
			list.push( store.federation.districts[id] );
		});
		districts = list;
		setHeader( url );
	}

	function setHeader( url ) {
		const title = 'Obmann';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Obmann', href:url.href},
			],
			options: [],
		};
		store.title = title;
		store.menu  = menu;
	}
</script>

<Districts {districts}/>



