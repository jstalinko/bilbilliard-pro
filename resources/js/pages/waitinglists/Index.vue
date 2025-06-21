<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm , Head } from '@inertiajs/vue3';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Select,SelectContent,SelectItem,SelectValue,SelectTrigger } from '@/components/ui/select';
import { dateTimeIndonesia } from '@/lib/utils';
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
    {
        key:'created_at',
        label:'Created Date',
        
    },
    {
        key:'updated_at',
        label:'Updated Date',
        
    }
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
const updateStatus = (id: number, newStatus: WaitingEntry['status'] | null | any) => {
  const form = useForm({
    status: newStatus,
  });

  form.post(`/dashboard/waitinglist/${id}/update-status`, {
    preserveScroll: true,
    onSuccess: () => {
      // Optional: Add toast or feedback here
    },
    onError: () => {
      // Optional: handle error
    }
  });
};
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
            >
            
                <template #cell-status="{ value, row }">
  <Select
    :model-value="value"
    @update:modelValue="(newStatus) => updateStatus(row.id, newStatus)"
  >
    <SelectTrigger class="w-[140px]">
      <SelectValue />
    </SelectTrigger>
    <SelectContent>
      <SelectItem value="waiting">Waiting</SelectItem>
      <SelectItem value="played">Played</SelectItem>
      <SelectItem value="cancel">Cancel</SelectItem>
    </SelectContent>
  </Select>
</template>
<template #cell-created_at="{value,row}">
    <span>{{ dateTimeIndonesia(value) }}</span>
</template>
<template #cell-updated_at="{value,row}">
    <span>{{ dateTimeIndonesia(value) }}</span>
</template>
            
            </DataTable>
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
