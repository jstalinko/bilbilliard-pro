<template>
  <div class="flex p-6 gap-6 bg-background min-h-screen">
    <!-- LEFT TABLE -->
 <!-- LEFT: Table Form Produk -->
<div class="w-2/3 rounded-2xl border border-border bg-muted/50 shadow-sm p-4 overflow-auto">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-foreground">Daftar Produk</h2>
    
      <Sheet>
    <SheetTrigger as-child>
      <Button variant="secondary">
        + Tambah Produk
      </Button>
    </SheetTrigger>
    <SheetContent class="min-w-[600px]" side="left">
      <SheetHeader>
        <SheetTitle>Tambahkan Produk</SheetTitle>
        <SheetDescription>
          Pilih produk untuk di tambahkan atau cari produk ...
        </SheetDescription>
        <br>
        <Input v-model="searchProduct" placeholder="Ketik nama produk yang ingin anda cari..."/>
        <Separator class="my-4"/>
      </SheetHeader>
      <div class="grid grid-cols-4 gap-2 ">
            <div class="rounded-xl border bg-muted p-2 shadow hover:bg-slate-800 transition-colors cursor-pointer" v-for="(x,i) in [1,2,3,4,5,6,7,8,9,10]">
  <img
    src="https://ui-avatars.com/api/?size=120&name=Es+Teh"
    alt="Produk"
    class="w-full h-24 object-cover rounded-md"
  />
  <div class="mt-2 text-center text-sm font-semibold text-foreground">
    ES TEH
  </div>
</div>

      </div>
      <SheetFooter>
        <SheetClose as-child>
          <Button type="submit">
            Save changes
          </Button>
        </SheetClose>
      </SheetFooter>
    </SheetContent>
  </Sheet>

  </div>

  <table class="w-full table-auto text-sm text-foreground border-collapse">
    <thead class="bg-muted text-muted-foreground">
      <tr>
        <th class="px-3 py-2 text-left w-1/6">Kode Produk</th>
        <th class="px-3 py-2 text-left w-2/6">Nama Produk</th>
        <th class="px-3 py-2 text-left w-1/6">Qty</th>
        <th class="px-3 py-2 text-left w-2/6">Subtotal</th>
        <th class="px-3 py-2 text-left w-1/6">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(item, index) in products"
        :key="index"
        class="border-t border-border hover:bg-muted/30"
      >
        <td class="px-3 py-2">
          <Input v-model="item.kode" placeholder="Kode" class="h-8" />
        </td>
        <td class="px-3 py-2">
          <Input v-model="item.nama" placeholder="Nama Produk" class="h-8" />
        </td>
        <td class="px-3 py-2">
          <Input v-model.number="item.qty" type="number" class="h-8" />
        </td>
        <td class="px-3 py-2">
          <Input v-model.number="item.subtotal" type="number" class="h-8" />
        </td>
        <td class="px-3 py-2">
          <Button
            variant="ghost"
            size="icon"
            class="text-destructive"
            @click="removeProduct(index)"
            title="Hapus Baris"
          >
            ❌
          </Button>
        </td>
      </tr>
    </tbody>
  </table>
</div>



    <!-- RIGHT FORM -->
    <div class="w-1/3 rounded-2xl border border-border bg-muted/50 shadow-sm p-4 space-y-4">
      <h2 class="text-xl font-semibold text-foreground mb-2">Detail Transaksi</h2>

      <!-- Select Sesi -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Sesi Billiard Table</label>
        <Select v-model="selectedSession">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Pilih sesi..." />
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="sesi in sessions" :key="sesi" :value="sesi">{{ sesi }}</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <!-- Metode Pembayaran -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Metode Pembayaran</label>
        <Select v-model="paymentMethod">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Pilih metode..." />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="cash">Cash</SelectItem>
            <SelectItem value="qris">QRIS</SelectItem>
            <SelectItem value="transfer">Transfer</SelectItem>
            <SelectItem value="edc">EDC</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <!-- Tipe Transaksi -->
      <div class="flex items-center justify-between">
        <span class="text-sm text-muted-foreground">Tipe Transaksi: <b>{{ isSession ? 'Session' : 'Direct' }}</b></span>
        <Switch v-model:checked="isSession" />
      </div>

      <!-- Total Bayar -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Total Bayar</label>
        <Input v-model="totalBayar" type="number" readonly class="w-full" />
      </div>

      <!-- Jumlah Bayar -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Jumlah Bayar</label>
        <Input v-model="jumlahBayar" type="number" class="w-full" />
      </div>

      <!-- Catatan -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Catatan</label>
        <Textarea v-model="note" placeholder="Tambahkan catatan..." class="w-full" />
      </div>

      <Button class="w-full mt-2" variant="default">Simpan Transaksi</Button>
    </div>
  </div>
</template>

<script setup>
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from '@/components/ui/select'
import { Switch } from '@/components/ui/switch'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { ref } from 'vue'
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet'
import {Separator} from '@/components/ui/separator'

const searchProduct = ref('');
const products = ref([
  { kode: '', nama: '', qty: 1, subtotal: 0 }
])

const addProduct = () => {
  products.value.push({ kode: '', nama: '', qty: 1, subtotal: 0 })
}

const removeProduct = (index) => {
  products.value.splice(index, 1)
}

const sessions = ['Table 1', 'Table 2', 'Table 3']
const selectedSession = ref(null)
const paymentMethod = ref(null)
const isSession = ref(true)
const totalBayar = ref(35000)
const jumlahBayar = ref(0)
const note = ref('')
</script>
