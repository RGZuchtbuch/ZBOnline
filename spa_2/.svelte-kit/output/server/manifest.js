export const manifest = (() => {
function __memo(fn) {
	let value;
	return () => value ??= (value = fn());
}

return {
	appDir: "_app",
	appPath: "_app",
	assets: new Set(["assets/bdrg_logo_r.png","assets/bdrg_zuchtbuch.jpg","assets/bdrg_zuchtbuch.png","assets/bg_bdrg_zuchtbuch.png","assets/icons/user-profile.svg","assets/menu_1.png","assets/menu_2.jpg","assets/menu_2.png","assets/ZB_ALBS.png","favicon.png"]),
	mimeTypes: {".png":"image/png",".jpg":"image/jpeg",".svg":"image/svg+xml"},
	_: {
		client: {start:"_app/immutable/entry/start.DtPBta2w.js",app:"_app/immutable/entry/app.B1srnafK.js",imports:["_app/immutable/entry/start.DtPBta2w.js","_app/immutable/chunks/By3uwZ0X.js","_app/immutable/chunks/BEiAv54w.js","_app/immutable/chunks/BmNLdPdp.js","_app/immutable/entry/app.B1srnafK.js","_app/immutable/chunks/BmNLdPdp.js","_app/immutable/chunks/BEiAv54w.js","_app/immutable/chunks/DsnmJJEf.js","_app/immutable/chunks/BKT08P8X.js","_app/immutable/chunks/CzKcQNEg.js","_app/immutable/chunks/D85d39nc.js"],stylesheets:[],fonts:[],uses_env_dynamic_public:false},
		nodes: [
			__memo(() => import('./nodes/0.js')),
			__memo(() => import('./nodes/1.js')),
			__memo(() => import('./nodes/2.js')),
			__memo(() => import('./nodes/3.js')),
			__memo(() => import('./nodes/4.js')),
			__memo(() => import('./nodes/5.js')),
			__memo(() => import('./nodes/6.js')),
			__memo(() => import('./nodes/7.js')),
			__memo(() => import('./nodes/8.js')),
			__memo(() => import('./nodes/9.js')),
			__memo(() => import('./nodes/10.js')),
			__memo(() => import('./nodes/11.js')),
			__memo(() => import('./nodes/12.js')),
			__memo(() => import('./nodes/13.js')),
			__memo(() => import('./nodes/14.js')),
			__memo(() => import('./nodes/15.js')),
			__memo(() => import('./nodes/16.js')),
			__memo(() => import('./nodes/17.js')),
			__memo(() => import('./nodes/18.js')),
			__memo(() => import('./nodes/19.js')),
			__memo(() => import('./nodes/20.js')),
			__memo(() => import('./nodes/21.js')),
			__memo(() => import('./nodes/22.js')),
			__memo(() => import('./nodes/23.js')),
			__memo(() => import('./nodes/24.js')),
			__memo(() => import('./nodes/25.js')),
			__memo(() => import('./nodes/26.js')),
			__memo(() => import('./nodes/27.js')),
			__memo(() => import('./nodes/28.js')),
			__memo(() => import('./nodes/29.js')),
			__memo(() => import('./nodes/30.js')),
			__memo(() => import('./nodes/31.js')),
			__memo(() => import('./nodes/32.js')),
			__memo(() => import('./nodes/33.js')),
			__memo(() => import('./nodes/34.js')),
			__memo(() => import('./nodes/35.js')),
			__memo(() => import('./nodes/36.js')),
			__memo(() => import('./nodes/37.js')),
			__memo(() => import('./nodes/38.js')),
			__memo(() => import('./nodes/39.js')),
			__memo(() => import('./nodes/40.js')),
			__memo(() => import('./nodes/41.js')),
			__memo(() => import('./nodes/42.js')),
			__memo(() => import('./nodes/43.js')),
			__memo(() => import('./nodes/44.js')),
			__memo(() => import('./nodes/45.js'))
		],
		remotes: {
			
		},
		routes: [
			{
				id: "/",
				pattern: /^\/$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 9 },
				endpoint: null
			},
			{
				id: "/admin",
				pattern: /^\/admin\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 10 },
				endpoint: null
			},
			{
				id: "/admin/article",
				pattern: /^\/admin\/article\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 11 },
				endpoint: null
			},
			{
				id: "/admin/article/[article]",
				pattern: /^\/admin\/article\/([^/]+?)\/?$/,
				params: [{"name":"article","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,2,], errors: [1,,], leaf: 12 },
				endpoint: null
			},
			{
				id: "/admin/district",
				pattern: /^\/admin\/district\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 13 },
				endpoint: null
			},
			{
				id: "/admin/log",
				pattern: /^\/admin\/log\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 14 },
				endpoint: null
			},
			{
				id: "/admin/setting",
				pattern: /^\/admin\/setting\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 15 },
				endpoint: null
			},
			{
				id: "/admin/standard",
				pattern: /^\/admin\/standard\/?$/,
				params: [],
				page: { layouts: [0,2,], errors: [1,,], leaf: 16 },
				endpoint: null
			},
			{
				id: "/article",
				pattern: /^\/article\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 17 },
				endpoint: null
			},
			{
				id: "/article/[article]",
				pattern: /^\/article\/([^/]+?)\/?$/,
				params: [{"name":"article","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,], errors: [1,], leaf: 18 },
				endpoint: null
			},
			{
				id: "/breeder",
				pattern: /^\/breeder\/?$/,
				params: [],
				page: { layouts: [0,3,], errors: [1,,], leaf: 19 },
				endpoint: null
			},
			{
				id: "/breeder/pair",
				pattern: /^\/breeder\/pair\/?$/,
				params: [],
				page: { layouts: [0,3,], errors: [1,,], leaf: 20 },
				endpoint: null
			},
			{
				id: "/breeder/pair/[pair]",
				pattern: /^\/breeder\/pair\/([^/]+?)\/?$/,
				params: [{"name":"pair","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,3,], errors: [1,,], leaf: 21 },
				endpoint: null
			},
			{
				id: "/breeder/profile",
				pattern: /^\/breeder\/profile\/?$/,
				params: [],
				page: { layouts: [0,3,], errors: [1,,], leaf: 22 },
				endpoint: null
			},
			{
				id: "/federation",
				pattern: /^\/federation\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 23 },
				endpoint: null
			},
			{
				id: "/federation/message",
				pattern: /^\/federation\/message\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 24 },
				endpoint: null
			},
			{
				id: "/moderator",
				pattern: /^\/moderator\/?$/,
				params: [],
				page: { layouts: [0,4,], errors: [1,,], leaf: 25 },
				endpoint: null
			},
			{
				id: "/moderator/district",
				pattern: /^\/moderator\/district\/?$/,
				params: [],
				page: { layouts: [0,4,], errors: [1,,], leaf: 26 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]",
				pattern: /^\/moderator\/district\/([^/]+?)\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,], errors: [1,,,], leaf: 27 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/breeder",
				pattern: /^\/moderator\/district\/([^/]+?)\/breeder\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,], errors: [1,,,], leaf: 28 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/breeder/[breeder]",
				pattern: /^\/moderator\/district\/([^/]+?)\/breeder\/([^/]+?)\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,6,], errors: [1,,,,], leaf: 29 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/breeder/[breeder]/pair",
				pattern: /^\/moderator\/district\/([^/]+?)\/breeder\/([^/]+?)\/pair\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,6,], errors: [1,,,,], leaf: 30 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/breeder/[breeder]/pair/[pair]",
				pattern: /^\/moderator\/district\/([^/]+?)\/breeder\/([^/]+?)\/pair\/([^/]+?)\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false},{"name":"pair","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,6,], errors: [1,,,,], leaf: 31 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/breeder/[breeder]/profile",
				pattern: /^\/moderator\/district\/([^/]+?)\/breeder\/([^/]+?)\/profile\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,6,], errors: [1,,,,], leaf: 32 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/report.obs",
				pattern: /^\/moderator\/district\/([^/]+?)\/report\.obs\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,], errors: [1,,,], leaf: 33 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/result",
				pattern: /^\/moderator\/district\/([^/]+?)\/result\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,7,], errors: [1,,,,], leaf: 34 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/result/breeder",
				pattern: /^\/moderator\/district\/([^/]+?)\/result\/breeder\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,7,], errors: [1,,,,], leaf: 35 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/result/breeder/[breeder]/pair",
				pattern: /^\/moderator\/district\/([^/]+?)\/result\/breeder\/([^/]+?)\/pair\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,7,8,], errors: [1,,,,,], leaf: 36 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/result/breeder/[breeder]/pair/[pair]",
				pattern: /^\/moderator\/district\/([^/]+?)\/result\/breeder\/([^/]+?)\/pair\/([^/]+?)\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false},{"name":"breeder","optional":false,"rest":false,"chained":false},{"name":"pair","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,7,8,], errors: [1,,,,,], leaf: 37 },
				endpoint: null
			},
			{
				id: "/moderator/district/[district]/result/district",
				pattern: /^\/moderator\/district\/([^/]+?)\/result\/district\/?$/,
				params: [{"name":"district","optional":false,"rest":false,"chained":false}],
				page: { layouts: [0,4,5,7,], errors: [1,,,,], leaf: 38 },
				endpoint: null
			},
			{
				id: "/report",
				pattern: /^\/report\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 39 },
				endpoint: null
			},
			{
				id: "/standard",
				pattern: /^\/standard\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 40 },
				endpoint: null
			},
			{
				id: "/tool",
				pattern: /^\/tool\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 41 },
				endpoint: null
			},
			{
				id: "/tool/grader",
				pattern: /^\/tool\/grader\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 42 },
				endpoint: null
			},
			{
				id: "/tool/lineage",
				pattern: /^\/tool\/lineage\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 43 },
				endpoint: null
			},
			{
				id: "/user",
				pattern: /^\/user\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 44 },
				endpoint: null
			},
			{
				id: "/user/reset",
				pattern: /^\/user\/reset\/?$/,
				params: [],
				page: { layouts: [0,], errors: [1,], leaf: 45 },
				endpoint: null
			}
		],
		prerendered_routes: new Set([]),
		matchers: async () => {
			
			return {  };
		},
		server_assets: {}
	}
}
})();
