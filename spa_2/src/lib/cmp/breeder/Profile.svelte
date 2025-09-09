<script>
    import {goto, invalidate} from '$app/navigation';
    import { ctx, dirty } from '$lib/js/store.svelte.js';
    import model from '$lib/js/model.js';
    import Form, { CheckBox, DateInput, EmailInput, NumberInput, TextInput, Status, validator } from '$lib/cmp/form/Form.svelte';
    import TextArea from '$lib/cmp/form/input/TextArea.svelte';

    let { breeder=$bindable(), district } = $props();

    let edit = $state( false );
    let remove = $state( false );
    let changed = false; // for invalidating load article

    let authorized = $derived( ctx.user !== null && ( ctx.user.admin || ctx.user.moderator.includes( breeder.districtId ) ) ); // can edit

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
            const ok = await model.Breeder.save( breeder );
            dirty.breeders++;
            dirty.results++
            return ok
        } else if( breeder.id > 0 && breeder.firstname === null && breeder.lastname === null && breeder.delete ) {
            console.log( 'Delete', breeder.firstname, 'TODO' );
        }

    }

</script>

{#if breeder && district }

    <section>
        {authorized ? 'Y' : 'n'}

        <div class='text-center'>
            <h2>Mitgliedsdaten</h2>
            {#if authorized}
                <p>
                    Mitglied abmelden, einfach 'bis' Datum eingeben. Damit ist dieser Züchter 'Inaktiv'.<br>
                    Ein Mitglied löschen geht nur wenn es keine Meldungen far ihm eingegeben sind.<br>
                    Löschen durch Vor- und Nachnamen leermachen und Löschen ankreuzen.
                </p>
                <hr class='w-192 mx-auto'>
            {:else}
                <p>Nur lesen</p>
            {/if}
        </div>

        {#if authorized }
            <div class='flex flex-row justify-end p-2 print:hidden'>
                <CheckBox label='Ändern' error='' bind:value={edit} />
            </div>
        {/if}

        <Form class='flex flex-col px-4 gap-y-4' autosubmit={true} onsubmit={onSubmit} disabled={ !edit}>
            <fieldset class='flex flex-row gap-x-2 p-4'>
                <legend>Mitglied <Status /></legend>
                <TextInput class='w-24' label='Verband' value={district.short} disabled/>
                <NumberInput class='w-24' label='Mitgliedsnummer' bind:value={breeder.member} validator={validate.member}/>
                <span class='w-4'></span>
                <DateInput class='' label='Seit *' bind:value={breeder.start} error='Pflichtfeld' validator={validate.start}/>
                <span class='pt-4'>:</span>
                <DateInput class='' label='Bis' bind:value={breeder.end} validator={validate.end}/>
                <span class='pt-8'></span>
                <CheckBox label='Online' title='Meldet selbst' bind:value={breeder.active}/>
                <span class='grow'></span>
                {#if authorized}
                    <CheckBox label='Löschen' title='Nur wenn Name leer ist !' bind:value={breeder.delete} disabled={ breeder.firstname || breeder.lastname }/>
                {/if}
            </fieldset>

            <fieldset class='p-4'>
                <legend>Anschrift <Status /></legend>
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
    </section>
{/if}