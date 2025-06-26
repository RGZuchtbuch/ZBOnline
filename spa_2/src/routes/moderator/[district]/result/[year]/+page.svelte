<script>
	import { invalidateAll } from '$app/navigation';
	import {page} from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { Result } from '$lib/js/result.svelte.js';
	import ResultsCmp from '$lib/cmp/moderator/district/Results.svelte';

	let { data } = $props();


	//let district   = $derived( data.federation.districts[ +page.params.district ] );
	//let year       = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );
	//let results    = $state( null );

	$effect( async () => {
		//const data = await Result.query( { district:+page.params.district, year:year } );
		//results = data.results;
		console.log( 'Results', data.results );
		setHeader();
	});

	function setHeader() {
		const title = `${data.district.name}`;
		const menu = {
			trail: [
				{ name:'Home',      href:'/' },
				{ name:'Obmann',    href:'/moderator' },
				{ name: data.district.short,   href:`/moderator/${data.district.id}` },
				{ name:'Eingaben', href: page.url.href },
			],
			options: [
				{ name:'Eingeben', href:`/moderator/${data.district.id}/result/${page.params.year}/edit/section/3/group/I` },
				{name: 'Züchter', href: `${page.url.href}/breeder` },
			],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	};

	// async function load( url ) {
	// 	console.log(' Load result', year );
	// 	const response = await api.result.get( { districtId:district.id, year:year } );
	// 	results = response.results;
	// }



</script>


<ResultsCmp results={data.results} district={data.district} year={data.year} {data} />

