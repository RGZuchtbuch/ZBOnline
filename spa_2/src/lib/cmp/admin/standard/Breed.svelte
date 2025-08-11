<script>
	import { slide } from 'svelte/transition';
	import { cfg, ctx} from '$lib/js/store.svelte.js';
	import Color from './Color.svelte';
	import Form, { CheckBox, NumberInput, Select, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';
	import model from '$lib/js/model.js';

	let { section, breed, unfold=false } = $props();
	let authorized = $derived( ctx.user && ctx.user.admin );
	let edit   = $state( false );
	let remove = $state( false );

	const validate = {
		name   : v => validator(v).string().length( 2, 64 ).orNullIf( remove ).isValid(),
		layEggs   : v => validator(v).number().range( 1, 399 ).orNull().isValid(),
		layWeight : v => validator(v).number().range( 1, 9999 ).orNull().isValid(),
		weight : v => validator(v).number().range( 50, 20000 ).orNull().isValid(),
		ring   : v => validator(v).number().range( 2, 99 ).orNull().isValid(),
	}

	function onToggle() {
		unfold = ! unfold;
	}

	function onEdit() {
		console.log( 'Breed edit' );
		edit = ! edit;
	}
	function onAddColor() {
		let newColor = model.Standard.createColor( breed.id );
		breed.colors.unshift( newColor );
		console.log( 'Add color' );
	}

	async function onSubmit( event ) {
		console.log( 'Submit breed' );
		//dirty.standard = true; // reload fed when needed
		if( breed.name ) {
			let response = await model.Standard.saveBreed( breed );
			return response;
		} else if( ! breed.name && remove && confirm('Lösschen?') ){ // name is null and delete
			console.log('A');
			let response = await model.Standard.deleteBreed( breed.id );
			if( response ) {
				let index = section.breeds.findIndex( item => item.id === breed.id );
				section.breeds.splice( index, 1 );
				console.log('B', index);
			}
			//await goto('/article'); // back to list, no return
			return response
		}
		return true;
	}

</script>

{#if breed}
	<li class=''>
		<button class='inline' type='button' title='Farben' onclick={onToggle}>{unfold?'▽':'▷'}</button>

		<button class='inline' type='button' title='Farben' onclick={toggle}>{unfold?'▽':'▷'}</button>
		<div class='grow' title='Rasse'>{breed.name}</div>
		<div class='w-10 text-right' title='Brutgruppe'>{breed.broodGroup}</div>
		<div class='w-10 text-right' title='Legeleistung'>{breed.layEggs}</div>
		<div class='w-10 text-right' title='Bruteigewicht'>{breed.layWeight}</div>
		<div class='w-12 text-right' title='Zielgewicht der Hähne'>{breed.sireWeight}</div>
		<div class='w-12 text-left' title='Zielgewicht der Hennen'>.{breed.dameWeight}</div>
		<div class='w-10 text-right' title='Ringgröße Hahn'>{breed.sireRing}</div>
		<div class='w-10 text-left' title='Ringgröße Henne'>.{breed.dameRing}</div>


		{#if authorized}
			<button class='w-8' title='Bearbeiten als Admin' onclick={onEdit}>e</button>
			{#if unfold}
				<button class='w-8' title='Rasse hinzufügen' onclick={onAddColor}>+</button>
			{/if}
		{:else}
			<div class='w-8'></div>
		{/if}
	</li>

	{#if authorized && edit}
		<Form autosubmit onsubmit={onSubmit}>
			<fieldset class='ml-8 flex flex-col px-2'>
				<div class='flex flex-row gap-x-2'>
					<TextInput class='w-8' label='Id' value={breed.id} disabled />
					<TextInput label='Name' bind:value={breed.name} validator={validate.name}/>
					<div class='grow'></div>
					<CheckBox bind:value={ remove }
					          label='Löschen'
					          title='Nur wenn Name leer und keine meldungen'
					          disabled={breed.name}
					/>
					<Status />
				</div>

				{#if section.parentId === 5}
					<Select class='w-16' label='Brutgruppe'  bind:value={breed.broodGroup}>
						{#each cfg.broodGroups as group}
							<option value={group}>{group}</option>
						{/each}
					</Select>
				{:else}
					<div class='flex flex-row gap-x-2'>
						<NumberInput class='w-16' label='Eier' bind:value={breed.layEggs} validator={validate.layEggs}/>
						<NumberInput class='w-16' label='Eigewicht' bind:value={breed.layWeight} validator={validate.layWeight}/>
					</div>
				{/if}

				<div class='flex flex-row gap-x-2'>
					<NumberInput class='w-32' label='Hahn Gewicht'  bind:value={breed.sireWeight} validator={validate.weight}/>
					.
					<NumberInput class='w-32' label='Henne Gewicht'  bind:value={breed.dameWeight} validator={validate.weight}/>
				</div>

				<div class='flex flex-row gap-x-2'>
					<NumberInput class='w-32' label='Hahn Ring'  bind:value={breed.sireRing} validator={validate.ring} />
					.
					<NumberInput class='w-32' label='Henne Ring'  bind:value={breed.dameRing} validator={validate.ring} />
				</div>
			</fieldset>
		</Form>
	{/if}

	{#if unfold}
		<ul class='pl-12' transition:slide={{duration:breed.colors.length*25}}>
			{#if breed.colors.length > 0}
				{#each breed.colors as color}
					{#key color.id}
						<Color {breed} {color} />
					{/key}
				{/each}
			{:else}
				<li>Noch keine farbenschläge eingegeben</li>
			{/if}
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