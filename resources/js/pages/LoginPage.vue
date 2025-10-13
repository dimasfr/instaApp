<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="email">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="email@example.com"
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 mb-2" for="password">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="********"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200"
        >
          Sign In
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-4">
        Belum punya akun?
        <router-link to="/register" class="text-indigo-600 hover:underline">Daftar</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from "vue-router";
import { useToast } from "@/utils/toast";

import api from "@/utils/api";
const { showToast } = useToast();

const router = useRouter();
const form = ref({
  email: "",
  password: "",
});
const error = ref("");

const handleLogin = async () => {
  try {
    const res = await api.post("api/login", form.value);
    localStorage.setItem("token", res.data.access_token);

    showToast("Login berhasil! Selamat datang 👋", "success");
    router.push("/dashboard");
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Login gagal!";
    showToast(message, "error");
  }
}
</script>
