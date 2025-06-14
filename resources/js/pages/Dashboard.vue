<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { 
  TableIcon, 
  UsersIcon, 
  PackageIcon, 
  CreditCardIcon, 
  CalendarDaysIcon, 
  CalendarIcon, 
  TrendingUpIcon, 
  TrendingDownIcon, 
  RefreshCcwDotIcon,
  CircleIcon,
  CircleDotIcon,
  CircleCheckBigIcon,
  PlusCircleIcon
} from 'lucide-vue-next';
import Button from '@/components/ui/button/Button.vue';
import { formatRupiah } from '@/lib/utils';


// Types
interface TotalStats {
  meja: number;
  members: number;
  product: number;
  transaksi: number;
}

interface TransactionStats {
  today: number;
  todayIncrease: number;
  thisMonth: number;
  thisMonthRevenue: number;
  lastMonth: number;
  lastMonthRevenue: number;
  monthComparison: number;
  pending:number;
  complete:number;
  pendingTotal:number;
  completeTotal:number;
}

interface Props {
  totalStats?: TotalStats;
  transactionStats?: TransactionStats;
}

// Props dari backend (Laravel)
const props = withDefaults(defineProps<Props>(), {
  totalStats: () => ({
    meja: 25,
    members: 342,
    product: 156,
    transaksi: 1240
  }),
  transactionStats: () => ({
    today: 12,
    todayIncrease: 8.2,
    thisMonth: 245,
    thisMonthRevenue: 15420000,
    lastMonth: 198,
    lastMonthRevenue: 12350000,
    monthComparison: 23.7,
    pending:0,
    complete:0,
    pendingTotal:0,
    completeTotal:0
  })
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

// Helper function untuk format currency
const formatCurrency = (amount: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
};
</script>
<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Stats Overview -->
    <div class="space-y-6 p-4">
      <!-- Top Stats Cards (Totals) -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Meja -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Total Meja</CardTitle>
            <TableIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ totalStats.meja }}</div>
            <p class="text-xs text-muted-foreground">
              Meja billiard tersedia
            </p>
          </CardContent>
        </Card>

        <!-- Total Members -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Total Members</CardTitle>
            <UsersIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ totalStats.members }}</div>
            <p class="text-xs text-muted-foreground">
              Members terdaftar
            </p>
          </CardContent>
        </Card>

        <!-- Total Product -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Total Product</CardTitle>
            <PackageIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ totalStats.product }}</div>
            <p class="text-xs text-muted-foreground">
              Produk tersedia
            </p>
          </CardContent>
        </Card>

        <!-- Total Transaksi -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Total Transaksi</CardTitle>
            <CreditCardIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ totalStats.transaksi }}</div>
            <p class="text-xs text-muted-foreground">
              Semua transaksi
            </p>
          </CardContent>
        </Card>
      </div>

      <!-- Bottom Stats Cards (Transaction Analytics) -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Transaksi Hari Ini -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Transaksi Hari Ini</CardTitle>
            <CalendarDaysIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ transactionStats.today }}</div>
            <p class="text-xs text-muted-foreground">
              +{{ transactionStats.todayIncrease }}% dari kemarin
            </p>
          </CardContent>
        </Card>

        <!-- Transaksi Bulan Ini -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Transaksi Bulan Ini</CardTitle>
            <CalendarIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ transactionStats.thisMonth }}</div>
            <p class="text-xs text-muted-foreground">
              {{ formatCurrency(transactionStats.thisMonthRevenue) }} revenue
            </p>
          </CardContent>
        </Card>

        <!-- Transaksi Bulan Kemarin -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Transaksi Bulan Kemarin</CardTitle>
            <CalendarIcon class="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ transactionStats.lastMonth }}</div>
            <p class="text-xs text-muted-foreground">
              {{ formatCurrency(transactionStats.lastMonthRevenue) }} revenue
            </p>
          </CardContent>
        </Card>

        <!-- Perbandingan Bulan -->
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Perbandingan Bulan</CardTitle>
            <TrendingUpIcon v-if="transactionStats.monthComparison > 0" class="h-4 w-4 text-green-600" />
            <TrendingDownIcon v-else class="h-4 w-4 text-red-600" />
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold" :class="transactionStats.monthComparison > 0 ? 'text-green-600' : 'text-red-600'">
              {{ transactionStats.monthComparison > 0 ? '+' : '' }}{{ transactionStats.monthComparison }}%
            </div>
            <p class="text-xs text-muted-foreground">
              {{ transactionStats.monthComparison > 0 ? 'Naik' : 'Turun' }} dari bulan kemarin
            </p>
          </CardContent>
        </Card>
      </div>


      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-2">
        <div >
          <Link href="/dashboard/billiards/update-all-tables?status=available">
          <Button class="cursor-pointer">
            <RefreshCcwDotIcon class="h-24 w-24"/> Update available all billiard tables
          </Button>
        </Link>
          &nbsp;
          <Link href="/dashboard/members/create">
          <Button class=" cursor-pointer ">
            <UsersIcon class="h-24 w-24"/> Tambah member
          </Button>
        </Link>
          &nbsp;
          <Link href="/dashboard/transactions/create">
          <Button class="cursor-pointer">
            <PlusCircleIcon class="h-24 w-24"/> Transaksi Baru
          </Button>
        </Link>
        </div>
        <div>
          
            <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Pending Transactions</CardTitle>
            <CircleDotIcon class="h-4 w-4 text-yellow-500"/>

          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-yellow-600">
              {{ transactionStats.pending }}
            </div>
            <p class="text-xs text-muted-foreground">
             Total transaksi pending : {{ formatRupiah(transactionStats.pendingTotal) }}
            </p>
          </CardContent>
        </Card>
        <br>
          <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Complete Transactions</CardTitle>
            <CircleCheckBigIcon class="h-4 w-4 text-green-500"/>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold text-green-600">
              {{ transactionStats.complete }}
            </div>
            <p class="text-xs text-muted-foreground">
             Total transaksi Complete : {{ formatRupiah(transactionStats.completeTotal) }}
            </p>
          </CardContent>
        </Card>
        
        </div>
      </div>
    </div>
  </AppLayout>
</template>

