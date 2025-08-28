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
	<li class='font-bold'>
		<button class='inline' type='button' title='Öffnen' onclick={toggle}>{unfold?'▽':'▷'} </button>
		<div class='grow' title='Sparte'>{section.name}</div>
		{#if unfold && section.breeds.length > 0}
			<div class='w-16 text-right' title='Brutgruppe'>
				{#if section.parentId === cfg.pigeons} Brutgruppe {/if}
			</div>
			<div class='w-16 text-right' title='Legeleistung'>
				{#if section.parentId !== cfg.pigeons} Eier/J {/if}</div>
			<div class='w-16 text-right' title='Bruteigewicht'>
				{#if section.parentId !== cfg.pigeons} Gewicht {/if}
			</div>
			<div class='w-32 text-center' title='Zielgewicht der Hähne'>Tiergewicht</div>
			<div class='w-24 text-center' title='Ringgröße Hahn'>Ring</div>

		{/if}
	</li>


	{#if unfold}
		<ul class='pl-8' transition:slide={{duration:500}}>
			{#each section.children as child}
				<Section section={child} />
			{/each}
		</ul>

		<ul class='pl-8' transition:slide={{duration:500}}>
			{#each section.breeds as breed}
				<Breed {breed} />
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