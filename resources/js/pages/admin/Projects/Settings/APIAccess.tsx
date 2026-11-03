import { Head, usePage } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import { toast } from 'sonner';
import { Copy, Plus, Trash2, Pencil, Globe } from 'lucide-react';
import { AlertDialog, AlertDialogContent, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogCancel } from '@/components/ui/alert-dialog';

import type { Project, ProjectOrigin, BreadcrumbItem, UserCan } from '@/types';

import AppLayout from '@/layouts/app-layout';
import ProjectSettingsLayout from './layout';
import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Switch } from '@/components/ui/switch';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

interface Props {
    project: Project;
    tokens: Array<{ id: number; name: string; abilities: string[]; created_at: string }>;
    whitelist: ProjectOrigin[];
}

export default function APIAccessSettings({ project, tokens: initialTokens, whitelist: initialWhitelist }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: project.name, href: route('projects.show', project.id) },
        { title: 'Settings', href: route('projects.settings.project', project.id) },
        { title: 'API Access', href: route('projects.settings.api-access', project.id) },
    ];

    const can = usePage().props.userCan as UserCan;

    const [tokens, setTokens] = useState(initialTokens);
    const [whitelist, setWhitelist] = useState<ProjectOrigin[]>(initialWhitelist);

    const [showTokenDialog, setShowTokenDialog] = useState(false);
    const [editingToken, setEditingToken] = useState<{ id: number; name: string; abilities: string[] } | null>(null);
    const [newTokenName, setNewTokenName] = useState('');
    const [newTokenAbilities, setNewTokenAbilities] = useState<string[]>(['read']);
    const [createdPlainToken, setCreatedPlainToken] = useState<string | null>(null);

    const [tokenToDelete, setTokenToDelete] = useState<number|null>(null);

    // Domain whitelist state
    const [showWhitelistDialog, setShowWhitelistDialog] = useState(false);
    const [editingOrigin, setEditingOrigin] = useState<ProjectOrigin | null>(null);
    const [newOriginUrl, setNewOriginUrl] = useState('');
    const [newOriginDescription, setNewOriginDescription] = useState('');
    const [newOriginAbilities, setNewOriginAbilities] = useState<string[]>(['read']);
    const [originToDelete, setOriginToDelete] = useState<ProjectOrigin | null>(null);

    const endpointUrl = `${window.location.origin}/api`;

    const copy = (value: string) => {
        navigator.clipboard.writeText(value);
        toast.success('Copied to clipboard');
    };

    // const togglePublic = async () => {
    //     try {
    //         const res = await axios.post(route('projects.settings.toggle-public', project.id));
    //         setPublicApi(res.data.public_api);
    //         toast.success('Public API setting updated');
    //     } catch {
    //         toast.error('Failed');
    //     }
    // };

    const resetDialogState = () => {
        setEditingToken(null);
        setCreatedPlainToken(null);
        setNewTokenName('');
        setNewTokenAbilities(['read']);
    };

    const saveToken = async () => {
        try {
            if (editingToken) {
                await axios.put(route('projects.settings.tokens.update', [project.id, editingToken.id]), {
                    name: newTokenName,
                    abilities: newTokenAbilities,
                });
                setTokens(tokens.map(t => t.id === editingToken.id ? { ...t, name: newTokenName, abilities: newTokenAbilities } : t));
                toast.success('Token updated');
                setShowTokenDialog(false);
                resetDialogState();
            } else {
                const res = await axios.post(route('projects.settings.tokens.create', project.id), {
                    name: newTokenName,
                    abilities: newTokenAbilities,
                });
                setTokens([...tokens, { id: res.data.token_id, name: newTokenName, abilities: newTokenAbilities, created_at: new Date().toISOString() }]);
                setCreatedPlainToken(res.data.token);
            }
        } catch {
            toast.error('Failed to save token');
        }
    };

    const confirmDelete = async () => {
        if(tokenToDelete===null) return;
        try {
            await axios.delete(route('projects.settings.tokens.delete', [project.id, tokenToDelete]));
            setTokens(tokens.filter(t => t.id !== tokenToDelete));
            toast.success('Token deleted');
        } catch { toast.error('Failed'); }
        setTokenToDelete(null);
    };

    // Domain whitelist functions
    const resetWhitelistDialogState = () => {
        setEditingOrigin(null);
        setNewOriginUrl('');
        setNewOriginDescription('');
        setNewOriginAbilities(['read']);
    };

    const saveOrigin = async () => {
        try {
            if (editingOrigin) {
                await axios.put(route('projects.settings.whitelist.update', [project.id, editingOrigin.id]), {
                    description: newOriginDescription,
                    abilities: newOriginAbilities,
                });
                setWhitelist(whitelist.map(o => o.id === editingOrigin.id ? { ...o, description: newOriginDescription, abilities: newOriginAbilities } : o));
                toast.success('Origin updated');
                setShowWhitelistDialog(false);
                resetWhitelistDialogState();
            } else {
                const res = await axios.post(route('projects.settings.whitelist.store', project.id), {
                    origin: newOriginUrl,
                    description: newOriginDescription,
                    abilities: newOriginAbilities,
                });
                setWhitelist([res.data, ...whitelist]);
                toast.success('Origin added to whitelist');
                setShowWhitelistDialog(false);
                resetWhitelistDialogState();
            }
        } catch (error: unknown) {
            if (axios.isAxiosError(error) && error.response?.data?.errors) {
                const errors = error.response.data.errors as Record<string, string[]>;
                const firstError = Object.values(errors)[0]?.[0] || 'Invalid input';
                toast.error(firstError);
            } else {
                toast.error('Failed to save origin');
            }
        }
    };

    const toggleOrigin = async (origin: ProjectOrigin) => {
        try {
            const res = await axios.post(route('projects.settings.whitelist.toggle', [project.id, origin.id]));
            setWhitelist(whitelist.map(o => o.id === origin.id ? { ...o, is_active: res.data.is_active } : o));
            toast.success(res.data.is_active ? 'Origin enabled' : 'Origin disabled');
        } catch {
            toast.error('Failed to toggle origin');
        }
    };

    const confirmDeleteOrigin = async () => {
        if (!originToDelete) return;
        try {
            await axios.delete(route('projects.settings.whitelist.destroy', [project.id, originToDelete.id]));
            setWhitelist(whitelist.filter(o => o.id !== originToDelete.id));
            toast.success('Origin removed from whitelist');
        } catch {
            toast.error('Failed to delete origin');
        }
        setOriginToDelete(null);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="API Access settings" />

            <ProjectSettingsLayout project={project}>
                <div className="space-y-6 max-w-2xl">
                    <div>
                        <HeadingSmall title="Project ID" />
                        <div className="flex gap-2">
                            <Input readOnly value={project.uuid} />
                            <Button variant="outline" onClick={() => copy(project.uuid)}>
                                <Copy className="w-4 h-4" />
                            </Button>
                        </div>
                    </div>

                    <Separator />

                    <div>
                        <HeadingSmall title="Content API Endpoint" />
                        <div className="flex gap-2">
                            <Input readOnly value={endpointUrl} />
                            <Button variant="outline" onClick={() => copy(endpointUrl)}>
                                <Copy className="w-4 h-4" />
                            </Button>
                        </div>
                    </div>

                    <Separator />

                    {/* <div className="flex items-center gap-4">
                        <Label htmlFor="public_toggle">
                            {publicApi ? 'Disable Public GET access' : 'Enable Public GET access'}
                        </Label>
                        <Switch id="public_toggle" checked={publicApi} onCheckedChange={togglePublic} />
                    </div> */}

                    <Separator />

                    <div className="flex justify-between items-center">
                        <HeadingSmall title="Allowed Domain Whitelist" />
                        {can.access_api_access_settings && (
                            <Button size="sm" onClick={() => { resetWhitelistDialogState(); setShowWhitelistDialog(true); }}>
                                <Plus className="w-4 h-4 mr-1" /> Add Domain
                            </Button>
                        )}
                    </div>

                    <div className="mt-4 rounded-md border border-sidebar-border/70 bg-sidebar">
                        <table className="min-w-full text-sm text-sidebar-foreground">
                            <thead className="border-b border-sidebar-border/70 bg-sidebar/60">
                                <tr>
                                    <th className="px-4 py-2 text-left">Domain</th>
                                    <th className="px-4 py-2 text-left">Abilities</th>
                                    <th className="px-4 py-2 text-left">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {whitelist.map((o) => (
                                    <tr key={o.id} className="border-t border-sidebar-border/70 hover:bg-sidebar-accent/20">
                                        <td className="px-4 py-2">
                                            <div className="flex items-center gap-2">
                                                <Globe className="w-4 h-4 text-sidebar-foreground/60" />
                                                <span>{o.origin}</span>
                                            </div>
                                            {o.description && (
                                                <div className="text-xs text-sidebar-foreground/60 mt-0.5">{o.description}</div>
                                            )}
                                        </td>
                                        <td className="px-4 py-2">{o.abilities.join(', ')}</td>
                                        <td className="px-4 py-2">
                                            {can.access_api_access_settings && (
                                                <Switch
                                                    checked={o.is_active}
                                                    onCheckedChange={() => toggleOrigin(o)}
                                                />
                                            )}
                                        </td>
                                        <td className="px-4 py-2 text-right">
                                            {can.access_api_access_settings && (
                                                <>
                                                    <Button variant="ghost" size="icon" onClick={() => { setEditingOrigin(o); setNewOriginUrl(o.origin); setNewOriginDescription(o.description || ''); setNewOriginAbilities(o.abilities); setShowWhitelistDialog(true); }}>
                                                        <Pencil className="w-4 h-4" />
                                                    </Button>
                                                    <Button variant="ghost" size="icon" onClick={() => setOriginToDelete(o)}>
                                                        <Trash2 className="w-4 h-4" />
                                                    </Button>
                                                </>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {whitelist.length === 0 && (
                                    <tr>
                                        <td className="px-4 py-6 text-sidebar-foreground/70" colSpan={4}>
                                            No whitelisted domains yet. Add a domain to allow frontend requests from that origin without requiring a token.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    <Separator />

                    <div className="flex justify-between items-center">
                        <HeadingSmall title="Access Tokens" />
                        {can.access_api_access_settings && (
                            <Button size="sm" onClick={() => { resetDialogState(); setShowTokenDialog(true); }}>
                                <Plus className="w-4 h-4 mr-1" /> Create Token
                            </Button>
                        )}
                    </div>
                    
                    <div className="mt-4 rounded-md border border-sidebar-border/70 bg-sidebar">
                        <table className="min-w-full text-sm text-sidebar-foreground">
                            <thead className="border-b border-sidebar-border/70 bg-sidebar/60">
                                <tr>
                                    <th className="px-4 py-2 text-left">Name</th>
                                    <th className="px-4 py-2 text-left">Abilities</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {tokens.map((t) => (
                                    <tr key={t.id} className="border-t border-sidebar-border/70 hover:bg-sidebar-accent/20">
                                        <td className="px-4 py-2">{t.name}</td>
                                        <td className="px-4 py-2">{t.abilities.join(', ')}</td>
                                        <td className="px-4 py-2 text-right">
                                            {can.access_api_access_settings && (
                                            <>
                                                <Button variant="ghost" size="icon" onClick={() => { setEditingToken(t); setNewTokenName(t.name); setNewTokenAbilities(t.abilities); setShowTokenDialog(true); }}>
                                                    <Pencil className="w-4 h-4" />
                                                </Button>
                                                <Button variant="ghost" size="icon" onClick={() => setTokenToDelete(t.id)}>
                                                    <Trash2 className="w-4 h-4" />
                                                </Button>
                                            </>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {tokens.length === 0 && (
                                    <tr>
                                        <td className="px-4 py-6 text-sidebar-foreground/70" colSpan={3}>
                                            No tokens yet.
                                        </td>
                                    </tr>
                                )}
                                </tbody>
                        </table>
                    </div>

                    <Dialog open={showTokenDialog} onOpenChange={(open)=>{if(!open){setShowTokenDialog(false);resetDialogState();}}}>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>{editingToken ? 'Edit API Token' : 'Create API Token'}</DialogTitle>
                                <DialogDescription>Give the token a name and select abilities.</DialogDescription>
                            </DialogHeader>

                            {createdPlainToken ? (
                                <div className="space-y-3">
                                    <p className="text-sm">Copy your new token now. You won't see it again!  If you lose it, you can create a new one.</p>
                                    <div className="flex gap-2">
                                        <Input readOnly value={createdPlainToken} />
                                        <Button variant="outline" onClick={() => copy(createdPlainToken!)}>
                                            <Copy className="w-4 h-4" />
                                        </Button>
                                    </div>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <div className="space-y-2">
                                        <Label>Name</Label>
                                        <Input value={newTokenName} onChange={(e) => setNewTokenName(e.target.value)} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Abilities</Label>
                                        <div className="flex items-center gap-4">
                                            {['create', 'read', 'update', 'delete', 'admin'].map((a) => (
                                                <label key={a} className="flex items-center gap-2 text-sm cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        checked={newTokenAbilities.includes(a)}
                                                        onChange={(e) => {
                                                            if (e.target.checked) setNewTokenAbilities([...newTokenAbilities, a]);
                                                            else setNewTokenAbilities(newTokenAbilities.filter((ab) => ab !== a));
                                                        }}
                                                    />
                                                    {a}
                                                </label>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            )}

                            <DialogFooter>
                                <Button variant="secondary" onClick={() => setShowTokenDialog(false)}>Close</Button>
                                {!createdPlainToken && (
                                    <Button onClick={saveToken} disabled={!newTokenName.trim()}>{editingToken ? 'Save' : 'Create'}</Button>
                                )}
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>

                    <AlertDialog open={tokenToDelete!==null} onOpenChange={(open)=>{if(!open) setTokenToDelete(null);}}>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Delete Token</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Are you sure you want to delete this token? Applications using it will stop working.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <Button variant="destructive" onClick={confirmDelete}>Delete</Button>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>

                    {/* Domain Whitelist Dialog */}
                    <Dialog open={showWhitelistDialog} onOpenChange={(open)=>{if(!open){setShowWhitelistDialog(false);resetWhitelistDialogState();}}}>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>{editingOrigin ? 'Edit Domain Whitelist' : 'Add Domain to Whitelist'}</DialogTitle>
                                <DialogDescription>
                                    Configure frontend domains that can access this project's API directly. Requests from whitelisted domains don't require a token - the browser automatically validates the origin.
                                </DialogDescription>
                            </DialogHeader>

                            <div className="space-y-4">
                                <div className="space-y-2">
                                    <Label>Domain *</Label>
                                    <Input
                                        value={newOriginUrl}
                                        onChange={(e) => setNewOriginUrl(e.target.value)}
                                        placeholder="https://example.com"
                                        maxLength={255}
                                        disabled={!!editingOrigin}
                                    />
                                    {editingOrigin && (
                                        <p className="text-xs text-muted-foreground">Domain cannot be edited. Delete and re-add to change it.</p>
                                    )}
                                </div>

                                <div className="space-y-2">
                                    <Label>Description</Label>
                                    <Input
                                        value={newOriginDescription}
                                        onChange={(e) => setNewOriginDescription(e.target.value)}
                                        placeholder="e.g., Production frontend"
                                    />
                                </div>

                                <div className="space-y-2">
                                    <Label>Abilities *</Label>
                                    <div className="flex items-center gap-4">
                                        {['read', 'create', 'update'].map((a) => (
                                            <label key={a} className="flex items-center gap-2 text-sm cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    checked={newOriginAbilities.includes(a)}
                                                    onChange={(e) => {
                                                        if (e.target.checked) setNewOriginAbilities([...newOriginAbilities, a]);
                                                        else setNewOriginAbilities(newOriginAbilities.filter((ab) => ab !== a));
                                                    }}
                                                />
                                                {a}
                                            </label>
                                        ))}
                                    </div>
                                    <p className="text-xs text-muted-foreground">
                                        Note: Delete and admin permissions cannot be granted via whitelist. Access Token is always required.
                                    </p>
                                </div>
                            </div>

                            <DialogFooter>
                                <Button variant="secondary" onClick={() => setShowWhitelistDialog(false)}>Cancel</Button>
                                <Button onClick={saveOrigin} disabled={!newOriginUrl.trim() || newOriginAbilities.length === 0}>
                                    {editingOrigin ? 'Save' : 'Add'}
                                </Button>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>

                    {/* Domain Whitelist Delete Confirmation */}
                    <AlertDialog open={originToDelete!==null} onOpenChange={(open)=>{if(!open) setOriginToDelete(null);}}>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Delete Domain Whitelist</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Are you sure you want to remove domain <strong>{originToDelete?.origin}</strong> from the whitelist? Frontend requests from this domain will require a token to access the API.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <Button variant="destructive" onClick={confirmDeleteOrigin}>Delete</Button>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </ProjectSettingsLayout>
        </AppLayout>
    );
} 