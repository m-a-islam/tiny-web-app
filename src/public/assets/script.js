document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('spreadsheet');
    const overlay = document.getElementById('drag-overlay');
    const fileDisplayArea = document.getElementById('file-display-area');
    const fileNameDisplay = document.getElementById('file-name-display');

    if (fileDisplayArea) {
        fileDisplayArea.addEventListener('click', () => {
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = 'No file selected';
            }
        });
    }

    let dragCounter = 0;

    window.addEventListener('dragenter', (e) => {
        e.preventDefault();
        dragCounter++;
        overlay.style.display = 'flex';
    });

    window.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    window.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dragCounter--;
        if (dragCounter === 0) {
            overlay.style.display = 'none';
        }
    });

    window.addEventListener('drop', (e) => {
        e.preventDefault();
        dragCounter = 0;
        overlay.style.display = 'none';

        const droppedFiles = e.dataTransfer.files;

        if (droppedFiles.length > 0) {
            fileInput.files = droppedFiles;
            fileNameDisplay.textContent = droppedFiles[0].name;
            // form.submit();
        }
    });
});
