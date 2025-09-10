<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spreadsheet Uploader</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>
<body class="bg-gray-100 text-gray-800">

<div id="drag-overlay" style="display: none;">
    <h1 id="drag-overlay-text">Drop File to Upload</h1>
</div>

<div class="container mx-auto p-8">
    @if (session('success'))
        <div class="max-w-xl mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="text-3xl font-bold mb-6 text-center">Upload Your Sample Data</h1>

    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md p-8">
        <form id="upload-form" action="/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="spreadsheet" class="block text-gray-700 text-sm font-bold mb-2">Drag a file anywhere or click to select:</label>
                <input type="file" name="spreadsheet" id="spreadsheet" required class="hidden">
                <div id="file-display-area" class="mt-2 text-center border-2 border-dashed border-gray-300 p-6 rounded-lg cursor-pointer hover:bg-gray-50">
                    <span id="file-name-display" class="text-gray-500">No file selected</span>
                </div>
            </div>
            <div class="text-center mt-6">
                <button type="submit" class="bg-violet-500 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded-full">
                    Upload and Display Data
                </button>
            </div>
        </form>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4 text-center">Spreadsheet Content</h2>
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Location</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse ($samples as $sample)
                        <tr class="bg-white hover:bg-gray-50">

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sample->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sample->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sample->location }}</td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">No data has been uploaded yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">{{ $samples->links() }}</div>
    </div>
</div>

<script src="{{ asset('assets/script.js') }}"></script>
</body>
</html>
