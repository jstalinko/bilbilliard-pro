import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    children?: NavItem[]; 
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

// @/types/dashboard.ts
export interface TotalStats {
  meja: number;
  members: number;
  product: number;
  transaksi: number;
}

export interface TransactionStats {
  today: number;
  todayIncrease: number;
  thisMonth: number;
  thisMonthRevenue: number;
  lastMonth: number;
  lastMonthRevenue: number;
  monthComparison: number;
}

export interface DashboardProps {
  totalStats?: TotalStats;
  transactionStats?: TransactionStats;
}

// Jika Anda ingin menambahkan ke file types utama @/types/index.ts
export interface BreadcrumbItem {
  title: string;
  href: string;
}

// Stats card interface untuk komponen reusable
export interface StatsCard {
  title: string;
  value: number | string;
  icon: string;
  description?: string;
  trend?: {
    value: number;
    isPositive: boolean;
    label: string;
  };
}