<script>
    import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
    import {Report} from '$lib/js/report.svelte.js';

    import ReportCmp from '$lib/cmp/report/Report.svelte';

	//let { data } = $props();

	let report = $state( null );

	console.log('In Report');

	$effect( async () => {
		report = await Report.load( page.url.searchParams );
		setHeader( page.url );
	});

	function setHeader( data ) {
		const title = 'Die Zuchtleistungen';
		const menu = {
			trail: [
				{name: 'Start', href: '/'},
				{name: 'Leistungen', href: '/report'},
			],
			options: [],
		};
		store.title = title;
		store.menu  = menu;
	}

</script>

<ReportCmp {report} />



