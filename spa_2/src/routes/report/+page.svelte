<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Report from '$lib/cmp/report/Report.svelte';

	let { data } = $props();

	console.log( 'RD', data );

	$effect( async () => {
		setHeader( data.report );
	});

	function setHeader( report ) {
		ctx.header = {
			title: 'Die Zuchtleistungen',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Leistungen', href: '/report'},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
				],
			},
		}
	}

</script>

<Report
	args={data.args}
	report={data.report}
    federation={data.federation}
	standard={data.standard}
/>



