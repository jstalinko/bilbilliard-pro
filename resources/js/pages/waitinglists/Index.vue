<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm , Head } from '@inertiajs/vue3';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
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
    AlertDialogTitle 
} from '@/components/ui/alert-dialog';

interface WaitingEntry {
    id: number;
    name: string;
    number: number;
    status: 'waiting' | 'played' | 'cancel';
}

const props = defineProps<{
    waitinglists: WaitingEntry[] | Object;
}>();

const deleteForm = useForm({});
const entryToDelete = ref<WaitingEntry | null>(null);
const showDeleteDialog = ref(false);

// Table columns
const columns: TableColumn<WaitingEntry>[] = [
    {
        key: 'name',
        label: 'Name',
        width: '200px',
    },
    {
        key: 'number',
        label: 'Number',
        width: '100px',
    },
    {
        key: 'status',
        label: 'Status',
        width: '120px',
    },
];

// Table actions
const actions: TableAction<WaitingEntry>[] = [
    {
        label: 'Edit',
        onClick: (row) => router.visit(`/dashboard/waitinglist/${row.id}/edit`),
    },
    {
        label: 'Delete',
        onClick: (row) => {
            entryToDelete.value = row;
            showDeleteDialog.value = true;
        },
        variant: 'destructive'
    }
];

// Filter options (optional if you have filters)
const filterOptions: FilterOption[] = [
    { label: 'Waiting', value: 'waiting' },
    { label: 'Played', value: 'played' },
    { label: 'Cancel', value: 'cancel' },
];

const confirmDelete = () => {
    if (entryToDelete.value) {
        deleteForm.get(`/dashboard/waitinglist/${entryToDelete.value.id}/delete`, {
            onSuccess: () => {
                showDeleteDialog.value = false;
                entryToDelete.value = null;
            }
        });
    }
};

const cancelDelete = () => {
    showDeleteDialog.value = false;
    entryToDelete.value = null;
};

const handleAddEntry = () => {
    router.visit('/dashboard/waitinglist/create');
};

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Waiting List',
        href: '/dashboard/waitinglist',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Waiting List" />

        <div class="space-y-6 p-4">
            <DataTable 
                :data="Object.values(waitinglists)" 
                :columns="columns" 
                :actions="actions" 
                title="Waiting List Management"
                description="Manage people waiting to be called or served"
                :searchable="true"
                search-placeholder="Search waiting entries..." 
                :add-button="true"
                @add="handleAddEntry"
                add-button-label="Add Entry"
                :caption="`Total entries: ${Object.values(waitinglists).length}`" 
                empty-message="No waiting entries found."
            />
        </div>

        <!-- Delete Confirmation Dialog -->
        <AlertDialog :open="showDeleteDialog" @update:open="showDeleteDialog = $event">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete the entry 
                        <strong>{{ entryToDelete?.name }}</strong> from the waiting list.
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
                        <span v-else>Delete Entry</span>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>
