<script>
	import { slide } from 'svelte/transition';
	import { app } from '$lib/js/store.svelte.js'
	import { Form, Select } from '$lib/cmp/form/Form.svelte';

	let { pair } = $props();


	// initial getting objects from pair
	let rootSection = $state( app.rootSections.find( ( section ) => section.id === pair.sectionId ) ?? null );
	let breed   = $state( app.standard.breeds[ app.pair.breedId ] ?? null );
	let color   = $state( app.standard.colors[ app.pair.colorId ] ?? null );

	function onSectionChange( event ) {
		//rootSection = data.rootSections[ data.pair.sectionId ]; // auto
		pair.sectionId = rootSection ? rootSection.id : null;
		breed = pair.breedId = null;
		color = pair.colorId = null;
	}
	function onBreedChange( event ) {
		//breed = data.standard.breeds[ data.pair.breedId ]; // auto
		pair.breedId = breed ? breed.id : null;
		color = pair.colorId = null;
	}
	function onColorChange( event ) {
		pair.colorId = color ? color.id : null;
	}

	$inspect( 'rootSection', rootSection );
	$inspect( 'breed', breed );
	$inspect( 'color', color );


</script>

<fieldset class='flex flex-row p-2 gap-x-2 border' in:slide>
	<legend>Standard</legend>
	<!--Form class='flex flex-row gap-x-2 border p-2'-->

		<Select class='w-56' label={'Sparte'} bind:value={rootSection} onchange={onSectionChange}>
			<option value={null} selected={rootSection === null}>
				Sparte ?
			</option>
			{#each app.rootSections as section }
				<option value={ section } selected={app.pair.sectionId === section.id}>
					{section.name} ({rootSection.id})
				</option>
			{/each}
		</Select>

		<Select class='w-96' label={'Rasse'} bind:value={breed} onchange={onBreedChange} disabled={rootSection === null}>
			<option value={null} selected={breed === null}>
				Rasse ?
			</option>
			{#if rootSection}
				{#each rootSection.breeds as breed }
					<option value={breed} selected={app.pair.breedId === breed.id}>
						{breed.name} ({breed.id})
					</option>
				{/each}
			{/if}
		</Select>

		<Select class='w-80' label={'Farbe'} bind:value={color} onchange={onColorChange} disabled={breed === null}>
			<option value={null} selected={color === null}>
				Farbenschlag ?
			</option>
			{#if breed}
				{#each breed.colors as color }
					<option value={color} selected={app.pair.colorId === color.id}>
						{color.name} ({color.id})
					</option>
				{/each}
			{/if}
		</Select>

	<!--/Form-->
</fieldset>