<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardDescription, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue, SelectLabel, SelectGroup } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
interface Billiard {
    id: number;
    name: string;
    number: string;
    status: string;
};

const props = defineProps<{
    billiard: Billiard | null;
    isEdit: boolean;
}>();


const form = useForm({
    name: props?.billiard?.name || '',
    number: props?.billiard?.number || '',
    status: props?.billiard?.status || 0,
});

const pageTitle = computed(() => props.isEdit ? 'Edit Billiard' : 'Create Billiard');
const submitLabel = computed(() => props.isEdit ? 'Update Billiard' : 'Create Billiard');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Billiard Tables',
        href: '/dashboard/billiards',
    },
    {
        title: pageTitle.value,
        href: '#',
    },
];

const submit = () => {
    if (props.isEdit && props.billiard) {


        form.post(`/dashboard/billiards/${props.billiard?.id}/edit`, {
            onSuccess: () => {
                // Success handled by redirect
            },
        });
    } else {
        form.post('/dashboard/billiards/create', {
            onSuccess: () => {
                form.reset();
            },
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
        <div class="space-y-6 p-4">

            <div class=" p-6">
                <Card>
                    <CardHeader>
                        <CardTitle>{{ pageTitle }}</CardTitle>
                        <CardDescription>
                            {{ isEdit ? 'Update Billiards information' : 'Create a new Billiards' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                          <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="form.name" type="text" placeholder="Member Name"
                                    :class="{ 'border-red-500': form.errors.name }" required />
                                <div v-if="form.errors.name" class="text-sm text-red-600">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                             <div class="space-y-2">
                                <Label for="name">Number</Label>
                                <Input id="name" v-model="form.number" type="text" placeholder="Member Name"
                                    :class="{ 'border-red-500': form.errors.number }" required />
                                <div v-if="form.errors.number" class="text-sm text-red-600">
                                    {{ form.errors.number }}
                                </div>
                            </div>
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
        </div>
    </AppLayout>
</template>