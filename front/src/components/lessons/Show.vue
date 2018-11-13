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
                <div class="mb-3" v-html="lesson.text_content"></div>
                <v-flex class="text-xs-center">
                    <youtube v-if="videoId" :video-id="videoId"
                    @ended="videoEnd()"></youtube>
                </v-flex>
            </v-container>
        </v-card>
        <questionnaire-show/>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';
import questionnaireShow from '../questionnaires/Show'

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
    components:{
        'questionnaire-show': questionnaireShow,
    },
    created() {
        eventHub.$on('show-lesson', (id, lesson) => {
            this.dialog = true;
            this.id = id;
            this.lesson = lesson;
            this.loadVideo(lesson.video);
        })
    },
    computed: {
            user() {
                return this.$store.state.auth.user;
            },
        },
    methods: {
        loadVideo(url){
            if (url != null){
                this.blockQuestionnaire = true;
                this.videoId = this.$youtube.getIdFromURL(url);
            }
            else
                this.blockQuestionnaire = false;
        },
        close(){
            this.dialog = false;
            this.videoId = null;
        },
        videoEnd() {
            this.blockQuestionnaire = false;
        },
    },

}
</script>
