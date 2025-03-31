<script>
	import {getContext} from 'svelte';
	import {page} from '$app/state';
	import store, { federation } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js.obs';
	import Results from '$lib/cmp/moderator/district/Results.svelte';

	let district   = $derived( $federation.districts[ +page.params.districtId ] );
	let year       = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );
	let results    = $state( null );

	$effect( async () => {
		await load( page.url );
	});

	$effect( () => {
		const title = `Leistungen für ${district.name}`;
		const menu = {
			trail: [
				{ name:'Home',      href:'/' },
				{ name:'Obmann',    href:'/moderator' },
				{ name: district.short,   href:`/moderator/${district.id}` },
				{ name:'Eingaben', href: page.url.href },
			],
			options: [],
		}
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	});

	async function load( url ) {
		console.log(' Load result', year );
		const response = await api.result.get( { districtId:district.id, year:year } );
		results = response.results;
	}

</script>


<Results {results} {district} {year} } />

