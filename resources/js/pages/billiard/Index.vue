<!-- BilliardTables.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import { Head, router , useForm} from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
import { Select , SelectContent,SelectTrigger,SelectValue,SelectItem } from '@/components/ui/select';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Billiard Tables',
        href: '/dashboard/billiards',
    },
];

// Sample data
interface BilliardTable {
  id: number;
  name: string;
  number: string;
  status: string;
}

const prop = defineProps<{
  billiardTables: BilliardTable[] | Object;
}>();



// Table columns configuration
const columns: TableColumn<BilliardTable>[] = [
  {
    key: 'number',
    label: 'Nomer',
    width: '100px',
  },
  {
    key: 'name',
    label: 'Name',
  },
  {
    key: 'status',
    label: 'Status',
  }
];

// Table actions configuration
const actions: TableAction<BilliardTable>[] = [
  
  {
    label: 'Edit',
    onClick: (row) => editTable(row.id),
  },
  {
    label: 'Delete',
    onClick: (row) => deleteTable(row.id),
    variant: 'destructive',
  },
];

// Filter options
const filterOptions: FilterOption[] = [
  { label: 'Available', value: 'available' },
  { label: 'Occupied', value: 'occupied' },
  { label: 'Maintenance', value: 'maintenance' },
];

// Helper functions
const getStatusVariant = (status: string) => {
  switch (status.toLowerCase()) {
    case 'available':
      return 'default';
    case 'occupied':
      return 'destructive';
    case 'maintenance':
      return 'secondary';
    case 'reserved':
      return 'outline';
    default:
      return 'default';
  }
};

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};

// Event handlers
const addTable = () => {
  return router.visit('/dashboard/billiards/create');
};

const editTable = (id: number) => {
  return router.visit('/dashboard/billiards/'+id+'/edit');
};

const deleteTable = (id: number) => {
  return router.visit('/dashboard/billiards/'+id+'/delete');
  // console.log('Delete table:', id);
  // Show confirmation dialog then delete
};



const handleSearch = (query: string) => {
  console.log('Search query:', query);
  // You can add custom search logic here if needed
};

const handleFilter = (value: string) => {
  console.log('Filter value:', value);
  // You can add custom filter logic here if needed
};
const updateStatus = (id: number, newStatus: BilliardTable['status'] | null | any) => {
  const form = useForm({
    status: newStatus,
  });

  form.post(`/dashboard/billiards/${id}/update-status`, {
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
        <Head title="Billiard Tables" />
        
       <div class="div-y-6 p-4">
         <DataTable
          :data="Object.values(billiardTables)"
          :columns="columns"
          :actions="actions"
          title="Billiard Tables"
          description="Manage your billiard tables and their availability"
          :searchable="true"
          search-placeholder="Search tables..."
          :filterable="true"
          :filter-options="filterOptions"
          filter-label="Status"
          :add-button="true"
          add-button-label="Add Table"
          :caption="`Total Tables: ${Object.values(billiardTables).length}`" 
          empty-message="No billiard tables found."
          @add="addTable"
          @filter="handleFilter"
        >
         <template #cell-status="{ value, row }">
  <Select 
    :model-value="value"
    @update:modelValue="(newStatus) => updateStatus(row.id, newStatus)"
  >
    <SelectTrigger
  :class="`w-[120px] border-1 ${
    value === 'available' ? 'border-green-500' :
    value === 'maintenance' ? 'border-yellow-500' :
    'border-red-500'
  }`"
>
      <SelectValue />
    </SelectTrigger>
    <SelectContent>
      <SelectItem value="available">Available</SelectItem>
      <SelectItem value="occupied">Occupied</SelectItem>
      <SelectItem value="maintenance">Maintenance</SelectItem>
    </SelectContent>
  </Select>
</template>

        </DataTable>
       </div>
    </AppLayout>
</template>