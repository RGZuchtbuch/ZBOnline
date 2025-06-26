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
	() => import('./nodes/38')
];

export const server_loads = [];

export const dictionary = {
		"/": [13],
		"/admin": [14,[2]],
		"/admin/article": [15,[2,3]],
		"/article": [16,[4]],
		"/article/[article]": [17,[4,5]],
		"/breeder/[breederId]": [18,[6]],
		"/breeder/[breederId]/pair": [19,[6,7]],
		"/district": [20],
		"/moderator": [21],
		"/moderator/[district]": [22,[8]],
		"/moderator/[district]/breeder": [23,[8]],
		"/moderator/[district]/breeder/[breeder]": [24,[8]],
		"/moderator/[district]/breeder/[breeder]/pair": [25,[8]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [26,[8]],
		"/moderator/[district]/breeder/[breeder]/profile": [27,[8]],
		"/moderator/[district]/result/[year]": [28,[8]],
		"/moderator/[district]/result/[year]/edit/section/[section]/group/[group]": [29,[8,9]],
		"/pair/[pairId]": [30,[10]],
		"/report": [31],
		"/result": [32,[11]],
		"/result/map/year/[year]/type/[type]": [33,[11]],
		"/result/table/district/[districtId]/year/[year]": [34,[11]],
		"/result/trend/district/[districtId]/type/[type]": [35,[11]],
		"/standard": [36],
		"/test": [37,[12]],
		"/user": [38]
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