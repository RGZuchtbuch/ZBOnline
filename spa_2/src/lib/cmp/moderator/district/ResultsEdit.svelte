<script>
	import { fade, slide } from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { dec, txt } from '$lib/js/tools.js';
	import {Select} from '$lib/cmp/form/Form.svelte';
	import AOC from './results/input/AOC.svelte';
	import Breed from './results/input/Breed.svelte';

	let { district, year, section, group, breeds, data } = $props();

	let authorized = $derived( data.user && ( data.user.id === data.district.moderator.id || data.user.admin ) ); // can edit

	let years = []; // for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year );

	function onYearChange( event ) {
		const year = event.target.value;
		//let url = new URL( page.url );
		//url.searchParams.set( 'year', year );
		//goto( url.href );
		const href = `/moderator/${data.district.id}/result/${year}/edit/section/${data.section.id}/group/${data.group}`;
		goto( href );
	}

	function onSectionChange( event ) {
		const sectionId = event.target.value;
		//let url = new URL( page.url );
		//url.searchParams.set( 'section', section.id );
		//goto( url.href );
		const href = `/moderator/${data.district.id}/result/${data.year}/edit/section/${sectionId}/group/${data.group}`;
		goto( href );

	}
	function onGroupChange( event ) {
		const group = event.target.value;
		const href = `/moderator/${data.district.id}/result/${data.year}/edit/section/${data.section.id}/group/${group}`;
		//let url = new URL( page.url );
		//url.searchParams.set( 'group', group );
		//goto( url.href );
	}

	$inspect( 'RE b', data );

</script>

{#if district && group && section && year }
	<div class='flex flex-row border border-gray-400 bg-gray-50 p-2 gap-x-4 justify-center' in:fade>
		<span class='py-3 font-bold'>Leistungen eingeben für </span>
		<Select class='' label='Jahr' value={data.year} onchange={onYearChange}>
			{#each years as y}
				<option value={y}>{y}</option>
			{/each}
		</Select>

		<Select label="Sparte" value={data.section.id} onchange={onSectionChange} title='Sparte zum Eingeben'>
			{#each store.standard.rootSections as section}
				<option value={section.id}>{section.name}</option>
			{/each}
			<option value={store.aocSection.id}>{store.aocSection.name}</option>
		</Select>

		<Select class='w-16' label="ZB Gruppe" value={data.group} onchange={onGroupChange} disabled={section && section.id === 5 } title='Zuchtbuchgruppe I, II oder III'>
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

{#if data.breeds}
	{#if data.section.id === 9999}
		<!-- div>
			<AocCreate {data}/>
			DB {data.breeds.length}
			{#each data.breeds as breed}
				A
				<AocResult section={data.section} {breed} {data}/>
			{/each}
		</div -->
		<AOC {data} />
	{:else}
		<div>
			{#each data.breeds as breed}
				<Breed district={data.district} year={data.year} group={data.group} {breed} {data} />
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