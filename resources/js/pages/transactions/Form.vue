<template>
  <div class="flex p-6 gap-6 bg-background min-h-screen">
    <!-- LEFT TABLE -->
    <!-- LEFT: Table Form Produk -->
    <div class="w-2/3 rounded-2xl border border-border bg-muted/50 shadow-sm p-4 overflow-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-foreground">Daftar Produk</h2>

        <Sheet>
          <SheetTrigger as-child>
            <Button variant="secondary" class="cursor-pointer">
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
              <Input v-model="searchProduct" placeholder="Ketik nama produk yang ingin anda cari..." />
              <Separator class="my-4" />
            </SheetHeader>
            <div class="grid grid-cols-4 gap-2 ">
              <div class="rounded-xl border bg-muted p-2 shadow hover:bg-slate-800 transition-colors cursor-pointer"
                v-for="(product, index) in listProducts" :key="index" @click="addProduct(product)">
                <img :src="'https://ui-avatars.com/api/?size=120&name=' + product.name" alt="Produk"
                  class="w-full h-24 object-cover rounded-md" />
                <!-- nama -->
                <div class="mt-2 text-center text-sm font-semibold text-foreground">
                  {{ product.name }}
                </div>

                <!-- harga -->
                <div class="text-center text-xs text-muted-foreground">
                  {{ formatRupiah(product.price) }}
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
            <th class="px-3 py-2 text-left w-1/6">@Price</th>
            <th class="px-3 py-2 text-left w-1/6">Qty</th>
            <th class="px-3 py-2 text-left w-2/6">Subtotal</th>
            <th class="px-3 py-2 text-left w-1/6">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in products" :key="index" class="border-t border-border hover:bg-muted/30">
            <td class="px-3 py-2">
              <Input v-model="item.kode" placeholder="Kode" class="h-8" />
            </td>
            <td class="px-3 py-2">
              <Input v-model="item.nama" placeholder="Nama Produk" class="h-8" />
            </td>
            <td class="px-3 py-2">
                <span class="h-8 rounded  border-2 p-1">@{{ formatRupiah(item.price) }}</span>
            </td>
            <td class="px-3 py-2">
              <NumberField id="qty" :default-value="1" :min="1" v-model.number="item.qty" @update:model-value="changeQty(item)">
                <NumberFieldContent>
                  <NumberFieldDecrement />
                  <NumberFieldInput />
                  <NumberFieldIncrement />
                </NumberFieldContent>
              </NumberField>
            </td>
            <td class="px-3 py-2">
              <span class="h-8 rounded  border-2 p-1">{{ formatRupiah(item.subtotal) }}</span>
            </td>
            <td class="px-3 py-2">
              <Button variant="ghost" size="icon" class="text-destructive" @click="removeProduct(index)"
                title="Hapus Baris">
                ❌
              </Button>
            </td>
          </tr>
        </tbody>
        <thead class="border-t-2 p-2">
          <tr class="px-3 py-2">
            <td colspan="3"></td>
            <td>
              <b>Total Qty</b>: {{ qtyTotal }}
            </td>
            <td>
              <b>Sub-Total</b>: <span class="text-2xl">{{ formatRupiah(subTotal) }}</span>
            </td>
          </tr>
        </thead>
      </table>
    </div>



    <!-- RIGHT FORM -->
    <div class="w-1/3 rounded-2xl border border-border bg-muted/50 shadow-sm p-4 space-y-4">
      <h2 class="text-xl font-semibold text-foreground mb-2">Detail Transaksi</h2>

      <div class="space-y-1">
        <label for="member" class="text-sm text-muted-foreground">Member (opsional)</label>
        <Select v-model="member_id">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Ada member?"/>
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="(mem,idx) in members" :key="idx" :value="mem.id" >{{ mem.phone }} - {{ mem.full_name }}</SelectItem>
          </SelectContent>
        </Select>
      </div>
      <!-- Select Sesi -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Sesi Billiard Table</label>
        <Select v-model="selectedSession">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Pilih sesi..." />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="direct">NO-BILLIARD ( RESTO ONLY )</SelectItem>
            <SelectItem v-for="(sesi, index) in sessions" :key="index" :value="sesi" >( No. {{ sesi.table_number }} ) -
              {{ sesi.table_name }}</SelectItem>
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

      <!-- Total Bayar -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Total Bayar</label>
        <Input v-model="totalBayar" type="number" readonly class="w-full" />
      </div>

      <div class="space-y-2">
    <label class="text-sm text-muted-foreground">Jumlah Bayar</label>

    <!-- Tombol otomatis -->
    <div class="flex flex-wrap gap-2">
      <Button class="cursor-pointer" :variant="( jumlahBayar == totalBayar ) ? 'default': 'outline'" @click="jumlahBayar=totalBayar">{{ formatRupiah(totalBayar) }}</Button>
      <Button
        v-for="nominal in opsiPembayaran"
        :key="nominal"
        :variant="(jumlahBayar == nominal) ? 'default' : 'outline'"
        @click="jumlahBayar = nominal"
        class="cursor-pointer"
      >
        {{ formatRupiah(nominal) }}
      </Button>

      <Button variant="secondary" @click="showCustomInput = !showCustomInput">
        Masukkan Nominal Pembayaran
      </Button>
    </div>

    <!-- Input manual -->
    <div v-if="showCustomInput">
      <Input v-model.number="jumlahBayar" type="number" class="w-full mt-2" />
    </div>

    <div class="text-sm text-muted-foreground">
      Nominal Dipilih: <strong>{{ formatRupiah(jumlahBayar) }}</strong>
    </div>
  </div>

      <!-- Catatan -->
      <div class="space-y-1">
        <label class="text-sm text-muted-foreground">Catatan</label>
        <Textarea v-model="note" placeholder="Tambahkan catatan..." class="w-full" />
      </div>

      <Button class="w-full mt-2" variant="default" @click="submitTx">Buat transaksi</Button>
    </div>
     <AlertDialog v-model:open="openDialog">
    <AlertDialogTrigger as-child>
      <!-- This trigger is optional since you’re opening via JS -->
      <span></span>
    </AlertDialogTrigger>
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ dialogContent.title }}</AlertDialogTitle>
        <AlertDialogDescription>
          {{ dialogContent.desc }}
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>Close</AlertDialogCancel>
        <!-- Optionally handle OK button -->
        <AlertDialogAction @click="openDialog = false">OK</AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
  </div>
</template>

<script setup lang="ts">
import { AlertDialog,AlertDialogAction,AlertDialogCancel,AlertDialogContent,AlertDialogTrigger,AlertDialogHeader,AlertDialogFooter,AlertDialogDescription,AlertDialogTitle } from '@/components/ui/alert-dialog'
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
import { ref, watch, computed, onMounted } from 'vue'
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
import { Separator } from '@/components/ui/separator'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/components/ui/number-field'
import { formatRupiah } from '@/lib/utils'

// 🧾 Define interfaces
interface ProductItem {
  kode: string
  nama: string
  price: number
  qty: number
  subtotal: number
}

interface BilliardSession {
  session_id: number
  table_number: string
  table_name: string
  total_price: number
}

interface PropProduct {
  id: string
  name: string
  price: number
}

interface TransactionItem {
  kode: string
  qty: number
  price: number
}

interface Transaction {
  id: number
  session_id: number
}

interface Props {
  billiard_sessions: BilliardSession[]
  products: PropProduct[]
  action: string
  members: any
  tx: Transaction | null
}
interface dialogContent{
  title: string;
  desc: string;
}

// 🎯 Define props and refs
const prop = defineProps<Props>()

const searchProduct = ref<string>('')
const products = ref<ProductItem[]>([])
const subTotal = ref<number>(0)
const qtyTotal = ref<number>(0)
const sessions = ref<BilliardSession[]>(prop.billiard_sessions)
const listProducts = ref<PropProduct[]>(prop.products)
const selectedSession = ref<BilliardSession | 'direct' | null>(null)
const paymentMethod = ref<string | null>(null)
const isSession = ref<boolean>(true)
const totalBayar = ref<number>(0)
const jumlahBayar = ref<number>(0)
const note = ref<string>('')
const showCustomInput = ref<boolean>(false)
const member_id = ref<number | null>(null)

const openDialog = ref<boolean>(false)
const dialogContent = ref<dialogContent>([]);

// 💵 Pecahan uang umum
const pecahanUmum: number[] = [
  10000, 20000, 50000, 100000, 150000,
  200000, 250000, 300000, 350000, 500000
]

const opsiPembayaran = computed(() =>
  pecahanUmum.filter(n => n >= totalBayar.value)
)

// 📊 Recalculate totalBayar when session is selected
watch(selectedSession, () => {
  if (selectedSession.value !== null && selectedSession.value !== 'direct') {
    const subTotalProduct = subTotal.value
    const billiardTotal = (selectedSession.value as BilliardSession).total_price
    totalBayar.value = subTotalProduct + billiardTotal
  } else {
    totalBayar.value = subTotal.value
  }
})

// 🛒 Add product
const addProduct = (product: PropProduct) => {
  const existingProduct = products.value.find(p => p.kode === product.id)

  if (existingProduct) {
    existingProduct.qty += 1
    existingProduct.subtotal = existingProduct.qty * product.price
  } else {
    products.value.push({
      kode: product.id,
      nama: product.name,
      price: product.price,
      qty: 1,
      subtotal: product.price
    })
  }

  updateTotals()
}

// 🔁 Change quantity
const changeQty = (product: ProductItem) => {
  const existingProduct = products.value.find(p => p.kode === product.kode)
  if (existingProduct) {
    existingProduct.subtotal = existingProduct.qty * product.price
    updateTotals()
  }
}

// ❌ Remove product
const removeProduct = (index: number) => {
  products.value.splice(index, 1)
  updateTotals()
}

// 🔄 Update subtotal & totalBayar
const updateTotals = () => {
  subTotal.value = products.value.reduce((acc, p) => acc + p.subtotal, 0)
  qtyTotal.value = products.value.reduce((acc, p) => acc + p.qty, 0)

  let billiardBayar = 0
  if (selectedSession.value !== null && selectedSession.value !== 'direct') {
    billiardBayar = (selectedSession.value as BilliardSession).total_price
  }

  totalBayar.value = subTotal.value + billiardBayar
}

// 💾 Submit transaction
const submitTx = async () => {
  const transaction_items = products.value
  const billiard_sessions = selectedSession.value

  const payload = {
    transaction_items,
    billiard_sessions,
    tx_id: prop.tx?.id ?? null,
    session_id: prop.tx?.session_id ?? null,
    payment_method: paymentMethod.value,
    total_bayar: totalBayar.value,
    jumlah_bayar: jumlahBayar.value,
    member_id: member_id.value,
    note: note.value
  }

  const resp = await fetch('/api/transaction/create', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authentication': 'Bearer '
    },
    body: JSON.stringify(payload)
  })

  const result = await resp.json()
  if(result.success)
  {
    openDialog.value = true;
    dialogContent.value.title = 'Transaction Successfully~';
    dialogContent.value.desc = `Transaksi berhasil dengan pembayaran ${formatRupiah(result.data.total_amount)} dan kembalian ${formatRupiah(result.data.change)} `;
  }else{
    openDialog.value = true;
    dialogContent.value.title = 'Transaction failed';
    dialogContent.value.desc = 'Please refresh page and try again~';
  }
}

// 🔃 Prefill session if transaction exists
onMounted(() => {
  if (prop.tx != null) {
    const foundSession = sessions.value.find(sesi => sesi.session_id === prop.tx?.session_id)
    if (foundSession) {
      selectedSession.value = foundSession
    }
  }
})
</script>

