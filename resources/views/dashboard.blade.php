<x-app-layout>
    <x-slot name="header">
        <div class="bg-red-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">{{ __('Home Page') }}</h2>
        </div>
    </x-slot>

    <div ng-app="socialApp" ng-controller="PostController" class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-800 text-white p-6 rounded-lg shadow-md">

        <!-- Create Post Form -->
        <form ng-submit="createPost()" enctype="multipart/form-data" class="bg-gray-700 p-4 rounded-lg shadow-md mb-6 border border-gray-600 transition-transform transform hover:scale-105">
            <textarea ng-model="newPost.content" placeholder="What's on your mind? 😊" required class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"></textarea>

            <!-- Media Type Selection -->
            <div class="flex items-center space-x-4 mt-4">
                <label for="media" class="text-gray-400">Upload:</label>
                <input type="file" accept="image/*,video/*" onchange="angular.element(this).scope().newPost.file = this.files[0]" />
            </div>

            <div class="flex justify-between mt-2">
                <div class="flex items-center space-x-2"></div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    📤 Share
                </button>
            </div>
        </form>
<!-- Display Posts -->
<div ng-repeat="post in posts" class="bg-gray-700 p-4 rounded-lg shadow-md mt-4 transition-transform transform hover:scale-105">
    <div class="flex items-center mb-2">
        <!-- Profile picture of the post author -->
        <div class="flex-shrink-0 mr-2">
            <!-- Display the profile picture of the post's author -->
            <img ng-src="http://127.0.0.1:8000/storage/@{{ post.user.profile_picture }}" 
                 alt="@{{ post.user.name }}'s Profile Picture" 
                 class="rounded-full w-10 h-10 object-cover"> 
        </div>
        
        <!-- Post author and timestamp -->
        <small class="text-gray-400 flex items-center">
            <!-- Display the post author's name -->
            <span class="text-xl font-bold text-gray-200">@{{ post.user.name }}</span>
            
            <!-- Display the post creation time -->
            <span class="text-gray-500 ml-1"> on @{{ post.created_at | date:'medium' }}</span>
        </small>
                <!-- Three Dots Menu -->
                <div class="relative" ng-if="post.user_id === {{ Auth::id() }}">
                    <button ng-click="post.showMenu = !post.showMenu" class="text-gray-400 hover:text-white">
                        ⋮
                    </button>

                    <!-- Dropdown Menu for Edit/Delete -->
                    <div ng-if="post.showMenu" class="absolute right-0 mt-2 w-32 bg-gray-800 rounded-lg shadow-lg z-10">
                        <ul class="text-gray-300 text-sm">
                            <li>
                                <button ng-click="enableEditPost(post)" class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded-t-lg">
                                    ✏️ Edit
                                </button>
                            </li>
                            <li>
                                <button ng-click="deletePost(post)" class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded-b-lg">
                                    🗑️ Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Post Content and Media -->
            <div ng-if="!post.isEditing">
                <p class="text-lg text-gray-100 mb-4">@{{ post.content }}</p>

                <!-- Display Media: Photo or GIF -->
                <div ng-if="post.mediaType === 'photo' && post.media_url" class="flex justify-center">
                    <img ng-src="http://127.0.0.1:8000/storage/media/@{{ post.media_url }}" 
                         alt="Uploaded photo or GIF" 
                         class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg mt-4 object-contain">
                </div>

                <!-- Display Media: Video -->
                <div ng-if="post.mediaType === 'video' && post.media_url" class="flex justify-center">
                    <video controls class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg mt-4">
                        <source ng-src="http://127.0.0.1:8000/storage/media/@{{ post.media_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Like Button -->
                <div class="flex items-center space-x-2 mt-4">
                    <button ng-click="likePost(post)" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        <span ng-if="!post.userHasLiked">❤️</span>
                        <span ng-if="post.userHasLiked">💔</span>
                        <span>@{{ post.likes_count }}</span>
                    </button>
                </div>
            </div>

            <!-- Edit Post Section -->
            <div ng-if="post.isEditing">
                <textarea ng-model="post.content" class="w-full border border-gray-600 bg-gray-900 text-white p-4 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"></textarea>
                
                <!-- Show current media (photo/video) -->
                <div ng-if="post.media_url && post.mediaType === 'photo'" class="flex justify-center my-4">
                    <img ng-src="http://127.0.0.1:8000/storage/media/@{{ post.media_url }}" class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg object-contain" alt="Current image">
                </div>

                <div ng-if="post.media_url && post.mediaType === 'video'" class="flex justify-center my-4">
                    <video controls class="w-full max-w-xs md:max-w-md lg:max-w-lg rounded-lg">
                        <source ng-src="http://127.0.0.1:8000/storage/media/@{{ post.media_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Upload new media -->
                <div class="flex items-center space-x-4 mt-4">
                    <label for="media" class="text-gray-400">Upload new media:</label>
                    <input type="file" accept="image/*,video/*" onchange="angular.element(this).scope().post.newFile = this.files[0]" />
                </div>

                <!-- Option to delete current media -->
                <div ng-if="post.media_url" class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" ng-model="post.deleteMedia" id="deleteMedia">
                    <label for="deleteMedia" class="text-gray-400">Delete current media</label>
                </div>

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
<form ng-submit="addComment(post)" class="mt-4" enctype="multipart/form-data">
    <input type="text" ng-model="post.newComment" placeholder="Add a comment... 💬" 
        class="w-full border border-gray-600 bg-gray-900 text-white p-3 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
    
    <!-- Upload media for comment -->
    <div class="flex items-center space-x-4 mt-2">
        <label for="commentMedia" class="text-gray-400">Upload:</label>
        <input type="file" accept="image/*,video/*" onchange="angular.element(this).scope().post.commentFile = this.files[0]" />
    </div>

    <button type="submit" class="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
        💬 Comment
    </button>
</form>

<!-- Display Comments -->
<ul ng-repeat="comment in post.comments" class="border-t border-gray-600 pt-2">
    <li class="bg-gray-800 p-2 rounded-lg mt-2 flex items-start">
        <!-- Media Display -->
        <div class="flex-shrink-0 mr-2">
            <div ng-if="comment.mediaType === 'photo' && comment.media_url">
                <img ng-src="http://127.0.0.1:8000/storage/media/@{{ comment.media_url }}" 
                     alt="Uploaded comment photo" 
                     class="max-w-xs rounded-lg object-contain" />
            </div>
            <div ng-if="comment.mediaType === 'video' && comment.media_url">
                <video controls class="max-w-xs rounded-lg">
                    <source ng-src="http://127.0.0.1:8000/storage/media/@{{ comment.media_url }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>

        <!-- Comment Content -->
<div class="flex-grow">
    <small class="flex items-center">
        <!-- Profile picture of the comment author -->
        <img ng-src="http://127.0.0.1:8000/storage/@{{ comment.user.profile_picture }}" 
             alt="@{{ comment.user.name }}'s Profile Picture" 
             class="rounded-full w-10 h-10 object-cover mr-2"> <!-- Adjusted margin right for spacing -->

        <!-- Comment author and timestamp -->
        <span class="text-lg font-bold text-gray-300">@{{ comment.user.name }}</span>
        <span class="text-gray-500 ml-1"> on @{{ comment.created_at | date:'medium' }}</span> <!-- Margin left for date -->
    </small>



            <!-- Display Comment or Edit Mode -->
            <div ng-if="!comment.isEditing" class="text-gray-200 mt-1">
                <p>@{{ comment.comment }}</p>

                <!-- Comment Actions (Edit & Delete) -->
                <div ng-if="comment.user_id === {{ Auth::id() }}" class="flex space-x-2 mt-2">
                    <button ng-click="enableEditComment(comment)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        ✏️ Edit
                    </button>
                    <button ng-click="deleteComment(post, comment)" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg transition duration-200">
                        🗑️ Delete
                    </button>
                </div>
            </div>

            <!-- Edit Comment Mode -->
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
        </div>
    </li>
</ul>
           
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
</x-app-layout>
