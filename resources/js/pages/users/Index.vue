<!-- UsersTable.vue - Contoh penggunaan DataTable untuk data lain -->
<script setup lang="ts">
import { ref } from 'vue';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

interface User {
    created_at: string;
    email: string;
    email_verified_at: string;
    id: number;
    name: string;
    updated_at: string;
}
const prop = defineProps({ users: Object });

console.log(prop);

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
        width:'130px'
    }
];

// Actions configuration
const actions: TableAction<User>[] = [
    {
        label: 'View Profile',
        onClick: (row) => console.log('View profile:', row.id),
    },
    {
        label: 'Edit',
        onClick: (row) => router.visit('/dashboard'),
    },
    {
        label: 'Delete',
        onClick:(row) => console.log('Delete',row.id)
    }
];

// Filter options
const filterOptions: FilterOption[] = [
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
];

const getRoleVariant = (role: string) => {
    switch (role.toLowerCase()) {
        case 'admin':
            return 'destructive';
        case 'moderator':
            return 'secondary';
        case 'user':
            return 'outline';
        default:
            return 'default';
    }
};

const getStatusVariant = (status: string) => {
    return status.toLowerCase() === 'active' ? 'default' : 'secondary';
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

        <Head title="Billiard Tables" />

        <div class="div-y-6 p-4">
            <DataTable :data="users" :columns="columns" :actions="actions" title="Users Management"
                description="Manage system users and their permissions" :searchable="true"
                search-placeholder="Search users..."  :add-button="true" add-button-label="Add User"
                :caption="`Total users: ${users.length}`" empty-message="No users found.">
                <!-- Custom user cell with avatar -->
                <template #cell-name="{ value, row }">
                    <div class="flex items-center gap-3">
                        <Avatar class="h-8 w-8">
                            <AvatarImage :src="row.avatar" :alt="value" />
                            <AvatarFallback>{{ getInitials(value) }}</AvatarFallback>
                        </Avatar>
                        <span class="font-medium">{{ value }}</span>
                    </div>
                </template>

                <!-- Custom role badge -->
                <template #cell-role="{ value }">
                    <Badge :variant="getRoleVariant(value)">
                        {{ value }}
                    </Badge>
                </template>

                <!-- Custom status badge -->
                <template #cell-status="{ value }">
                    <Badge :variant="getStatusVariant(value)">
                        {{ value }}
                    </Badge>
                </template>
            </DataTable>
        </div>
    </AppLayout>
</template>