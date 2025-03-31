<script>
	import {page} from '$app/state';

	import store from '$lib/js/store.svelte.js';
	import { District } from '$lib/js/federation.svelte.js';
	import DistrictCmp from '$lib/cmp/moderator/District.svelte';

	let { data } = $props();

	let district = $state( null );

	$effect( async () => {
		district = await District.load( +page.params.district );//store.federation.districts[ +page.params.district ];
		setHeader();
	});

	function setHeader() {
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
		store.title = title; // to set after loading
		store.menu  = menu;
	}



</script>

<DistrictCmp {district}/>

