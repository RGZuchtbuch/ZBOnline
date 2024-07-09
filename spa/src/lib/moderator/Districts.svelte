<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { breeder, district, user } from '../../js/store.js'

    import Page from "../common/Page.svelte";
    import Tree from "../common/Tree.svelte";

    let districts = null; // a hierarchy of districts

//    const dispatch = createEventDispatcher();
    const route = meta();


    function loadDistricts( user ) {
        api.district.descendants.get( 1 ).then( response => {
            districts = filter( response.district );
            district.set( null );
            breeder.set( null );
        } );
    }

    function filter( sourceDistrict, moderated = false ) {
        moderated ||= sourceDistrict.moderatorId === $user.id; // moderated an ancestor or this
        const parentDistrict = { id:sourceDistrict.id, name:sourceDistrict.name, moderated:moderated, children:[] };

        for( let child of sourceDistrict.children ) {
            const childDistrict = filter( child, moderated );
            if( moderated || childDistrict.moderated || childDistrict.children.length > 0 ) {
                parentDistrict.children.push( childDistrict );
            }
        }
        return parentDistrict;
    }

    $: loadDistricts( $user ); // reload if user changes by logout/login or exp
</script>


<Page>
    <div slot='title'>Obmann {$user.fullname}</div>
    <div slot='header' class='text-center'>Verbände im Zuchtbuch BDRG / LV / KV zum Verwalten</div>
    <div slot='body'>
        {#if districts}
            <Tree node={districts} open={true} let:node={districts}>
                <div class='flex flex-row'>
                    {#if districts.moderated}
                        <a class='cursor-pointer' class:moderated={districts.moderated} href={'/obmann/verband/'+districts.id+'/leistung'} title='Zum Verband'>{districts.name} </a>
                    {:else}
                        <span class='cursor-not-allowed'>{districts.name}</span>
                    {/if}
                    <small class='w-8 text-center'> [{districts.children.length}]</small>
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