<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { type BreadcrumbItem, type JsonResponse } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ClockIcon, PlayIcon, UserIcon } from 'lucide-vue-next';
import DurationTimer from '@/components/DurationTimer.vue';
import {
    Dialog,
    DialogTrigger,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogClose,
} from '@/components/ui/dialog';
import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select';
import { formatRupiah } from '@/lib/utils';
import { toast, Toaster } from 'vue-sonner';

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



const props = defineProps({ billiardTables: Object, availableTable: Number, occupiedTable: Number, maintenanceTable: Number, pricingrates: Object });

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Monitoring Billiard Table',
        href: '/dashboard/billiard/tables',
    },
];

const formatTime = (timeString: string): string => {
    
    const date = new Date(`${timeString}`);

    // Gunakan toLocaleTimeString dengan id-ID dan 24 jam
    let localized= date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    });
    return localized.replace(".",":");
};


const calculateDuration = (startTime: string): string => {
 
    const start = new Date(`${startTime}`);
    const now = new Date();
    const diffMs = now.getTime() - start.getTime();
    const diffMins = Math.floor(diffMs / (1000 * 60));

    const hours = Math.floor(diffMins / 60);
    const minutes = diffMins % 60;

    const hourStr = hours > 0 ? `${hours} jam` : '';
    const minuteStr = `${minutes} menit`;

    return `${hourStr} ${minuteStr}`.trim();
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

const isOpenDialogOpen = ref(false);
const isCloseDialogOpen = ref(false);
const selectedRate = ref<number | null>(null);
const selectedTable = ref<BilliardTable[] | any>([]);
function actionOpen(table: BilliardTable[]) {
    isOpenDialogOpen.value = true;
    selectedTable.value = table;
}
function actionClose(table:BilliardTable[])
{
    isCloseDialogOpen.value = true
    selectedTable.value = table;
}

async function openTable(id: number) {
    // Logic to open table, e.g. POST to backend with selectedRate
    console.log('Open table with rate:', selectedRate.value);
    const form = new FormData();
    form.append('table_id', String(id));
    form.append('rate_per_hour', String(selectedRate.value));

    const respon = await fetch('/api/session-billiard/create', {
        method: 'POST',
        headers: {
            Authentication: 'Bearer '
        },
        body: form
    });
    isOpenDialogOpen.value = false;

    if (!respon.ok) {
        return toast('Error! , please try again~', {
            description: 'Server error please reload page and try again',
            action: {
                label: 'Reload page',
                onClick: () => window.location.reload()
            }
        });
    }

    let json_response: JsonResponse = await respon.json();
    if (json_response.success) {
        setTimeout(() => window.location.reload(), 2000);
        return toast('Success!', {
            description: 'Table has opened!',
        });
    } else {
        return toast('Error! , please try again~', {
            description: 'Server error please reload page and try again',
            action: {
                label: 'Reload page',
                onClick: () => window.location.reload()
            }
        });
    }

}

async function closeTable(action: 'continue' | 'just_close' , table_id:number) {
    

    const form = new FormData();
    form.append('action',action);
    form.append('table_id',String(table_id));

    const respon = await fetch('/api/session-billiard/close',{
        method:'POST',
        headers:{
            Authentication:'Bearer '
        },
        body: form
    });
      isCloseDialogOpen.value = false;

    if (!respon.ok) {
        return toast('Error! , please try again~', {
            description: 'Server error please reload page and try again',
            action: {
                label: 'Reload page',
                onClick: () => window.location.reload()
            }
        });
    }

    let json_response: JsonResponse = await respon.json();
    if (json_response.success) {
        setTimeout(() => window.location.href = json_response?.data?.redirect_url, 500);
        return toast('Success!', {
            description: 'Table has opened!',
        });
    } else {
        return toast('Error! , please try again~', {
            description: 'Server error please reload page and try again',
            action: {
                label: 'Reload page',
                onClick: () => window.location.reload()
            }
        });
    }

}
function getRateByCurrentTime(rates: any[]): number | null {
    const now = new Date();
    const currentTime = now.toTimeString().slice(0, 8); // Format HH:mm:ss

    for (const rate of rates) {
        const start = rate.start_at;
        const end = rate.end_at;

        // Cek apakah interval waktu melewati tengah malam
        if (start <= end) {
            // interval normal, misal 10:00 - 18:00
            if (start <= currentTime && currentTime <= end) {
                return rate.price_per_hour;
            }
        } else {
            // interval malam, misal 21:00 - 02:00
            if (currentTime >= start || currentTime <= end) {
                return rate.price_per_hour;
            }
        }
    }

    return null;
}


onMounted(() => {
    selectedRate.value = getRateByCurrentTime(props?.pricingrates);
    console.log(selectedRate.value);
});
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
                            <p class="text-2xl font-bold text-green-600">{{ availableTable }}</p>
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
                            <p class="text-2xl font-bold text-red-600">{{ occupiedTable }}</p>
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
                            <p class="text-2xl font-bold text-yellow-600">{{ maintenanceTable }}</p>
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
            <Card v-for="table in billiardTables" :key="table.id"
                :class="['cursor-pointer transition-all duration-200 hover:shadow-lg', getStatusColor(table.status)]">
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
                    <div class="text-center bg-slate-800 text-white p-4 rounded-lg shadow-lg">
                        <div class="font-bold text-xl mb-4">{{ table.name }}</div>
                        <!-- Open / Close Table Button -->
                        <span v-if="table.status == 'available' || table.status == 'occupied'">
                            <button
                                class="w-full py-2 rounded-md font-semibold transition cursor-pointer bg-slate-500 hover:bg-slate-400"
                                @click="table.status === 'available' ? actionOpen(table) : actionClose(table)">
                                <span v-if="table.status == 'available'">Open Table</span>
                                <span v-else-if="table.status == 'occupied'">Close Table</span>
                            </button>
                        </span>
                        <span v-else>
                            <span>🏗️ Maintenance</span>

                        </span>
                        <!-- 🔓 Dialog for Open Table -->
                        <Dialog v-model:open="isOpenDialogOpen">
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Open Table: {{ selectedTable?.name }}</DialogTitle>
                                </DialogHeader>

                                <div class="space-y-4">
                                    <label class="block font-medium">Select Price Rate / Hour</label>
                                    <Select v-model="selectedRate">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Choose rate" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(rate, index) in pricingrates" :key="index"
                                                :value="rate.price_per_hour">[{{ rate.name }}] - {{
                                                formatRupiah(rate.price_per_hour) }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <DialogFooter class="mt-6">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 cursor-pointer"
                                        @click="openTable(selectedTable?.id)">
                                        Continue
                                    </button>
                                    <DialogClose as-child>
                                        <button class="ml-2 px-4 py-2 text-gray-600">Cancel</button>
                                    </DialogClose>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- 🔒 Dialog for Close Table -->
                        <Dialog v-model:open="isCloseDialogOpen">
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Close Table: {{ table.name }}</DialogTitle>
                                </DialogHeader>

                                <p class="text-sm text-gray-50openTable0">How do you want to proceed?</p>

                                <DialogFooter class="mt-6 space-x-2">
                                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500"
                                        @click="closeTable('continue' , selectedTable.id)">
                                        Continue Transaction
                                    </button>
                                    <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-400"
                                        @click="closeTable('just_close',selectedTable.id)">
                                        Just Close Table
                                    </button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                    </div>


                    <!-- Occupied Information -->
                    <div v-if="table.status === 'occupied'" class="space-y-2 pt-2 border-t">
                        <div class="flex items-center gap-2 text-sm">
                            <ClockIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Start:</span>
                            
                            <span>{{ formatTime(table.session.start_time) }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <UserIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Harga/Jam:</span>
                            <span class="truncate">{{ formatRupiah(table.session.rate_per_hour) }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <PlayIcon class="h-4 w-4 text-gray-600" />
                            <span class="font-medium">Duration:</span>
                           <DurationTimer :startTime="table.session.start_time" />
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