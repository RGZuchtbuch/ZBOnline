
// this file is generated — do not edit it


declare module "svelte/elements" {
	export interface HTMLAttributes<T> {
		'data-sveltekit-keepfocus'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-noscroll'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-preload-code'?:
			| true
			| ''
			| 'eager'
			| 'viewport'
			| 'hover'
			| 'tap'
			| 'off'
			| undefined
			| null;
		'data-sveltekit-preload-data'?: true | '' | 'hover' | 'tap' | 'off' | undefined | null;
		'data-sveltekit-reload'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-replacestate'?: true | '' | 'off' | undefined | null;
	}
}

export {};


declare module "$app/types" {
	export interface AppTypes {
		RouteId(): "/" | "/admin" | "/admin/article" | "/admin/article/[article]" | "/admin/district" | "/admin/log" | "/admin/setting" | "/admin/standard" | "/article" | "/article/[article]" | "/breeder" | "/breeder/pair" | "/breeder/pair/[pair]" | "/breeder/profile" | "/federation" | "/federation/message" | "/moderator" | "/moderator/district" | "/moderator/district/[district]" | "/moderator/district/[district]/breeder" | "/moderator/district/[district]/breeder/[breeder]" | "/moderator/district/[district]/breeder/[breeder]/pair" | "/moderator/district/[district]/breeder/[breeder]/pair/[pair]" | "/moderator/district/[district]/breeder/[breeder]/profile" | "/moderator/district/[district]/report.obs" | "/moderator/district/[district]/result" | "/moderator/district/[district]/result/breeder" | "/moderator/district/[district]/result/breeder/[breeder]" | "/moderator/district/[district]/result/breeder/[breeder]/pair" | "/moderator/district/[district]/result/breeder/[breeder]/pair/[pair]" | "/moderator/district/[district]/result/district" | "/report" | "/standard" | "/tool" | "/tool/grader" | "/tool/lineage" | "/user" | "/user/reset";
		RouteParams(): {
			"/admin/article/[article]": { article: string };
			"/article/[article]": { article: string };
			"/breeder/pair/[pair]": { pair: string };
			"/moderator/district/[district]": { district: string };
			"/moderator/district/[district]/breeder": { district: string };
			"/moderator/district/[district]/breeder/[breeder]": { district: string; breeder: string };
			"/moderator/district/[district]/breeder/[breeder]/pair": { district: string; breeder: string };
			"/moderator/district/[district]/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/district/[district]/breeder/[breeder]/profile": { district: string; breeder: string };
			"/moderator/district/[district]/report.obs": { district: string };
			"/moderator/district/[district]/result": { district: string };
			"/moderator/district/[district]/result/breeder": { district: string };
			"/moderator/district/[district]/result/breeder/[breeder]": { district: string; breeder: string };
			"/moderator/district/[district]/result/breeder/[breeder]/pair": { district: string; breeder: string };
			"/moderator/district/[district]/result/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/district/[district]/result/district": { district: string }
		};
		LayoutParams(): {
			"/": { article?: string; pair?: string; district?: string; breeder?: string };
			"/admin": { article?: string };
			"/admin/article": { article?: string };
			"/admin/article/[article]": { article: string };
			"/admin/district": Record<string, never>;
			"/admin/log": Record<string, never>;
			"/admin/setting": Record<string, never>;
			"/admin/standard": Record<string, never>;
			"/article": { article?: string };
			"/article/[article]": { article: string };
			"/breeder": { pair?: string };
			"/breeder/pair": { pair?: string };
			"/breeder/pair/[pair]": { pair: string };
			"/breeder/profile": Record<string, never>;
			"/federation": Record<string, never>;
			"/federation/message": Record<string, never>;
			"/moderator": { district?: string; breeder?: string; pair?: string };
			"/moderator/district": { district?: string; breeder?: string; pair?: string };
			"/moderator/district/[district]": { district: string; breeder?: string; pair?: string };
			"/moderator/district/[district]/breeder": { district: string; breeder?: string; pair?: string };
			"/moderator/district/[district]/breeder/[breeder]": { district: string; breeder: string; pair?: string };
			"/moderator/district/[district]/breeder/[breeder]/pair": { district: string; breeder: string; pair?: string };
			"/moderator/district/[district]/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/district/[district]/breeder/[breeder]/profile": { district: string; breeder: string };
			"/moderator/district/[district]/report.obs": { district: string };
			"/moderator/district/[district]/result": { district: string; breeder?: string; pair?: string };
			"/moderator/district/[district]/result/breeder": { district: string; breeder?: string; pair?: string };
			"/moderator/district/[district]/result/breeder/[breeder]": { district: string; breeder: string; pair?: string };
			"/moderator/district/[district]/result/breeder/[breeder]/pair": { district: string; breeder: string; pair?: string };
			"/moderator/district/[district]/result/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/district/[district]/result/district": { district: string };
			"/report": Record<string, never>;
			"/standard": Record<string, never>;
			"/tool": Record<string, never>;
			"/tool/grader": Record<string, never>;
			"/tool/lineage": Record<string, never>;
			"/user": Record<string, never>;
			"/user/reset": Record<string, never>
		};
		Pathname(): "/" | "/admin" | "/admin/" | "/admin/article" | "/admin/article/" | `/admin/article/${string}` & {} | `/admin/article/${string}/` & {} | "/admin/district" | "/admin/district/" | "/admin/log" | "/admin/log/" | "/admin/setting" | "/admin/setting/" | "/admin/standard" | "/admin/standard/" | "/article" | "/article/" | `/article/${string}` & {} | `/article/${string}/` & {} | "/breeder" | "/breeder/" | "/breeder/pair" | "/breeder/pair/" | `/breeder/pair/${string}` & {} | `/breeder/pair/${string}/` & {} | "/breeder/profile" | "/breeder/profile/" | "/federation" | "/federation/" | "/federation/message" | "/federation/message/" | "/moderator" | "/moderator/" | "/moderator/district" | "/moderator/district/" | `/moderator/district/${string}` & {} | `/moderator/district/${string}/` & {} | `/moderator/district/${string}/breeder` & {} | `/moderator/district/${string}/breeder/` & {} | `/moderator/district/${string}/breeder/${string}` & {} | `/moderator/district/${string}/breeder/${string}/` & {} | `/moderator/district/${string}/breeder/${string}/pair` & {} | `/moderator/district/${string}/breeder/${string}/pair/` & {} | `/moderator/district/${string}/breeder/${string}/pair/${string}` & {} | `/moderator/district/${string}/breeder/${string}/pair/${string}/` & {} | `/moderator/district/${string}/breeder/${string}/profile` & {} | `/moderator/district/${string}/breeder/${string}/profile/` & {} | `/moderator/district/${string}/report.obs` & {} | `/moderator/district/${string}/report.obs/` & {} | `/moderator/district/${string}/result` & {} | `/moderator/district/${string}/result/` & {} | `/moderator/district/${string}/result/breeder` & {} | `/moderator/district/${string}/result/breeder/` & {} | `/moderator/district/${string}/result/breeder/${string}` & {} | `/moderator/district/${string}/result/breeder/${string}/` & {} | `/moderator/district/${string}/result/breeder/${string}/pair` & {} | `/moderator/district/${string}/result/breeder/${string}/pair/` & {} | `/moderator/district/${string}/result/breeder/${string}/pair/${string}` & {} | `/moderator/district/${string}/result/breeder/${string}/pair/${string}/` & {} | `/moderator/district/${string}/result/district` & {} | `/moderator/district/${string}/result/district/` & {} | "/report" | "/report/" | "/standard" | "/standard/" | "/tool" | "/tool/" | "/tool/grader" | "/tool/grader/" | "/tool/lineage" | "/tool/lineage/" | "/user" | "/user/" | "/user/reset" | "/user/reset/";
		ResolvedPathname(): `${"" | `/${string}`}${ReturnType<AppTypes['Pathname']>}`;
		Asset(): "/assets/bdrg_logo_r.png" | "/assets/bdrg_zuchtbuch.jpg" | "/assets/bdrg_zuchtbuch.png" | "/assets/bg_bdrg_zuchtbuch.png" | "/assets/icons/user-profile.svg" | "/assets/menu_1.png" | "/assets/menu_2.jpg" | "/assets/menu_2.png" | "/assets/ZB_ALBS.png" | "/favicon.png" | string & {};
	}
}