document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        const fileContainer = document.createElement('div');
        fileContainer.className = 'file-input-container';
        
        const filePreview = document.createElement('div');
        filePreview.className = 'file-preview';
        
        input.parentNode.insertBefore(fileContainer, input);
        fileContainer.appendChild(input);
        fileContainer.appendChild(filePreview);

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                filePreview.innerHTML = '<span class="no-file">No file selected</span>';
                return;
            }

            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Convert to MB

            if (input.accept.includes('video')) {
                filePreview.innerHTML = `
                    <div class="file-info">
                        <i class="fas fa-video"></i>
                        <div class="file-details">
                            <span class="file-name">${fileName}</span>
                            <span class="file-size">${fileSize} MB</span>
                        </div>
                    </div>
                `;
            } else if (input.accept.includes('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.innerHTML = `
                        <div class="file-info">
                            <img src="${e.target.result}" alt="Thumbnail preview">
                            <div class="file-details">
                                <span class="file-name">${fileName}</span>
                                <span class="file-size">${fileSize} MB</span>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    const form = document.querySelector('form');
    const progressBar = document.querySelector('.progress-bar');
    const progressContainer = document.querySelector('.upload-progress');

    form.addEventListener('submit', function() {
        progressContainer.style.display = 'block';
        let progress = 0;
        const interval = setInterval(() => {
            progress += 1;
            progressBar.style.width = `${progress}%`;
            if (progress >= 100) clearInterval(interval);
        }, 50);
    });
});