<script>

    import {meta, Route } from "tinro";
    import api from "../../js/api.js";
    import {user} from "../../js/store.js";

    import NumberInput from '../common/input/Number.svelte';
    import Page from "../common/Page.svelte";
    import TextInput from '../common/input/Text.svelte';
    import TextArea from "../common/input/TextArea.svelte";
    import Color from "./Color.svelte";
    import {txt} from "../../js/util.js";

    export let breed;

    let route = meta();

</script>

{#if breed }
    <div class='flex flex-col border border-gray-600 rounded'>
        <div class='flex flex-row bg-header text-white text-center px-2'>
            <div class='grow'>Farbenschläge</div>
            {#if $user && $user.admin }
                <a class='w-6 border rounded align-middle text-center' href={route.match+'/farbe/0'}>✚</a>
            {/if}
        </div>
        <div class='flex-flex-col'>
            {#each breed.colors as color}
                <a class='block mx-2 pl-2 border-b border-gray-300' href={route.match+'/farbe/'+color.id}> → {color.name} </a>
            {/each}
        </div>

    </div>
{/if}

<style>
    .disabled {
        @apply text-white;
    }
</style>