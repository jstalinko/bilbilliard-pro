<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ClockIcon, PlayIcon, UserIcon } from 'lucide-vue-next';

// Types
interface BilliardTable {
  id: number;
  name: string;
  number: number;
  status: 'available' | 'occupied' | 'maintenance';
  start_time?: string;
  customer_name?: string;
  duration?: number; // in minutes
  price_per_hour?: number;
}

interface Props {
  billiardTables: BilliardTable[];
}

const props = defineProps({billiardTables: Object})

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Monitoring Billiard Table',
        href: '/dashboard/billiard/tables',
    },
];

// Helper functions
const formatTime = (dateString: string): string => {
  return new Date(dateString).toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const calculateDuration = (startTime: string): string => {
  const start = new Date(startTime);
  const now = new Date();
  const diffMs = now.getTime() - start.getTime();
  const diffMins = Math.floor(diffMs / (1000 * 60));
  
  const hours = Math.floor(diffMins / 60);
  const minutes = diffMins % 60;
  
  if (hours > 0) {
    return `${hours}j ${minutes}m`;
  }
  return `${minutes}m`;
};

const getStatusColor = (status: string): string => {
  switch (status) {
    case 'available':
      return 'bg-green-300 border-green-500 hover:bg-green-500';
    case 'occupied':
      return 'bg-red-300 border-red-500 hover:bg-red-500';
    case 'maintenance':
      return 'bg-yellow-300 border-yellow-500 hover:bg-yellow-500';
    default:
      return 'bg-gray-300 border-gray-300';
  }
};

const getStatusBadgeColor = (status: string): string => {
  switch (status) {
    case 'available':
      return 'bg-green-500 hover:bg-green-600';
    case 'occupied':
      return 'bg-red-500 hover:bg-red-600';
    case 'maintenance':
      return 'bg-yellow-500 hover:bg-yellow-600';
    default:
      return 'bg-gray-500';
  }
};

const getStatusText = (status: string): string => {
  switch (status) {
    case 'available':
      return 'Tersedia';
    case 'occupied':
      return 'Sedang Digunakan';
    case 'maintenance':
      return 'Maintenance';
    default:
      return status;
  }
};

// // Stats
// const availableTables = computed(() => 
//   props.billiardTables.value.filter(table => table.status === 'available').length
// );

// const occupiedTables = computed(() => 
//   props.billiardTables.value.filter(table => table.status === 'occupied').length
// );

// const maintenanceTables = computed(() => 
//   props.billiardTables.value.filter(table => table.status === 'maintenance').length
// );
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Monitoring Billiard Table" />
        
        <!-- Stats Overview -->
        <div class="mb-6 grid gap-4 md:grid-cols-3 p-4">
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Meja Tersedia</p>
                            <p class="text-2xl font-bold text-green-600">{{ availableTables }}</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                            <PlayIcon class="h-4 w-4 text-green-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>
            
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Sedang Digunakan</p>
                            <p class="text-2xl font-bold text-red-600">{{ occupiedTables }}</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                            <UserIcon class="h-4 w-4 text-red-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>
            
            <Card>
                <CardContent class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Maintenance</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ maintenanceTables }}</p>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <ClockIcon class="h-4 w-4 text-yellow-600" />
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Billiard Tables Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 p-4">
            <Card
                v-for="table in billiardTables"
                :key="table.id"
                :class="['cursor-pointer transition-all duration-200 hover:shadow-lg', getStatusColor(table.status)]"
            >
                <CardHeader class="pb-3">
                    <div class="flex items-center justify-between">
                        <CardTitle class="text-lg bg-slate-500 p-2 rounded-lg">{{ table.number }}</CardTitle>
                        <Badge :class="['text-white', getStatusBadgeColor(table.status)]">
                            {{ getStatusText(table.status) }}
                        </Badge>
                    </div>
                </CardHeader>
                
                <CardContent class="space-y-3">
                    <!-- Table Number -->
                    <div class="text-center">
                        <div class="mx-auto h-16 w-auto rounded-lg bg-slate-700 flex items-center justify-center text-white font-bold text-xl shadow-lg hover:bg-slate-500">
                            <span v-if="table.status == 'available'">
                                Open Table
                            </span>
                            <span v-else>
                                Close Table
                            </span>
                        </div>
                    </div>
                    
                    <!-- Occupied Information -->
                    <div v-if="table.status === 'occupied'" class="space-y-2 pt-2 border-t">
                        <div class="flex items-center gap-2 text-sm">
                            <ClockIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Start:</span>
                            <span>{{ formatTime(table.start_time!) }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm">
                            <UserIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Customer:</span>
                            <span class="truncate">{{ table.customer_name }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm">
                            <PlayIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Duration:</span>
                            <span class="font-mono font-semibold text-red-600">
                                {{ calculateDuration(table.start_time!) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Available Information -->
                    <div v-else-if="table.status === 'available'" class="text-center py-2">
                        <p class="text-sm text-gray-600">Siap digunakan</p>
                        <p class="text-xs text-gray-500 mt-1">Klik untuk mulai permainan</p>
                    </div>
                    
                    <!-- Maintenance Information -->
                    <div v-else-if="table.status === 'maintenance'" class="text-center py-2">
                        <p class="text-sm text-yellow-700 font-medium">Sedang Maintenance</p>
                        <p class="text-xs text-gray-500 mt-1">Tidak dapat digunakan</p>
                    </div>
                </CardContent>
            </Card>
        </div>
        
        <!-- Legend -->
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded bg-green-500"></div>
                <span class="text-sm text-gray-600">Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded bg-red-500"></div>
                <span class="text-sm text-gray-600">Sedang Digunakan</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="h-4 w-4 rounded bg-yellow-500"></div>
                <span class="text-sm text-gray-600">Maintenance</span>
            </div>
        </div>
    </AppLayout>
</template>