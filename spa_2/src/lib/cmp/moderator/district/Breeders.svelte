<script>
	import { page } from '$app/state';
	import {selectName} from '$lib/js/tools.js';

	let {breeders, district} = $props();


	function onSortByNumber() {
		breeders.sort( (a, b) => a.member - b.member );
	}
	function onSortByName() {
		breeders.sort( (a, b) => (''+selectName( a )).localeCompare( selectName(b) ) );
	}
	function onSortByClub() {
		breeders.sort( (a, b) => (''+a.club).localeCompare( b.club ) );
	}
	function onSortByStart() {
		breeders.sort( (a, b) => (''+a.start).localeCompare( b.start ) );
	}
	function onSortByEnd() {
		breeders.sort( (a, b) => (''+a.end).localeCompare( b.end ) );
	}
	function onSortByActive() {
		breeders.sort( (b, a) => (''+a.active).localeCompare( b.active ) );
	}

</script>


<div class='flex flex-row justify-end gap-x-4'>
	<a href={`${page.url.href}/0`} title='Züchter hinzufìgen'>[+]</a>
</div>

<header class='flex flex-row border-header bg-header text-header section items-end px-2 py-0 pl-6 gap-x-2'>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByNumber}>ZbNr</span>
	<span class='w-80' title='Sortieren' role='button' onclick={onSortByName}>Name</span>
	<span class='w-48' title='Sortieren' role='button' onclick={onSortByClub}>Ortverein</span>
	<span class='w-24' title='Sortieren' role='button' onclick={onSortByStart}>Seit</span>
	<span class='w-24' title='Sortieren' role='button' onclick={onSortByEnd}>Bis</span>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByActive}>Online</span>
</header>

{#each breeders as breeder }
	{#key breeder}
		<a class='flex flex-row' href={page.url.pathname+'/'+breeder.id}>
			<span class='w-12'> {breeder.member}</span>
			<span class='w-80'> {breeder.lastname}, {breeder.firstname} {breeder.infix}</span>
			<span class='w-48'> {breeder.club}</span>
			<span class='w-24'> {breeder.start}</span>
			<span class='w-24'> {breeder.end}</span>
			<span class='w-12 text-green-600 text-center'> {breeder.active ? '✓' : '.'}</span>
		</a>
	{/key}
{/each}



<style>
    h3 {
        @apply text-center text-xl bg-teal-200 font-bold sticky top-0;
    }
    p {
        @apply text-center italic;
    }
    a {
        @apply border-b flex flex-row p-2 pl-6 gap-x-2;
    }
</style>