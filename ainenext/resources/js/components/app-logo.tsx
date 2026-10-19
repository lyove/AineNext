import AppLogoIcon from './app-logo-icon';
import { usePage } from '@inertiajs/react';
import { type SharedData } from '@/types/index.d';

export default function AppLogo() {
    const { branding } = usePage<SharedData>().props;
    const appName = branding.app_name || import.meta.env.VITE_APP_NAME || 'Application';

    return (
        <>
            <span>
                <AppLogoIcon className="size-7 group-has-data-[state=collapsed]/sidebar-wrapper:size-8 fill-current dark:text-white text-black block mx-auto" />
            </span>
            <span className="ml-1 grid flex-1 text-left text-sm truncate leading-none font-semibold group-has-data-[state=collapsed]/sidebar-wrapper:hidden">
                {appName}
            </span>
        </>
    );
}
