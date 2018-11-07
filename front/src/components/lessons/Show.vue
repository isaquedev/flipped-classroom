<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        scrollable
    >
        <v-card>
            <v-toolbar card color="amber" class="elevation-2">
                <v-btn icon @click.native="close()">
                <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title> {{lesson.title}} </v-toolbar-title>
                <v-spacer></v-spacer>
                <!--create-student/-->
            </v-toolbar>
            <v-container>
                <div v-html="lesson.text_content"></div>
                <iframe class="iframe-container" width="560" height="315" :src="videoId" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script>
import { eventHub } from '../../eventHub';
import { PlyrVideo } from 'vue-plyr'
import 'vue-plyr/dist/vue-plyr.css'

function onYouTubeIframeAPIReady (){
    console.log('ready');
}

export default {
    data() {
        return {
            dialog: false,
            id: null,
            videoId: null,
            lesson: [],
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
            if(url != null)
                this.videoId = "https://www.youtube-nocookie.com/embed/" + url.split('=')[1];
            else
                this.videoId = null;
            console.log(this.videoId);
        },
        close(){
            this.dialog = false;
            this.videoId = null;
        }
    },

}
</script>

<style>
 .iframe-container{
    position: relative;
    margin: 0;
    width: 80%;
	padding-top: 5px;
	height: 90%;
 }
</style>
