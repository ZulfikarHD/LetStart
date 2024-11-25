<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserPlus, Mail, Lock, User } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar ke SportVenue" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl">
            <!-- Logo and Welcome Section -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-appGreenLight via-appBlueMedium to-appGreenLight animate-gradient-x blur-lg opacity-75"></div>
                        <div class="relative rounded-full bg-white dark:bg-gray-800 p-4">
                            <img src="/images/logo.svg" alt="SportVenue Logo" class="h-14 w-14 transform transition-transform hover:scale-110" />
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-appGreenLight to-appBlueMedium bg-clip-text text-transparent mb-2 animate-text">
                    Daftar ke SportVenue
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Bergabunglah dengan komunitas olahraga terbesar
                </p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <!-- Name Input -->
                <div class="transform transition-all duration-300 hover:scale-[1.01]">
                    <InputLabel for="name" value="Nama Lengkap" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <User class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <TextInput
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="block w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                        />
                    </div>
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <!-- Email Input -->
                <div class="transform transition-all duration-300 hover:scale-[1.01]">
                    <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <Mail class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="block w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                            required
                            autocomplete="username"
                            placeholder="nama@email.com"
                        />
                    </div>
                    <InputError :message="form.errors.email" class="mt-1" />
                </div>

                <!-- Password Input -->
                <div class="transform transition-all duration-300 hover:scale-[1.01]">
                    <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <Lock class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            class="block w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <!-- Confirm Password Input -->
                <div class="transform transition-all duration-300 hover:scale-[1.01]">
                    <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <Lock class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            class="block w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError :message="form.errors.password_confirmation" class="mt-1" />
                </div>

                <!-- Register Button & Login Link -->
                <div class="space-y-6">
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 rounded-xl text-white font-semibold overflow-hidden bg-gradient-to-r from-appGreenLight to-appGreenMedium hover:from-appGreenMedium hover:to-appGreenDark focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 disabled:opacity-50 transform hover:scale-[1.02]"
                        :disabled="form.processing"
                    >
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <UserPlus class="h-5 w-5 transition-transform group-hover:translate-x-1" />
                        </span>
                        <span class="ml-6">{{ form.processing ? "Memproses..." : "Daftar Sekarang" }}</span>
                    </button>

                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        Sudah punya akun?
                        <Link
                            :href="route('login')"
                            class="font-medium text-appGreenLight hover:text-appGreenMedium dark:hover:text-appGreenLight transition-colors"
                        >
                            Masuk sekarang
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
