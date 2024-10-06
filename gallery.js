document.addEventListener('DOMContentLoaded', function() {
    fetch('get_photos.php')
        .then(response => response.json())
        .then(data => {
            const galleryContainer = document.querySelector('.gallery-container');
            data.forEach(photo => {
                const photoItem = document.createElement('div');
                photoItem.classList.add('photo-item');
                
                const photoItemInner = document.createElement('div');
                photoItemInner.classList.add('photo-item-inner');

                const photoItemFront = document.createElement('div');
                photoItemFront.classList.add('photo-item-front');
                
                // Use image as background
                const img = document.createElement('img');
                img.src = photo.image_url;
                photoItemFront.appendChild(img);

                const photoItemBack = document.createElement('div');
                photoItemBack.classList.add('photo-item-back');
                photoItemBack.innerHTML = `
                    <h3>${photo.name}</h3>
                    <p>Email: ${photo.email}</p>
                `;

                photoItemInner.appendChild(photoItemFront);
                photoItemInner.appendChild(photoItemBack);
                photoItem.appendChild(photoItemInner);
                galleryContainer.appendChild(photoItem);
            });
        })
        .catch(error => console.error('Error fetching photos:', error));
});
