<script>
    import { active, router, Route, meta } from 'tinro';
    import Select, { Option } from '@smui/select';


    const route = meta();
    let selected = { district:0, section:0, breed:0, color:0 };

    let types = [ '', 'trend', 'map', 'table' ];
    selected.type = types[2];
    let districts = [];
    selected.districts = null;
    let results=[ '', 'Laying', 'Brooding', 'Showing' ];
    selected.resultType = results[1];
    let years = [ 2001, 2002, 2003, 2003, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014];
    selected.year = '2001';
    let sections=[ '', 'A', 'B', 'C', 'D' ];
    selected.section = sections[1];
    let breeds=[ '*', 'A', 'B', 'C', 'D' ];
    selected.breed = breeds[1]
    let colors=[ '*', 'A', 'B', 'C', 'D' ];
    selected.color = colors[0]



    $: if( selected.type === 'trend' ) {
        if( selected.color !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/district/'+selected.district+'/color/'+selected.color )
        } else if ( selected.breed !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/district/'+selected.district+'/breed/'+selected.breed )
        } else if ( selected.section !== '*' ){
            router.goto( route.match+'/'+selected.type+'/district/'+selected.district+'/section/'+selected.section )
        }
    } else if( selected.type === 'map' ) {
        if( selected.color !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/color/'+selected.color )
        } else if ( selected.breed !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/breed/'+selected.breed )
        } else if ( selected.section !== '*' ){
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/section/'+selected.section )
        }

    } else if( selected.type === 'table' ) {
        if( selected.color !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/color/'+selected.color )
        } else if ( selected.breed !== '*' ) {
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/breed/'+selected.breed )
        } else if ( selected.section !== '*' ){
            router.goto( route.match+'/'+selected.type+'/'+selected.resultType+'/year/'+selected.year+'/section/'+selected.section )
        }
    } else {
        console.log( 'Error' );
    }


</script>

<div class='flex flex-row'>

    <Select bind:value={selected.type} label='Präsentierart ?'>
        {#each types as type}
            <Option value={type}>{type}</Option>
        {/each}
    </Select>

    <Select bind:value={selected.resultType} label='Select leistung'>
        {#each results as result}
            <Option value={result}>{result}</Option>
        {/each}
    </Select>

    {#if selected.type === 'trend'}
    {:else if selected.type === 'map'}
    {:else if selected.type === 'table'}
    {/if}
    <Select bind:value={selected.district} label='Filter Verband'>
        {#each districts as district}
            <Option value={district}>{district}</Option>
        {/each}
    </Select>


    <Select bind:value={selected.year} label='Filter Jahr'>
        {#each years as year}
            <Option value={''+year}>{year}</Option>
        {/each}
    </Select>

    <Select bind:value={selected.section} label='Filter Gruppe'>
        {#each sections as section }
            <Option value={section}>{section}</Option>
        {/each}
    </Select>

    <Select bind:value={selected.breed} label='Filter Rasse'>
        {#each breeds as breed }
            <Option value={breed}>{breed}</Option>
        {/each}
    </Select>

    <Select bind:value={selected.color} label='Filter Farbe'>
        {#each colors as color }
            <Option value={color}>{color}</Option>
        {/each}
    </Select>


</div>
<div> {selected.type} - {selected.district} - {selected.section} - {selected.breed}  - {selected.color} </div>

<style></style>