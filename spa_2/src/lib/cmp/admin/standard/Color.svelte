<script>
	import { slide } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import Form, { CheckBox, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';
	import model from '$lib/js/model.js';
	import {goto} from '$app/navigation';

	let { breed, color=$bindable() } = $props();

	console.log('Color cmp');

	let authorized = $derived( ctx.user && ctx.user.admin );
	let edit = $state( false );
	let remove = $state( false );

	if( color.id === 0 ) edit = true;

	const validate = {
		name   : v => validator(v).string().length( 2, 64 ).orNullIf( remove ).isValid(),
	}

	function onEdit() {
		edit = ! edit;
	}

	async function onSubmit( event ) {
		console.log( 'Submit color' );
		//dirty.standard = true; // reload fed when needed
		if( color.name ) {
			let response = await model.Standard.saveColor( color );
			return response;
		} else if( ! color.name && remove && confirm('Lösschen?') ){ // name is null and delete
			console.log('A');
			let response = await model.Standard.deleteColor( color.id );
			if( response ) {
				let index = breed.colors.findIndex( item => item.id === color.id );
				console.log('B', index);
				breed.colors.splice( index, 1 );
			}
			//await goto('/article'); // back to list, no return
			return response
		}
		return true;
	}

</script>

<div class='flex flex-col'>
	{#if color}
		<div class='flex flex-rowflex flex-row p-2 gap-x-1' title='Farbenschlag'><div class='grow italic'>• {color.name}</div>
			{#if authorized}
				<button class='w-8 border-button bg-button text-button' onclick={onEdit}>
					{#if edit}⯇{:else}▶{/if}
				</button>
			{:else}
				<div class='w-8'></div>
			{/if}
			<div class='w-8'></div>
		</div>

		{#if authorized && edit}
			<Form autosubmit onsubmit={onSubmit}>
				<fieldset class='ml-8 flex flex-col px-2'>
					<div class='flex flex-row gap-x-2'>
						<TextInput class='w-8' label='Id' value={color.id} disabled />
						<TextInput class='w-128' label='Name' bind:value={color.name} validator={validate.name}/>
						<div class='grow'></div>
						<CheckBox bind:value={ remove }
						          label='Löschen'
						          title='Nur wenn Name leer und keine meldungen'
						          disabled={color.name}
						/>
						<Status />
					</div>

				</fieldset>
			</Form>
		{/if}
	{/if}
</div>

<style>
    li {
        @apply flex flex-row p-2 italic;
    }
</style>