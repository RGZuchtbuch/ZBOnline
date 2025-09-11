<script>
	import { fade, slide } from 'svelte/transition';
	import {goto, invalidate} from '$app/navigation';
	import { page } from '$app/state';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	import { Select } from '$lib/cmp/form/Form.svelte';
	import Breed from './input/Breed.svelte';

	let { args, results=$bindable() } = $props();
	// ex. args => { district:7, group:'I', section:12, year:2025 }

	$inspect( 'r', args )

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
		//const href = `/moderator/${data.district.id}/result/${data.year}/edit/section/${sectionId}/group/${data.group}`;
		//goto( href );
	}
	function onGroupChange( event ) {
		const group = event.target.value;
		//const href = `/moderator/${data.district.id}/result/${data.year}/edit/section/${data.section.id}/group/${group}`;
		let url = new URL( page.url );
		url.searchParams.set( 'group', group );
		//goto( url.href );
	}

</script>

{#if ctx.district && args }
	<div class='flex flex-row border border-gray-400 bg-gray-50 p-2 gap-x-4 justify-center' in:fade>
		<span class='py-3 font-bold'>Leistungen eingeben für </span>
		<Select class='' label='Jahr' value={ctx.year} onchange={onYearChange}>
			{#each years as year}
				<option value={year}>{year}</option>
			{/each}
		</Select>

		<Select label="Sparte" value={args.section} onchange={onSectionChange} title='Sparte zum Eingeben'>
			{#each ctx.standard.rootSections as section}
				<option value={section.id}>{section.name}</option>
			{/each}
			<!--option-- value={cfg.aocSection.id}>{ cfg.aocSection.name }</option-->
		</Select>

		<Select class='w-16' label="ZB Gruppe" value={args.group} onchange={onGroupChange} disabled={args.section && args.section.id === 5 } title='Zuchtbuchgruppe I, II oder III'>
			{#each cfg.groups as group}
				<option value={group}>{group}</option>
			{/each}
		</Select>
	</div>

	<div class ='flex flex-row bg-gray-50' in:fade>
		<p class='grow info'>
			Leistungsdaten gesamt eingabe per Sparte und Gruppe für den Verband
		</p>
	</div>
{/if}

{#if results}
	{#if args.section.id === 9999} <!-- AOC -->
		<!-- div>
			<AocCreate {data}/>
			DB {data.breeds.length}
			{#each data.breeds as breed}
				A
				<AocResult section={data.section} {breed} {data}/>
			{/each}
		</div -->
		<!--AOC {district} {year} {group} /-->
	{:else}
		<div>
			{#each results as breed}
				<Breed district={ctx.district} year={args.year} sectionId={args.section} group={args.group} {breed} />
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
	span {
		@apply align-bottom;
	}
</style>