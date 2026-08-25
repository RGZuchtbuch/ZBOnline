<script>
    import { page } from '$app/state';
    import { fade, slide } from 'svelte/transition';
    import { ctx } from '$lib/js/store.svelte.js';
    import model from '$lib/js/model.js';
    import { dec, selectName } from '$lib/js/tools.js';
    import BreedersResultForm from '$lib/cmp/result/BreedingResultForm.svelte';

    let { breeder=$bindable() } = $props();

    let open = $state( false );
    let count = $derived( countPairs( breeder ) );

    //breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );

//    $effect( async () => {
//        if( breeder.pairs.length === count ) {
//            console.log( 'Added pair', breeder.pairs.length, count );
//            //breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );
//            let newPair = newResult( breeder, ctx.district, ctx.year );
//            console.log( 'New pair', newPair );
//            breeder.pairs.push( newPair );
//        }
//    } );

    function countPairs( breeder ) {
        return breeder.pairs.filter( pair => pair.id > 0 ).length;
    }

    function onOpen( event ) {
        open = ! open;
        //if( open && breeder.results.length === 0 ) {
            //breeder.results.push( newBreeding( ctx.district.id, breeder.id, ctx.year ) );
        //}
    }
//    function onAddResult( breeder ) {
//		return ( event ) => {
//			console.log( 'Adding result' );
//			const new_breeding = newBreeding( ctx.district.id, breeder.id, ctx.year );
//			breeder.results.push( new_breeding );
//			console.log( 'Breeder after new', breeder );
//		}
//	}

	function newResult( breeder, district, year ) {
		return {
			"id": 0,
            "name": null,
			"breederId": breeder.id,
			//"pairId": null,
			"breedingId": breeder.id,
			"districtId": district.id,
			"year": year,
			"group": "I",
			"sectionId": null,
			"breedId": null,
			"colorId": null,
			//"breeders": 1,
			//"pairs": null,
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
<main id={breeder.id} class='flex flex-col pl-2 pt-2' >
    <div class='flex flex-row gap-x-4 hover:font-bold' class:open>
        <button class='flex flex-row gap-x-4 bg-inherit text-inherit'  title='öffnen/schliesen' onclick={onOpen}>
            {#if open}
                <div class='w-2'>▽</div>
            {:else}
                <div class='w-2'>▷</div>
            {/if}
            <span class='w-10 text-right'> {breeder.member} </span>
            <span class='w-128 text-left'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
            <span class='w-12 text-right'> {#if count > 0 } {count} {/if}</span>

            <!--http://localhost:5173/moderator/district/6/result/breeder/217/pair/0 -->
        </button>
        <span class='grow'></span>
        {#if false && open}
            <button class='w-12 bg-inherit text-black' title='Zucht hinzufügen' onclick={ onAddResult( breeder ) }>[ + ]</button>
        {/if}
    </div>

    {#if open}
        <div class='flex flex-col' transition:slide>
            {#each breeder.pairs as pair, p}
                {#key p}
                    
                    <a class='flex flex-row pl-3 pt-2' href={page.url.pathname+'/'+pair.id}>
                        <span class='w-6'>⤷</span>
                        <span class='w-16'>{pair.name}</span>
                        <span class='w-64 whitespace-nowrap'>{ pair.breedId ? ctx.standard.breeds[ pair.breedId].name : '' }</span>
                        <span class='w-64 whitespace-nowrap'>{ pair.colorId ? ctx.standard.colors[ pair.colorId].name : '' }</span>
                        <span class='grow'></span>
                        <span class='w-16 px-1 text-right'>{ dec( pair.lay.eggs ) }</span> ,
                        <span class='w-16 px-1 text-right'>{ dec( pair.lay.weight ) }</span> |
                        <span class='w-16 px-1 text-right'>{ pair.brood.eggs }</span> ,
                        <span class='w-16 px-1 text-right'>{ pair.brood.fertile }</span> ,
                        <span class='w-16 px-1 text-right'>{ pair.brood.hatched }</span> |
                        <span class='w-16 px-1 text-right'>{ pair.show.count }</span> ,
                        <span class='w-16 px-1 text-right'>{ dec( pair.show.score, 1 ) }</span>
                    </a>
                {/key}
            {/each}
                <a class='pl-3 flex flex-row bg-button text-button' href={page.url.pathname+'/0?breeder='+breeder.id}>
                    <span class='grow text-center'>Neuer Stamm</span>
                </a>
        </div>
    {/if}
</main>


<style>
    .open {
        @apply bg-gray-200;
    }

</style>