<script>
	import {page} from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { Result } from '$lib/js/result.svelte.js';
	import ResultsEditCmp from '$lib/cmp/moderator/district/ResultsEdit.svelte';

	// let district = $derived( store.federation.districts[ +page.params.district ] );
	// let year     = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );
	// let section  = $derived( +page.url.searchParams.get( 'section' ) === 9999 ? store.aocSection : store.standard.rootSections.find( item => item.id === ( +page.url.searchParams.get( 'section' ) || 3 ) ) );
	// let group    = $derived( page.url.searchParams.get( 'group' ) || 'I' );

	let district = $state( null );
	let year     = $state( null );
	let section  = $state( null );
	let group    = $state( null );

	let breeds   = $state( null ); // load result

	$effect( async () => {
		await update( page );
		setHeader();
	})

	async function update( page ) {
		const query = {
			district : +page.params.district,
			year     : +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1,
			section  : +page.url.searchParams.get( 'section' ) || 3, // G&W
			group    :  page.url.searchParams.get( 'group' ) || 'I',
		};

		breeds = null; // avoiding early update targets as section is updated before breeds
		const data = await Result.query( query );
		breeds = data.results;

		district = store.federation.districts[ query.district ];
		year     = query.year;
		section  = query.section === 9999 ? // special case for aoc
			store.aocSection :
			store.standard.rootSections.find( item => item.id === ( +page.url.searchParams.get( 'section' ) || 3 ) );
		group    = query.group;
	}

	function setHeader() {
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

