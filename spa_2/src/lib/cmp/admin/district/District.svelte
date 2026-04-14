<script>
	import {ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import { fullName, selectName } from '$lib/js/tools.js';
	import Form, { CheckBox, NumberInput, Select, Status, TextInput } from '$lib/cmp/form/Form.svelte';
	import District from './District.svelte';
	import {goto} from '$app/navigation';
	import {onDestroy} from 'svelte';

	let { district=$bindable() } = $props();

	let authorized = $state( ctx.user && ctx.user.admin );
	let edit = $state( false );
	let changed = false;
	let breeders = $state( null );

	$effect( async () => {
		if( edit && breeders === null ) {
			breeders = await model.Breeder.query( {district:district.id} ); // for moderator list
		}
	})

	function onEdit() {
		edit = ! edit;
	}

	function onModeratorChange( event ) {
		console.log( 'Mod' );
		district.moderator = breeders.find( ( breeder ) => breeder.id === district.moderatorId );
	}

	async function onSubmit() {
		if( authorized ) {
			console.log('Submit', district.name);
			return await model.Federation.saveDistrict( district );
		} else {
			console.log( 'Not authorized' );
			return false;
		}
	}

</script>

<div class='ml-6 flex flex-col py-2'>
	<div class='flex flex-row grow py-2 border-b rounded-b-none'>
		<span class='grow'>{district.name}</span>
		<span class='w-64'>{ fullName( district.moderator ) }</span>
		<div class='w-16 text-center'>
			{#if district.moderator && district.moderator.email}
				<a class='px-2 border-button bg-button text-button text-center print:hidden' href={`/federation/message?district=${district.id}&to=${district.moderator.id}`} title='Email schicken'> ✉ </a>
			{/if}
		</div>

		<span class='w-16 text-center'>
			<a class='px-2 border-button bg-button text-button text-center' href={`/moderator/district/${district.id}`} title='Verband verwalten als Obmann'> ⚙ </a>
			<!--a class='px-2 border-button bg-button text-button text-center' href={`/admin/district/${district.id}`} title='Verband verwalten als Obmann'> ⚙ </a-->
		</span>

		<div class='w-16 text-center'>
			<button class='px-2 border-button bg-button text-button' title='Daten bearbeiten' onclick={onEdit}>
				{#if edit}⯇{:else}▶{/if}
			</button>
		</div>
	</div>

	{#if authorized && edit}
		<Form autosubmit onsubmit={onSubmit} >
			<fieldset class='rounded px-4'>
				<legend>Verbandsdaten ändern</legend>
				<div class='flex flex-row justify-end'><Status /></div>
				<TextInput class='w-128' label='Name' bind:value={district.name} />
				<TextInput class='w-192' label='Name voll' bind:value={district.fullname} />
				<TextInput class='w-64' label='Name abk.' bind:value={district.short} />
				<TextInput class='w-192' label='Webseite (URL)' bind:value={district.url} />
				<div class='flex flex-row gap-x-2'>
					<NumberInput class='w-32' label='latitude' bind:value={district.latitude} />
					<NumberInput class='w-32' label='longitude' bind:value={district.longitude} />
				</div>

				<Select label='Obmann' bind:value={district.moderatorId} onchange={onModeratorChange}>
					<option value={null} >?</option>
					{#if breeders}
						{#each breeders as breeder}
							{#key breeder}
								<option value={breeder.id} > {selectName( breeder ) } </option>
							{/key}
						{/each}
					{/if}
				</Select>
			</fieldset>
		</Form>
	{/if}

	{#if district.children}
		{#each district.children as child, i}
			<District bind:district={district.children[i]} />
		{/each}
	{/if}
</div>



<style>
    section {
        @apply flex flex-col my-2 border;
    }
    li {
        @apply pl-4 whitespace-nowrap;
    }
    .district {
        @apply grow;
    }
    .moderator {
        @apply w-64;
    }
    .email {
        @apply w-12 text-center;
    }
    .goto {
        @apply w-20 text-center;
    }
    .edit {
        @apply w-20 text-center print:hidden;
    }
</style>