<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm , Head } from '@inertiajs/vue3';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { 
    AlertDialog, 
    AlertDialogAction, 
    AlertDialogCancel, 
    AlertDialogContent, 
    AlertDialogDescription, 
    AlertDialogFooter, 
    AlertDialogHeader, 
    AlertDialogTitle, 
    AlertDialogTrigger 
} from '@/components/ui/alert-dialog';

interface User {
    created_at: string;
    email: string;
    email_verified_at: string;
    id: number;
    name: string;
    updated_at: string;
}

const props = defineProps<{
    users: User[] | Object;
}>();

const deleteForm = useForm({});
const userToDelete = ref<User | null>(null);
const showDeleteDialog = ref(false);

// Columns configuration
const columns: TableColumn<User>[] = [
    {
        key: 'name',
        label: 'User',
        width: '200px',
    },
    {
        key: 'email',
        label: 'Email',
    },
    {
        key: 'created_at',
        label: 'Join Date',
        width: '130px',
    },
    {
        key: 'updated_at',
        label: 'Updated At',
        width: '130px'
    }
];

// Actions configuration
const actions: TableAction<User>[] = [
    {
        label: 'Edit',
        onClick: (row) => router.visit(`/dashboard/users/${row.id}/edit`),
    },
    {
        label: 'Delete',
        onClick: (row) => {
            userToDelete.value = row;
            showDeleteDialog.value = true;
        },
        variant: 'destructive'
    }
];

// Filter options
const filterOptions: FilterOption[] = [
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
];

const confirmDelete = () => {
    if (userToDelete.value) {
        deleteForm.get(`/dashboard/users/${userToDelete.value.id}/delete`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                userToDelete.value = null;
            },
            onError: () => {
                // Error handling is done by Laravel's flash messages
            }
        });
    }
};

const cancelDelete = () => {
    showDeleteDialog.value = false;
    userToDelete.value = null;
};

const handleAddUser = () => {
    router.visit('/dashboard/users/create');
};

const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase();
};

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: '/dashboard/users',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="User Management" />

        <div class="space-y-6 p-4">
            <DataTable 
                :data="Object.values(users)" 
                :columns="columns" 
                :actions="actions" 
                title="Users Management"
                description="Manage system users and their permissions" 
                :searchable="true"
                search-placeholder="Search users..." 
                :add-button="true"
                @add="handleAddUser"
                add-button-label="Add User"
                :caption="`Total users: ${Object.values(users).length}`" 
                empty-message="No users found."
                
            >
                <!-- Custom user cell with avatar -->
                <template #cell-name="{ value, row }">
                    <div class="flex items-center gap-3">
                        <Avatar class="h-8 w-8">
                            <AvatarFallback>{{ getInitials(value) }}</AvatarFallback>
                        </Avatar>
                        <span class="font-medium">{{ value }}</span>
                    </div>
                </template>
                
                <template #cell-created_at="{ value }">
                    {{ new Date(value).toLocaleDateString() }}
                </template>
                
                <template #cell-updated_at="{ value }">
                    {{ new Date(value).toLocaleDateString() }}
                </template>
            </DataTable>
        </div>

        <!-- Delete Confirmation Dialog -->
        <AlertDialog :open="showDeleteDialog" @update:open="showDeleteDialog = $event">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete the user 
                        <strong>{{ userToDelete?.name }}</strong> and remove their data from the system.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="cancelDelete" :disabled="deleteForm.processing">
                        Cancel
                    </AlertDialogCancel>
                    <AlertDialogAction 
                        @click="confirmDelete" 
                        :disabled="deleteForm.processing"
                        class="bg-red-600 hover:bg-red-700"
                    >
                        <span v-if="deleteForm.processing">Deleting...</span>
                        <span v-else>Delete User</span>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>