<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import DataTable ,{type TableColumn,type TableAction} from '@/components/DataTable.vue';
import { type BreadcrumbItem  } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';


const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Members',
        href: '/dashboard/members',
    },
];

interface Member {
    id: number;
    full_name: string;
    phone: string;
    point: string;
    total_tx: string|null;
    created_at: string;
    updatef_at:string;
};
const prop = defineProps<{members: Member|Object}>();
const columns: TableColumn<Member>[] = [
  {
    key: 'full_name',
    label: 'Nama Lengkap',

  },
  {
    key: 'phone',
    label: 'No. HP',
  },
  {
    key:'point',
    label:'Total Point',
  },
  {
    key: 'total_tx',
    label: 'Total Transaksi',
  }
];
const actions: TableAction<Member>[] = [
  
  {
    label: 'Edit',
    onClick: (row) => router.visit('/dashboard/members/'+row.id+'/edit'),
  },
  {
    label: 'Delete',
    onClick: (row) => router.visit('/dashboard/members/'+row.id+'/delete'),
    variant: 'destructive',
  },
];

const handleAdd = () => {
  router.visit('/dashboard/members/create');
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Products" />
        <div class="space-y-6 p-4">
               <DataTable :data="Object.values(members)" :columns="columns" :actions="actions" title="Members Management"
                description="Manage Members" :searchable="true"
                search-placeholder="Search members..."  :add-button="true" add-button-label="Add Member"
                @add="handleAdd"
                :caption="`Total members: ${Object.values(members).length}`" empty-message="No member found.">
              
            </DataTable>
        </div>
    </AppLayout>
</template>
