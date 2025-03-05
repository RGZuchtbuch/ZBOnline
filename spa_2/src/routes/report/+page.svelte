<script>
    import { getContext } from 'svelte';
    import Report from '$lib/cmp/report/Report.svelte';

    let { data } = $props();
    let state    = getContext( 'state' );

    state.title = 'Die Zuchtleistungen';
    state.menu = {
        trail : [
            { name:'Start',       href:'/' },
            { name:'Leistungen', href:'/result' },
        ],
        options : [
            { name:'Karte',       href:'/result/map/year/2024/type/2' },
            { name:'Trend',       href:'/result/trend/district/1/type/2' },
            { name:'Tabelle',       href:'/result/table/district/1/year/2024' },
        ],
    };

    $inspect('DF', data.reports );

</script>

<Report {data}/>


