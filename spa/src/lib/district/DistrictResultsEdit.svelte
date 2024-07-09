<script>
	import { slide } from 'svelte/transition';
	import {router} from 'tinro';
	import api      from '../../js/api.js';
	import {toNumber} from '../../js/util.js';

	import Selector from './edit/Selector.svelte';
	import Header   from './edit/Header.svelte';
	import Aoc      from './edit/Aoc.svelte';
	import Breed    from './edit/Breed.svelte';
	import Help     from './edit/Help.svelte';
	import AocInput from './edit/AocInput.svelte';

	// params
	export let districtId = null;
	export let year = null;

	// query
	let sectionId = null;
	let group = null

	let district = null;
	let breeds = [];

	let help = false; // triggered in selector

	function onQuery( route ) { // when route or query changes
		const now = new Date();
		sectionId = route.query.section ? toNumber(route.query.section) : 3; // get from route or 3 G&W section
		group = route.query.group && ['I', 'II', 'III'].includes(route.query.group) ? route.query.group : 'I'; // defaults to 'I'
		loadDistrict();
		loadBreeds();
	}

	function onHelp( event ) {
		help = ! help;
	}

	function loadDistrict() {
		api.district.get( districtId ).then( response => {
			district = response.district
		});
	}

	function loadBreeds() {
		breeds = []; // empty
		if( sectionId === 5 ) group = 'I'; // pigeons don't have group, so defaults to 'I' locally
		if( sectionId && year && group ) { // on change of any reload all
			api.district.results.section.get( districtId, sectionId, year, group ).then( response => {
				breeds = response.results;
			});
		}
	}

	$: onQuery( $router );
	console.log( 'Edit', districtId, year );
</script>


<div class='w-256 flex rounded-t'>
	<h2 class='grow text-center'>Eingabe Leistungen {district ? district.name : '...'}</h2>
	<button type='button' class='w-8 justify-center m-2 circled bg-alert cursor-pointer text-white' on:click={onHelp} title='Anleitung'>?</button>
</div>


<Selector {year} {sectionId} {group}/>

<Header {sectionId}/>

<div class='w-256 bg-gray-200 overflow-y-scroll border border-t-gray-400 rounded-b scrollbar'>
	{#if sectionId === 9999 }

		<AocInput {districtId} {year} bind:results={breeds}/>
		<div class='h-4 bg-white'></div>

		{#each breeds as result }
			<Aoc {districtId} {sectionId} {result} {year} {group} />
		{/each}
	{:else}
		{#each breeds as breed }
			<Breed {districtId} {sectionId} {breed} {year} {group} title='Wähle zum Eingeben' />
		{/each}
	{/if}
</div>
{#if help}
	<Help on:help={onHelp} />
{/if}