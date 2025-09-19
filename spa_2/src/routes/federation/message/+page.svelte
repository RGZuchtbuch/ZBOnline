<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Message from '$lib/cmp/message/Message.svelte';

	let { data } = $props();

	let districtId = page.url.searchParams.has('district') ? +page.url.searchParams.get('district') : null;
	let to = page.url.searchParams.has('to') ? +page.url.searchParams.get('to') : null;

	let district = districtId ? ctx.federation.districts[ districtId ] : null;

	let mounted = $state( false );

	$effect( () => {
		if( district ) setHeader();

	});

	function setHeader() {
		ctx.menustate[ '/tools' ] = page.url.href;

		ctx.title = `Nachricht am Obmann vom ${district.name}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Verbände', href: '/federation'},
			{name: 'Nachricht' },
		];
	}

	onMount( () => mounted = true );

</script>

{#if to && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Message {to} />
	</main>
{/if}


