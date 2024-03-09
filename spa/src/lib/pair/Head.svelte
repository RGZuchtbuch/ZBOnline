<script>

    import {afterUpdate, getContext, onDestroy, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import {txt} from '../../js/util.js';
    import validator from '../../js/validator.js';

    import InputNumber from '../common/form/input/NumberInput.svelte';
    import InputText   from '../common/form/input/TextInput.svelte';
    import Select from '../common/form/input/Select.svelte';

    import BreedSelect from '../common/form/input/BreedSelect.svelte';

//    export let id;
    export let pair;
//    export let disabled = true;

    let needFocus = true;
    let focusElement = null;

    const state = getContext( 'state'); // form state

    const validate = {
        year:       (v) => validator(v).number().range(MINYEAR, MAXYEAR).isValid(),
        name:       (v) => validator(v).string().length(1,16).isValid(),
    }

    onMount( () => {
    })

    afterUpdate( () => {
        if( needFocus ) {
            focusElement.focus();
            needFocus = false;
        }
    })

    function setFocus( disabled ) {
        if ( ! disabled && focusElement ) {
            needFocus = true;
        }
    }

//    $: setFocus( disabled ) // on switch to ! disabled
//    $: validate( pair );

</script>


<div class='flex flex-col border rounded border-gray-400'>
    <div class='flex flex-row bg-header px-2 py-1 text-center text-white'>
        <div class='grow'>Stamm / Paar (#{pair.id})</div>
        <div class='w-6'></div>
    </div>


    <div class='flex flex-row px-2 gap-x-1'>
        <InputText class='w-64' label='Züchter' value={txt(pair.breeder.firstname)+' '+txt(pair.breeder.infix)+' '+txt(pair.breeder.lastname)} disabled/>
        <InputNumber class='w-20' bind:element={focusElement} label='Jahr' name='year' bind:value={pair.year} validator={validate.year}/>
        <InputText class='w-20' label='Name' bind:value={pair.name} error='* 1..16 bs' validator={validate.name} />
        <Select class='w-20' label='ZB Gruppe' bind:value={pair.group} >
            {#each ['I', 'II', 'III' ] as group}
                <option value={group} selected={group === pair.group}>{group}</option>
            {/each}
        </Select>
    </div>
    <BreedSelect class='flex flex-row p-2 gap-x-1' bind:value={pair} />
</div>

<style>

</style>