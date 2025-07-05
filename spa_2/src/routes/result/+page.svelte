<script>
    import { getContext } from 'svelte';
    import Report from '$lib/cmp/report/Report.svelte';

    let { data } = $props();
    let state    = getContext( 'state' );

    $effect( () => {
        ctx.header = {
            title : 'Die Zuchtleistungen',
            menu : {
                trail: [
                    {name: 'Start', href: '/'},
                    {name: 'Leistungen', href: '/result'},
                ],
                options: [
                    {name: 'Karte', href: '/result/map/year/2024/type/2'},
                    {name: 'Trend', href: '/result/trend/district/1/type/2'},
                    {name: 'Tabelle', href: '/result/table/district/1/year/2024'},
                ],
            },
        }
    });

</script>

<Report {...data}/>

<h2 class='header'>Leistungen</h2>
<p>Leistungen per sparte, rasse, farbe, gruppe und zuchten, legen, bruten H/T schau</p>
<br>
<ul>
    <li>Karte für Jahr / Leistung</li>
    <li>Trend für verband / leistung</li>
    <li>Tabelle für verband / jahr</li>
</ul>

Type: breeders, lay:eggs,weight, brood:fertile,hatched/prod, show:count,score

districtId, year, type, breed, color, group

Graph: district, year, breed, color
Map  :     year, type, breed, color
Trend: districtId, type, breed, color
Table: districtId, year

Pie: sections
Bar: L,B,S

