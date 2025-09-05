<script>
	import { ctx } from '$lib/js/store.svelte.js';
	//import model from '$lib/js/model.js';
	import { email_icon, link_icon } from '$lib/cmp/icons.svelte';
	//import { name } from '$lib/cmp/snippets.svelte';
	//import Form, { CheckBox, NumberInput, Select, Status, TextInput } from '$lib/cmp/form/Form.svelte';
	//import Text from '$lib/cmp/form/input/Text.svelte';
	import District from './District.svelte';

	let { root } = $props();

	let authorized = $state( ctx.user && ctx.user.admin );

</script>

<section class='flex flex-col pl-4'>
	{#if authorized && root}
		<header class='flex flex-row border-header bg-header text-header px-2 sticky top-1'>
			<span class='grow'>Verbände </span>
			<span class='w-64'>Obmann</span>
			<span class='w-16 text-center' title='Email schicken'>Email</span>
			<span class='w-16 text-center' title='Als Obmann bearbeiten'>Verw.</span>
			<span class='w-16 text-center' title='Daten bearbeiten'>Bearb.</span>
		</header>

		<District district={ root } />
	{/if}
</section>



<style>
    .district {
	    @apply grow;
    }
    .moderator {
	    @apply w-64;
    }
    .email {
	    @apply w-12;
    }
    .goto {
        @apply w-20 text-center;
    }
    .edit {
        @apply w-20 text-center print:hidden;
    }


</style>