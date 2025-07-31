<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Standard from '$lib/cmp/standard/Standard.svelte';


	$effect( () => {
		if( page.url ) setHeader();
	})

	function setHeader() {
		ctx.header = {
			title: 'BDRG Rassegeflügel Standard',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Standard', href: '/standard'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		}
	}
</script>

{#if ctx.standard}
	<Standard standard={ctx.standard}/>
{/if}
