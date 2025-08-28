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

<div class='flex flex-col pl-6'>
	<div class='flex flex-row py-2 '>
		<span class='w-4'>•</span>
		<div class='district'>{district.name}</div>
		<div class='moderator'>{ fullName( district.moderator ) }</div>
		<div class='email print:hidden'>
			{#if district.moderator}
				<a href={`/federation/message?district=${district.id}&to=${district.moderator.id}`} title='Email schicken'> ✉ </a>
			{/if}
		</div>
		<div class='link print:hidden'>
			{#if district.url}
				<a href={district.url} class='' title='Website besuchen' target='_blank'> ⓘ </a>
			{/if}
		</div>

	</div>
	{#if district.children}
		<ul>
			{#each district.children as child}
				<District district={child} />
			{/each}
		</ul>
	{/if}
</div>



<style>

    li {
        @apply pl-4;
    }
    .district {
        @apply grow whitespace-nowrap;
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