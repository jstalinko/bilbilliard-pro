<!-- DataTable.vue -->
<script setup lang="ts" generic="T extends Record<string, any>">
import { ref, computed ,watch} from 'vue';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { 
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Search, Filter, Plus, MoreHorizontal } from 'lucide-vue-next';

export interface TableColumn<T = any> {
  key: string;
  label: string;
  sortable?: boolean;
  searchable?: boolean;
  width?: string;
  align?: 'left' | 'center' | 'right';
  render?: (value: any, row: T) => any;
}

export interface TableAction<T = any> {
  label: string;
  onClick: (row: T) => void;
  variant?: 'default' | 'destructive';
  show?: (row: T) => boolean;
}

export interface FilterOption {
  label: string;
  value: string;
}

interface Props {
  data: T[];
  columns: TableColumn<T>[];
  actions?: TableAction<T>[];
  title?: string;
  description?: string;
  searchable?: boolean;
  searchPlaceholder?: string;
  filterable?: boolean;
  filterOptions?: FilterOption[];
  filterLabel?: string;
  addButton?: boolean;
  addButtonLabel?: string;
  caption?: string;
  loading?: boolean;
  emptyMessage?: string;
}

const props = withDefaults(defineProps<Props>(), {
  searchable: true,
  searchPlaceholder: 'Search...',
  filterable: false,
  filterLabel: 'Filter',
  addButton: false,
  addButtonLabel: 'Add New',
  caption: '',
  loading: false,
  emptyMessage: 'No data found.',
});

const emit = defineEmits<{
  add: [];
  search: [query: string];
  filter: [value: string];
}>();

const searchQuery = ref('');
const filterValue = ref('all');

// Computed filtered data
const filteredData = computed(() => {
  let filtered = [...props.data];

  // Apply search filter
  if (props.searchable && searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(item => {
      return props.columns.some(column => {
        if (column.searchable !== false) {
          const value = getNestedValue(item, column.key);
          return String(value).toLowerCase().includes(query);
        }
        return false;
      });
    });
  }

  // Apply filter
  if (props.filterable && filterValue.value !== 'all') {
    // This is a basic implementation, you might want to customize based on your needs
    filtered = filtered.filter(item => {
      // Assuming the first filterable column is used for filtering
      const filterColumn = props.columns.find(col => col.key === 'status') || props.columns[0];
      const value = getNestedValue(item, filterColumn.key);
      return String(value).toLowerCase() === filterValue.value.toLowerCase();
    });
  }

  return filtered;
});

// Helper function to get nested object values
const getNestedValue = (obj: any, path: string) => {
  return path.split('.').reduce((current, key) => current?.[key], obj);
};

// Helper function to get cell alignment class
const getCellAlignClass = (align?: string) => {
  switch (align) {
    case 'center':
      return 'text-center';
    case 'right':
      return 'text-right';
    default:
      return 'text-left';
  }
};

// Emit events
const handleAdd = () => {
  emit('add');
};

const handleSearch = (value: string) => {
  emit('search', value);
};

const handleFilter = (value: string) => {
  emit('filter', value);
};

// Watch for search changes
watch(searchQuery, (newValue) => {
  handleSearch(newValue);
});

// Watch for filter changes
watch(filterValue, (newValue) => {
  handleFilter(newValue);
});
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center" v-if="title || addButton">
      <div v-if="title">
        <h1 class="text-3xl font-bold tracking-tight">{{ title }}</h1>
        <p class="text-muted-foreground" v-if="description">
          {{ description }}
        </p>
      </div>
      <Button v-if="addButton" @click="handleAdd" class="flex items-center gap-2">
        <Plus class="h-4 w-4" />
        {{ addButtonLabel }}
      </Button>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-4" v-if="searchable || filterable">
      <!-- Search -->
      <div class="relative flex-1 max-w-sm" v-if="searchable">
        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground h-4 w-4" />
        <Input
          v-model="searchQuery"
          :placeholder="searchPlaceholder"
          class="pl-10"
        />
      </div>
      
      <!-- Filter -->
      <DropdownMenu v-if="filterable && filterOptions">
        <DropdownMenuTrigger as-child>
          <Button variant="outline" class="flex items-center gap-2">
            <Filter class="h-4 w-4" />
            {{ filterLabel }}: {{ filterValue === 'all' ? 'All' : filterOptions.find(opt => opt.value === filterValue)?.label }}
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuItem @click="filterValue = 'all'">
            All {{ filterLabel }}
          </DropdownMenuItem>
          <DropdownMenuItem 
            v-for="option in filterOptions" 
            :key="option.value"
            @click="filterValue = option.value"
          >
            {{ option.label }}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>

    <!-- Data Table -->
    <div class="rounded-md border">
      <Table>
        <TableCaption v-if="caption">
          {{ caption }}
        </TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead 
              v-for="column in columns" 
              :key="column.key"
              :class="[getCellAlignClass(column.align)]"
              :style="{ width: column.width }"
            >
              {{ column.label }}
            </TableHead>
            <TableHead v-if="actions && actions.length > 0" class="text-right">
              Actions
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <!-- Loading state -->
          <TableRow v-if="loading">
            <TableCell :colspan="columns.length + (actions?.length ? 1 : 0)" class="h-24 text-center">
              Loading...
            </TableCell>
          </TableRow>
          
          <!-- Data rows -->
          <TableRow v-else-if="filteredData.length > 0" v-for="(row, index) in filteredData" :key="index">
            <TableCell 
              v-for="column in columns" 
              :key="column.key"
              :class="[
                getCellAlignClass(column.align),
                index === 0 && column.key === columns[0].key ? 'font-medium' : ''
              ]"
            >
              <slot 
                :name="`cell-${column.key}`" 
                :value="getNestedValue(row, column.key)" 
                :row="row"
                :column="column"
              >
                <span v-if="column.render" v-html="column.render(getNestedValue(row, column.key), row)"></span>
                <span v-else>{{ getNestedValue(row, column.key) }}</span>
              </slot>
            </TableCell>
            
            <!-- Actions column -->
          <TableCell v-if="actions && actions.length > 0" class="text-right space-x-2">
  <slot name="actions" :row="row">
    <template v-for="action in actions" :key="action.label">
      <Button
        size="sm"
        :variant="action.variant === 'destructive' ? 'destructive' : 'default'"
        @click="action.onClick(row)"
        v-show="!action.show || action.show(row)"
      >
        {{ action.label }}
      </Button>
    </template>
  </slot>
</TableCell>

          </TableRow>
          
          <!-- Empty state -->
          <TableRow v-else>
            <TableCell :colspan="columns.length + (actions?.length ? 1 : 0)" class="h-24 text-center">
              <slot name="empty">
                {{ emptyMessage }}
              </slot>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
</template>