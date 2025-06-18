<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataTable from '@/components/DataTable.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardDescription, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select,SelectItem,SelectContent,SelectTrigger,SelectValue,SelectLabel,SelectGroup } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
interface Product {
    id: number;
    name: string;
    category?: string;
    price: string;
    stock: string;
};

const props = defineProps<{
    product?: Product | null;
    isEdit: boolean;
}>();


const form = useForm({
    name: props?.product?.name || '',
    category: props?.product?.category || '',
    price: props?.product?.price || '',
    stock: props?.product?.stock || 1
});

const pageTitle = computed(() => props.isEdit ? 'Edit Product' : 'Create Product');
const submitLabel = computed(() => props.isEdit ? 'Update Product' : 'Create Product');

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/dashboard/products',
    },
    {
        title: pageTitle.value,
        href: '#',
    },
];

const submit = () => {
    if (props.isEdit && props.product) {

        console.log(props.product);
        
        form.post(`/dashboard/products/${props.product?.id}/edit`, {
            onSuccess: () => {
                // Success handled by redirect
            },
        });
    } else {
        form.post('/dashboard/users/create', {
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
                            {{ isEdit ? 'Update product information' : 'Create a new product' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div class="space-y-2">
                                <Label for="name">Product Name</Label>
                                <Input id="name" v-model="form.name" type="text" placeholder="Product Name"
                                    :class="{ 'border-red-500': form.errors.name }" required />
                                <div v-if="form.errors.name" class="text-sm text-red-600">
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="category">Category</Label>
                                <Select >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Category</SelectLabel>
                                            <SelectItem value="food">
                                                Food
                                            </SelectItem>
                                            <SelectItem value="beverege">
                                                Beverege
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                
                            </div>

                            <div class="space-y-2">
                                <Label for="price">Price</Label>
                                <Input id="price" v-model="form.price" type="number" placeholder="10000" :class="{'border-red-500' : form.errors.price}" required/>
                                <div v-if="form.errors.price" class="text-sm text-red-600">
                                    {{ form.errors.price }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="stock">Stock</Label>
                                 <Input id="stock" v-model="form.stock" type="number" placeholder="10" :class="{'border-red-500' : form.errors.stock}" required/>
                                <div v-if="form.errors.stock" class="text-sm text-red-600">
                                    {{ form.errors.stock }}
                                </div>
                            </div>

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

        </div>
    </AppLayout>
</template>