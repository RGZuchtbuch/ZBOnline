<script>
	import { slide } from 'svelte/transition';
	import { Form, Select } from '$lib/cmp/form/Form.svelte';

	let { pair=$bindable(), standard } = $props();

	// initial getting objects from pair
	let rootSection = $state( standard.rootSections.find( ( section ) => section.id === pair.sectionId ) ?? null );
	let breed   = $state( standard.breeds[ pair.breedId ] ?? null );
	let color   = $state( standard.colors[ pair.colorId ] ?? null );

	let breedId = $state( pair.breedId );

	function onSectionChange( event ) {
		rootSection = standard.rootSections.find( ( section ) => section.id === pair.sectionId );
			//pair.sectionId = rootSection ? rootSection.id : null;
		pair.sectionId = rootSection.id;
		pair.breedId = breed = null;
		pair.colorId = color = null;
	}
	function onBreedChange( event ) {
		pair.breed = breed = standard.breeds[ pair.breedId ];
		pair.colorId = color = null;
	}
	function onColorChange( event ) {
		color = standard.colors[ pair.colorId ];
	}

</script>

<fieldset class='flex flex-row p-2 gap-x-2 border' in:slide>
	<legend>Standard</legend>
	<Select class='w-56' label={'Sparte'} bind:value={pair.sectionId} onchange={onSectionChange} disabled={breed}>
		<option value={null} selected={rootSection === null}>
			Sparte ?
		</option>
		{#each standard.rootSections as section }
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
					{breed.name} ({breed.id})
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