<script>
	import { getContext, setContext } from 'svelte';
    import Districts from '$lib/cmp/moderator/Districts.svelte';
	import {goto} from '$app/navigation';
	import store, { federation, user } from '$lib/js/store.svelte.js';
	import {page} from '$app/state';

	let districts   = $state( null );

	$effect( () => {
		load( page );
	})

	async function load( page ) {
		let list = [];
		$user.moderator.forEach( id => {
			list.push( $federation.districts[id] );
		});
		districts = list;
		setHeader();
	}

	function setHeader() {
		const title = 'Obmann';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Obmann', href:page.url.href},
			],
			options: [],
		};
		store.title.update(() => title);
		store.menu.update(() => menu);
	}
</script>

<Districts {districts}/>



