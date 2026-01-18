<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import OfferSearchForm from '@/components/OfferSearchForm.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Offer {
    id: number;
    user_id: number;
    type: 'buy' | 'sell';
    description: string;
    metal: 'gold' | 'silver' | 'platinum';
    weight: number;
    weight_unit: 'oz' | 'gram';
    created_at: string;
    updated_at: string;
    user: User;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedOffers {
    data: Offer[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginationLink[];
    prev_page_url: string | null;
    next_page_url: string | null;
}

interface Filters {
    metal: string;
    weight: string;
    gtlt: string;
    units: string;
    orderby: string;
    direction: string;
    keywords: string;
}

const props = defineProps<{
    offers: PaginatedOffers;
    filters: Filters;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Sell Offers',
        href: '/offers/sell',
    },
];

const metalColors: Record<string, string> = {
    gold: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    silver: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    platinum: 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300',
};

function formatDate(dateString: string): string {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function formatWeight(weight: number, unit: string): string {
    return `${weight} ${unit}`;
}
</script>

<template>
    <Head title="Sell Offers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <OfferSearchForm :filters="filters" base-url="/offers/sell" />

            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-xl">Sell Offers</CardTitle>
                    <Button as-child>
                        <Link href="/offers/sell/create">Create Offer</Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b text-xs uppercase text-muted-foreground">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">User</th>
                                    <th class="px-4 py-3">Metal</th>
                                    <th class="px-4 py-3">Weight</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="offer in offers.data"
                                    :key="offer.id"
                                    class="border-b transition-colors hover:bg-muted/50"
                                >
                                    <td class="px-4 py-3 font-medium">
                                        {{ offer.id }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ offer.user?.name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <Badge
                                            :class="metalColors[offer.metal]"
                                            variant="secondary"
                                        >
                                            {{ offer.metal }}
                                        </Badge>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ formatWeight(offer.weight, offer.weight_unit) }}
                                    </td>
                                    <td class="px-4 py-3 max-w-xs truncate">
                                        {{ offer.description }}
                                    </td>
                                    <td class="px-4 py-3 text-muted-foreground">
                                        {{ formatDate(offer.created_at) }}
                                    </td>
                                </tr>
                                <tr v-if="offers.data.length === 0">
                                    <td
                                        colspan="6"
                                        class="px-4 py-8 text-center text-muted-foreground"
                                    >
                                        No sell offers found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="offers.last_page > 1"
                        class="mt-4 flex items-center justify-between border-t pt-4"
                    >
                        <p class="text-sm text-muted-foreground">
                            Showing page {{ offers.current_page }} of {{ offers.last_page }}
                            ({{ offers.total }} total)
                        </p>
                        <div class="flex gap-1">
                            <template v-for="link in offers.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    preserve-scroll
                                >
                                    <Button
                                        :variant="link.active ? 'default' : 'outline'"
                                        size="sm"
                                    >
                                        <span v-html="link.label" />
                                    </Button>
                                </Link>
                                <Button
                                    v-else
                                    variant="outline"
                                    size="sm"
                                    disabled
                                >
                                    <span v-html="link.label" />
                                </Button>
                            </template>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
