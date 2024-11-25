<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LogIn, Mail, Lock, Facebook, Twitter } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk ke SportVenue" />

        <div class="mx-auto max-w-md">
            <!-- Logo and Welcome Section -->
            <div class="mb-10 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="rounded-full bg-appGreenLight/10 p-4">
                        <img src="/images/logo.svg" alt="SportVenue Logo" class="h-12 w-12" />
                    </div>
                </div>
                <h2 class="mb-2 bg-gradient-to-r from-appGreenLight to-appBlueMedium bg-clip-text text-3xl font-extrabold text-transparent">
                    Selamat Datang di SportVenue
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Booking lapangan olahraga jadi lebih mudah</p>
            </div>

            <!-- Status Message -->
            <div v-if="status"
                class="mb-6 rounded-xl bg-gradient-to-r from-appGreenLight/10 to-appBlueMedium/10 p-4 text-sm font-medium text-appGreenLight">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email Input -->
                <div class="space-y-2">
                    <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300" />
                    <div class="relative">
                        <Mail class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-12 placeholder:text-gray-400 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300" />
                    <div class="relative">
                        <Lock class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                        <TextInput
                            id="password"
                            type="password"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 pl-12 placeholder:text-gray-400 focus:border-appGreenLight focus:ring-appGreenLight dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember"
                            class="rounded border-gray-300 text-appGreenLight focus:ring-appGreenLight" />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-appGreenLight transition-colors hover:text-appGreenMedium dark:hover:text-appGreenLight"
                    >
                        Lupa password?
                    </Link>
                </div>

                <!-- Login Button & Social Login -->
                <div class="space-y-4">
                    <button
                        type="submit"
                        class="group relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-gradient-to-r from-appGreenLight to-appGreenMedium px-4 py-3 font-semibold text-white transition-all hover:from-appGreenMedium hover:to-appGreenDark focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 disabled:opacity-50 dark:focus:ring-offset-gray-800"
                        :disabled="form.processing"
                    >
                        <div class="absolute inset-0 flex items-center justify-center bg-black/5 opacity-0 transition-opacity group-hover:opacity-100">
                            <LogIn class="h-5 w-5" />
                        </div>
                        <span class="transition-transform group-hover:translate-x-2">
                            {{ form.processing ? 'Memproses...' : 'Masuk Sekarang' }}
                        </span>
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-2 text-gray-500 dark:bg-gray-900 dark:text-gray-400">Atau masuk dengan</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button"
                            class="flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <Facebook class="h-5 w-5 text-blue-600" />
                            Facebook
                        </button>
                        <button type="button"
                            class="flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <Twitter class="h-5 w-5 text-blue-400" />
                            Twitter
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        Belum punya akun?
                        <Link
                            :href="route('register')"
                            class="font-medium text-appGreenLight transition-colors hover:text-appGreenMedium dark:hover:text-appGreenLight"
                        >
                            Daftar sekarang
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
