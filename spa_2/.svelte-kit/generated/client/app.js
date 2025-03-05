export { matchers } from './matchers.js';

export const nodes = [
	() => import('./nodes/0'),
	() => import('./nodes/1'),
	() => import('./nodes/2'),
	() => import('./nodes/3'),
	() => import('./nodes/4'),
	() => import('./nodes/5'),
	() => import('./nodes/6'),
	() => import('./nodes/7'),
	() => import('./nodes/8'),
	() => import('./nodes/9'),
	() => import('./nodes/10'),
	() => import('./nodes/11'),
	() => import('./nodes/12'),
	() => import('./nodes/13'),
	() => import('./nodes/14'),
	() => import('./nodes/15'),
	() => import('./nodes/16'),
	() => import('./nodes/17'),
	() => import('./nodes/18'),
	() => import('./nodes/19'),
	() => import('./nodes/20'),
	() => import('./nodes/21'),
	() => import('./nodes/22'),
	() => import('./nodes/23'),
	() => import('./nodes/24'),
	() => import('./nodes/25'),
	() => import('./nodes/26'),
	() => import('./nodes/27'),
	() => import('./nodes/28'),
	() => import('./nodes/29'),
	() => import('./nodes/30'),
	() => import('./nodes/31'),
	() => import('./nodes/32'),
	() => import('./nodes/33'),
	() => import('./nodes/34'),
	() => import('./nodes/35'),
	() => import('./nodes/36'),
	() => import('./nodes/37'),
	() => import('./nodes/38'),
	() => import('./nodes/39'),
	() => import('./nodes/40'),
	() => import('./nodes/41'),
	() => import('./nodes/42'),
	() => import('./nodes/43'),
	() => import('./nodes/44'),
	() => import('./nodes/45'),
	() => import('./nodes/46')
];

export const server_loads = [];

export const dictionary = {
		"/": [19],
		"/article": [20,[2]],
		"/article/[articleId]": [21,[2,3]],
		"/breeder/[breederId]": [22,[4]],
		"/breeder/[breederId]/pair": [23,[4,5]],
		"/district": [24],
		"/moderator": [25,[6]],
		"/moderator/[districtId]": [26,[6,7]],
		"/moderator/[districtId]/breeder": [27,[6,7,8]],
		"/moderator/[districtId]/breeder/[breederId]": [28,[6,7,8,9]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [29,[6,7,8,9,10]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [30,[6,7,8,9,10,11]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [31,[6,7,8,9]],
		"/moderator/[districtId]/pair": [32,[6,7,12]],
		"/moderator/[districtId]/pair/[pairId]": [33,[6,7,12,13]],
		"/moderator/[districtId]/result": [34,[6,7,14]],
		"/pair/[pairId]": [35,[15]],
		"/report": [36,[16]],
		"/report/map/year/[year]/type/[type]": [37,[16]],
		"/report/table/district/[districtId]/year/[year]": [38,[16]],
		"/report/trend/district/[districtId]/type/[type]": [39,[16]],
		"/result": [40,[17]],
		"/result/map/year/[year]/type/[type]": [41,[17]],
		"/result/table/district/[districtId]/year/[year]": [42,[17]],
		"/result/trend/district/[districtId]/type/[type]": [43,[17]],
		"/standard": [44],
		"/test": [45,[18]],
		"/user": [46]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),
	
	reroute: (() => {}),
	transport: {}
};

export const decoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.decode]));

export const hash = false;

export const decode = (type, value) => decoders[type](value);

export { default as root } from '../root.js';