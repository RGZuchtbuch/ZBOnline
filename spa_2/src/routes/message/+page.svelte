<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import Message from '$lib/cmp/message/Message.svelte';

	let { data } = $props();

	let to = page.url.searchParams.has('to') ? +page.url.searchParams.get('to') : null;

	console.log( 'ID', to )

	$effect( () => {
		ctx.header = {
			title : `Bericht am Obmann ${to}`,
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Bericht' },
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		}
	});


</script>

{#if to }
	<Message {to} />
{/if}


