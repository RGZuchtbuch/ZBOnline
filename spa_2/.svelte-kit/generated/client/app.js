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
	() => import('./nodes/48'),
	() => import('./nodes/49'),
	() => import('./nodes/50'),
	() => import('./nodes/51')
];

export const server_loads = [];

export const dictionary = {
		"/": [24],
		"/admin": [25,[2]],
		"/admin/article": [26,[2,3]],
		"/article": [27,[4]],
		"/article/[article]": [28,[4,5]],
		"/breeder": [29,[6]],
		"/breeder/[breeder]": [30,[6,7]],
		"/breeder/[breeder]/pair": [31,[6,7,8]],
		"/federation": [32,[9]],
		"/message": [33,[10]],
		"/moderator": [34,[11]],
		"/moderator/[district]": [35,[11,12]],
		"/moderator/[district]/breeder": [36,[11,12,13]],
		"/moderator/[district]/breeder/[breeder]": [37,[11,12,13,14]],
		"/moderator/[district]/breeder/[breeder]/pair": [38,[11,12,13,14,15]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [39,[11,12,13,14,15,16]],
		"/moderator/[district]/breeder/[breeder]/profile": [40,[11,12,13,14]],
		"/moderator/[district]/result": [41,[11,12,17]],
		"/moderator/[district]/result/edit": [42,[11,12,17,18]],
		"/moderator/[district]/result/[year]/edit/section/[section]/group/[group]": [43,[11,12,17,19]],
		"/report": [44,[20]],
		"/result": [45,[21]],
		"/result/map/year/[year]/type/[type]": [46,[21]],
		"/result/table/district/[districtId]/year/[year]": [47,[21]],
		"/result/trend/district/[districtId]/type/[type]": [48,[21]],
		"/standard": [49,[22]],
		"/test": [50,[23]],
		"/user": [51]
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