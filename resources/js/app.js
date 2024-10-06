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
        visibility: 'Public' // Pre-select 'Public'
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
        $http.post('/posts', $scope.newPost)
            .then(function(response) {
                $scope.posts.unshift(response.data);
                $scope.newPost = {}; // Clear form
            }, function(error) {
                console.error('Error creating post:', error);
                alert('Error creating post');
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
                    alert('Error deleting comment');
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
        $http.post('/posts/' + post.id + '/comment', { comment: post.newComment })
            .then(function(response) {
                post.comments.push(response.data); // Add the new comment to the post's comments array
                post.newComment = ''; // Clear the input field after successful comment submission
            }, function(error) {
                console.error('Error adding comment:', error);
                alert('Error adding comment');
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
        if (!$scope.newMessage.receiver_id || !$scope.newMessage.content) {
            alert('Please select a receiver and enter a message.');
            return;
        }

        $http.post('/messages/send', $scope.newMessage)
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
                $scope.newMessage.content = '';
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