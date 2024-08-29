<script>
	import {meta, router} from 'tinro';
	import {onMount, setContext} from 'svelte';
	import {slide} from 'svelte/transition';
	import screens from '../../js/screens.js';
	import Item from './Item.svelte';
	import api from '../../js/api.js';
	import { admin, moderator, user } from '../../js/store.js';
	import Link from './Link.svelte';

	let route = meta();

	let focus = null; // what menu shown ?
	let articles = [];

	let showMenu = false;

	// writables
	const adminDistrict = admin.district;
	const adminBreeder = admin.breeder;
	const moderatorDistrict = moderator.district;
	const moderatorBreeder = moderator.breeder;

	function toggleMenu() {
		showMenu = ! showMenu;
	}

	function hideOnChange() {
		showMenu = false;
	}

	function loadArticles() {
		api.article.getAll().then( response => {
			articles = response.articles;
		})
	}

	function onSelected( event ) {
		if( event.target.tagName === 'A' ) {
			showMenu = screen.width >= screens.md; // only true on small screen
			focus = null;
			console.log( 'Selected', screen.width, screens.md, showMenu );
		}
	}

	onMount( () => {
		loadArticles();
	})

	$: hideOnChange( route );

</script>

<header class='relative flex flex-row justify-center'>
	<a class='absolute top-0.25 left-1 z-50' href='/'>
		<img class='w-6 print:w-24 md:w-16 md:mt-2' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>
	</a>

	<div class='text-center font-bold italic no-print'>
		Rassegeflügel Zuchtbuch {#if $user} für {$user.firstname} {/if}
	</div>

	<button type='button' class='absolute top-0 right-1 w-6 border-0 md:hidden text-xl no-print' on:click={toggleMenu}>
		{#if showMenu}&#10006;{:else}&#8803;{/if}
	</button>
</header>

<nav class:showMenu class='hidden md:flex flex-col md:flex-row bg-header rounded justify-between gap-x-1 mb-1 md:mb-8 px-2 print:hidden' on:click={onSelected} transition:slide>
	<div class='hidden md:block w-16'></div>
	<div class='flex flex-col md:flex-row md:gap-x-2'>
		<Item name='Info *' url='/zuchtbuch' bind:focus={focus}>
			{#each articles as article}
				<Link href={'/zuchtbuch/'+article.id}>&#9755; {article.title}</Link>
			{/each}
		</Item>
		<Item name='Verbände' url='/verband' bind:focus={focus} href={'/verband'}>
			<!--a href={'/verband'}>&#9755; Verbände im Zuchtbuch</a -->
		</Item>
		<Item name='Rassestandard' url='/standard' bind:focus={focus} href={'/standard'}>
			<!-- a href={'/standard'}>&#9755; BDRG Rassestandard</a -->
		</Item>
		<Item name='Leistungen *' url='/leistungen' bind:focus={focus}>
			<Link href={ '/leistungen' }>&#9755; Leistungen</Link>
			<Link href={ '/leistungen/rechner' }>&#9755; Zuchtbuch Bewertungsrechner</Link>
		</Item>

		{#if $user && $user.moderator.length > 0 }
			<Item name='Obmann *' url='/obmann' bind:focus={focus}>
				<Link href='/obmann/verband'>&#9755; Verbände</Link>
				<Link href={ '/leistungen/nachweis' }>&#9755; Abstammungsnachweis Formular</Link>

				{#if $moderatorDistrict}
					<div class='bg-gray-300 text-center'>Verband {$moderatorDistrict.short}</div>
					<Link href={'/obmann/verband/'+$moderatorDistrict.id+'/leistung'}>&#9755; Leistungen</Link>
					<Link href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter'}>&#9755; Züchter</Link>
					<Link href={'/obmann/verband/'+$moderatorDistrict.id+'/meldung'}>&#9755; Meldungen</Link>
					<!--Link href={'/obmann/verband/'+$moderatorDistrict.id+'/details'}>&#9755; Verbandsdaten</Link-->
					{#if $moderatorBreeder}
						<div class='bg-gray-300 text-center'>Züchter {$moderatorBreeder.lastname}</div>
						<Link href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter/'+$moderatorBreeder.id+'/meldung'}>&#9755; Meldungen</Link>
						<Link href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter/'+$moderatorBreeder.id+'/details'}>&#9755; Züchterdaten</Link>
						<!--Link href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter/'+$moderatorBreeder.id+'/entwicklung'}>&#9755; Entwicklung</Link-->
					{/if}
				{/if}
			</Item>
		{/if}

		{#if $user && $user.admin }
			<Item name='Admin *' url='/admin' bind:focus={focus}>
				<Link href='/admin/verband'>&#9755; Verbände</Link>
				{#if $adminDistrict }
					<div class='bg-gray-300 text-center'>Verband {$adminDistrict.short}</div>
					<Link href={'/admin/verband/'+$adminDistrict.id+'/leistung'}>&#9755; Leistungen</Link>
					<Link href={'/admin/verband/'+$adminDistrict.id+'/zuechter'}>&#9755; Züchter</Link>
					<Link href={'/admin/verband/'+$adminDistrict.id+'/meldung'}>&#9755; Meldungen</Link>
					<Link href={'/admin/verband/'+$adminDistrict.id+'/details' }>&#9755; Verbandsdaten</Link>
					{#if $adminBreeder}
						<div class='bg-gray-300 text-center'>Züchter {$adminBreeder.lastname}</div>
						<Link href={'/admin/verband/'+$adminDistrict.id+'/zuechter/'+$adminBreeder.id+'/meldung'}>&#9755; Meldungen</Link>
						<Link href={'/admin/verband/'+$adminDistrict.id+'/zuechter/'+$adminBreeder.id+'/details' }>&#9755; Züchterdaten</Link>
					{/if}
				{/if}
			</Item>
		{/if}

	</div>

	<div class='hidden md:flex w-16 pr-2 justify-center text-white'>
		{#if $user}
			<a href='/abmelden'>Abmelden</a>
		{:else}
			<a href='/anmelden'>Anmelden</a>
		{/if}
	</div>

</nav>


<style>
	.showMenu {
		@apply flex print:hidden;
	}

	.chosen {
		@apply font-bold;
	}
</style>