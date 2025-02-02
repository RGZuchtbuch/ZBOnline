<script>
    import {createEventDispatcher, getContext} from 'svelte';
    import { slide } from 'svelte/transition';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';
    import validator from '../../js/validator.js';
    import {txt} from '../../js/util.js';

    import Page from "../common/Page.svelte";

    import Form from '../common/form/Form.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import Select from '../common/form/input/Select.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import FormStatus from '../common/form/Status.svelte';

    let disabled = true;
    let changed = false;
    let members = null; // for selecting obmann
    let childDistricts = null;

    const route    = meta();
    const dispatch = createEventDispatcher();

    const district = getContext( 'district' );

    const validate = {
        name:      (v) => validator(v).string().length( 3, 48 ).isValid(),
        fullName:  (v) => validator(v).string().length( 3, 96 ).isValid(),
        shortName: (v) => validator(v).string().length( 1, 16 ).isValid(),
        url:       (v) => validator(v).url().orNull().isValid(),
        lattitude: (v) => validator(v).number().range( MINLATITUDE, MAXLATITUDE ).isValid(),
        longitude: (v) => validator(v).number().range( MINLONGITUDE, MAXLONGITUDE ).isValid(),
        // mod ?
    }

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onChange( event ) {
        changed = true;
    }

    function onSubmit() {
        if( $district.id > 0 ) {
            api.district.put( $district.id, $district );
        } else {
            api.district.post( $district ).then( response => {
                $district.id = response.id;
            });
        }
    }

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
            $district.set( response.district );
        });
    }

    function loadMembers( id ) {
        api.district.breeders.get( id ).then(response => {
            members = response.breeders;
        })
    }

//    $: loadDistrict( districtId );
    $: loadMembers( $district.id );
</script>


{#if district}
    <Page>
        <div slot='title'> Verband {$district.name}</div>

        <div slot='header' class='flex flex-row'>
            <div class='grow text-center font-bold'>Verbandsdaten</div>
            {#if $user.admin}
                <div class='w-6 h-6 border-2 border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title={ disabled ? 'Daten ändern' : 'Daten nicht ändern' }>&#9998;</div>
            {/if}
        </div>
        <div slot='body' class='p-2' transition:slide>
            <Form on:submit={onSubmit} {disabled}>
                {#if ! disabled}
                    <div class='flex'>
                        <div class='grow text-center'>Verbandsdaten ändern</div>
                        <FormStatus />
                    </div>
                {/if}
                <TextInput class='w-80' bind:value={$district.name} label='Name' validator={validate.name}/>
                <TextInput class='w-160' bind:value={$district.fullname} label='Name voll' validator={validate.fullName}/>
                <TextInput class='w-24' bind:value={$district.short} label='Name abk.' validator={validate.shortName}/>
                <TextInput class='w-160' bind:value={$district.url} label='Webseite https://...' maxlength=128 validator={validate.url}/>

                <div class='flex gap-x-2'>
                    <NumberInput class='w-32' bind:value={$district.latitude} label='Breitegrad N' validator={validate.lattitude} />
                    <NumberInput class='w-32' bind:value={$district.longitude}  label='Längegrad O' validator={validate.longitude} />
                </div>

                <Select class='w-128' bind:value={$district.moderatorId} label='Zuchtbuch Obmann' >
                    {#if members}
                        <option value={null}>Keiner</option>
                        {#each members as member}
                            <option value={member.id} selected={$district.moderatorId === member.id ? 'selected' : '' }>
                                {txt(member.lastname)}, {txt(member.firstname)} {txt(member.infix)}
                            </option>
                        {/each}
                    {/if}
                </Select>
            </Form>
        </div>
    </Page>
{/if}


<style>
    .disabled {
        @apply text-green-600;
    }
    select {
        background: green;
    }
</style>