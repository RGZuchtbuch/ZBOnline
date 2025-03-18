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

	//let query = $derived( extractQuery( page.url ) );
	//let query = $state( null );
	let report = $state( null );
	//let report = $derived( load{ chart:null, map:null, trend:null, table:null	} );

	//let report = null;
	$effect( async () => {
	 	await load( page.url );
	});

	async function load( url ) {
		console.log( 'Load' );
		const args = extractQuery( page.url );
		if( args && args.district && args.year ) {
			const responses = await Promise.all( [
				getPromise('chart', args ),
				getPromise('map',   args ),
				getPromise('trend', args ),
				getPromise('table', args ),
			]);
			// store.report.chart = responses[0].report; // state
			// store.report.map   = responses[1].report; // state
			// store.report.trend = responses[2].report; // state
			// store.report.table = responses[3].report; // state
			console.log('Promises loaded', responses);
			//query = args;
			report = {
				query: args,
				chart: responses[0].report,
				map:   responses[1].report,
				trend: responses[2].report,
				table: responses[3].report,
			}
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

	//$inspect( 'Report query', query );
	//$inspect( 'Report report', report );

</script>

{#if report }
	<Report {report} />
{/if}


