import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { UserInfo } from '@/components/user-info';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { type User, UserCan } from '@/types';
import { Link, router, usePage } from '@inertiajs/react';
import { LogOut, User as UserIcon, Users as UsersIcon } from 'lucide-react';
import AppearanceTabs from './appearance-tabs';

interface UserMenuContentProps {
    user: User;
}

export function UserMenuContent({ user }: UserMenuContentProps) {
    const can = usePage().props.userCan as UserCan;

    const cleanup = useMobileNavigation();

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <>
            <DropdownMenuLabel className="p-0 font-normal">
                <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <UserInfo user={user} showEmail={true} />
                </div>
            </DropdownMenuLabel>

            <DropdownMenuSeparator />

            <DropdownMenuGroup>
                <DropdownMenuItem asChild>
                <AppearanceTabs className='justify-center' showLabels={false} />
                </DropdownMenuItem>
            </DropdownMenuGroup>

            <DropdownMenuSeparator />

            <DropdownMenuGroup>
                <DropdownMenuItem asChild>
                    <Link className="block w-full" href={route('profile.edit')} as="button" onClick={cleanup}>
                        <UserIcon className="mr-2" />
                        Account
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuGroup>

            <DropdownMenuSeparator />
            
            {(can.access_users || can.access_roles || can.access_permissions) && 
                (<DropdownMenuGroup>
                    <DropdownMenuItem asChild>
                        <Link 
                            className="block w-full" 
                            href={route(`user-management.${can.access_users ? 'users' : can.access_roles ? 'roles' : 'permissions'}.index`)} 
                            as="button" 
                            onClick={cleanup}
                        >
                            <UsersIcon className="mr-2" />
                            <span>Users & Roles</span>
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuGroup>
            )}

            <DropdownMenuSeparator />

            <DropdownMenuItem asChild>
                <Link className="block w-full" method="post" href={route('logout')} as="button" onClick={handleLogout}>
                    <LogOut className="mr-2" />
                    Log out
                </Link>
            </DropdownMenuItem>
        </>
    );
}
