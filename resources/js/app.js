import './bootstrap';
import Alpine from 'alpinejs';
import angular from 'angular';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // Import Pusher

window.Alpine = Alpine;

Alpine.start();

// Define the AngularJS application module
angular.module('socialApp', [])
.controller('PostController', function($scope, $http) {
   
   
    $scope.posts = [];
    $scope.newPost = {
        content: '',
        visibility: 'Public', // Pre-select 'Public'
        file: null // Initialize file to null
    };

    // Fetch posts when the controller initializes
    $scope.getPosts = function() {
        $http.get('/posts')
            .then(function(response) {
                $scope.posts = response.data;
            }, function(error) {
                console.error('Error fetching posts:', error);
                alert('Error fetching posts');
            });
    };

    $scope.createPost = function() {
        var formData = new FormData();
        formData.append('content', $scope.newPost.content);
        formData.append('visibility', $scope.newPost.visibility);
        
        // Check if a file is selected and append it to the form data
        if ($scope.newPost.file) {
            formData.append('file', $scope.newPost.file);
        }
        
        $http.post('/posts', formData, {
            headers: { 'Content-Type': undefined },
            transformRequest: angular.identity
        })
        .then(function(response) {
            $scope.posts.unshift(response.data);
            $scope.newPost = {
                content: '',
                visibility: 'Public',
                file: null // Reset file
            }; // Clear form
        })
        .catch(function(error) {
            console.error('Error creating post:', error);
            alert('Error creating post: ' + (error.data.message || 'Something went wrong'));
        });
    };

    $scope.enableEditPost = function(post) {
        post.isEditing = true; // Set the editing mode
        post.originalContent = post.content; // Store original content for cancellation
    };

    $scope.editPost = function(post) {
        $http.patch('/posts/' + post.id, { content: post.content })
            .then(function(response) {
                post.isEditing = false; // Exit editing mode
            }, function(error) {
                console.error('Error editing post:', error);
                alert('Error editing post');
            });
    };

    $scope.cancelEditPost = function(post) {
        post.content = post.originalContent; // Reset to original content
        post.isEditing = false; // Exit editing mode
    };

    $scope.enableEditComment = function(comment) {
        comment.isEditing = true; // Set the editing mode
        comment.originalComment = comment.comment; // Store original comment for cancellation
    };

    $scope.editComment = function(post, comment) {
        $http.patch('/posts/' + post.id + '/comments/' + comment.id, { comment: comment.comment })
            .then(function(response) {
                comment.isEditing = false; // Exit editing mode
            }, function(error) {
                console.error('Error editing comment:', error);
                alert('Error editing comment');
            });
    };

    $scope.cancelEditComment = function(comment) {
        comment.comment = comment.originalComment; // Reset to original comment
        comment.isEditing = false; // Exit editing mode
    };

    $scope.deleteComment = function(post, comment) {
        console.log('Post:', post); // Check the post structure
        console.log('Comment:', comment); // Check the comment structure
    
        if (!post || !post.id) {
            console.error('Post is undefined or does not have an ID.');
            alert('Error: Post is not valid.');
            return;
        }
        if (!comment || !comment.id) {
            console.error('Comment is undefined or does not have an ID.');
            alert('Error: Comment is not valid.');
            return;
        }
    
        if (confirm('Are you sure you want to delete this comment?')) {
            $http.delete('/posts/' + post.id + '/comments/' + comment.id)
                .then(function(response) {
                    // Remove the comment from the post's comments array
                    var index = post.comments.indexOf(comment);
                    if (index > -1) {
                        post.comments.splice(index, 1);
                    }
                    alert(response.data.message);
                }, function(error) {
                    console.error('Error deleting comment:', error);
                    alert('Error deleting comment: ' + (error.data.message || 'Something went wrong'));
                });
        }
    };
    
    

    $scope.likePost = function(post) {
        $http.post('/posts/' + post.id + '/like')
            .then(function(response) {
                // Toggle like/unlike logic based on the response
                if (response.data.message === 'Post liked') {
                    post.likes_count++;
                    post.userHasLiked = true; // Keep track that the user has liked the post
                } else if (response.data.message === 'Post unliked') {
                    post.likes_count--;
                    post.userHasLiked = false; // Keep track that the user has unliked the post
                }
            }, function(error) {
                console.error('Error toggling like:', error);
                alert('Error toggling like');
            });
    };

    $scope.addComment = function(post) {
        var formData = new FormData();
        formData.append('comment', post.newComment);

        // Check if a file is selected for the comment
        if (post.commentFile) {
            formData.append('file', post.commentFile);
        }

        $http.post('/posts/' + post.id + '/comment', formData, {
            headers: { 'Content-Type': undefined },
            transformRequest: angular.identity
        })
        .then(function(response) {
            post.comments.push(response.data); // Add the new comment to the post's comments array
            post.newComment = ''; // Clear the input field after successful comment submission
            post.commentFile = null; // Reset the file input
        }, function(error) {
            console.error('Error adding comment:', error);
            alert('Error adding comment: ' + (error.data.message || 'Something went wrong'));
        });
    };

    $scope.deletePost = function(post) {
        if (confirm('Are you sure you want to delete this post?')) {
            $http.delete('/posts/' + post.id)
                .then(function(response) {
                    // Remove the post from the list after deletion
                    var index = $scope.posts.indexOf(post);
                    if (index > -1) {
                        $scope.posts.splice(index, 1);
                    }
                    alert(response.data.message);
                }, function(error) {
                    console.error('Error deleting post:', error);
                    alert('Error deleting post');
                });
        }
    };

    
    
    
    
    $scope.getPosts();  // Fetch posts when the controller initializes
   
   
   
   
    $scope.stories = [];
    $scope.newStory = {}; // Initialize newStory object
    
    // Function to create a story
    $scope.createStory = function () {
        const formData = new FormData();
        if ($scope.newStory.file) {
            formData.append('media', $scope.newStory.file); // Append the file
        }
        if ($scope.newStory.content) {
            formData.append('content', $scope.newStory.content); // Append the text content
        }
    
        $http.post('/stories', formData, {
            headers: { 'Content-Type': undefined }
        }).then(function (response) {
            alert('Story uploaded successfully.');
            $scope.getStories();
            $scope.newStory = {}; // Clear form fields
            $scope.closeStoryUploadModal(); // Close modal after upload
        }, function (error) {
            console.error('Error uploading story:', error);
            alert('Error uploading story. Please try again.');
        });
    };
    
    // Function to get all stories
    $scope.getStories = function () {
        $http.get('/stories').then(function (response) {
            $scope.stories = response.data;
        }, function (error) {
            console.error('Error fetching stories:', error);
        });
    };
    
    // Call getStories on page load to load existing stories
    $scope.getStories();
    
    $scope.showStoryUploadModal = function () {
        document.getElementById('storyUploadModal').classList.remove('hidden');
    };
    
    $scope.closeStoryUploadModal = function () {
        document.getElementById('storyUploadModal').classList.add('hidden');
    };
    
    // Function to view story in fullscreen
    $scope.viewStory = function (story) {
        $scope.selectedStory = story; // Set the selected story for viewing
        document.getElementById('storyFullscreenModal').classList.remove('hidden');
    };
    
    // Function to close the fullscreen modal
    $scope.closeStoryFullscreenModal = function () {
        document.getElementById('storyFullscreenModal').classList.add('hidden');
    };
    
    $scope.goToStory = function (storyId) {
        window.location.href = '/story/' + storyId; // Adjust this URL as per your routing structure
    };
    
   
    $scope.getMostRecentStories = function(stories) {
        const recentStoriesMap = {};
    
        stories.forEach(story => {
            const userId = story.user.id; // Assuming `story.user.id` is available
            if (!recentStoriesMap[userId] || new Date(story.created_at) > new Date(recentStoriesMap[userId].created_at)) {
                recentStoriesMap[userId] = story;
            }
        });
    
        return Object.values(recentStoriesMap);
    };

    
})

.controller('MessageController', function($scope, $http) {
    $scope.messages = [];
    $scope.users = [];
    $scope.newMessage = {
        receiver_id: '',
        content: ''
    };

    // Function to fetch users
    $scope.getUsers = function() {
        $http.get('/api/users')
            .then(function(response) {
                $scope.users = response.data;
            }, function(error) {
                console.error('Error fetching users:', error);
                alert('Error fetching users: ' + (error.data.message || 'Something went wrong'));
            });
    };

    // Function to fetch messages between the logged-in user and a specific receiver
    $scope.getMessages = function(receiverId) {
        $http.get('/messages/' + receiverId)
            .then(function(response) {
                $scope.messages = response.data.messages; // Access messages from the response
            }, function(error) {
                console.error('Error fetching messages:', error);
                alert('Error fetching messages: ' + (error.data.message || 'Something went wrong'));
            });
    };

// Function to send a message
$scope.sendMessage = function() {
    if (!$scope.newMessage.receiver_id) {
        alert('Please select a receiver.');
        return;
    }

    // Prepare the data to send
    let dataToSend = {
        receiver_id: $scope.newMessage.receiver_id,
        content: $scope.newMessage.content || null // Allow content to be null
    };

    // Send the message
    $http.post('/messages/send', dataToSend)
        .then(function(response) {
            // Push the new message to the local messages array
            $scope.messages.push(response.data.message);

            // Mark the last message as read if it's a reply
            $http.post('/messages/markAsRead/' + response.data.message.id)
                .then(function() {
                    console.log('Message marked as read.');
                }, function(error) {
                    console.error('Error marking message as read:', error);
                });

            // Clear the message content
            $scope.newMessage.content = ''; // Clear input after sending
        }, function(error) {
            console.error('Error sending message:', error);
            alert('Error sending message: ' + (error.data.message || 'Something went wrong'));
        });
};
    // Function to listen for new messages via Laravel Echo
    $scope.listenForMessages = function(receiverId) {
        Echo.private('chat.' + receiverId)
            .listen('MessageSent', (e) => {
                $scope.$apply(() => {
                    // Push the new message to the messages array
                    $scope.messages.push(e.message);

                    // Mark the new message as read
                    $http.post('/messages/markAsRead/' + e.message.id)
                        .then(function() {
                            console.log('Received message marked as read.');
                        }, function(error) {
                            console.error('Error marking received message as read:', error);
                        });
                });
            });
    };

    // Initialize the chat
    $scope.initializeChat = function(receiverId) {
        $scope.newMessage.receiver_id = receiverId;
        $scope.getMessages(receiverId); // Fetch initial messages
        $scope.listenForMessages(receiverId); // Start listening for new messages
    };

    // Fetch users on controller initialization
    $scope.getUsers();
})
.controller('FriendController', function($scope, $http) {
    // FriendController code remains unchanged
});

// CSRF Token configuration
angular.module('socialApp').config(function($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
});
