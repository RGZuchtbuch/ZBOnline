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
	() => import('./nodes/46'),
	() => import('./nodes/47'),
	() => import('./nodes/48')
];

export const server_loads = [];

export const dictionary = {
		"/": [22],
		"/admin": [23,[2]],
		"/admin/district": [24,[2]],
		"/article": [25,[3]],
		"/article/[article]": [26,[3,4]],
		"/breeder": [27,[5]],
		"/breeder/[breeder]": [28,[5,6]],
		"/breeder/[breeder]/pair": [29,[5,6,7]],
		"/federation": [30,[8]],
		"/message": [31,[9]],
		"/moderator": [32,[10]],
		"/moderator/[district]": [33,[10,11]],
		"/moderator/[district]/breeder": [34,[10,11,12]],
		"/moderator/[district]/breeder/[breeder]": [35,[10,11,12,13]],
		"/moderator/[district]/breeder/[breeder]/pair": [36,[10,11,12,13,14]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [37,[10,11,12,13,14,15]],
		"/moderator/[district]/breeder/[breeder]/profile": [38,[10,11,12,13]],
		"/moderator/[district]/result": [39,[10,11,16]],
		"/moderator/[district]/result/edit": [40,[10,11,16,17]],
		"/report": [41,[18]],
		"/result": [42,[19]],
		"/result/map/year/[year]/type/[type]": [43,[19]],
		"/result/table/district/[districtId]/year/[year]": [44,[19]],
		"/result/trend/district/[districtId]/type/[type]": [45,[19]],
		"/standard": [46,[20]],
		"/test": [47,[21]],
		"/user": [48]
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