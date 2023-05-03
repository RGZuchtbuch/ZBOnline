<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { txt } from '../../js/util.js';
    import { newBreeder } from '../../js/template.js';
    import { user } from '../../js/store.js'

    import BreederList from '../breeder/BreederList.svelte';
    import Button from '../common/input/Button.svelte';
    import Date from '../common/input/Date.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';

    export let district = null;

    let breeders = null;
    let clubs = null;

    let allBreeders = false;

    const route = meta();

    function loadBreeders( district ) {
        api.district.breeders.get( district.id ).then( response => {
           breeders = response.breeders;
           console.log( 'Breeders', breeders );
        });
    }

    onMount( () => {
    })

    $: loadBreeders( district );

    console.log( 'Check', $user.admin, $user.id, district.moderatorId );

</script>
{#if $user && ( $user.admin || $user.id === district.moderatorId ) }

    <h3 class='w-256 text-center'>
        Zuchtbuchmitglieder im {district.name}
    </h3>

    <div class='w-256 bg-gray-100 overflow-y-scroll border border-gray-400 rounded scrollbar'>
        {#if breeders}
            <BreederList {breeders} />
        {/if}
    </div>
{:else}
    NOT AAUTORIZED
{/if}



<style>

</style>