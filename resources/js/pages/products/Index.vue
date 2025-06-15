<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import DataTable ,{type TableColumn,type TableAction} from '@/components/DataTable.vue';
import { type BreadcrumbItem  } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/dashboard/products',
    },
];

interface Product {
    id: number;
    name: string;
    price: string;
    category?: string;
    stock?: string|null;
    created_at: string;
    updatef_at:string;
};
const prop = defineProps<{products: Product|Object}>();
const columns: TableColumn<Product>[] = [
  {
    key: 'name',
    label: 'Nama Produk',

  },
  {
    key: 'price',
    label: 'Price',
        width: '100px',
  },
  {
    key:'category',
    label:'Category',
  },
  {
    key: 'stock',
    label: 'Stock',
        width: '100px',
  }
];
const actions: TableAction<Product>[] = [
  
  {
    label: 'Edit',
    onClick: (row) => console.log(row.id),
  },
  {
    label: 'Delete',
    onClick: (row) => console.log(row.id),
    variant: 'destructive',
  },
];

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Products" />
        <div class="space-y-6 p-4">
               <DataTable :data="Object.values(products)" :columns="columns" :actions="actions" title="products Management"
                description="Manage system products and their permissions" :searchable="true"
                search-placeholder="Search products..."  :add-button="true" add-button-label="Add User"
                :caption="`Total products: ${Object.values(products).length}`" empty-message="No products found.">
              
            </DataTable>
        </div>
    </AppLayout>
</template>
