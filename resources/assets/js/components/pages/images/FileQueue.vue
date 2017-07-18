<template>
  <v-container fluid grid-list-md class="file-queue" v-if="files.length > 0">
    <transition-group name="scale-transition" tag="v-layout" class="row wrap" appear>
      <v-flex xs3 lg2 xl1 v-for="(file, index) in files" :key="index">
        <!-- Card -->
        <v-card flat tile>
          <!-- Card Media -->
          <v-card-media
            class="image"
            :src="file.src"
            alt="upload-preview"
            height="200px"
          >
            <!-- Image / Upload Progress -->
            <v-container fluid fill-height class="image-name">
              <v-layout fill-height>
                <v-flex xs12 flexbox justify-center align-center fill-height>
                  <transition name="slide-y-reverse-transition" mode="out-in" appear>
                    <v-icon
                      v-if="file.complete"
                      class="file-queue__upload white--text"
                    >cloud_done</v-icon>
                    <v-progress-circular
                      v-else
                      :size="100"
                      :width="15"
                      :value="file.progress"
                      class="file-queue__progress blue--text text--lighten-2"
                    >
                      <span>{{ file.progress }}%</span>
                    </v-progress-circular>
                  </transition>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-media>
        </v-card>
      </v-flex>
    </transition-group>
  </v-container>
</template>

<script>
export default {
  // Name
  name: 'file-queue',

  // Props
  props: {
    files: Array
  }
}
</script>

<style scoped lang="scss">
.file-queue {
  &__upload {
    width: 75px;
    height: 75px;
    font-size: 32px;
    background-color: rgba(0, 0, 0, .54);
    border-radius: 50%;
  }
}
</style>
