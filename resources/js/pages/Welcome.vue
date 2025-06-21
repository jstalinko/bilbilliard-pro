<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
</script>

<template>
  <Head title="bilbilliard-pro - Billing System & Cashier" />

  <div class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] flex flex-col">
    <!-- Navbar -->
    <header class="w-full p-4 shadow-sm flex justify-between items-center lg:px-8">
      <h1 class="text-xl font-bold">bilbilliard-pro</h1>
      <div class="flex gap-4">
        <Button variant="ghost" as="a" href="/login">Login</Button>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="flex flex-col-reverse items-center justify-between px-6 py-12 lg:flex-row lg:gap-16 lg:px-20 lg:py-24">
      <div class="max-w-xl">
        <h2 class="text-3xl font-bold leading-tight mb-4 lg:text-5xl">
          Solusi Modern untuk Billing dan Kasir Tempat Biliar
        </h2>
        <p class="mb-6 text-base text-[#706f6c] dark:text-[#A1A09A]">
          bilbilliard-pro membantu Anda mengelola meja, waktu bermain, transaksi, dan stok dengan cepat dan efisien.
        </p>
        <div class="flex flex-wrap gap-3">
          <Button as="a" href="https://javaradigital.com/product/bilbilliard-pro-billiard-billing-system-point-of-sales" target="_blank">Dapatkan Sekarang</Button>
          <Button variant="outline" as="a" href="#features">Lihat Fitur</Button>
        </div>
      </div>
      <div class="w-full max-w-md lg:max-w-lg">
        <img src="/billiard.png" alt="Billiard Illustration" class="w-full h-auto rounded-full" />
      </div>
    </section>

    <!-- Features Section -->
<!-- Features Section -->
<section id="features" class="bg-[#f9f9f7] dark:bg-[#121212] py-16 px-6 lg:px-20">
  <h3 class="text-2xl font-semibold text-center mb-12">Fitur Unggulan</h3>
  <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Real-time Timer Billing</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Pantau waktu permainan secara otomatis dengan sistem timer yang akurat dan real-time.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Transaksi Cepat</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Proses pembayaran instan untuk meja, makanan, dan minuman tanpa antre lama.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Cashier Billiard & Resto</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Satu sistem kasir untuk mengelola tagihan billiard dan restoran secara bersamaan.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Sistem Membership Point</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Kumpulkan poin untuk setiap transaksi dan dapatkan keuntungan loyalitas pelanggan.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Pricing Meja Billiard Fleksibel</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Atur tarif berdasarkan jam, hari, atau event khusus dengan mudah.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Cetak Struk</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Struk otomatis untuk setiap transaksi, mendukung transparansi dan profesionalisme.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Bisa Cashier Resto Only</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Gunakan sistem kasir secara terpisah untuk restoran tanpa mengganggu operasional billiard.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Laporan Harian & Bulanan</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Rekap otomatis pendapatan dan aktivitas harian hingga bulanan.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Laporan Perbandingan Harian, Bulanan</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Bandingkan performa operasional antar periode untuk analisis bisnis yang lebih tajam.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Multi User Admin</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Kelola akses admin lebih dari satu pengguna dengan hak akses yang bisa diatur.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Unlimited Table Billiard</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Tambahkan sebanyak mungkin meja tanpa batas dalam sistem billing Anda.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Transaksi Transparan</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Setiap transaksi terekam secara jelas untuk meminimalisir kesalahan dan kecurangan.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Management Produk</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Kelola daftar makanan, minuman, dan produk lainnya dengan mudah.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Management Meja Billiard</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Atur status, nomor, dan ketersediaan meja billiard secara real-time.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Management Member</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Simpan data pelanggan, histori kunjungan, dan status keanggotaan dengan rapi.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Management Price Rate</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Sesuaikan tarif berdasarkan waktu, jenis meja, dan kategori pelanggan.</p>
    </div>

    <div class="rounded-xl border p-6 shadow-sm">
      <h4 class="font-medium text-lg mb-2">Support Online & Offline</h4>
      <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Gunakan sistem kapan pun, baik terhubung internet maupun saat offline.</p>
    </div>
    <div class="rounded-xl border p-6 shadow-sm">
  <h4 class="font-medium text-lg mb-2">Sistem Waiting List</h4>
  <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">Fitur antrean otomatis saat semua meja penuh, pelanggan dapat mendaftar dan menunggu giliran dengan notifikasi giliran main.</p>
</div>

  </div>
</section>


    <!-- CTA Section -->
    <section class="text-center py-20 px-6 bg-[#fdfbf3] dark:bg-[#1c1c1a]">
      <h3 class="text-2xl font-semibold mb-4">Siap meningkatkan efisiensi tempat biliar Anda?</h3>
      <p class="mb-6 text-[#5c5c59] dark:text-[#A1A09A]">
        Gunakan bilbilliard-pro dan nikmati sistem billing yang profesional dan mudah digunakan.
      </p>
      <Button as="a" href="/register">Mulai Sekarang</Button>
    </section>

    <!-- Footer -->
    <footer class="text-center py-6 text-sm text-[#888884] dark:text-[#A1A09A]">
      &copy; 2025 bilbilliard-pro. All rights reserved.
    </footer>
  </div>
</template>
