<script>
	import { slide } from 'svelte/transition';
	import {cfg, ctx} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Section from './Section.svelte';
	import Breed from './Breed.svelte';
	import Form, { CheckBox, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';

	let { section=$bindable(), unfold=false } = $props();

	let authorized = $derived( ctx.user && ctx.user.admin );
	let edit = $state( false );

	function onToggle() {
		unfold = ! unfold;
	}

	function onEdit() {
		edit = ! edit;
	}

	function onAddBreed() {
		let newBreed = model.Standard.createBreed( section.id );
		section.breeds.unshift( newBreed );
		console.log( 'Add breed' );
	}

</script>

<div class='flex flex-col'>
	{#if section}
		<div class='flex flex-row p-2 gap-x-1'>
			<button class='grow flex flex-row bg-inherit text-black font-bold py-2 gap-x-2 text-left' type='button' title='Öffnen' onclick={onToggle}>
				<span class='w-4'>{unfold?'▽':'▷'}</span>
				<span class='grow text-left' title='Sparte'>{section.name} <sup>({section.breeds.length})</sup></span>

				{#if unfold && section.children.length === 0}
					<div class='w-10 text-center' title='Brutgruppe'>
						{#if section.parentId === cfg.pigeons} B.G. {/if}
					</div>
					<div class='w-10 text-center' title='Legeleistung'>
						{#if section.parentId !== cfg.pigeons} Eier {/if}
					</div>
					<div class='w-10 text-center' title='Bruteigewicht'>
						{#if section.parentId !== cfg.pigeons} Gew. {/if}
					</div>
					<div class='w-24 text-center' title='Zielgewicht der Hähne'>Gewicht</div>
					<div class='w-20 text-center' title='Ringgröße Hahn'>Ring</div>
				{/if}
			</button>

			{#if authorized}
				<button class='w-8 border-button bg-inherit text-black' title='Bearbeiten' onclick={onEdit}>
					{#if edit}⯇{:else}▶{/if}
				</button>
				{#if unfold && section.children.length === 0 }
					<button class='w-8 bg-inherit text-black' title='Rasse hinzufügen' onclick={onAddBreed}>✙</button>
				{:else}
					<div class='w-8'></div>
				{/if}
			{:else}
				<div class='w-8'></div>
			{/if}

		</div>

		{#if authorized && edit}
			<Form autosave='true'>
				<fieldset class='ml-8 flex flex-col px-2'>
					<div class='flex flex-row'>
						<TextInput class='w-8' label='Id' value={section.id} disabled />
						<TextInput label='Name' bind:value={section.name} />
						<CheckBox label='Leger' bind:value={section.layer} />
						<div class='grow'></div>
						<Status />
					</div>
					<TextInput class='w-16' label='Folge' bind:value={section.order} />
					<TextInput class='w-8' label='Parent' value={section.parentId} disabled />
					<TextInput class='w-8' label='Root' value={section.rootId} disabled />
				</fieldset>
			</Form>
		{/if}

		{#if unfold}
			<ul class='pl-8' transition:slide={{duration:500}}>
				{#each section.children as child, i}
					{#key child}
						<Section bind:section={section.children[i]} />
					{/key}
				{/each}
			</ul>
			{#if section.children.length === 0}
				<ul class='pl-8' transition:slide={{duration:500}}>
					{#each section.breeds as breed, i}
						{#key breed}
							<Breed {section} bind:breed={section.breeds[i]} />
						{/key}
					{/each}
				</ul>
			{/if}
		{/if}
	{/if}
</div>


<style>
    li {
        @apply flex flex-row p-2 gap-x-1;
    }

</style>