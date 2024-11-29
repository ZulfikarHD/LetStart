<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import LogoNoText from '@/Components/LogoNoText.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserPlus, Mail, Lock, User, Loader2Icon, Users, MapPin, Eye, EyeOff, AlertCircle, Check, Facebook as FacebookIcon, Mail as GmailIcon } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';

// Define debouncedValidation helper function first
const debouncedValidation = (fn, delay = 300) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);
const isEmailValid = ref(true);
const formStep = ref(1);
const totalSteps = 2;
const showSuccessMessage = ref(false);

// Email validation
const validateEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
};

// Debounced email validation
const debouncedEmailValidation = debouncedValidation((email) => {
    isEmailValid.value = validateEmail(email);
}, 500);

// Watch email changes
watch(() => form.email, (newValue) => {
    debouncedEmailValidation(newValue);
});

// Password requirements
const passwordRequirements = computed(() => [
    { text: 'Minimal 8 karakter', met: form.password.length >= 8 },
    { text: 'Mengandung huruf besar', met: /[A-Z]/.test(form.password) },
    { text: 'Mengandung huruf kecil', met: /[a-z]/.test(form.password) },
    { text: 'Mengandung angka', met: /\d/.test(form.password) },
    { text: 'Mengandung karakter spesial', met: /[!@#$%^&*]/.test(form.password) },
]);

const passwordStrength = computed(() => {
    if (!form.password) return '';
    const metRequirements = passwordRequirements.value.filter(req => req.met).length;
    return metRequirements <= 2 ? 'weak' : metRequirements <= 4 ? 'medium' : 'strong';
});

const isFormValid = computed(() => {
    return form.name &&
           form.email &&
           isEmailValid.value &&
           form.password &&
           form.password_confirmation &&
           form.password === form.password_confirmation &&
           form.terms;
});

const nextStep = () => {
    if (formStep.value < totalSteps) {
        formStep.value++;
    }
};

const prevStep = () => {
    if (formStep.value > 1) {
        formStep.value--;
    }
};

const submit = () => {
    if (!isFormValid.value) return;

    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            // Show success message
            showSuccessMessage.value = true;
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 3000);
        },
    });
};
</script>

<template>
    <Head title="Daftar ke SportVenue" />

    <Transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="translate-y-[-100%] opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transform transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-[-100%] opacity-0"
    >
        <div
            v-if="showSuccessMessage"
            class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 rounded-lg shadow-lg bg-green-100 dark:bg-green-900"
            role="alert"
        >
            <Check class="w-5 h-5 mr-2 text-green-500 dark:text-green-300" />
            <span class="text-green-800 dark:text-green-200">Pendaftaran berhasil! Silahkan cek email Anda.</span>
        </div>
    </Transition>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl">
            <!-- Logo and Welcome Section -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-appGreenLight via-appBlueMedium to-appGreenLight animate-gradient-x blur-lg opacity-75"></div>
                        <div class="relative rounded-full bg-white dark:bg-gray-800 p-4">
                            <LogoNoText />
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-appGreenLight to-appBlueMedium bg-clip-text text-transparent mb-2 animate-text">
                    Daftar ke MaiNow
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Bergabunglah dengan komunitas olahraga terbesar
                </p>
            </div>

            <!-- Main Email Registration Form -->
            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <!-- Progress Steps -->
                <div class="relative pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center w-full">
                            <!-- Step 1 -->
                            <div class="relative flex items-center justify-center">
                                <div :class="{
                                    'bg-appGreenLight': formStep >= 1,
                                    'bg-gray-300 dark:bg-gray-600': formStep < 1
                                }" class="rounded-full h-8 w-8 flex items-center justify-center text-white transition-colors duration-300">
                                    1
                                </div>
                            </div>

                            <!-- Connecting Line -->
                            <div class="flex-1 h-1 mx-4"
                                 :class="formStep > 1 ? 'bg-appGreenLight' : 'bg-gray-300 dark:bg-gray-600'">
                            </div>

                            <!-- Step 2 -->
                            <div class="relative flex items-center justify-center">
                                <div :class="{
                                    'bg-appGreenLight': formStep >= 2,
                                    'bg-gray-300 dark:bg-gray-600': formStep < 2
                                }" class="rounded-full h-8 w-8 flex items-center justify-center text-white transition-colors duration-300">
                                    2
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step Labels -->
                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span class="absolute left-0">Informasi Dasar</span>
                        <span class="absolute right-0">Keamanan</span>
                    </div>
                </div>

                <!-- Step 1: Basic Information -->
                <div v-show="formStep === 1" class="space-y-6">
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
                            <Check v-if="form.name.length > 2"
                                  class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-green-500" />
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
                                @input="debouncedEmailValidation(form.email)"
                                class="block w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                                :class="{ 'border-red-500': !isEmailValid && form.email }"
                                required
                                autocomplete="username"
                                placeholder="nama@email.com"
                            />
                            <Check v-if="isEmailValid && form.email"
                                  class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-green-500" />
                            <AlertCircle v-if="!isEmailValid && form.email"
                                       class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-red-500" />
                        </div>
                        <InputError :message="form.errors.email" class="mt-1" />
                        <p v-if="!isEmailValid && form.email" class="text-sm text-red-500 mt-1">
                            Format email tidak valid
                        </p>
                    </div>
                </div>

                <!-- Step 2: Password -->
                <div v-show="formStep === 2" class="space-y-6">
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
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <Eye v-if="!showPassword" class="h-5 w-5" />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <InputError :message="form.errors.password" class="mt-1" />

                        <!-- Password Requirements -->
                        <div class="mt-2 space-y-2">
                            <div v-for="(req, index) in passwordRequirements"
                                 :key="index"
                                 class="flex items-center text-sm"
                                 :class="req.met ? 'text-green-500' : 'text-gray-500'"
                            >
                                <Check v-if="req.met" class="h-4 w-4 mr-2" />
                                <AlertCircle v-else class="h-4 w-4 mr-2" />
                                {{ req.text }}
                            </div>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="transform transition-all duration-300 hover:scale-[1.01]">
                        <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-gray-700 dark:text-gray-300 mb-2" />
                        <div class="relative">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <TextInput
                                id="password_confirmation"
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                v-model="form.password_confirmation"
                                class="block w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-appGreenLight focus:border-transparent transition duration-200"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <Eye v-if="!showPasswordConfirm" class="h-5 w-5" />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <InputError :message="form.errors.password_confirmation" class="mt-1" />
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input
                            id="terms"
                            type="checkbox"
                            v-model="form.terms"
                            class="h-4 w-4 text-appGreenLight focus:ring-appGreenLight border-gray-300 rounded"
                        />
                        <label for="terms" class="ml-2 block text-sm text-gray-600 dark:text-gray-400">
                            Saya setuju dengan <a href="#" class="text-appGreenLight hover:text-appGreenMedium">Syarat dan Ketentuan</a>
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between space-x-4">
                    <button
                        type="button"
                        v-if="formStep > 1"
                        @click="prevStep"
                        class="flex-1 py-3 px-4 rounded-xl border border-gray-300 hover:border-gray-400 transition-colors duration-300"
                    >
                        Kembali
                    </button>

                    <button
                        v-if="formStep < totalSteps"
                        type="button"
                        @click="nextStep"
                        class="flex-1 py-3 px-4 rounded-xl bg-appGreenLight hover:bg-appGreenMedium text-white transition-colors duration-300"
                        :disabled="formStep === 1 && (!form.name || !form.email || !isEmailValid)"
                    >
                        Lanjut
                    </button>

                    <button
                        v-if="formStep === totalSteps"
                        type="submit"
                        class="flex-1 py-3 px-4 rounded-xl bg-appGreenLight hover:bg-appGreenMedium text-white transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95"
                        :class="{ 'animate-success': showSuccessMessage }"
                        :disabled="!isFormValid || form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <Loader2Icon class="animate-spin h-5 w-5 mr-2" />
                            Memproses...
                        </span>
                        <span v-else class="flex items-center justify-center">
                            <span v-if="showSuccessMessage">
                                <Check class="h-5 w-5 mr-2" />
                                Berhasil!
                            </span>
                            <span v-else>
                                Daftar Sekarang
                            </span>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">Atau daftar dengan</span>
                </div>
            </div>

            <!-- Social Login Options -->
            <div class="space-y-4">
                <button class="w-full flex items-center justify-center space-x-2 py-3 px-4 rounded-xl border border-gray-300 hover:border-gray-400 transition-colors duration-300">
                    <GmailIcon class="w-5 h-5 text-red-500" />
                    <span>Daftar dengan Google</span>
                </button>
                <button class="w-full flex items-center justify-center space-x-2 py-3 px-4 rounded-xl border border-gray-300 hover:border-gray-400 transition-colors duration-300">
                    <FacebookIcon class="w-5 h-5 text-blue-600" />
                    <span>Daftar dengan Facebook</span>
                </button>
            </div>

            <!-- Login Link -->
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
    </div>
</template>
