<script>
	import {ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import { fullName, selectName } from '$lib/js/tools.js';
	import Form, { CheckBox, NumberInput, Select, Status, TextInput } from '$lib/cmp/form/Form.svelte';
	import District from './District.svelte';
	import {goto} from '$app/navigation';
	import {onDestroy} from 'svelte';

	let { district } = $props();

	let authorized = $state( ctx.user && ctx.user.admin );
	let edit = $state( false );
	let changed = false;
	let breeders = $state( null );

	$effect( async () => {
		if( edit && breeders === null ) {
			breeders = await model.Breeder.query( {district:district.id} );
		}
	})

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

<li class='pl-4'>
	<div class='flex flex-row grow py-2 border-b rounded-b-none'>
		<div class='district'>{district.name}</div>
		<div class='moderator'>{ fullName( district.moderator ) }</div>
		<div class='email print:hidden'>
			{#if district.moderator}
				<!--a href={`mailto:${district.moderator.email}`}> ✉ </a-->
				<a href={`/message?to=${district.moderator.id}`} title='Email schicken'> ✉ </a>
			{/if}
		</div>
		<div class='link print:hidden'>
			{#if district.url}
				<a href={district.url} title='Website besuchen' target='_blank'> 🔗 </a>
			{/if}
		</div>
		{#if authorized}
			<div class='w-8' title='Daten ändern'>
				<CheckBox bind:value={edit} />
			</div>
		{/if}

	</div>

	{#if authorized && edit}
		<Form autosubmit onsubmit={onSubmit} >
			<fieldset class='rounded px-4'>
				<legend>Verbandsdaten ändern</legend>
				<div class='flex flex-row justify-end'><Status /></div>
				<TextInput class='w-128' label='Name' bind:value={district.name} />
				<TextInput class='w-192' label='Name voll' bind:value={district.fullname} />
				<TextInput class='w-192' label='Name abk.' bind:value={district.short} />
				<TextInput class='w-192' label='Name voll' bind:value={district.url} />
				<div class='flex flex-row gap-x-2'>
					<NumberInput class='w-32' label='latitude' bind:value={district.latitude} />
					<NumberInput class='w-32' label='longitude' bind:value={district.longitude} />
				</div>

				<Select label='Obmann' bind:value={district.moderatorId}>
					<option value={null} >?</option>
					{#if breeders}
						{#each breeders as breeder}
							<option value={breeder.id} > {selectName( breeder ) } </option>
						{/each}
					{/if}
				</Select>
			</fieldset>
		</Form>
	{/if}

	{#if district.children}
		<ul>
			{#each district.children as child}
				<District district={child} />
			{/each}
		</ul>
	{/if}
</li>



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
    .link {
        @apply w-12 text-center;
    }

</style>