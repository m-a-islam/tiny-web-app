document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('upload-form');
    const fileInput = document.getElementById('spreadsheet');
    const overlay = document.getElementById('drag-overlay');
    const fileDisplayArea = document.getElementById('file-display-area');
    const fileNameDisplay = document.getElementById('file-name-display');


    const MAX_SIZE_MB = 10;
    const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

    function handleFile(file) {
        if (!file) return;

        if (file.size > MAX_SIZE_BYTES) {
            alert(`Error: File size cannot exceed ${MAX_SIZE_MB}MB.`);
            fileInput.value = '';
            fileNameDisplay.textContent = 'No file selected';
            return;
        }
        fileNameDisplay.textContent = file.name;
    }


    if (fileDisplayArea) {
        fileDisplayArea.addEventListener('click', () => {
            fileInput.click();
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            handleFile(file);
        });
    }

    let dragCounter = 0;
    window.addEventListener('dragenter', (e) => { e.preventDefault(); dragCounter++; overlay.style.display = 'flex'; });
    window.addEventListener('dragover', (e) => { e.preventDefault(); });
    window.addEventListener('dragleave', (e) => { e.preventDefault(); dragCounter--; if (dragCounter === 0) { overlay.style.display = 'none'; }});

    window.addEventListener('drop', (e) => {
        e.preventDefault();
        dragCounter = 0;
        overlay.style.display = 'none';

        const droppedFiles = e.dataTransfer.files;
        if (droppedFiles.length > 0) {
            fileInput.files = droppedFiles;
            handleFile(droppedFiles[0]);
        }
    });
});
