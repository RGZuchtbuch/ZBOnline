<script>
	import { goto } from '$app/navigation';
	import { slide, fade } from 'svelte/transition';

	let { data } = $props();
	let years = [];
	let nextYear = +( new Date().getFullYear() )+1;
	for( let year=nextYear; year>=1980; year-- ) {
		years.push( year );
	}


	function onYearChange( event ) {
		const year = +event.target.value;
		data.url.searchParams.set( 'year', year );
		goto( data.url );
		//goto( `./${year}`);
	}
</script>

<h3 class=''>Alle Stämme im {data.district.short}
	<select class='border border-teal-600 border-1 bg-inherit' value={data.year} onchange={onYearChange}>
		{#each years as y}
			<option value={y}>{y}</option>
		{/each}
	</select>
</h3>

<p>Die Züchtermeldungen die als stamm eingegeben wurden. ( needed as we have results ? )</p>

<header class=''>
	<div class='w-16 text-right '>#</div>
	<div class='w-16'>Jahr</div>
	<div class='w-16'>Name</div>
	<div class='w-80'>Rasse</div>
	<div class='grow'>Farbe</div>
	<div class='w-32'>Züchter</div>
</header>

<ol in:slide>
	{#each data.pairs as pair, i}
		<li>
			<a class='grow' href={`/moderator/${pair.districtId}/breeder/${pair.breederId}/pair/${pair.id}`}>
				<div class='w-16 text-right '>{i+1}.</div>
				<div class='w-16'>{pair.year}</div>
				<div class='w-16'>{pair.name}</div>
				<div class='w-80'>{pair.breedName}</div>
				<div class='grow'>{pair.colorName}</div>
				<div class='w-32'>{pair.firstname} {pair.infix} {pair.lastname}</div>
			</a>
		</li>
	{/each}
</ol>


<style>
    h3 {
        @apply text-center text-xl bg-teal-200 font-bold sticky top-0;
    }
    header {
	    @apply flex flex-row border-b bg-slate-100 p-2 gap-x-2 sticky top-0
    }
	p {
		@apply text-center italic;
	}
    a {
        @apply flex flex-row border-b p-2 gap-x-2;
    }

</style>