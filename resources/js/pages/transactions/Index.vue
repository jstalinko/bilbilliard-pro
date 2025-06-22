<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem, type BilliardSession, type BilliardTable , type TransactionItem } from '@/types';
import DataTable from '@/components/DataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type TableAction, type TableColumn, type FilterOption } from '@/components/DataTable.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { diffHourDuration, dateTimeIndonesia, formatRupiah } from '@/lib/utils';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import { Separator } from '@/components/ui/separator';

interface Transaction {
  id: number;
  session_id?: number | null;
  session?: BilliardSession | null;
  items?: any;

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
    key: 'session.id',
    label: 'Session ID'
  },
  {
    key: 'type',
    label: 'TYPE'
  },
  {
    key: 'payment_method',
    label: 'Payment Method'
  },
  {
    key: 'total_amount',
    label: 'Total'
  },
  {
    key: 'paid_amount',
    label: 'Di bayar'
  },
  {
    key: 'change',
    label: 'Kembalian'
  },
  {
    key: 'tx_status',
    label: 'Status Tx'
  },
  {
    key: 'note',
    label: 'Notes'
  }
];
const prop = defineProps<{
  transactions: Transaction[] | Object;
}>();
const openDialogDetail = ref<boolean>(false);
const txDetail:any = ref([]);

// contoh data dummy; real-nya bisa diisi dari props atau API
const transactionItems = ref([
  { product_name: 'Mie Goreng', quantity: 2, price: 10000 },
  { product_name: 'Teh Botol', quantity: 1, price: 8000 },
])
const openDialog = (tx_id: number | string | any) => {
  openDialogDetail.value = true;
  // cari transaksi berdasarkan ID
  const transaction = Object.values(prop.transactions).find((tx: Transaction) => tx.id === tx_id);
  if (transaction) {
    txDetail.value = transaction;
  } else {
    txDetail.value = []; // jika tidak ditemukan, set null
  }
  return openDialogDetail.value;
}
const closeDialog = () => {
  openDialogDetail.value = false
}

// Table actions configuration
const actions: TableAction<Transaction>[] = [

  {
    label: 'Edit',
    onClick: (row) => '',
  },
  {
    label: 'Detail',
    onClick: (row) => openDialog(row.id),

  }

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
      <DataTable :data="Object.values(transactions)" :columns="columns" :actions="actions" title="Transaksi"
        description="Manage your transactions and their availability" :searchable="true"
        search-placeholder="Search transactions..." :add-button="true" add-button-label="New Transactions"
        :caption="`Total Transactions : ${Object.values(transactions).length}`" empty-message="No Transactions found."
        :filterable="true" :filter-label="'Filter Status'" :filter-options="filterOptions" @add="handleAdd">
        <template #cell-session.id="{ value }">
          <Badge v-if="value != null">{{ value }}</Badge>
          <Badge v-else>RESTO</Badge>
        </template>
        <template #cell-tx_status="{ value }">
          <Badge :variant="getStatusVariant(value)">{{ value }}</Badge>
        </template>
        <template #cell-total_amount="{ value }">
          <Badge>{{ formatRupiah(value) }}</Badge>
        </template>
        <template #cell-paid_amount="{ value }">
          <Badge>{{ formatRupiah(value) }}</Badge>
        </template>
        <template #cell-change="{ value }">
          <Badge>{{ formatRupiah(value) }}</Badge>
        </template>
      </DataTable>
    </div>
    <Dialog :open="openDialogDetail" @update:open="openDialogDetail = $event">
      <DialogTrigger class="hidden">Open Dialog</DialogTrigger>
      <DialogContent class="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>Detail Transaksi</DialogTitle>
          <DialogDescription>
            Rincian item yang dibeli pada sesi ini.
          </DialogDescription>
        </DialogHeader>

        <div class="grid gap-4 py-4">

          <b>PRODUK F&B</b>
          <div v-if="txDetail.items.length > 0" v-for="(item, index) in txDetail.items" :key="index"
            class="flex items-center justify-between">
            <span>{{ item.product.name }}</span>
            <span class="text-right">
              {{ item.quantity }} x {{ formatRupiah(item.price) }} = {{ formatRupiah(item.quantity * item.price) }}
            </span>
          </div>
          <div v-else class="text-center text-muted-foreground">
            Tidak ada produk restoran yang dibeli.
          </div>
          <span class="flex items-center justify-between">
            <span class="font-medium">Total Produk</span>
            <span class="font-medium">{{ formatRupiah(txDetail.items.reduce((total:any, item:any) => total + (item.quantity * item.price), 0)) }}</span>
          </span>
          <Separator class="my-2" />
          <b>BILLIARD BILLING</b>
          <div v-if="txDetail.session">
        <span class="flex items-center justify-between">
          <span>Meja Billiard</span>
          <span class="text-right">No. {{txDetail.session.table.number}} - {{ txDetail.session.table.name }}</span>
        </span>
        <span class="flex items-center justify-between">
          <span>Waktu Mulai</span>
          <span class="text-right">{{ dateTimeIndonesia(txDetail.session.start_time) }}</span>
        </span>
        <span class="flex items-center justify-between">
          <span>Waktu Selesai</span>
          <span class="text-right">{{ txDetail.session.end_time ? dateTimeIndonesia(txDetail.session.end_time) : 'Ongoing' }}</span>
        </span>
        <span class="flex items-center justify-between">
          <span>Tarif per Jam</span>
          <span class="text-right">{{ formatRupiah(txDetail.session.rate_per_hour) }} x {{ diffHourDuration(txDetail.session.start_time , txDetail.session.end_time) }} Jam</span>
        </span>
      
        <span class="flex items-center justify-between">
          <span>Total Harga</span>
          <span class="text-right">{{ formatRupiah(txDetail.session.total_price || 0) }}</span>
        </span>
        </div>
        <div v-else class="text-center text-muted-foreground">
          Tidak ada sesi billiard yang terkait dengan transaksi ini.
        </div>
     
          <Separator class="my-2" />
          <div class="flex items-center justify-between">
          <span class="font-medium">Di-Bayar</span>
          <span class="font-medium">{{ formatRupiah(txDetail.paid_amount) }}</span>
          </div>
          <div class="flex items-center justify-between">
          <span class="font-medium">Kembalian</span>
          <span class="font-medium">{{ formatRupiah(txDetail.change) }}</span>
          </div>
          <div class="flex items-center justify-between">
          <span class="font-medium">Status</span>
          <span class="font-medium">{{ txDetail.tx_status }}</span>
          </div>
          <div class="flex items-center justify-between">
          <span class="font-medium">Catatan</span>
          <span class="font-medium">{{ txDetail.note || 'Tidak ada catatan' }}</span>
          </div>
          <div class="flex items-center justify-between">
          <span class="font-medium">Metode Bayar</span>
          <span class="font-medium">{{ txDetail.payment_method.toUpperCase() }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="font-medium">Total</span>
            <span class="font-medium">{{ formatRupiah(txDetail.total_amount) }}</span>
          </div>
        
        </div>

        <DialogFooter>
          <Button @click="closeDialog">Tutup</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

  </AppLayout>
</template>
