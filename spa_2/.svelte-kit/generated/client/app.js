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
	() => import('./nodes/41')
];

export const server_loads = [];

export const dictionary = {
		"/": [17],
		"/article": [18,[2]],
		"/article/[articleId]": [19,[2,3]],
		"/breeder/[breederId]": [20,[4]],
		"/breeder/[breederId]/pair": [21,[4,5]],
		"/district": [22],
		"/moderator": [23,[6]],
		"/moderator/[districtId]": [24,[6,7]],
		"/moderator/[districtId]/breeder": [25,[6,7,8]],
		"/moderator/[districtId]/breeder/[breederId]": [26,[6,7,8,9]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [27,[6,7,8,9,10]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [28,[6,7,8,9,10,11]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [29,[6,7,8,9]],
		"/moderator/[districtId]/pair": [30,[6,7,12]],
		"/moderator/[districtId]/pair/[pairId]": [31,[6,7,12,13]],
		"/moderator/[districtId]/report": [32,[6,7]],
		"/moderator/[districtId]/result": [33,[6,7,14]],
		"/pair/[pairId]": [34,[15]],
		"/result": [35],
		"/result/map/year/[year]/type/[type]": [36],
		"/result/table/district/[districtId]/year/[year]": [37],
		"/result/trend/district/[districtId]/type/[type]": [38],
		"/standard": [39],
		"/test": [40,[16]],
		"/user": [41]
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