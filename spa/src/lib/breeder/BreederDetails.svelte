<script>
    import { afterUpdate, getContext, onMount } from 'svelte';
    import { slide } from 'svelte/transition';

    import { user } from '../../js/store.js';
    import api from '../../js/api.js';
    import dic from '../../js/dictionairy.js';
    import {txt} from '../../js/util.js';
    import validator from '../../js/validator.js';

    import Page from "../common/Page.svelte";

    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import DateInput from '../common/form/input/DateInput.svelte';
    import EmailInput from '../common/form/input/EmailInput.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import SelectInput from '../common/form/input/Select.svelte';
    import TextAreaInput from '../common/form/input/TextArea.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import ToggleInput from '../common/form/input/ToggleInput.svelte';
    import CheckBoxInput from '../common/form/input/CheckBoxInput.svelte';
    import {router} from 'tinro';


//    export let breeder;
    const district = getContext( 'district' );
    const breeder  = getContext( 'breeder' );

    console.log('A', $breeder );

    let disabled = true; // enabled if new breeder
    let needFocus = true;
    let clubs = null;
    let changed = false; // form changed
    let invalid = false;

    let toRemove = false;

    let focusElement; // to set focus to for starters

    const validate = { // for validating Form fields
        name:       (v) => validator(v).string().length(1,32).isValid(),
        infix:      (v) => validator(v).string().length(1,16).orNull().isValid(),
        email:      (v) => validator(v).email().orNull().isValid(),
        start:      (v) => validator(v).date().isValid(),
        end:        (v) => validator(v).date().after( $breeder.start ).orNull().isValid(),
    }

    function onToggleEdit() {
        disabled = ! disabled;
        needFocus = true;
    }

    function onSubmit(event) {
        console.log('Submit');
        if( $breeder.firstname && $breeder.lastname && $breeder.start ) {
            if ($breeder.id > 0) {
                api.breeder.put($breeder.id, $breeder).then(response => { // chenge breeder
                    changed = false;
                });
            } else {
                api.breeder.post($breeder).then(response => { // new breeder
                    $breeder.id = response.id;
                    changed = false;
                });
            }
        } else if( toRemove && $breeder.id > 0 ) {
            disabled = true;
            api.breeder.delete( $breeder.id ).then( response => {
                breeder.id = null;;
                $router.goto( $router.from );
            })
        }
    }


    onMount( () => {
        //focusElement.focus();
//        clubs = [ breeder.club ];
        if( $breeder.id === null ) { // in case of new
            onToggleEdit();
        }
    })

    afterUpdate( () => {
        if( needFocus ) {
            //focusElement.focus();
            needFocus = false;
        }
    })

    console.log( 'BreederDetails', $breeder );
</script>

{#if $breeder }
    <Page>
        <div slot='title'> Zuchtbuchmitglied {$breeder.firstname} {txt($breeder.infix)} {$breeder.lastname}</div>
        <div slot='header' class='flex flex-row'>
            <div class='grow text-center font-bold'>Mitgliedsdaten</div>
            {#if $user && ( $user.admin || $user.moderator.includes( $breeder.districtId ) ) }
                <div class='w-6 border border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title='Daten ändern'>&#9998;</div>
            {/if}
        </div>
        <div slot='body' class='p-2' transition:slide>
            <Form {disabled} on:submit={onSubmit}>
                {#if ! disabled}
                    <div class='flex'>
                        <div class='grow text-center'>Züchteranschrift ändern</div>
                        <FormStatus />
                    </div>
                {/if}
                <div class='flex flex-row gap-x-2'>
                    <TextInput class='w-48' bind:value={$breeder.firstname} label={dic.label.firstname} error={dic.error.required} validator={validate.name}/>
                    <TextInput class='w-24' bind:value={$breeder.infix} label={dic.label.infix}/>
                    <TextInput class='w-64' bind:value={$breeder.lastname} label={dic.label.lastname} error={dic.error.required} validator={validate.name}/>
                    <div class='grow'></div>
                    <!--CheckBoxInput class='w-10' label='Löschen' bind:value={toRemove} title={dic.title.delete.breeder} disabled={ $breeder.firstname != null || $breeder.lastname != null }/-->
                </div>

                <TextInput class='w-128' bind:value={$breeder.club} label={dic.label.club} />

                <div class='flex gap-x-2'>
                    <DateInput class='w-28' bind:value={$breeder.start} label={dic.label.since} validator={validate.start} />

                    <DateInput class='w-28' bind:value={$breeder.end} label={dic.label.until} validator={validate.end} />
                </div>

                <EmailInput class='w-128' bind:value={$breeder.email} label='Email' validator='{validate.email}'/>

                <TextAreaInput class='w-full h-32' bind:value={$breeder.info} label='Info' />
            </Form>
        </div>

    </Page>
{/if}

<style>
    .disabled {
        @apply text-green-600;
    }
</style>