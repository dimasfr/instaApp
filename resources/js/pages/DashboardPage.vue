<template>
  <div class="p-4 max-w-3xl mx-auto">
    <!-- Navbar mini -->
    <header class="mb-6 flex justify-between items-center">
      <!-- Logo / Nama App -->
      <h1 class="text-3xl font-bold text-blue-600">InstaApp</h1>

      <!-- Tombol Logout / Login -->
      <button
        @click="user ? logout() : goLogin()"
        class="p-2 text-white bg-gray-700 rounded-full hover:bg-gray-800 transition duration-200"
        :title="user ? 'Logout' : 'Login'"
      >
        <component :is="user ? LogOut : LogIn" class="w-5 h-5" />
      </button>
    </header>

    <div v-if="!user" class="text-center py-20">
      <p class="text-gray-600 mb-4">Anda harus login untuk berinteraksi di linimasa.</p>
      <button
        @click="goLogin"
        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
      >
        Login Sekarang
      </button>
    </div>

    <!-- Linimasa Title -->
    <h2 class="text-2xl font-bold text-center mb-6">Linimasa</h2>

    <!-- Form upload post -->
    <div v-if="user">
      <div class="mb-6 p-4 bg-white rounded shadow">
        <textarea 
          v-model="newPost.content"
          placeholder="Apa yang sedang Anda pikirkan?"
          class="w-full border rounded p-2 mb-2"
        ></textarea>

        <div class="mb-2">
          <input 
            type="file" 
            ref="fileInput"
            multiple 
            accept="image/*"
            @change="handleFiles" 
            class="mb-2"
          />
        </div>

        <!-- Preview gambar sebelum upload -->
        <div class="flex gap-2 flex-wrap">
          <div
            v-for="(photo, index) in previewImages"
            :key="index"
            class="relative w-24 h-24"
          >
            <img
              :src="photo"
              class="w-full h-full object-cover rounded-md border"
            />
            <button
              @click="removeImage(index)"
              class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs"
            >
              ✕
            </button>
          </div>
        </div>

        <div class="flex justify-end">
          <button
            @click="submitPost"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
            title="Kirim postingan"
          >
            <Send class="w-5 h-5" />
            <span>Post</span>
          </button>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center">Loading...</div>

    <div v-else>
      <div v-for="post in posts.data" :key="post.id" class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Content -->
        <div class="p-4 flex justify-between items-start">
          <p class="text-gray-800 mb-3">{{ post.content }}</p>

          <!-- Tombol Delete jika post milik user login -->
          <button
            v-if="user && post.user.id === user.id"
            @click="deletePost(post.id)"
            class="p-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition duration-200"
            title="Hapus postingan"
          >
            <Trash2 class="w-5 h-5" />
          </button>
        </div>

        <!-- Photos horizontal scroll -->
        <div class="flex overflow-x-auto gap-2 px-4 pb-4">
          <img
            v-for="photo in post.photos"
            :key="photo.id"
            :src="`/storage/${photo.photo_path}`"
            class="h-40 w-40 object-cover rounded-lg flex-shrink-0 cursor-pointer"
            @click="openModal(`/storage/${photo.photo_path}`)"
          />
        </div>

        <!-- Likes & Comment actions -->
        <div class="px-4 pb-2 flex items-center gap-3">
          <!-- Tombol Like -->
          <button
            @click="toggleLike(post)"
            class="flex items-center gap-1 p-2 rounded-lg transition duration-200"
            :class="post.liked_by_user ? 'bg-blue-500 text-white hover:bg-red-600' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
            title="Suka"
            v-show="user"
          >
            <ThumbsUp
              class="w-5 h-5"
              :class="{ 'fill-white': post.liked_by_user }"
            />
          </button>

          <!-- Jumlah like -->
          <span class="text-gray-700 text-sm">
            {{ post.likes_count }} like{{ post.likes_count > 1 ? 's' : '' }}
          </span>
        </div>

        <!-- Comment list -->
        <div class="px-4 pb-2">
          <div
            v-for="comment in post.comments"
            :key="comment.id"
            class="text-sm mb-1 flex justify-between items-center group"
          >
            <div>
              <span class="font-semibold">{{ comment.name }}:</span> {{ comment.content }}
            </div>

            <!-- Tombol hapus hanya muncul jika user aktif -->
            <button
              v-if="user && comment.user_id === user.id"
              @click="deleteComment(comment.id, post)"
              class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity"
              title="Hapus komentar"
            >
              <svg xmlns="http://www.w3.org/2000/svg" 
                  class="w-4 h-4" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor" 
                  stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Input komentar -->
          <div class="flex gap-2 mt-2" v-show="user">
            <input 
              v-model="post.new_comment"
              placeholder="Tulis komentar..."
              class="flex-1 border rounded p-1 text-sm"
              @keyup.enter="submitComment(post)"
            />
            <!-- <button 
              @click="submitComment(post)" 
              class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
            >
              Post
            </button> -->
            <button
              @click="submitComment(post)"
              class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
              title="Kirim komentar"
            >
              <SendHorizontal class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Timestamp -->
        <div class="px-4 pb-4 text-right text-sm text-gray-400">
          Posted by: {{ post.user.name }} | {{ formatDate(post.created_at) }}
        </div>
      </div>

      <div v-if="modalOpen" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50" @click="closeModal">
        <img :src="modalImage" class="max-h-[90%] max-w-[90%] rounded shadow-lg" />
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <button 
          @click="prevPage" 
          :disabled="posts.current_page <= 1"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button 
          @click="nextPage" 
          :disabled="posts.current_page >= posts.last_page"
          class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import postService from '../services/postService';
import { useToast } from "@/utils/toast";
import { Trash2, ThumbsUp, Send, SendHorizontal, LogOut, LogIn } from "lucide-vue-next";

const posts = ref({});
const loading = ref(true);
const page = ref(1);
const router = useRouter();
const user = ref(null);
const { showToast } = useToast();

// New post
const newPost = ref({
  content: '',
  photos: []
});

const modalOpen = ref(false);
const modalImage = ref('');
const previewImages = ref([]);
const fileInput = ref(null);

const openModal = (url) => {
  modalImage.value = url;
  modalOpen.value = true;
};

const closeModal = () => {
  modalOpen.value = false;
  modalImage.value = '';
};

// Handle file input
const handleFiles = (event) => {
  const files = Array.from(event.target.files)
  newPost.value.photos = files

  // Buat preview URL
  previewImages.value = files.map(f => URL.createObjectURL(f))
}

// Hapus satu gambar
const removeImage = (index) => {
  files.value.splice(index, 1)
  previewImages.value.splice(index, 1)
}

const fetchPosts = async () => {
  loading.value = true;
  try {
    const res = await postService.getPosts(page.value);
    // Inisialisasi properti tambahan untuk tiap post
    posts.value = res.data;
    posts.value.data.forEach(p => {
      p.liked_by_user = p.likes.some(like => like.user_id === user.value?.id);
      p.new_comment = '';
    });
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const toggleLike = async (post) => {
  try {
    const res = await postService.toggleLike(post.id);
    post.liked_by_user = res.data.liked;
    post.likes_count = res.data.likes_count;
  } catch (err) {
    console.error(err);
  }
};

const submitComment = async (post) => {
  if (!post.new_comment) return;
  try {
    const res = await postService.addComment(post.id, post.new_comment);
    res.data = { ...res.data, name: res.data.user.name }; // Tambah nama user ke response
    post.comments.push(res.data);
    post.new_comment = '';
    showToast("Komentar Berhasil di Unggah", "success");
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Gagal mengunggah Komentar!";
    showToast(message, "error");
  }
};

const deleteComment = async (commentId, post) => {
  if (!confirm("Hapus komentar ini?")) return;
  try {
    await postService.deleteComment(commentId);
    post.comments = post.comments.filter(c => c.id !== commentId);
    showToast("Komentar Berhasil di Hapus", "success");
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Gagal menghapus Komentar!";
    showToast(message, "error");
  }
};

const deletePost = async (postId) => {
  if (!confirm("Are you sure you want to delete this post?")) return;

  try {
    await postService.deletePost(postId, localStorage.getItem('token'));
    showToast("Post Berhasil di Hapus", "success");
    fetchPosts(); // refresh
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Gagal menghapus Post!";
    showToast(message, "error");
  }
};

const fetchUser = async () => {
  try {
    const res = await fetch("/api/user", {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    if (res.ok) {
      const data = await res.json();
      user.value = data;
    } else {
      user.value = null;
    }
  } catch (err) {
    console.error(err);
    user.value = null;
  }
};

const goLogin = () => {
  router.push('/login');
};

const logout = () => {
  localStorage.removeItem('token');
  showToast("Anda Telah Logout", "info");
  router.push('/login');
};

const nextPage = () => {
  if (page.value < posts.value.last_page) {
    page.value++;
    fetchPosts();
  }
};

const prevPage = () => {
  if (page.value > 1) {
    page.value--;
    fetchPosts();
  }
};

const formatDate = (dateStr) => {
  const d = new Date(dateStr);
  return d.toLocaleString();
};

// Submit new post
const submitPost = async () => {
  if (!newPost.value.content && newPost.value.photos.length === 0) {
    alert("Isi konten atau pilih foto dulu!");
    return;
  }

  const formData = new FormData();
  formData.append('content', newPost.value.content);
  newPost.value.photos.forEach(photo => formData.append('photos[]', photo));

  try {
    await postService.createPost(formData);
    // Reset setelah sukses
    if (fileInput.value) {
      fileInput.value.value = ''
    }

    newPost.value.content = ''
    newPost.value.photos = []
    previewImages.value.forEach((url) => URL.revokeObjectURL(url))
    previewImages.value = []

    showToast("Postingan berhasil diunggah!", "success");
    fetchPosts(); // refresh linimasa
  } catch (err) {
    console.error(err);
    const message = err.response?.data?.message || "Gagal membuat post!";
    showToast(message, "error");
  }
};

onMounted(async () => {
  await fetchUser();
  fetchPosts();
});
</script>

<style scoped>
/* Scrollbar styling untuk horizontal scroll */
.flex::-webkit-scrollbar {
  height: 6px;
}

.flex::-webkit-scrollbar-thumb {
  background-color: rgba(100, 100, 100, 0.5);
  border-radius: 3px;
}
</style>