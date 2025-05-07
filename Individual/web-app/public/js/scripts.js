$(document).ready(function() {
    // Resource form validation
    $('#resourceForm').on('submit', function(e) {
        let title = $('#title').val();
        let description = $('#description').val();
        let priority = $('#priority').val();
        if (!title || !description || !priority || priority < 1 || priority > 10) {
            e.preventDefault();
            alert('Please fill all fields correctly. Priority must be between 1 and 10.');
        }
    });

    // Register form validation
    $('#registerForm').on('submit', function(e) {
        let password = $('#password').val();
        if (password.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long.');
        }
    });
});