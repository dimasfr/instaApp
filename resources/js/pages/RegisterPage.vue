<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold text-center mb-6">Create an Account</h2>

      <form @submit.prevent="handleRegister">
        <!-- Name -->
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="name">Full Name</label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="John Doe"
          />
        </div>

        <!-- Email -->
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

        <!-- Password -->
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
          Register
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
          Already have an account?
          <router-link to="/login" class="text-indigo-600 hover:underline">Login here</router-link>
        </p>
      </form>

      <!-- Error message -->
      <p v-if="error" class="text-red-600 text-sm text-center mt-4">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "@/utils/api";
import { useToast } from "@/utils/toast";

const router = useRouter();
const { showToast } = useToast();

const form = ref({
  name: "",
  email: "",
  password: "",
});
const error = ref("");

const handleRegister = async () => {
  try {
    const response = await api.post("api/register", form.value);
    console.log(response.data);

    showToast("Registrasi berhasil! Silakan login 🎉", "success");
    router.push("/login");
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Registrasi gagal!";
    showToast(message, "error");
  }
};
</script>
