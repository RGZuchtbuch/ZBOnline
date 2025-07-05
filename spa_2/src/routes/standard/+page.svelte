<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import Standard from '$lib/cmp/standard/Standard.svelte';

	let { data } = $props();

	$effect( () => {
		setHeader( data.standard );
	})

	function setHeader( standard ) {
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
					{name: 'Leistungen', href: '/report'},
				],
			},
		}
	}
</script>

<Standard standard={data.standard}/>

