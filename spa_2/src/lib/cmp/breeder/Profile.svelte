<script>
    import store from '$lib/js/store.svelte.js';
    import { Breeder } from '$lib/js/breeder.svelte.js';
    import Form, { CheckBox, DateInput, EmailInput, NumberInput, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';
    import {goto} from '$app/navigation';
    import TextArea from '$lib/cmp/form/input/TextArea.svelte';

    let { breeder, district } = $props();

    const validate = {
        member:     v => validator(v).number().orNull().isValid(),
        start:      v => validator(v).date().after('1949-01-01').isValid(),
        end:        v => validator(v).date().after( breeder.start ).orNull().isValid(),
        firstname:  v => validator(v).string().length( 2, 64 ).orNullIf( breeder.delete ).isValid(),
        infix:      v => validator(v).string().length( 1, 32 ).orNull().isValid(),
        lastname:   v => validator(v).string().length( 2, 96 ).orNullIf( breeder.delete ).isValid(),
        email:      v => validator(v).email().orNull().isValid(),
        club:       v => validator(v).string().length( 2, 64 ).isValid(),
        info:       v => validator(v).string().orNull().isValid(),
    }
//    name:       v => validator(v).string().orNullIf( pair.delete ).isValid(),

    async function onSubmit() {
        if( breeder.start && breeder.firstname && breeder.lastname && breeder.club ) {
            return await Breeder.save( breeder );
        } else if( breeder.id > 0 && breeder.firstname === null && breeder.lastname === null && breeder.delete ) {
            console.log( 'Delete', breeder.firstname, 'TODO' );
        }
        // if( pair.breederId && pair.year && pair.name && pair.group && pair.sectionId && pair.breedId && pair.colorId ) {
        //     return await Pair.save( pair );
        //     //if( saved ) changed = false;
        // } else if( pair.id > 0 && pair.name === null && pair.delete ){ // name is null and delete
        //     console.log( 'Delete' ) // TODO pair delete
        //     const ok = Pair.delete( pair.id );
        //     if( ok ) {
        //         await goto(`/moderator/${pair.districtId}/breeder/${pair.breederId}/pair`);
        //     }
        //     return ok;
        // }
    }

    $effect( () => {
        // if( pair ) {
        //     pair.delete = pair.name ? false : pair.delete;
        // }
    })
</script>

{#if breeder && district }
    <div class='text-center'>
        <h2>Mitgliedsdaten</h2>
        Mitglied abmelden, einfach 'bis' Datum eingeben. Damit ist dieser Züchter 'Inaktiv'.<br>
        Ein Mitglied löschen geht nur wenn es keine Meldungen eingegeben sind.<br>
        Löschen durch Vor- und Nachnamen leermachen und Löschen ankreuzen.
    </div>
    <hr class='w-228 self-center'>
    <Form class='flex flex-col p-4 gap-y-4' autosubmit={true} onsubmit={onSubmit}>
        <div class='text-right'><Status /></div>

        <fieldset class='flex flex-row gap-x-2 p-4'>
            <legend>Mitglied</legend>
            <NumberInput class='w-24' label='Mitgliedsnummer' bind:value={breeder.member} validator={validate.member}/>
            <span class='w-4'></span>
            <DateInput class='' label='Seit *' bind:value={breeder.start} error='Pflichtfeld' validator={validate.start}/>
            <span class='pt-4'>:</span>
            <DateInput class='' label='Bis' bind:value={breeder.end} validator={validate.end}/>
            <span class='grow'></span>
            <CheckBox label='Löschen' title='Nur wenn Name leer ist !' bind:value={breeder.delete} disabled={ breeder.firstname || breeder.lastname }/>

        </fieldset>
        <fieldset class='p-4'>
            <legend>Anschrift</legend>
            <div class='flex flex-row gap-x-2'>
                <TextInput class='w-64' label='Vorname *' bind:value={breeder.firstname} error='Pflichtfeld' validator={validate.firstname}/>
                <TextInput class='w-28' label='Zusatz' bind:value={breeder.infix} validator={validate.infix}/>
                <TextInput class='w-96' label='Nachname *' bind:value={breeder.lastname} error='Pflichtfeld' validator={validate.lastname}/>
            </div>
            <EmailInput class='w-192' label='Email' bind:value={breeder.email} validator={validate.email}/>
            <TextInput class='w-96' label='Ortsverein *' bind:value={breeder.club} error='Pflichtfeld' validator={validate.club}/>
        </fieldset>




        <TextArea class='h-32' label='Info' bind:value={breeder.info} validator={validate.info}/>

    </Form>
{/if}