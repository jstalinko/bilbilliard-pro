<!-- BilliardTables.vue -->
<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable, { type TableColumn, type TableAction, type FilterOption } from '@/components/DataTable.vue';
import { Badge } from '@/components/ui/badge';

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
  type: string;
  status: string;
  location: string;
  hourlyRate: number;
  lastMaintenance: string;
}

const billiardTables = ref<BilliardTable[]>([
  {
    id: 1,
    name: 'Table 1',
    type: '8-Ball',
    status: 'Available',
    location: 'Main Hall',
    hourlyRate: 25000,
    lastMaintenance: '2024-06-10',
  },
  {
    id: 2,
    name: 'Table 2',
    type: '9-Ball',
    status: 'Occupied',
    location: 'Main Hall',
    hourlyRate: 30000,
    lastMaintenance: '2024-06-08',
  },
  {
    id: 3,
    name: 'Table 3',
    type: '8-Ball',
    status: 'Maintenance',
    location: 'Side Room',
    hourlyRate: 25000,
    lastMaintenance: '2024-06-05',
  },
  {
    id: 4,
    name: 'Table 4',
    type: 'Snooker',
    status: 'Available',
    location: 'VIP Room',
    hourlyRate: 50000,
    lastMaintenance: '2024-06-12',
  },
  {
    id: 5,
    name: 'Table 5',
    type: '9-Ball',
    status: 'Reserved',
    location: 'Main Hall',
    hourlyRate: 30000,
    lastMaintenance: '2024-06-11',
  },
]);

// Table columns configuration
const columns: TableColumn<BilliardTable>[] = [
  {
    key: 'name',
    label: 'Table',
    width: '100px',
  },
  {
    key: 'type',
    label: 'Type',
  },
  {
    key: 'status',
    label: 'Status',
  },
  {
    key: 'location',
    label: 'Location',
  },
  {
    key: 'hourlyRate',
    label: 'Hourly Rate',
    render: (value: number) => formatCurrency(value),
  },
  {
    key: 'lastMaintenance',
    label: 'Last Maintenance',
  },
];

// Table actions configuration
const actions: TableAction<BilliardTable>[] = [
  {
    label: 'View Details',
    onClick: (row) => viewDetails(row.id),
  },
  {
    label: 'Edit Table',
    onClick: (row) => editTable(row.id),
  },
  {
    label: 'Delete Table',
    onClick: (row) => deleteTable(row.id),
    variant: 'destructive',
  },
];

// Filter options
const filterOptions: FilterOption[] = [
  { label: 'Available', value: 'available' },
  { label: 'Occupied', value: 'occupied' },
  { label: 'Reserved', value: 'reserved' },
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
  console.log('Add new table');
  // Navigate to add table form or open modal
};

const editTable = (id: number) => {
  console.log('Edit table:', id);
  // Navigate to edit form or open modal
};

const deleteTable = (id: number) => {
  console.log('Delete table:', id);
  // Show confirmation dialog then delete
};

const viewDetails = (id: number) => {
  console.log('View table details:', id);
  // Navigate to details page or open modal
};

const handleSearch = (query: string) => {
  console.log('Search query:', query);
  // You can add custom search logic here if needed
};

const handleFilter = (value: string) => {
  console.log('Filter value:', value);
  // You can add custom filter logic here if needed
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Billiard Tables" />
        
       <div class="div-y-6 p-4">
         <DataTable
          :data="billiardTables"
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
          :caption="`A list of your billiard tables (${billiardTables.length} total)`"
          empty-message="No billiard tables found."
          @add="addTable"
          @search="handleSearch"
          @filter="handleFilter"
        >
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