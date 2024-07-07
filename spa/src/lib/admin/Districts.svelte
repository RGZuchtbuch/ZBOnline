<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

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

    function filter( source ) {
        let districts = { id:source.id, name:source.name, moderated:true, children:[] };
        for( let child of source.children ) {
            districts.children.push( filter( child ) );
        }
        return districts;
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp
</script>


<Page>
    <div slot='title'>Obmann {$user.fullname}</div>
    <div slot='header' >Verbände und Unterverbände im BDRG Zuchtbuch </div>
    <div slot='body'>
        {#if district}
            <Tree node={districts} open={true} let:node={districts}>
                <div class='flex flex-row'>
                    {#if districts.moderated}
                        <a class='cursor-pointer' class:moderated={districts.moderated} href={route.match+'/'+districts.id} title='Zum Verband'>{districts.name} </a>
                    {:else}
                        <span class='cursor-not-allowed'>{districts.name}</span>
                    {/if}
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