<script>
	import {page} from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { Result } from '$lib/js/result.svelte.js';
	import ResultsEditCmp from '$lib/cmp/moderator/district/ResultsEdit.svelte';

	let district = $derived( store.federation.districts[ +page.params.district ] );
	let year     = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );
	let section  = $derived( +page.url.searchParams.get( 'section' ) === 9999 ? store.aocSection : store.standard.rootSections.find( item => item.id === ( +page.url.searchParams.get( 'section' ) || 3 ) ) );
	let group    = $derived( page.url.searchParams.get( 'group' ) || 'I' );

	let breeds    = $state( null );

	$effect( async () => {
		const data = await Result.query( { district:district.id, year:year, section:section.id, group:group } );
		breeds = data.results;
		setHeader();
	});

	function setHeader() {
		console.log( 'Set');
		const title = `${district.name}`;
		const menu = {
			trail: [
				{ name:'Home',      href:'/' },
				{ name:'Obmann',    href:'/moderator' },
				{ name: district.short,   href:`/moderator/${district.id}` },
				{ name:'Eingaben', href: `/moderator/${district.id}/result?year=${year}` },
				{ name:'Bearbeiten', href:`/moderator/${district.id}/result/edit?year=${year}` },
			],
			options: [],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}

</script>


<ResultsEditCmp {district} {group} {section} {year} {breeds} } />

