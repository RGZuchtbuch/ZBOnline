<script>
    import { fade, slide } from 'svelte/transition';
    import { ctx } from '$lib/js/store.svelte.js';
    import BreedersResultForm from '$lib/cmp/result/BreedingResultForm.svelte';

    let { breeder=$bindable() } = $props();

    let open = $state( false );
    let count = $derived( countBreedings( breeder ) );

    //breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );

    $effect( () => {
        if( breeder.results.length === count ) {
            console.log( 'Added breeding', breeder.results.length, count );
            breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );
        }
    } );
    function countBreedings( breeder ) {
        return breeder.results.filter( result => result.id > 0 ).length;
    }

    function onOpen( event ) {
        open = ! open;
        if( open && breeder.results.length === 0 ) {
            //breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );
        }
    }
    function onAddResult( breeder ) {
		return ( event ) => {
			console.log( 'Adding result' );
			const new_breeding = newBreeding( ctx.district.id, breeder.id, ctx.year );
			breeder.results.push( new_breeding );
			console.log( 'Breeder after new', breeder );
		}
	}

	function newBreeding( districtId, breederId, year ) {
		return {
			"id": 0,
			"breederId": breederId,
			"pairId": null,
			"breedingId": breederId,
			"districtId": districtId,
			"year": year,
			"group": "I",
			"sectionId": null,
			"breedId": null,
			"colorId": null,
			"breeders": 1,
			"pairs": null,
			"lay": {
				"eggs": null,
				"weight": null
			},
			"brood": {
				"eggs": null,
				"fertile": null,
				"hatched": null
			},
			"show": {
				"count": null,
				"score": null
			}
		};
	}

</script>
<!--a class='flex flex-row' href={page.url.pathname+'/'+breeder.id+'/pair?year='+ctx.year}>
<span class='w-12'> {breeder.member} </span>
<span class='w-80'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
<span class='w-48'> {breeder.club} </span>
<span class='w-12 text-green-600'> {breeder.active?'✓':'.'} </span>
</a -->
<main id={breeder.id} class='flex flex-col pl-2' >
    <div  class='flex flex-row gap-x-4 hover:font-bold' class:open>
        <button class='flex flex-row gap-x-4 bg-inherit text-inherit'  title='öffnen/schliesen' onclick={onOpen}>
            {#if open}
                <div class='w-2'>▽</div>
            {:else}
                <div class='w-2'>▷</div>
            {/if}
            <span class='w-10 text-right'> {breeder.member} </span>
            <span class='w-128 text-left'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
            <!--span class='w-12 text-right'> {#if breeder.results.length > 0 } {breeder.results.length} {/if}</span-->
            <span class='w-12 text-right'> {#if count > 0 } {count} {/if}</span>

            <!--http://localhost:5173/moderator/district/6/result/breeder/217/pair/0 -->
        </button>
        <span class='grow'></span>
        {#if false && open}
            <button class='w-12 bg-inherit text-black' title='Zucht hinzufügen' onclick={ onAddResult( breeder ) }>[ + ]</button>
        {/if}
    </div>

    {#if open}
        <div class='p-0' transition:slide>
            {#each breeder.results as result, i}
                {#key result.id}
                    <BreedersResultForm bind:result={ breeder.results[ i ] }></BreedersResultForm>
                {/key}
            {/each}
        </div>
    {/if}
</main>

<style>
    .open {
        @apply bg-gray-200;
    }

</style>