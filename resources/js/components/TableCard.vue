<template>
  <Card class="h-full">
    <CardHeader>
      <CardTitle>{{ session.table?.number ?? 'Meja' }}</CardTitle>
      <CardDescription>
        Mulai: {{ formatTime(session.start_time) }}<br />
        Tarif: Rp{{ formatCurrency(session.rate_per_hour) }}/jam
      </CardDescription>
    </CardHeader>
    <CardContent>
      <div class="text-sm text-gray-700">
        <ul class="list-disc ml-4">
          <li v-for="item in session.items" :key="item.id">
            {{ item.product?.name ?? 'Produk' }} x {{ item.quantity }}
          </li>
        </ul>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card'

const props = defineProps({
  session: {
    type: Object,
    required: true,
  },
})

const formatTime = (iso) => {
  const date = new Date(iso)
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const formatCurrency = (val) => {
  return Number(val).toLocaleString('id-ID')
}
</script>
