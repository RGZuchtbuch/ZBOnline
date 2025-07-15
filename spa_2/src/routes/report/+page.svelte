<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Report from '$lib/cmp/report/Report.svelte';

	let { data } = $props();

	$inspect( 'Data', data );

	let a = null;

	$effect( async () => {
		ctx.args = data.args;
		ctx.report = data.report;
	})

	$effect( async () => {
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
	});

</script>

{#if ctx.args && ctx.report}
	<Report
		args={ctx.args}
		report={ctx.report}
	    federation={ctx.federation}
		standard={ctx.standard}
	/>
{/if}



