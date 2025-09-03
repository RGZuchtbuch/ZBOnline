<script>
	import { slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';
	import { Form, Select, Status } from '$lib/cmp/form/Form.svelte';

	let { pair=$bindable() } = $props();

	// initial getting objects from pair

	let rootSection = $state( ctx.standard.rootSections.find( ( section ) => section.id === pair.sectionId ) ?? null );
	let breed   = $state( ctx.standard.breeds[ pair.breedId ] ?? null );
	let color   = $state( ctx.standard.colors[ pair.colorId ] ?? null );

	let breedId = $state( pair.breedId );

	function onSectionChange( event ) {
		console.log( 'OnSectionChange' );
		rootSection = ctx.standard.rootSections.find( ( section ) => section.id === pair.sectionId );
			//pair.sectionId = rootSection ? rootSection.id : null;
		pair.sectionId = rootSection.id;
		pair.breedId = breed = null;
		pair.colorId = color = null;
	}
	function onBreedChange( event ) {
		console.log( 'OnBreedChange' );
		pair.breed = breed = ctx.standard.breeds[ pair.breedId ];
		pair.colorId = color = null;
		console.log( 'OnBreedChange', pair.breed );

	}
	function onColorChange( event ) {
		color = ctx.standard.colors[ pair.colorId ];
	}

</script>

<fieldset class='flex flex-row p-2 gap-x-2 border' in:slide>
	<legend>Standard <Status /></legend>
	<Select class='w-56' label={'Sparte'} bind:value={pair.sectionId} onchange={onSectionChange}>
		<option value={null} selected={rootSection === null}>
			Sparte ?
		</option>
		{#each ctx.standard.rootSections as section }
			<option value={ section.id } selected={pair.sectionId === section.id}>
				{section.name}
			</option>
		{/each}
	</Select>

	<Select class='w-96' label={'Rasse'} bind:value={pair.breedId} onchange={onBreedChange} disabled={rootSection === null}>
		<option value={null} selected={breed === null}>
			Rasse ?
		</option>
		{#if rootSection}
			{#each rootSection.breeds as breed }
				<option value={breed.id} selected={pair.breedId === breed.id}>
					{breed.name}
				</option>
			{/each}
		{/if}
	</Select>

	<Select class='w-80' label={'Farbe'} bind:value={pair.colorId} onchange={onColorChange} disabled={breed === null}>
		<option value={null} selected={color === null}>
			Farbenschlag ?
		</option>
		{#if breed}
			{#each breed.colors as color }
				<option value={color.id} selected={pair.colorId === color.id}>
					{color.name} ({color.id})
				</option>
			{/each}
		{/if}
	</Select>
</fieldset>