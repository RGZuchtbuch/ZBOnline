<script>
	let { district, pairs } = $props();
</script>

<h3>{district.name}</h3>

{#each pairs as pair}
	<div>
		<a href={`/moderator/${district.id}/pair/${pair.id}`}>
			{pair.name} {pair.breedId} {pair.breedName} {pair.firstname} {pair.infix} {pair.lastname}
		</a>
	</div>
{/each}
