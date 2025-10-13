import api from '@/utils/api';

export default {
  async getPosts(page = 1) {
    return api.get(`api/posts?page=${page}`);
  },
  async getAllPosts(page = 1) {
    return api.get(`api/posts/all?page=${page}`);
  },
  async createPost(formData) {
    return api.post(`api/posts`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
  },
  async toggleLike(postId) {
    return api.post(`api/posts/${postId}/like`);
  },
  async addComment(postId, content) {
    return api.post(`api/posts/${postId}/comment`, { content });
  },
  async deleteComment(id) {
    return api.delete(`api/comments/${id}`);
  },
  async deletePost(id) {
    return api.delete(`api/posts/${id}`);
  },
  async restorePost(id) {
    return api.post(`api/posts/${id}/restore`, {});
  },
};
