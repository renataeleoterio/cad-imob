<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" md="4">
        <v-card class="pa-4">
          <v-card-title class="text-h5 justify-center">Login</v-card-title>

          
          <v-alert
            v-if="status"
            type="success"
            outlined
            class="mb-4"
          >
            {{ status }}
          </v-alert>

          <v-form @submit.prevent="submit" ref="loginForm">
           
            
            <v-text-field
              v-model="form.email"
              label="E-mail"
              type="email"
              :error-messages="form.errors.email"
              required
              autofocus
            />

            <v-text-field
              v-model="form.password"
              label="Senha"
              type="password"
              :error-messages="form.errors.password"
              required
            />

            <v-checkbox
              v-model="form.remember"
              label="Lembrar-me"
            />

            <v-row justify="space-between" align="center" class="mt-4">
              <v-col cols="6">
                <v-btn
                  v-if="canResetPassword"
                  text
                  :href="route('password.request')"
                  small
                >
                  Esqueceu a senha?
                </v-btn>
              </v-col>

              <v-col cols="6" class="text-right">
                <v-btn
                  type="submit"
                  color="primary"
                  :loading="form.processing"
                  :disabled="form.processing"
                >
                  Entrar
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'; 
import { defineProps } from 'vue';

const props = defineProps({
  canResetPassword: Boolean,
  status: String,
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
