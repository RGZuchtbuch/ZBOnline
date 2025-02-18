<script>
	import { slide } from 'svelte/transition';
	import { app } from '$lib/js/store.svelte.js'
	import { Form, Select } from '$lib/cmp/form/Form.svelte';

	let { pair, standard } = $props();

	// initial getting objects from pair
	let rootSection = $state( standard.rootSections.find( ( section ) => section.id === pair.sectionId ) ?? null );
	let breed   = $state( standard.breeds[ pair.breedId ] ?? null );
	let color   = $state( standard.colors[ pair.colorId ] ?? null );

	function onSectionChange( event ) {
		//rootSection = data.rootSections[ data.pair.sectionId ]; // auto
		pair.sectionId = rootSection ? rootSection.id : null;
		breed = pair.breedId = null;
		color = pair.colorId = null;
		console.log( 'BS', rootSection.id )
	}
	function onBreedChange( event ) {
		//breed = data.standard.breeds[ data.pair.breedId ]; // auto
		pair.breed = breed;
		pair.breedId = breed ? breed.id : null;
		color = pair.colorId = null;
		console.log( 'BB', breed.id);
	}
	function onColorChange( event ) {
		pair.colorId = color ? color.id : null;
		console.log( 'BC')
	}

</script>

<fieldset class='flex flex-row p-2 gap-x-2 border' in:slide>
	<legend>Standard</legend>
	<!--Form class='flex flex-row gap-x-2 border p-2'-->

		<Select class='w-56' label={'Sparte'} bind:value={rootSection} onchange={onSectionChange}>
			<option value={null} selected={rootSection === null}>
				Sparte ?
			</option>
			{#each standard.rootSections as section }
				<option value={ section } selected={pair.sectionId === section.id}>
					{section.name}
				</option>
			{/each}
		</Select>

		<Select class='w-96' label={'Rasse'} bind:value={breed} onchange={onBreedChange} disabled={rootSection === null}>
			<option value={null} selected={breed === null}>
				Rasse ?
			</option>
			{#if rootSection}
				{#each rootSection.breeds as breed }
					<option value={breed} selected={pair.breedId === breed.id}>
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
					<option value={color} selected={pair.colorId === color.id}>
						{color.name} ({color.id})
					</option>
				{/each}
			{/if}
		</Select>

	<!--/Form-->
</fieldset>