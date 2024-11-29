<script setup>
import { ref } from 'vue';
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Logo from "@/Components/LogoNoText.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    LogIn,
    Mail,
    Lock,
    Facebook,
    Twitter,
    Eye as EyeIcon,
    EyeOff as EyeOffIcon,
    Loader2 as Loader2Icon
} from "lucide-vue-next";

// Definisikan showPassword ref
const showPassword = ref(false);

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Masuk ke SportVenue" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl">
            <!-- Logo and Welcome Section -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-appGreenLight via-appBlueMedium to-appGreenLight animate-gradient-x blur-lg opacity-75"></div>
                        <div class="relative rounded-full bg-white dark:bg-gray-800 p-4">
                            <Logo />
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-appGreenLight to-appBlueMedium bg-clip-text text-transparent mb-2 animate-text">
                    Selamat Datang di MaiNow
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Booking lapangan olahraga jadi lebih mudah
                </p>
            </div>

            <!-- Status Message -->
            <div v-if="status"
                class="p-4 rounded-xl bg-gradient-to-r from-appGreenLight/10 to-appBlueMedium/10 border border-appGreenLight/20 animate-fade-in">
                <p class="text-sm font-medium text-appGreenDark dark:text-appGreenLight">
                    {{ status }}
                </p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
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
                            autofocus
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
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            class="block w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 focus:outline-none focus:text-gray-600 dark:focus:text-gray-300"
                        >
                            <EyeIcon v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" />
                            <EyeOffIcon v-else class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" />
                        </button>
                    </div>
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                            class="rounded border-gray-300 text-appGreenLight focus:ring-appGreenLight"
                        />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-appGreenLight hover:text-appGreenMedium dark:hover:text-appGreenLight transition-colors"
                    >
                        Lupa password?
                    </Link>
                </div>

                <!-- Login Button & Social Login -->
                <div class="space-y-6">
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 rounded-xl text-white font-semibold overflow-hidden bg-gradient-to-r from-appGreenLight to-appGreenMedium hover:from-appGreenMedium hover:to-appGreenDark focus:outline-none focus:ring-2 focus:ring-appGreenLight focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 disabled:opacity-50 transform hover:scale-[1.02]"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="absolute left-4">
                            <Loader2Icon class="h-5 w-5 animate-spin" />
                        </span>
                        <span class="ml-6">{{ form.processing ? "Memproses..." : "Masuk Sekarang" }}</span>
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                Atau masuk dengan
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button
                            type="button"
                            class="flex items-center justify-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-md"
                        >
                            <Facebook class="h-5 w-5 text-blue-600" />
                            Facebook
                        </button>
                        <button
                            type="button"
                            class="flex items-center justify-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-md"
                        >
                            <Twitter class="h-5 w-5 text-blue-400" />
                            Twitter
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        Belum punya akun?
                        <Link
                            :href="route('register')"
                            class="font-medium text-appGreenLight hover:text-appGreenMedium dark:hover:text-appGreenLight transition-colors"
                        >
                            Daftar sekarang
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
