<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { BreadcrumbItem } from '@/types';

interface PricingRate {
  id?: number;
  name: string;
  price_per_hour: string;
  start_at: string;
  end_at: string | null;
  created_at?: string;
  updated_at?: string;
}

const props = defineProps<{
  pricingrate?: PricingRate | null;
  isEdit: boolean;
}>();

const form = useForm({
  name: props.pricingrate?.name || '',
  price_per_hour: props.pricingrate?.price_per_hour || '',
  start_at: props.pricingrate?.start_at || '',
  end_at: props.pricingrate?.end_at || '',
});

const pageTitle = computed(() => props.isEdit ? 'Edit Pricing Rate' : 'Create Pricing Rate');
const submitLabel = computed(() => props.isEdit ? 'Update Rate' : 'Create Rate');

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: 'Pricing Rates',
    href: '/dashboard/price-rate',
  },
  {
    title: pageTitle.value,
    href: '#',
  },
];

const submit = () => {
  if (props.isEdit && props.pricingrate) {
    form.post(`/dashboard/price-rate/${props.pricingrate.id}/edit`, {
      onSuccess: () => { /* redirect handled */ },
    });
  } else {
    form.post('/dashboard/price-rate/create', {
      onSuccess: () => form.reset(),
    });
  }
};

const cancel = () => {
  window.history.back();
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head :title="pageTitle" />

    <div class="p-6">
      <Card>
        <CardHeader>
          <CardTitle>{{ pageTitle }}</CardTitle>
          <CardDescription>
            {{ isEdit ? 'Edit existing pricing rate' : 'Define a new pricing rate' }}
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Name -->
            <div class="space-y-2">
              <Label for="name">Name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="e.g. Morning Rate"
                :class="{ 'border-red-500': form.errors.name }"
                required
              />
              <div v-if="form.errors.name" class="text-sm text-red-600">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Price -->
            <div class="space-y-2">
              <Label for="price">Price per Hour</Label>
              <Input
                id="price"
                v-model="form.price_per_hour"
                type="number"
                step="0.01"
                placeholder="e.g. 15000"
                :class="{ 'border-red-500': form.errors.price_per_hour }"
                required
              />
              <div v-if="form.errors.price_per_hour" class="text-sm text-red-600">
                {{ form.errors.price_per_hour }}
              </div>
            </div>

            <!-- Start At -->
            <div class="space-y-2">
              <Label for="start_at">Start Time</Label>
              <Input
                id="start_at"
                v-model="form.start_at"
                type="time"
                step="60"
                required
              />
            </div>

            <!-- End At -->
            <div class="space-y-2">
              <Label for="end_at">End Time</Label>
              <Input
                id="end_at"
                v-model="form.end_at"
                type="time"
                step="60"
              />
              <p class="text-xs text-gray-500">Leave blank if it has no end time</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-4">
              <Button type="submit" :disabled="form.processing" class="flex-1">
                <span v-if="form.processing">
                  {{ isEdit ? 'Updating...' : 'Creating...' }}
                </span>
                <span v-else>
                  {{ submitLabel }}
                </span>
              </Button>
              <Button type="button" variant="outline" @click="cancel" :disabled="form.processing">
                Cancel
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
