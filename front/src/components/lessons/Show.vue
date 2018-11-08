<template>
    <v-dialog
        v-model="dialog"
        fullscreen
    >
        <v-card>
            <v-toolbar card color="amber" class="elevation-2">
                <v-btn icon @click.native="close()">
                <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title> {{lesson.title}} </v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-container>
                <div v-html="lesson.text_content"></div>
                <youtube v-if="videoId" :video-id="videoId"
                    @ended="videoEnd()"></youtube>
                
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';

export default {
    data() {
        return {
            dialog: false,
            id: null,
            videoId: null,
            lesson: [],
            blockQuestionnaire: true,
        }
    },
    created() {
        eventHub.$on('show-lesson', (id, lesson) => {
            this.dialog = true;
            this.id = id;
            this.lesson = lesson;
            this.loadVideo(lesson.video);
        })
    },
    methods: {
        loadVideo(url){
            if (url != null)
                this.videoId = this.$youtube.getIdFromURL(url);
        },
        close(){
            this.dialog = false;
            this.videoId = null;
        },
        videoEnd() {
            blockQuestionnaire = false;
        }
    },

}
</script>
