<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { breeder, district, user } from '../../js/store.js';


    import Page from "../common/Page.svelte";
    import Tree from '../common/Tree.svelte';

    let districts = null; // a hierarchy of districts

    const route = meta();


    function loadDistricts( u ) {
        api.district.descendants.get( 1 ).then( response => {
            districts = filter( response.district );
            district.set( null );
            breeder.set( null );
        } );
    }

    function filter( input ) {
        let output = { id:input.id, name:input.name, moderated:true, children:[] };
        for( let child of input.children ) {
            output.children.push( filter( child ) );
        }
        return output;
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp
</script>


<Page>
    <div slot='title'>Admin {$user.fullname}</div>
    <div slot='header' >Verbände und Unterverbände im BDRG Zuchtbuch </div>
    <div slot='body'>
        {#if districts}
            <Tree node={districts} open={true} let:node={districts}>
                <div class='flex flex-row'>
                    <a class='cursor-pointer' class:moderated={districts.moderated} href={`/admin/verband/${districts.id}/leistung`} title='Zum Verband'>{districts.name} </a>
                    <small class='w-8 text-center' title='Zahl der Unterverbände'> [{districts.children.length}]</small>
                    <div class='grow'></div>
                    <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title='item id'>[{districts.id}]</small>
                </div>
            </Tree>
        {/if}
    </div>
</Page>

<style>
    .moderated {
        @apply font-bold;
    }
</style>