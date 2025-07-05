
export async function load( { params, parent } ) {
	let data = await parent(); // wait for federation to have loaded
	const district = data.federation.districts[ +params.district ];//store.federation.districts[ +page.params.district ];
	return { district:district };
}
