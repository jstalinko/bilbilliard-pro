<template>
  <div class="p-6 space-y-6">
    <Card>
      <CardHeader>
        <CardTitle>Meja Aktif</CardTitle>
        <CardDescription>Lihat dan kelola sesi yang sedang berjalan</CardDescription>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <TableCard
            v-for="session in activeSessions"
            :key="session.id"
            :session="session"
          />
        </div>
      </CardContent>
    </Card>
  </div>
</template>

<script setup>
import TableCard from '@/components/TableCard.vue'
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from '@/components/ui/card'

const props = defineProps({
  activeSessions: {
    type: Array,
    required: true,
  },
})
</script>
