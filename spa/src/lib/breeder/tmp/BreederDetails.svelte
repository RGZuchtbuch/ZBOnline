<script>
    import { afterUpdate, onMount } from 'svelte';
    import { slide } from 'svelte/transition';
    import dic from '../../js/dictionairy.js';
    import {txt} from '../../js/util.js';
    import Button from '../common/input/Button.svelte';
    import DateInput from '../common/input/Date.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';
    import api from "../../js/api.js";
    import TextArea from "../common/input/TextArea.svelte";
    import Page from "../common/Page.svelte";
    import {user} from "../../js/store.js";

    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import SelectInput from '../common/form/input/Select.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import ToggleInput from '../common/form/input/ToggleInput.svelte';
    import validator from '../../js/validator.js';

    export let breeder;

    let disabled = true; // enabled if new breeder
    let needFocus = true;
    let clubs = null;
    let changed = false; // form changed
    let invalid = false;

    let focusElement; // to set focus to for starters

    const validate = { // for validating Form fields
        name:       (v) => validator(v).string().length(1,32).isValid(),
        infix:      (v) => validator(v).string().length(1,16).orNull().isValid(),

//        eggs:       (v) => validator(v).number().range(1,366).orNull().isValid(),
//        eggWeight:  (v) => validator(v).number().range(1,9999).orNull().isValid(),
//        weight:     (v) => validator(v).number().range(1,99999).orNull().isValid(),
//        ring:       (v) => validator(v).number().range(1,99).orNull().isValid(),
    }

    function onToggleEdit() {
        disabled = ! disabled;
        needFocus = true;
        if( ! disabled ) {
            loadClubs( breeder.district.id );
        }
    }

    function onChange(event) {
        changed = true;
        validateOld();
    }

    function validateOld() {
        invalid = ! breeder.firstname  || ! breeder.lastname || ! breeder.start;
    }

    function onSubmit(event) {
        console.log('Submit');
        //disabled = true;
        breeder.club = clubs.find( club => club.id === breeder.clubId );
        api.breeder.post( breeder ).then( response => {
            const id = response.id; // new or existing
            breeder.id = id;
            changed = false;
        })
    }

    function loadClubs( id ) {
        clubs = null; // to trigger the select again
        api.district.descendants.get( breeder.districtId ).then( response => {
            clubs = filterClubs( response.district );
            console.log( 'Clubs', clubs );
        })
    }

    function filterClubs( district, resultClubs ) {
        if( ! resultClubs ) { // init
            resultClubs = [];
        }
        if( district.level === 'OV' ) {
            resultClubs.push( district )
        }
        for( const child of district.children ) {
            filterClubs( child, resultClubs );
        }
        return resultClubs;
    }

    function findClub( clubId ) {

    }

    onMount( () => {
        //focusElement.focus();
        clubs = [ breeder.club ];
        console.log( 'Cid', breeder.club.id );
        if( breeder.id === null ) { // in case of new
            onToggleEdit();
        }
    })

    afterUpdate( () => {
        if( needFocus ) {
            //focusElement.focus();
            needFocus = false;
        }
    })


</script>

<Page>
    <div slot='title'> Zuchtbuchmitglied {breeder.firstname} {txt(breeder.infix)} {breeder.lastname}</div>
    <div slot='header' class='flex flex-row'>
        <div class='grow'>Mitgliedsdaten</div>
        {#if $user && ( $user.admin || $user.moderator.includes( breeder.districtId ) ) }
            <div class='w-6 border rounded text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
        {/if}
    </div>
    <div slot='body' class='pl-4' transition:slide>
        <Form {disabled} on:submit={onSubmit}>
            <div class='flex'>
                <div>Züchter ändern</div>
                <FormStatus />
            </div>
            <div class='flex flex-row gap-x-1'>
                <TextInput class='w-48' bind:value={breeder.firstname} label={dic.label.firstname} error={dic.error.required} validator={validate.name}/>
                <TextInput class='w-24' bind:value={breeder.infix} label={dic.label.infix}/>
                <TextInput class='w-64' bind:value={breeder.lastname} label={dic.label.lastname} error={dic.error.required} validator={validate.name}/>
                <div class='grow' />
                <ToggleInput class='w-16' bind:value={breeder.active} label='Aktiv' />
            </div>

            {#if ! disabled && clubs}
                <SelectInput class='w-64 h-12' bind:value={breeder.clubId} label={dic.label.club}>
                    {#each clubs as option}
                        <option class='' value={option.id}>{option.name}</option>
                    {/each}
                </SelectInput>
            {:else}
                <TextInput class='w-64 h-12' value={txt( breeder.club ? breeder.club.name : null )} label={dic.label.club} />
            {/if}

            {breeder.club.id}

        </Form>

        <form slot='bodyy' class='flex flex-col' on:input={onChange}>
            <fieldset {disabled}>

                <div class='flex gap-x-2'>
                    <Text class='w-64' bind:element={focusElement} bind:value={breeder.firstname} label='Vorname' error='Pflichtfeld' required/>
                    <Text class='w-32' bind:value={breeder.infix} label='Zusatz'/>
                    <Text class='w-64' bind:value={breeder.lastname} label='Nachname' error='Pflichtfeld' required/>
                </div>
                <div class='h-4'></div>

                {#if ! disabled && clubs}
                    <Select class='w-64'  bind:value={breeder.club}  label='Ortsverein' >
                        <option value={null}> ? </option>
                        {#each clubs as club}
                            <option value={club} selected={club.id === breeder.clubId}>  {club.name} </option>
                        {/each}
                    </Select>
                {:else}
                    <Text class='w-64' value={txt( breeder.club ? breeder.club.name : null )} label='Ortsverein'/>
                {/if}

                <div class='flex gap-2'>
                    <DateInput class='w-24' label='ZB-Mitglied seit' bind:value={breeder.start} required/>
                    <DateInput class='w-24' label='bis' bind:value={breeder.end} />

                </div>

                <Text class='' bind:value={breeder.email} label='Email'/>

                <TextArea class='' label='Info' bind:value={breeder.info} />

                {#if ! disabled}
                    {#if changed && ! invalid }
                        <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
                    {:else}
                        <div class='bg-gray-300 text-center font-bold text-white cursor-pointer'>Kann (noch) nicht Speichern !</div>
                    {/if}
                {/if}
            </fieldset>
        </form>
    </div>

</Page>

<style>
    .disabled {
        @apply text-white;
    }
</style>