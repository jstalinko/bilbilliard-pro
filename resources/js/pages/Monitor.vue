<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { RangeCalendar } from '@/components/ui/range-calendar'
import {onMounted,ref,computed } from 'vue';

interface BilliardTable {
  id: number;
  name: string;
  number: number;
  status: 'available' | 'occupied' | 'maintenance';
  start_time?: string;
  customer_name?: string;
  duration?: number;
  price_per_hour?: number;
}

interface WaitingList {
  id: number;
  name: string;
  number:number;
  status: 'played' | 'waiting' | 'cancel';
  created_at?: string;
  updated_at?: string;
}

const props = defineProps<{
  billiard_tables: BilliardTable[];
  waiting_lists: WaitingList[];
}>();

const selectedFilter = ref('today')
const customRange = ref('') // Format: dd/mm/yyyy-dd/mm/yyyy
const liveTime = ref('')
const dateRange = ref(null);

const getStatusColor = (status: BilliardTable['status']) => {
  switch (status) {
    case 'available':
      return 'bg-green-500';
    case 'occupied':
      return 'bg-red-500';
    case 'maintenance':
      return 'bg-yellow-400';
    default:
      return 'bg-gray-300';
  }
};

const getWaitingStatusColor = (status: WaitingList['status']) => {
  switch (status) {
    case 'waiting':
      return 'text-white bg-blue-500';
    case 'played':
      return 'text-white bg-gray-500';
    case 'cancel':
      return 'text-white bg-red-300';
    default:
      return 'text-white bg-gray-400';
  }
};



function updateTime() {
  const now = new Date()
  let jam = now.toLocaleTimeString('id-ID', { hour12: false })
  jam = jam.replace(/\./g, ':')
  liveTime.value = jam
}

onMounted(() => {
  updateTime()
  setInterval(updateTime, 1000)
})
const today = new Date()
const formattedToday = computed(() =>
  today.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  })
)

function handleFilterChange(value) {
  if (value === 'today' || value === 'yesterday') {
    window.location.href = `?show=${value}`
    // or use router.push({ query: { show: value } }) if using SPA
  }
  // else wait for custom date input
}

function handleCustomRangeChange(value) {
  if (value?.from && value?.to) {
    const formatDate = (date) =>
      date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }).replaceAll('/', '/')

    const formattedRange = `${formatDate(value.from)}-${formatDate(value.to)}`
    window.location.href = `?show=${formattedRange}`
  }
}

</script>

<template>
  <div class="w-screen h-screen p-6 bg-black text-white flex flex-col">
    <!-- Header Navigation with Live Clock -->
    <nav class="flex items-center justify-between bg-gray-900 rounded-2xl px-6 py-4 mb-6 shadow-lg">
      <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1Zm.75 11.66h-4a.75.75 0 0 1 0-1.5h3.25V6.75a.75.75 0 0 1 1.5 0v5.16a.75.75 0 0 1-.75.75Z" />
        </svg>
        <h1 class="text-2xl font-semibold">BILBILLIARD-PRO</h1>
      </div>
         <!-- Formatted Current Date -->
          <div class="border-2 border-slate-100 rounded-lg px-3 py-2 text-white bg-slate-800">
            {{ formattedToday }}
          </div>
      <div class="text-yellow-400 font-mono text-lg font-bold">
        JAM : {{ liveTime }}
      </div>
      
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-full">
      <!-- Left Column: Billiard Tables -->
      <div class="overflow-auto">
        <h2 class="text-3xl font-bold mb-4 text-white">Meja Billiard</h2>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="table in billiard_tables"
            :key="table.id"
            class="rounded-2xl shadow-lg text-white p-4 h-44 flex flex-col justify-between border-2 border-5 border-yellow-800"
            :class="getStatusColor(table.status)"
          >
            <div class="text-xl font-bold">Meja {{ table.number }}</div>
            <div class="text-sm italic">{{ table.name }}</div>
            <div v-if="table.status === 'occupied'" class="text-xs mt-2">
              <div>Nama: {{ table.customer_name }}</div>
              <div>Mulai: {{ table.start_time }}</div>
              <div>Durasi: {{ table.duration }} menit</div>
            </div>
            <div v-else-if="table.status === 'maintenance'" class="text-xs mt-2">
              <div>Sedang dalam perawatan</div>
            </div>
            <div v-else class="text-xs mt-2">Tersedia</div>
          </div>
        </div>
      </div>

      <!-- Right Column: Waiting List -->
      <div class="overflow-auto">
        <h2 class="text-3xl font-bold mb-4 text-white">Antrian Menunggu</h2>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
       

          <!-- ShadCN Select -->
          <Select v-model="selectedFilter" @update:modelValue="handleFilterChange">
            <SelectTrigger class="w-[250px] bg-slate-800 text-white border-slate-300">
              <SelectValue placeholder="Pilih filter" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem value="today">Tampilkan antrian hari ini</SelectItem>
                <SelectItem value="yesterday">Tampilkan antrian kemarin</SelectItem>
                <SelectItem value="custom">Pilih tanggal sendiri</SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>

          <!-- ShadCN Range Calendar (v-if) -->
          <div v-if="selectedFilter === 'custom'" class="mt-2 md:mt-0">
            <RangeCalendar
              v-model="dateRange"
              locale="id"
              :number-of-months="2"
              @update:modelValue="handleCustomRangeChange"
            />
          </div>
        </div>

        <!-- Waiting List -->
        <div class="space-y-2 mt-6">
          <div
            v-for="waiting in waiting_lists"
            :key="waiting.id"
            class="rounded-xl px-4 py-3 shadow-md flex items-center justify-between text-lg font-medium"
            :class="getWaitingStatusColor(waiting.status)"
          >
            
            <span>{{waiting.number}}. {{ waiting.name }}</span>
            <span class="capitalize text-sm">Status: {{ waiting.status }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
