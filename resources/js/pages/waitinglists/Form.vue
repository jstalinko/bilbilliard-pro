<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import { BreadcrumbItem } from '@/types';

interface Waitingwaitinglist {
    id?: number;
    name: string;
    number: number;
    status: 'waiting' | 'played' | 'cancel';
}

const props = defineProps<{
    waitinglist: Waitingwaitinglist | null;
    isEdit: boolean;
}>();

const form = useForm({
    name: props.waitinglist?.name || '',
    number: props.waitinglist?.number || 0,
    status: props.waitinglist?.status || 'waiting',
});

const pageTitle = computed(() => props.isEdit ? 'Edit Waiting waitinglist' : 'Create Waiting waitinglist');
const submitLabel = computed(() => props.isEdit ? 'Update waitinglist' : 'Create waitinglist');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Waiting List',
        href: '/dashboard/waiting-list',
    },
    {
        title: pageTitle.value,
        href: '#',
    },
];

const submit = () => {
    if (props.isEdit && props.waitinglist) {
        form.post(`/dashboard/waiting-list/${props.waitinglist.id}/edit`, {
            onSuccess: () => {
                // Success handled by redirect
            },
        });
    } else {
        form.post('/dashboard/waiting-list/create', {
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

        <div class="p-6">
            <Card>
                <CardHeader>
                    <CardTitle>{{ pageTitle }}</CardTitle>
                    <CardDescription>
                        {{ isEdit ? 'Update waiting list waitinglist' : 'Create a new waiting list waitinglist' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Enter name"
                                :class="{ 'border-red-500': form.errors.name }"
                                required
                            />
                            <div v-if="form.errors.name" class="text-sm text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Number Field -->
                        <div class="space-y-2">
                            <Label for="number">Queue Number</Label>
                            <Input
                                id="number"
                                v-model="form.number"
                                type="number"
                                placeholder="Enter queue number"
                                :class="{ 'border-red-500': form.errors.number }"
                                required
                            />
                            <div v-if="form.errors.number" class="text-sm text-red-600">
                                {{ form.errors.number }}
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="space-y-2">
                            <Label for="status">Status</Label>
                            <Select v-model="form.status">
                                <SelectTrigger id="status">
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="waiting">Waiting</SelectItem>
                                    <SelectItem value="played">Played</SelectItem>
                                    <SelectItem value="cancel">Cancel</SelectItem>
                                </SelectContent>
                            </Select>
                            <div v-if="form.errors.status" class="text-sm text-red-600">
                                {{ form.errors.status }}
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex gap-4 pt-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                                class="flex-1"
                            >
                                <span v-if="form.processing">
                                    {{ isEdit ? 'Updating...' : 'Creating...' }}
                                </span>
                                <span v-else>
                                    {{ submitLabel }}
                                </span>
                            </Button>
                            <Button 
                                type="button" 
                                variant="outline" 
                                @click="cancel"
                                :disabled="form.processing"
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
