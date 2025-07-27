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
	() => import('./nodes/45')
];

export const server_loads = [];

export const dictionary = {
		"/": [9],
		"/admin": [10,[2]],
		"/admin/district": [11,[2]],
		"/article": [12],
		"/article/[article]": [13],
		"/breeder": [14],
		"/breeder/grader": [15],
		"/breeder/lineageform": [16],
		"/breeder/me/[breeder]": [17,[3]],
		"/breeder/me/[breeder]/pair": [18,[3]],
		"/breeder/me/[breeder]/pair/[pair]": [19,[3]],
		"/breeder/me/[breeder]/profile": [20,[3]],
		"/breeder/[breeder]": [21,[4]],
		"/breeder/[breeder]/pair": [22,[4]],
		"/breeder/[breeder]/pair/[pair]": [23,[4]],
		"/breeder/[breeder]/profile": [24,[4]],
		"/federation": [25],
		"/message": [26],
		"/moderator": [27,[5]],
		"/moderator/[district]": [28,[5,6]],
		"/moderator/[district]/breeder": [29,[5,6]],
		"/moderator/[district]/breeder/[breeder]": [30,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/pair": [31,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [32,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/profile": [33,[5,6,7]],
		"/moderator/[district]/result": [34,[5,6]],
		"/moderator/[district]/result/edit": [35,[5,6]],
		"/pair": [36],
		"/pair/[pair]": [37],
		"/report": [38],
		"/result": [39],
		"/result/map/year/[year]/type/[type]": [40],
		"/result/table/district/[districtId]/year/[year]": [41],
		"/result/trend/district/[districtId]/type/[type]": [42],
		"/standard": [43],
		"/test": [44,[8]],
		"/user": [45]
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