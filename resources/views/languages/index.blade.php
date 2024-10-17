<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Languages</title>
    <!-- Include Bootstrap (Optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        <!-- Add New Key -->
        <h2>Add New Translation</h2>
        <form id="addForm">
            <div class="form-group mb-2">
                <label for="key">Key</label>
                <input type="text" class="form-control" id="newKey" placeholder="Enter key">
            </div>
            <div class="form-group mb-2">
                <label for="enValue">English Value</label>
                <input type="text" class="form-control" id="newEnValue" placeholder="Enter English value">
            </div>
            <div class="form-group mb-2">
                <label for="arValue">Arabic Value</label>
                <input type="text" class="form-control" id="newArValue" placeholder="Enter Arabic value">
            </div>
            <button type="button" class="btn btn-success" onclick="addTranslation()">Add Translation</button>
        </form>

        <h1>Manage Translations</h1>


        <!-- Language Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>English</th>
                    <th>Arabic</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($languages['en'] as $key => $value)
                <tr>
                    <td>{{ $key }}</td>
                    <td>
                        <input type="text" value="{{ $value }}" class="form-control" id="en-{{ $key }}">
                    </td>
                    <td>
                        <input type="text" value="{{ $languages['ar'][$key] ?? '' }}" class="form-control" id="ar-{{ $key }}">
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="updateTranslation('{{ $key }}')">Update</button>
                        <button class="btn btn-danger" onclick="deleteTranslation('{{ $key }}')">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- Include jQuery and Axios (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Update translation
        function updateTranslation(key) {
            const enValue = document.getElementById(`en-${key}`).value;
            const arValue = document.getElementById(`ar-${key}`).value;

            axios.post('{{ route('languages.update') }}', {
                locale: 'en',
                key: key,
                value: enValue
            }).then(() => {
                axios.post('{{ route('languages.update') }}', {
                    locale: 'ar',
                    key: key,
                    value: arValue
                }).then(() => {
                    alert('Translation updated successfully');
                });
            });
        }

        // Delete translation
        function deleteTranslation(key) {
            axios.delete('{{ route('languages.delete') }}', {
                data: {
                    locale: 'en',
                    key: key
                }
            }).then(() => {
                axios.delete('{{ route('languages.delete') }}', {
                    data: {
                        locale: 'ar',
                        key: key
                    }
                }).then(() => {
                    alert('Translation deleted successfully');
                    location.reload();
                });
            });
        }

        // Add new translation
        function addTranslation() {
            const newKey = document.getElementById('newKey').value;
            const newEnValue = document.getElementById('newEnValue').value;
            const newArValue = document.getElementById('newArValue').value;

            axios.post('{{ route('languages.store') }}', {
                locale: 'en',
                key: newKey,
                value: newEnValue
            }).then(() => {
                axios.post('{{ route('languages.store') }}', {
                    locale: 'ar',
                    key: newKey,
                    value: newArValue
                }).then(() => {
                    // alert('Translation added successfully');
                    location.reload();
                });
            });
        }
    </script>
</body>
</html>
