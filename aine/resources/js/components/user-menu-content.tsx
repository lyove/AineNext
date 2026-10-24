import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
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

    return (
        <>
            <DropdownMenuLabel className="p-0 font-normal">
                <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <UserInfo user={user} showEmail={true} />
                    <AlertDialog>
                        <AlertDialogTrigger asChild>
                            <Button variant="ghost" size="icon" className="ml-auto h-8 w-8 shrink-0 cursor-pointer" title="Log out">
                                <LogOut className="h-4 w-4" />
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Log out</AlertDialogTitle>
                                <AlertDialogDescription>Are you sure you want to log out of your account?</AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction onClick={() => router.post(route('logout'))}>Log out</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
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
                    <Link className="block w-full" href={route('account.profile')} as="button" onClick={cleanup}>
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
        </>
    );
}
