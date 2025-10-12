<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50">
    <Card class="w-full max-w-sm p-6 shadow-lg">
      <h1 class="text-2xl font-semibold mb-6 text-center">Login</h1>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <Label for="email">Email</Label>
          <Input
            id="email"
            type="email"
            v-model="email"
            placeholder="you@example.com"
            class="mt-1"
          />
        </div>

        <div>
          <Label for="password">Password</Label>
          <Input
            id="password"
            type="password"
            v-model="password"
            placeholder="********"
            class="mt-1"
          />
        </div>

        <Button type="submit" class="w-full mt-4">Sign In</Button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-4">
        Belum punya akun? <a href="#" class="text-blue-600 hover:underline">Daftar</a>
      </p>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Card } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const email = ref('')
const password = ref('')

const handleLogin = () => {
  console.log('Email:', email.value)
  console.log('Password:', password.value)
  alert(`Login attempt by ${email.value}`)
}
</script>
