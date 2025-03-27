<template>
    <div class="container">
        <h2>YouTube to MP3 Converter 🎵</h2>

        <input
            v-model="youtubeUrl"
            type="text"
            placeholder="Paste YouTube URL here"
            class="input"
        />

        <button @click="convert" :disabled="loading || !youtubeUrl" class="btn">
            {{ loading ? "Converting..." : "Convert" }}
        </button>

        <div v-if="loading || progress > 0" class="progress-container">
            <div class="progress-bar" :style="{ width: progress + '%' }"></div>
            <p>{{ progress }}%</p>
        </div>

        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>

        <div v-if="preview.title" class="preview">
            <h3>{{ preview.title }}</h3>
            <iframe
                :src="preview.embed"
                width="360"
                height="215"
                frameborder="0"
                allowfullscreen
            ></iframe>
        </div>

        <div v-if="audioInfo" class="audio-info">
            <h4>🎧 Info Audio:</h4>
            <ul>
                <li><strong>Format:</strong> {{ audioInfo.format }}</li>
                <li><strong>Ukuran:</strong> {{ audioInfo.size_mb }} MB</li>
                <li><strong>Bitrate:</strong> {{ audioInfo.bitrate }}</li>
                <li><strong>Durasi:</strong> {{ audioInfo.duration }}</li>
                <li><strong>Nama File:</strong> {{ audioInfo.filename }}</li>
            </ul>
        </div>

        <a v-if="downloadUrl" :href="downloadUrl" download class="download-link"
            >⬇️ Download MP3</a
        >
    </div>
</template>

<script>
export default {
    data() {
        return {
            youtubeUrl: "",
            loading: false,
            error: "",
            success: "",
            downloadUrl: "",
            progress: 0,
            preview: {
                title: "",
                embed: "",
            },
            interval: null, // untuk simulasi progress
            audioInfo: null,
        };
    },
    methods: {
        async convert() {
            this.loading = true;
            this.error = "";
            this.success = "";
            this.downloadUrl = "";
            this.preview = { title: "", embed: "" };
            this.progress = 0;
            this.audioInfo = null; // reset

            // Simulasi progress
            this.interval = setInterval(() => {
                if (this.progress < 90) {
                    this.progress += Math.floor(Math.random() * 5) + 1; // tambah 1-5%
                }
            }, 300);

            try {
                const response = await fetch("/convert", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ youtube_url: this.youtubeUrl }),
                });

                const data = await response.json();
                clearInterval(this.interval);
                this.progress = 100;

                if (data.error) {
                    this.error = data.error;
                } else {
                    this.success = data.success;
                    this.downloadUrl = data.download;
                    this.preview.title = data.title;
                    this.preview.embed = data.embed;
                    this.audioInfo = data.audio_info;
                }
            } catch (err) {
                clearInterval(this.interval);
                this.error = "Terjadi kesalahan: " + err.message;
            } finally {
                this.loading = false;
                setTimeout(() => (this.progress = 0), 3000); // sembunyikan progress bar setelah 3 detik
            }
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: 30px auto;
    text-align: center;
    font-family: sans-serif;
}

.input {
    padding: 10px;
    width: 80%;
    margin: 10px 0;
}

.btn {
    padding: 10px 20px;
    cursor: pointer;
}

.error {
    color: red;
}

.success {
    color: green;
}

.preview {
    margin-top: 20px;
}

.download-link {
    display: inline-block;
    margin-top: 15px;
    padding: 10px;
    background: green;
    color: white;
    text-decoration: none;
    border-radius: 4px;
}

.progress-container {
    width: 100%;
    background: #eee;
    height: 20px;
    margin-top: 10px;
    position: relative;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(to right, #4caf50, #81c784);
    width: 0%;
    transition: width 0.3s ease;
}

.audio-info {
    text-align: left;
    margin-top: 20px;
    padding: 10px;
    background: #f4f4f4;
    border-radius: 6px;
}
.audio-info ul {
    list-style: none;
    padding: 0;
}
.audio-info li {
    margin: 4px 0;
}
</style>
