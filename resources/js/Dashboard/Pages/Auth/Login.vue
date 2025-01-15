<template>
    <div class="flex h-screen w-full">
        <!-- Left side with the centered image and background color -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 p-10 max-md:hidden">
            <img src="/images/login-bg.png" alt="character-illustration" class="dashboard-login-bg" />
        </div>

        <!-- Right side with the login form, with a white background -->
        <div class="w-full max-w-lg flex flex-col justify-center items-start p-8 md:p-12 bg-white">
            <div class="w-full space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Welcome to Dashboard! 👋</h2>
                <p class="text-gray-500">Please sign in to your account to manage website content</p>

                <form @submit.prevent="submit" class="flex flex-col gap-6 w-full">
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700">Login</label>
                        <input
                            v-model="form.login"
                            type="text"
                            id="login"
                            class="w-full mt-1 p-2 border rounded-lg text-gray-900 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your username"
                        />
                        <p v-if="form.errors.login" class="text-red-500 text-sm mt-2 pl-2">{{ form.errors.login }}</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            id="password"
                            class="w-full mt-1 p-2 border rounded-lg text-gray-900 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="············"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-sm mt-2 pl-2">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between w-full">
                        <label class="flex items-center space-x-2">
                            <input v-model="form.remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
                            <span class="text-sm text-gray-700">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg transition hover:bg-blue-700 focus:outline-none"
                    >
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('dashboard.login.submit'));
};
</script>

<style scoped>
body, html {
    overflow: hidden;
}

.dashboard-login-bg {
    z-index: 2;
    max-height: 680px;
    width: auto;
    margin: 3rem;
}

@media (max-width: 1535.95px) {
    .dashboard-login-bg {
        max-height: 550px;
    }
}

@media (max-width: 1199.95px) {
    .dashboard-login-bg {
        max-height: 450px;
    }
}
</style>
