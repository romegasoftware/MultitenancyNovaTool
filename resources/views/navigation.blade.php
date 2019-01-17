@if (Auth::user()->can('viewAny', app(\RomegaDigital\Multitenancy\Multitenancy::class)->getTenantClass()))
<router-link tag="h3" :to="{name: 'index', params: {resourceName: 'tenants'}}" class="cursor-pointer flex items-center font-normal dim text-white mb-6 text-base no-underline">
	<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="var(--sidebar-icon)" d="M9 12A5 5 0 1 1 9 2a5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2zm1-5a1 1 0 0 1 0-2 5 5 0 0 1 5 5v2a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3zm-2-4a1 1 0 0 1 0-2 3 3 0 0 0 0-6 1 1 0 0 1 0-2 5 5 0 0 1 0 10z"/></svg>
	<span class="sidebar-label">
		Multitenancy
	</span>
</router-link>
@endif