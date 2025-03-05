<script module>
    export { email_icon, fullname, info_icon, link_icon, profile_icon, test };
    // note wrapping svg in span is needed for visibility, donno why


    // https://www.svgrepo.com/svg/498301/profile-circle
    // A rx ry rotation large-arc-flag sweep-flag x y
</script>

{#snippet info_icon()}
<span>
    <svg class="" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" width="20" height="20" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" fill='none' stroke='currentColor'/>
        <line x1="12" x2="12" y1="16" y2="12"/>
        <line x1="12" x2="12" y1="8" y2="8"/>
    </svg>
</span>
{/snippet}

{#snippet profile_icon()}
<span>
    <svg class="" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" width="20" height="20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" fill='none'/>
        <circle cx="12" cy="10" r="4" fill='none' stroke='currentColor'/>
        <path d='M 6 18 A 8 8 0 0 1 18 18' fill='none' stroke='currentColor'/>
    </svg>
</span>
{/snippet}

{#snippet email_icon()}
    <span>
        <svg class="" viewBox="0 0 32 32" fill="currentColor" stroke="currentColor" width="20" height="20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" xmlns="http://www.w3.org/2000/svg">
            <rect x='1' y='6' width='22' height='14' fill='none'/>
            <line x1='1' x2='11' y1='6' y2='16'/>
            <line x1='23' x2='12' y1='6' y2='16'/>
        </svg>
    </span>
{/snippet}

{#snippet link_icon()}
<span>
    <svg class="" viewBox="0 0 32 32" fill="currentColor" stroke="currentColor" width="20" height="20" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
        <circle cx="16" cy="16" r="15" fill='none'/>
        <path d='M  3  8 A 28 28 0 0 0 29  8' fill='none'/>
        <path d='M  3 24 A 28 28 0 0 1 29 24' fill='none'/>
        <path d='M  8  3 A 28 28 0 0 1  8 29' fill='none'/>
        <path d='M 24  3 A 28 28 0 0 0 24 29' fill='none'/>
        <line x1='16' x2='16' y1='1' y2='31'/>
        <line x1='1' x2='31' y1='16' y2='16'/>
    </svg>
</span>
{/snippet}

{#snippet fullname( person )}
    {#if person}
        {person.firstname} {person.infix} {person.lastname}
    {:else}
        ?
    {/if}
{/snippet}

{#snippet test()}
    <span>TEST</span>
{/snippet}