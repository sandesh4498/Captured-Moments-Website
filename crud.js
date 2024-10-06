document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('crud-form');
    const tableBody = document.querySelector('#photos-table tbody');
    const clearButton = document.getElementById('clear-form');

    // Load photos from the database
    function loadPhotos() {
        fetch('load_photos.php')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = ''; // Clear existing data
                data.forEach(photo => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="${photo.image_url}" alt="Photo" style="width: 100px;"></td>
                        <td>${photo.name}</td>
                        <td>${photo.email}</td>
                        <td>
                            <button onclick="editPhoto(${photo.id})">Edit</button>
                            <button onclick="deletePhoto(${photo.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading photos:', error);
            });
    }

    // Handle form submission for adding or updating a photo
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch('save_photo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Photo added/updated successfully!');
                form.reset(); // Clear form
                loadPhotos(); // Reload photos
            } else {
                alert('An error occurred: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error saving photo:', error);
        });
    });

    // Handle form clear button
    clearButton.addEventListener('click', function() {
        form.reset();
    });

    // Edit a photo
    window.editPhoto = function(id) {
        fetch(`get_photo.php?id=${id}`)
            .then(response => response.json())
            .then(photo => {
                document.getElementById('photo-id').value = photo.id;
                document.getElementById('name').value = photo.name;
                document.getElementById('email').value = photo.email;
                // File input cannot be set programmatically for security reasons
            })
            .catch(error => {
                console.error('Error fetching photo details:', error);
            });
    };

    // Delete a photo
    window.deletePhoto = function(id) {
        if (confirm('Are you sure you want to delete this photo?')) {
            fetch('delete_photo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPhotos(); // Reload photos
                } else {
                    alert('An error occurred while deleting the photo.');
                }
            })
            .catch(error => {
                console.error('Error deleting photo:', error);
            });
        }
    };

    loadPhotos(); // Initial load
});
