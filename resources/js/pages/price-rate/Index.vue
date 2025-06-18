<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import DataTable ,{type TableColumn,type TableAction} from '@/components/DataTable.vue';
import { type BreadcrumbItem  } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';


const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Pricing Rates',
        href: '/dashboard/price-rate',
    },
];

interface PricingRate {
    id: number;
    name: string;
    price_per_hor: string;
    start_at: string;
    end_at: string|null;
    created_at: string;
    updatef_at:string;
};
const prop = defineProps<{pricingrates: PricingRate|Object}>();
const columns: TableColumn<PricingRate>[] = [
  {
    key: 'name',
    label: 'Name',

  },
  {
    key: 'price_per_hour',
    label: 'Pricing Rate/Hour',
        width: '100px',
  },
  {
    key:'start_at',
    label:'Start Time',
    width:'100px'
  },
  {
    key: 'end_at',
    label: 'End Time',
        width: '100px',
  }
];
const actions: TableAction<PricingRate>[] = [
  
  {
    label: 'Edit',
    onClick: (row) => router.visit('/dashboard/price-rate/'+row.id+'/edit'),
  },
  {
    label: 'Delete',
    onClick: (row) => router.visit('/dashboard/price-rate/'+row.id+'/delete'),
    variant: 'destructive',
  },
];

const handleAdd = () => {
  router.visit('/dashboard/price-rate/create');
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pricing Rates" />
        <div class="space-y-6 p-4">
               <DataTable :data="Object.values(pricingrates)" :columns="columns" :actions="actions" title="Pricing Rates Management"
                description="Manage system Price Rate" :searchable="true"
                search-placeholder="Search Price rate..."  :add-button="true" add-button-label="Add Rate"
                @add="handleAdd"
                :caption="`Total pricingrates: ${Object.values(pricingrates).length}`" empty-message="No pricingrates found.">
              
            </DataTable>
        </div>
    </AppLayout>
</template>
