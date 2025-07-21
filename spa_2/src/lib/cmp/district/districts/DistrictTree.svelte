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

<section>
	{#if root}
		<div class='flex flex-row header sticky top-0'>
			<div class='district'>Verbände </div>
			<div class='moderator'>Obmann</div>
			<div class='email' title='Email schicken'> ✉ </div>
			<div class='link' title='Website besuchen'> 🔗 </div>
			{#if authorized}
				<div class='w-8' title='Admin'>!</div>
			{/if}

		</div>

		<ul>
			<District district={ root } />
		</ul>
	{/if}
</section>



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
        @apply w-12;
    }
    .link {
        @apply w-12;
    }


</style>