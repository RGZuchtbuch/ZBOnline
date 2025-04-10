<script>
	import { fade, slide } from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { dec, txt } from '$lib/js/toolbox.js';
	import {Select} from '$lib/cmp/form/Form.svelte';
	import AocCreate from './results/input/AocCreate.svelte';
	import AocResult from './results/input/AocResult.svelte';
	import Breed from './results/input/Breed.svelte';

	let { district, group, section, year, breeds } = $props();

	let authorized = $derived( store.user && ( store.user.id === district.moderator.id || store.user.admin ) ); // can edit

	let years = []; // for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year );

	function onYearChange( event ) {
		const year = event.target.value;
		let url = new URL( page.url );
		url.searchParams.set( 'year', year );
		goto( url.href );
	}

	function onSectionChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'section', section.id );
		goto( url.href );
	}
	function onGroupChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'group', group );
		goto( url.href );
	}

</script>

{#if district && group && section && year }
	<div class='flex flex-row border border-gray-400 bg-gray-50 p-2 gap-x-4 justify-center' in:fade>
		<span class='py-3 font-bold'>Leistungen eingeben für </span>
		<Select class='' label='Jahr' value={year} onchange={onYearChange}>
			{#each years as y}
				<option value={y}>{y}</option>
			{/each}
		</Select>

		<Select label="Sparte" bind:value={section} onchange={onSectionChange} title='Sparte zum Eingeben'>
			{#each store.standard.rootSections as section}
				<option value={section}>{section.name}</option>
			{/each}
			<option value={store.aocSection}>{store.aocSection.name}</option>
		</Select>

		<Select label="Gruppe" bind:value={group} onchange={onGroupChange} disabled={section && section.id === 5 } title='Zuchtbuchgruppe I, II oder III'>
			{#each store.groups as group}
				<option value={group}>{group}</option>
			{/each}
		</Select>
	</div>

	<div class ='flex flex-row bg-gray-50' in:fade>
		<p class='grow info'>
			Leistungsdaten gesamt eingabe per Sparte und Gruppe
		</p>
	</div>
{/if}

{#if breeds}
	{#if section.id === 9999}
		<AocCreate />
		{breeds.length}
		{#each breeds as result}
			<AocResult {section} {result} />
		{/each}
	{:else}

		<div>
			{#each breeds as breed (breed.id) }
				<Breed {breed} {district} {group} {year}/>
			{/each}
		</div>
	{/if}
{/if}




<style>
	h3 {
		@apply text-center text-xl bg-teal-200 font-bold sticky top-0;
	}
	p.info {
		@apply px-8 py-4 text-center;
	}
	.section {
		@apply mt-4 py-1 font-bold bg-teal-200;
	}
    .number {
        @apply px-1 text-right;
    }
    .text {
        @apply px-1 text-center;
    }
	.pair {
		@apply bg-teal-50;
	}

	span {
		@apply align-bottom;
	}
</style>