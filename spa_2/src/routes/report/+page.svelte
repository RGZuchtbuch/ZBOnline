<script>
    //import { getContext } from 'svelte';
    import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';
    import Report from '$lib/cmp/report/Report.svelte';

    const title = 'Die Zuchtleistungen';
    const menu = {
        trail : [
            { name:'Start',       href:'/' },
            { name:'Leistungen', href:'/report' },
        ],
        options : [
            { name:'Karte',       href:'/result/map/year/2024/type/2' },
            { name:'Trend',       href:'/result/trend/district/1/type/2' },
            { name:'Tabelle',       href:'/result/table/district/1/year/2024' },
        ],
    };
	store.title.update( () => title );
	store.menu.update( () => menu );

	let query = $derived( extractQuery( page.url ) );
	//let report = null;
	$effect( async () => {
	 	await load( query );
	});

	async function load( query ) {
		if( query && query.district && query.year ) {
			const responses = await Promise.all( [
				getPromise('chart', query ),
				getPromise('map',   query ),
				getPromise('trend', query ),
				getPromise('table', query ),
			]);
			store.report.chart = responses[0].report; // state
			store.report.map   = responses[1].report; // state
			store.report.trend = responses[2].report; // state
			store.report.table = responses[3].report; // state
		}
	}
	async function getPromise( target, query ) {
		return api.report.get( Object.assign( { target:target }, query ) );
	}
    function extractQuery( url ) {
		const query = {};
			addToQuery( query, 'district', +page.url.searchParams.get('district') || 1 );
			addToQuery( query, 'year',     +page.url.searchParams.get('year') || new Date().getFullYear() - 1 );
			addToQuery( query, 'group',     page.url.searchParams.get('group') );
			addToQuery( query, 'section',  +page.url.searchParams.get('section') );
			addToQuery( query, 'breed',    +page.url.searchParams.get('breed'));
			addToQuery( query, 'color',    +page.url.searchParams.get('color'));
			addToQuery( query, 'type',     +page.url.searchParams.get('type') || 2 ); // defaults to breeders
		return query;
	}
	function addToQuery( query, key, value ) {
		if( value ) query[ key ] = value; // only if > 0
	}

</script>

{#if true }
	<Report {query} {...store.report} />
{/if}


