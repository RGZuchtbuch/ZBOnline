<script>
	import { fade, slide } from 'svelte/transition';
	import {goto, invalidate} from '$app/navigation';
	import { page } from '$app/state';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import { Select } from '$lib/cmp/form/Form.svelte';
	import Breed from './district/Breed.svelte';

	let { args, results=$bindable() } = $props();

	let authorized = $derived( ctx.user && ( ctx.user.id === ctx.district.moderator.id || ctx.user.admin ) ); // can edit

	let years = []; // create years array for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year ); // Todo, move to tools as function ?

	function onYearChange( event ) {
		const year = event.target.value;
		let url = new URL( page.url ); // needed tro work
		url.searchParams.set( 'year', year );
		goto( url.href );
	}

	function onSectionChange( event ) {
		const sectionId = event.target.value;

		let url = new URL( page.url );
		url.searchParams.set( 'section', sectionId );
		goto( url.href );
	}
	function onGroupChange( event ) {
		console.log('Group', event.target.value)
		const group = event.target.value;
		//const href = `/moderator/${data.district.id}/result/${data.year}/edit/section/${data.section.id}/group/${group}`;
		let url = new URL( page.url );
		url.searchParams.set( 'group', group );
		goto( url.href );
	}

</script>

{#if ctx.district && args }
	<div class='flex flex-row border-header bg-header text-header text-xl p-2 pt-1 gap-x-2 justify-center sticky top-1' in:fade>
		<span class='pt-5 font-bold'>Leistungen eingeben für </span>
		<div class='flex flex-col'>
			<span class='text-xs pl-2'>Jahr</span>
			<select class='border-header bg-white' label='Jahr' value={ctx.year} onchange={onYearChange}>
				{#each years as year}
					<option value={year}>{year}</option>
				{/each}
			</select>
		</div>

		<div class='flex flex-col'>
			<span class='text-xs pl-2'>Sparte</span>
			<select class='border-header bg-white' label="Sparte" value={args.section} onchange={onSectionChange} title='Sparte zum Eingeben'>
				{#each ctx.standard.rootSections as section}
					<option value={section.id}>{section.name}</option>
				{/each}
				<!--option-- value={cfg.aocSection.id}>{ cfg.aocSection.name }</option-->
			</select>
		</div>

		<div class='flex flex-col'>
			<span class='text-xs pl-2'>ZB Gruppe</span>
			<select class='w-24 border-header bg-white'  label="ZB Gruppe" value={args.group} onchange={onGroupChange} disabled={args.section && args.section.id === 5 } title='Zuchtbuchgruppe I, II oder III'>
				{#each cfg.groups as group}
					<option value={group}>{group}</option>
				{/each}
			</select>
		</div>
	</div>

	<div class ='flex flex-row bg-gray-50' in:fade>
		<p class='grow info'>
			Leistungsdaten gesamt eingabe per Sparte und Gruppe für den Verband
		</p>
	</div>
{/if}

{#if results}
	<div>
		{#each results as breed}
			<Breed district={ctx.district} year={args.year} sectionId={args.section} group={args.group} {breed} />
		{/each}
	</div>
{/if}




<style>
	h3 {
		@apply text-center text-xl bg-teal-200 font-bold sticky top-0;
	}
	p.info {
		@apply px-8 py-4 text-center;
	}
	span {
		@apply align-bottom;
	}
</style>