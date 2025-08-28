<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Message from '$lib/cmp/message/Message.svelte';

	let { data } = $props();

	let districtId = page.url.searchParams.has('district') ? +page.url.searchParams.get('district') : null;
	let to = page.url.searchParams.has('to') ? +page.url.searchParams.get('to') : null;

	let district = districtId ? ctx.federation.districts[ districtId ] : null;

	console.log( 'ID', to )

	$effect( () => {

		ctx.menustate[ '/tools' ] = page.url.href;

		ctx.title = `Nachricht am Obmann vom ${district.name}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			//{name: 'Verbände', href: '/federation'},
			{name: 'Nachricht' },
		];
	});


</script>

{#if to }
	<Message {to} />
{/if}


