<template>
  <div class="min-h-screen flex flex-col items-center justify-center">
    <h1 class="text-2xl font-bold">Welcome to Dashboard 🎉</h1>
    <button
      @click="logout"
      class="mt-6 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const logout = () => {
  localStorage.removeItem("token");
  router.push("/login");
};
</script>
