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
	() => import('./nodes/33')
];

export const server_loads = [];

export const dictionary = {
		"/": [9],
		"/admin": [10,[2]],
		"/admin/article": [11,[2,3]],
		"/article": [12],
		"/article/[article]": [13],
		"/breeder/[breederId]": [14,[4]],
		"/breeder/[breederId]/pair": [15,[4,5]],
		"/district": [16],
		"/moderator": [17],
		"/moderator/[district]": [18],
		"/moderator/[district]/breeder": [19],
		"/moderator/[district]/breeder/[breeder]": [20],
		"/moderator/[district]/breeder/[breeder]/pair": [21],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [22],
		"/moderator/[district]/breeder/[breeder]/profile": [23],
		"/moderator/[district]/result": [24],
		"/pair/[pairId]": [25,[6]],
		"/report": [26],
		"/result": [27,[7]],
		"/result/map/year/[year]/type/[type]": [28,[7]],
		"/result/table/district/[districtId]/year/[year]": [29,[7]],
		"/result/trend/district/[districtId]/type/[type]": [30,[7]],
		"/standard": [31],
		"/test": [32,[8]],
		"/user": [33]
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