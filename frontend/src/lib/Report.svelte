<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let promise;
    export let legend = '';
    export let link='';

    let report = null;

    onMount( () => {
        if( promise ) promise.then( data => {
            report = data;
        }).catch( error => {
            console.error( 'Error', error );
        });
    })


</script>

<div class='flex flex-col '>
    <h2>Meldung</h2>
    {#if report}
        <div class='flex flex-col my-2'>
            <div>Stamm</div>
            <div class='flex flex-row'>
                <div class='flex flex-col p-1'>
                    <div class='label'>Züchter</div>
                    <div class='data'>Eelco Jannink</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Jahr</div>
                    <div class='data'>{report.year}</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Name</div>
                    <div class='data'>{report.name}</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Gruppe</div>
                    <div class='data'>{report.group}</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Anpaarung</div>
                    <div class='data'>{report.paired}</div>
                </div>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Rasse</div>
            <div class='flex flex-row'>
                <div class='flex flex-col p-1'>
                    <div class='label'>Sparte</div>
                    <div class='data'>Zwerghühner</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Rasse</div>
                    <div class='data'>Bielefelder Kennhuhn</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Farbe</div>
                    <div class='data'>kennsperber</div>
                </div>
            </div>
        </div>

        <div class='flex flex-col my-2'>
            <div>Abstammung</div>
            <div class='flex flex-row'>
                <div class='flex flex-col p-1'>
                    <div class='label'>Sex</div>
                    <div class='data'>1.0</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Ring</div>
                    <div class='data'>D21 NH 239</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Note</div>
                    <div class='data'>94</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Abstammung</div>
                    <div class='data'>2021 C1</div>
                </div>

                <div class='flex flex-col p-1'>
                    <div class='label'>Leistungen (L,B,S)</div>
                    <div class='data'>160 49, 90% 80%, 94.1</div>
                </div>

            </div>
        </div>

        Report {report.id}
    {/if}
</div>

<style>

</style>