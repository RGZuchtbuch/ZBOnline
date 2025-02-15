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
	() => import('./nodes/40')
];

export const server_loads = [];

export const dictionary = {
		"/": [16],
		"/article": [17,[2]],
		"/article/[articleId]": [18,[2]],
		"/breeder/[breederId]": [19,[3]],
		"/breeder/[breederId]/pair": [20,[3,4]],
		"/district": [21],
		"/moderator": [22,[5]],
		"/moderator/[districtId]": [23,[5,6]],
		"/moderator/[districtId]/breeder": [24,[5,6,7]],
		"/moderator/[districtId]/breeder/[breederId]": [25,[5,6,7,8]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [26,[5,6,7,8,9]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [27,[5,6,7,8,9,10]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [28,[5,6,7,8]],
		"/moderator/[districtId]/pair": [29,[5,6,11]],
		"/moderator/[districtId]/pair/[pairId]": [30,[5,6,11,12]],
		"/moderator/[districtId]/report": [31,[5,6]],
		"/moderator/[districtId]/result": [32,[5,6,13]],
		"/pair/[pairId]": [33,[14]],
		"/result": [34],
		"/result/map/year/[year]/type/[type]": [35],
		"/result/table/district/[districtId]/year/[year]": [36],
		"/result/trend/district/[districtId]/type/[type]": [37],
		"/standard": [38],
		"/test": [39,[15]],
		"/user": [40]
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