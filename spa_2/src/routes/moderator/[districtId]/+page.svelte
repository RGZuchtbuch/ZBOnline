<script>
	import {setContext} from 'svelte';
	import {page} from '$app/state';
	import api from '$lib/js/api.js';
	import store, { federation, user } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';

	let { data } = $props();

	let district = $state( data.district );

	$effect( () => {
		setHeader( district );
	});

	function setHeader( district ) {
		const title = null; //`Verband ${district.name}`;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short, href: `/moderator/${district.id}` },

			],
			options: [
				{name: 'Eingaben', href: `${page.url.href}/result` },
				{name: 'Züchter', href: `${page.url.href}/breeder` },
			],
		}
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	}



</script>

<District {district}/>

