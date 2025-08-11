<script>
	import { slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Section from './Section.svelte';
	import Breed from './Breed.svelte';
	import Form, { CheckBox, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';

	let { section=null, unfold=false } = $props();

	let authorized = $derived( ctx.user && ctx.user.admin );
	let edit = $state( false );

	function onToggle() {
		unfold = ! unfold;
	}

	function onEdit() {
		edit = ! edit;
	}

	function onAddChild() {
		console.log( 'Add child' );
	}
	function onAddBreed() {
		let newBreed = model.Standard.createBreed( section.id );
		section.breeds.unshift( newBreed );
		console.log( 'Add breed' );
	}

</script>

{#if section}
	<li class=''>
		<button class='inline' type='button' title='Öffnen' onclick={onToggle}>{unfold?'▽':'▷'} </button>
		<div class='grow font-bold' title='Sparte'>{section.name}</div>
		{#if authorized}
			<button class='w-8' title='Bearbeiten als Admin' onclick={onEdit}>e</button>
			{#if unfold && section.children.length === 0 }
				<button class='w-8' title='Rasse hinzufügen' onclick={onAddBreed}>+</button>
			{/if}
		{:else}
			<div class='w-8'></div>
		{/if}

	</li>

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
			{#each section.children as child}
				<Section section={child} />
			{/each}
		</ul>

		<ul class='pl-8' transition:slide={{duration:500}}>
			{#each section.breeds as breed}
				{#key breed.id}
					<Breed {section} {breed} />
				{/key}
			{/each}
		</ul>
	{/if}
{/if}

<style>
    li {
        @apply flex flex-row p-2 gap-x-1;
    }
    button {
        @apply bg-inherit text-black;
    }



</style>