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
        <Button variant="outline" as="a" href="/register">Register</Button>
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
          <Button as="a" href="/register">Coba Gratis</Button>
          <Button variant="outline" as="a" href="#features">Lihat Fitur</Button>
        </div>
      </div>
      <div class="w-full max-w-md lg:max-w-lg">
        <img src="/billiard.png" alt="Billiard Illustration" class="w-full h-auto rounded-full" />
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-[#f9f9f7] dark:bg-[#121212] py-16 px-6 lg:px-20">
      <h3 class="text-2xl font-semibold text-center mb-12">Fitur Unggulan</h3>
      <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border p-6 shadow-sm">
          <h4 class="font-medium text-lg mb-2">Real-time Timer Meja</h4>
          <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">
            Lacak durasi permainan otomatis dan akurat untuk setiap meja.
          </p>
        </div>
        <div class="rounded-xl border p-6 shadow-sm">
          <h4 class="font-medium text-lg mb-2">Transaksi Kasir Cepat</h4>
          <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">
            Proses pembayaran mudah untuk sewa meja, makanan & minuman.
          </p>
        </div>
        <div class="rounded-xl border p-6 shadow-sm">
          <h4 class="font-medium text-lg mb-2">Laporan Harian & Bulanan</h4>
          <p class="text-sm text-[#5c5c59] dark:text-[#A1A09A]">
            Dapatkan ringkasan transaksi, profit, dan aktivitas operasional secara instan.
          </p>
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
