<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spreadsheet Uploader</title>
    <!-- Include Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Upload Your Sample Data</h1>

    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md p-8">
        <!--
            - action="/upload": The URL where the form data will be sent.
            - method="POST": The HTTP method for file uploads.
            - enctype="multipart/form-data": Essential for file uploads.
        -->
        <form action="/upload" method="POST" enctype="multipart/form-data">

            <!-- Laravel's CSRF protection token -->
            @csrf

            <div class="mb-4">
                <label for="spreadsheet" class="block text-gray-700 text-sm font-bold mb-2">Spreadsheet File:</label>
                <input
                    type="file"
                    name="spreadsheet"
                    id="spreadsheet"
                    required
                    class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0
                               file:text-sm file:font-semibold
                               file:bg-violet-50 file:text-violet-700
                               hover:file:bg-violet-100"
                >
            </div>

            <div class="text-center">
                <button
                    type="submit"
                    class="bg-violet-500 hover:bg-violet-700 text-white font-bold py-2 px-4 rounded-full"
                >
                    Upload and Display Data
                </button>
            </div>
        </form>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-4 text-center">Database Content</h2>
        <!-- We will add the table to display data here in a later step -->
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Data rows will be dynamically inserted here by Laravel -->
                    <tr class="bg-white border-b">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-400">
                            No data has been uploaded yet.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
