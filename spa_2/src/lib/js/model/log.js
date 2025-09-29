import api from '$lib/js/server.js';

export default class Log {

	static async query(args) {
		console.log( 'Load articles', args );
		const data = await api.query(`/api/2/log`, args);
		return data && data.logs ? data.logs : null;
	}

	static async delete() {
		return await api.delete(`/api/2/log` );
	}
}