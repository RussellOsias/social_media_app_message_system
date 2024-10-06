<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('Home Page') }}</h2>
        </div>
    </x-slot>

    <div ng-app="socialApp" ng-controller="PostController" class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md">

        <!-- Create Post Form -->
        <form ng-submit="createPost()" class="bg-gray-700 p-4 rounded-lg shadow-md mb-6 border border-gray-600 transition-transform transform hover:scale-105">
            <textarea ng-model="newPost.content" placeholder="What's on your mind? 😊" required class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"></textarea>
            <div class="flex justify-between mt-2">
                <div class="flex items-center space-x-2">
                    <button type="button" class="text-gray-400 hover:text-red-500 transition duration-200">
                        📸 Upload Image
                    </button>
                    <button type="button" class="text-gray-400 hover:text-red-500 transition duration-200">
                        🎥 Upload Video
                    </button>
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    📤 Share
                </button>
            </div>
        </form>

        <!-- Display Posts -->
        <div ng-repeat="post in posts" class="bg-gray-700 p-4 rounded-lg shadow-md mt-4 transition-transform transform hover:scale-105">
            <div class="flex justify-between items-center mb-2">
                <small class="text-gray-400">
                    <span class="text-xl font-bold text-gray-200">@{{ post.user.name }}</span>
                    <span class="text-gray-500"> on @{{ post.created_at | date:'medium' }}</span>
                </small>
                <div ng-if="post.user_id === {{ Auth::id() }}" class="flex space-x-2">
                    <button ng-click="enableEditPost(post)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        ✏️ Edit
                    </button>
                    <button ng-click="deletePost(post)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        🗑️ Delete
                    </button>
                </div>
            </div>

            <div ng-if="!post.isEditing">
                <p class="text-lg text-gray-100 mb-4">@{{ post.content }}</p>
                <div class="flex items-center space-x-2">
                    <button ng-click="likePost(post)" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        <span ng-if="!post.userHasLiked">❤️</span>
                        <span ng-if="post.userHasLiked">💔</span>
                        <span>@{{ post.likes_count }}</span>
                    </button>
                </div>
            </div>

            <div ng-if="post.isEditing">
                <textarea ng-model="post.content" class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"></textarea>
                <div class="flex space-x-2 mt-2">
                    <button ng-click="editPost(post)" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        💾 Save
                    </button>
                    <button ng-click="cancelEditPost(post)" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        ❌ Cancel
                    </button>
                </div>
            </div>

            <!-- Add Comment Section -->
            <form ng-submit="addComment(post)" class="mt-4">
                <input type="text" ng-model="post.newComment" placeholder="Add a comment... 💬" class="w-full border border-gray-600 bg-gray-900 text-white p-3 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                <button type="submit" class="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                    💬 Comment
                </button>
            </form>

            <!-- Display Comments -->
            <ul ng-repeat="comment in post.comments" class="border-t border-gray-600 pt-2">
                <li class="mt-2">
                    <small>
                        <span class="text-lg font-bold text-gray-300">@{{ comment.user.name }}</span>
                        <span class="text-gray-500"> on @{{ comment.created_at | date:'medium' }}</span>
                    </small>
                    <div ng-if="!comment.isEditing" class="text-gray-200 mt-1">
                        <p>@{{ comment.comment }}</p>
                        <div ng-if="comment.user_id === {{ Auth::id() }}" class="flex space-x-2 mt-2">
                            <button ng-click="enableEditComment(comment)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                                ✏️ Edit
                            </button>
                            <button ng-click="deleteComment(post, comment)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                    <div ng-if="comment.isEditing">
                        <textarea ng-model="comment.comment" class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"></textarea>
                        <div class="flex space-x-2 mt-2">
                            <button ng-click="editComment(post, comment)" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                                💾 Save
                            </button>
                            <button ng-click="cancelEditComment(comment)" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                                ❌ Cancel
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Sidebar Section -->
        <div class="mt-8 bg-gray-700 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-300 mb-4">Trending Topics</h3>
            <ul class="space-y-2">
                <li><a href="#" class="text-blue-500 hover:underline">#AngularJS</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">#TailwindCSS</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">#WebDevelopment</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">#JavaScript</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">#SocialMedia</a></li>
            </ul>
        </div>

    
   

    </div>

    <script>
        // Set the default value for visibility
        $scope.newPost = {
            // Removed visibility option
        };
    </script>
</x-app-layout>
