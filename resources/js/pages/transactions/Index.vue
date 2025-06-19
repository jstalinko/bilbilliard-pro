<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem , type BilliardSession , type BilliardTable} from '@/types';
import DataTable from '@/components/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type TableAction, type TableColumn , type FilterOption} from '@/components/DataTable.vue';
import Badge from '@/components/ui/badge/Badge.vue';
interface Transaction {
  id: number;
  session_id?: number | null;
  session?: BilliardSession | null;

  type: 'session' | 'direct';
  payment_method: string;
  paid_amount: number;
  total_amount: number;
  change: number;
  note?: string | null;

  billiard_table_id: number;
  billiard_table: BilliardTable;

  start_time: string; // ISO timestamp (e.g. "2025-06-14T14:00:00Z")
  end_time?: string | null;
  rate_per_hour: number;
  total_price?: number | null;
  status: 'ongoing' | 'finished';

  created_at: string;
  updated_at: string;
}
const columns: TableColumn<Transaction>[] = [
    {
        key:'session.id',
        label:'Session ID'
    },
    {
        key: 'type',
        label: 'TYPE'
    },
    {
        key:'payment_method',
        label:'Payment Method'
    },
    {
        key:'total_amount',
        label: 'Total'
    },
    {
        key:'paid_amount',
        label:'Di bayar'
    },
    {
        key:'change',
        label:'Kembalian'
    },
    {
        key:'tx_status',
        label:'Status Tx'
    },
    {
        key:'note',
        label:'Notes'
    }
];
const prop = defineProps<{
  transactions: Transaction[] | Object;
}>();

// Table actions configuration
const actions: TableAction<Transaction>[] = [
  
  {
    label: 'Edit',
    onClick: (row) => '',
  },
  {
    label: 'Delete',
    onClick: (row) => '',
    variant: 'destructive',
  },
];

// Filter options
const filterOptions: FilterOption[] = [
  { label: 'Pending', value: 'pending' },
  { label: 'Cancel', value: 'canceled' },
  { label: 'Complete', value: 'complete' },
];
const getStatusVariant = (status: string) => {
  switch (status.toLowerCase()) {
    case 'success':
      return 'default';
    case 'canceled':
      return 'destructive';
    case 'pending':
      return 'secondary';
    default:
      return 'default';
  }
};

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Transaksi',
        href: '/dashboard/transactions',
    },
];
const handleAdd = () => router.visit('/dashboard/transactions/create');
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Transactions" />
        <div class="space-y-6 p-4">
             <DataTable
          :data="Object.values(transactions)"
          :columns="columns"
          :actions="actions"
          title="Transaksi"
          description="Manage your transactions and their availability"
          :searchable="true"
          search-placeholder="Search transactions..."
          :add-button="true"
          add-button-label="New Transactions"
          :caption="`Total Transactions : ${Object.values(transactions).length}`" 
          empty-message="No Transactions found."
          :filterable="true"
          :filter-label="'Filter Status'"
          :filter-options="filterOptions"
          @add="handleAdd"
        >
            <template #cell-tx_status="{value}">
                <Badge :variant="getStatusVariant(value)">{{ value }}</Badge>
            </template>
        </DataTable>
        </div>
    </AppLayout>
</template>
