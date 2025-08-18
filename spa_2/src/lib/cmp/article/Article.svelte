<script>
	import {onDestroy} from 'svelte';
	import { goto, invalidate, invalidateAll} from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Form, { CheckBox, NumberInput, Status, TextArea, TextInput, validator } from '$lib/cmp/form/Form.svelte';

	let { article } = $props();

</script>

{#if article}
	<div class='flex flex-row items-center justify-end gap-x-2 p-2 text-xs italic'>
		{article.author}, {article.modified}
	</div>

	<p class='px-6 py-2'>
		{@html article.html}
	</p>


{/if}

<style>
	.meta {
		font-size: 0.6em;
		@apply italic;
	}
</style>
