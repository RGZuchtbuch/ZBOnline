<script>
	import {page} from '$app/state';
	import { goto } from '$app/navigation';

	import store from '$lib/js/store.svelte.js';
	import { District } from '$lib/js/model/federation.svelte.js';
	import DistrictCmp from '$lib/cmp/moderator/District.svelte';

	let { data } = $props();

	$inspect('District Data', data );

	//let district = $state( null );

	$effect( async () => {
//		district = await District.load( +page.params.district );//store.federation.districts[ +page.params.district ];
		//district = data.district;
		console.log( "District page", data.district );
		setHeader();
	});

	function setHeader() {
		const title = `${data.district.name}`;

		const year = new Date().getFullYear()-1;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short, href: `/moderator/${data.district.id}` },

			],
			options: [
				{name: 'Eingaben', href: `${page.url.href}/result/${year}` },
				{name: 'Züchter', href: `${page.url.href}/breeder` },
			],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}

	//goto( `${page.url.href}/result` );

</script>

<DistrictCmp district={data.district}/>

