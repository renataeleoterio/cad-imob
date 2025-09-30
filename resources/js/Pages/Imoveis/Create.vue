<template>
  <AppLayout>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>Cadastrar Imóvel</v-card-title>

        <v-form @submit.prevent="submit">
          <v-row>
            <v-col cols="12" md="4">
              <v-select 
              v-model="form.tipo"
              :items="['Terreno','Casa','Apartamento']"
              label="Tipo"
              :error-messages="form.errors.tipo"
              required
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.area_terreno"
              label="Area do Terreno (m²)"
              inputmode="decimal"
              :error-messages="form.errors.area_terreno"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.area_edificacao"
              label="Area da Edificação (m²)"
              inputmode="decimal"
              :error-messages="form.errors.area_edificacao"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.logradouro"
              label="Logradouro"
              :error-messages="form.errors.logradouro"
              required
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.numero"
              type="number"
              label="Numero"
              :error-messages="form.errors.numero"
              required
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.bairro"
              label="Bairro"
              :error-messages="form.errors.bairro"
              required
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
              v-model="form.complemento"
              label="Complemento"
              :error-messages="form.errors.complemento"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-select
              v-model="form.contribuinte_id"
              :items="pessoasSelect"
              item-value="id"
              item-title="nome"
              label="Contribuinte"
              :error-messages="form.errors.contribuinte_id"
              required
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-select
              v-model="form.situacao"
              :items="situacoes"
              label="Situação"
              :error-messages="form.errors.situacao"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-btn type="submit" :loading="form.processing" class="mr-2">
                Salvar
              </v-btn>

              <v-btn text @click="router.get(route('imoveis.index'))">
                Cancelar
              </v-btn>
            </v-col>

          </v-row>
        </v-form>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { computed } from 'vue';

const props = defineProps({
  pessoas: Array,
  tipos: Array,
  situacoes: Array,
})

const form = useForm('post', route('imoveis.store'), {
  tipo: '',
  area_terreno: '',
  area_edificacao: '',
  logradouro: '',
  numero: '',
  bairro: '',
  complemento: '',
  contribuinte_id: '',
  situacao: 'Ativo',
})

const pessoasSelect = computed(() => props.pessoas || [])


function submit() {
  form.submit({
    onSuccess: () => router.get(route('imoveis.index')),
  })
}
</script>