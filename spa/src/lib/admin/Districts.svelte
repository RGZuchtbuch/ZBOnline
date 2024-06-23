<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";
    import Tree from '../common/Tree.svelte';

    let district = null;
    const route = meta();


    function loadDistricts( u ) {
        api.district.descendants.get( 1 ).then( response => {
            district = filter( response.district );
        } );
    }

    function filter( source ) {
        let district = { id:source.id, name:source.name, moderated:true, children:[] };
        for( let child of source.children ) {
            district.children.push( filter( child ) );
        }
        return district;
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp
</script>


<List>
    <div slot='title'>Obmann {$user.fullname}</div>
    <div slot='header' >Verbände und Unterverbände im BDRG Zuchtbuch </div>
    <div slot='body'>
        {#if district}
            <Tree node={district} open={true} let:node={district}>
                <div class='flex flex-row'>
                    {#if district.moderated}
                        <a class='cursor-pointer' class:moderated={district.moderated} href={route.match+'/'+district.id} title='Zum Verband'>{district.name} </a>
                    {:else}
                        <span class='cursor-not-allowed'>{district.name}</span>
                    {/if}
                    <small class='w-8 text-center' title='Zahl der Unterverbände'> [{district.children.length}]</small>
                    <div class='grow'></div>
                    <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title='item id'>[{district.id}]</small>
                </div>
            </Tree>
        {/if}
    </div>
</List>

<style>
    .moderated {
        @apply font-bold;
    }
</style>