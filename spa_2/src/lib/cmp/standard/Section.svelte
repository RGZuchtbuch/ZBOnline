<script>
	import { slide } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import Section from '$lib/cmp/standard/Section.svelte';
	import Breed from '$lib/cmp/standard/Breed.svelte';
	import Form, { CheckBox, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';

	let { section=null, unfold=false } = $props();

	let authorized = $derived( ctx.user && ctx.user.admin );
	let edit = $state( false );

	function toggle() {
		unfold = ! unfold;
	}

	function onEdit() {
		edit = ! edit;
	}

	function onAddChild() {
		console.log( 'Add child' );
	}
	function onAddBreed() {
		console.log( 'Add breed' );
	}

</script>

{#if section}
	<button class='bg-inherit text-black font-bold w-full flex flex-row py-2 gap-x-2 text-left' type='button' title='Öffnen' onclick={toggle}>
		<span class='w-4'>{unfold?'▽':'▷'}</span>
		<span class='grow text-left' title='Sparte'>{section.name} <sup>({section.breeds.length})</sup></span>
		{#if unfold && section.children.length === 0}
			<span class='w-16 text-right' title='Brutgruppe'>
				{#if section.parentId === cfg.pigeons}B.G.{/if}
			</span>
			<span class='w-16 text-right' title='Legeleistung'>
				{#if section.parentId !== cfg.pigeons}Eier{/if}
			</span>
			<span class='w-16 text-right' title='Bruteigewicht'>
				{#if section.parentId !== cfg.pigeons}Gewicht{/if}
			</span>
			<span class='w-32 text-center' title='Zielgewicht der Hähne'>Tiergewicht</span>
			<span class='w-24 text-center' title='Ringgröße Hahn'>Ring</span>
		{/if}
	</button>




	{#if unfold}
		<div class='pl-8' transition:slide={{duration:500}}>
			{#each section.children as child}
				<Section section={child} />
			{/each}
		</div>
	{/if}

	{#if unfold && section.children.length === 0}
		<div class='pl-8' transition:slide={{duration:500}}>
			{#each section.breeds as breed}
				<Breed {breed} />
			{/each}
		</div>
	{/if}

{/if}



<style>

</style>